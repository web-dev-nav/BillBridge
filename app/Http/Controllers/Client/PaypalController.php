<?php

namespace App\Http\Controllers\Client;

use Flash;
use Exception;
use Throwable;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use App\Mail\ClientMakePaymentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Filament\Notifications\Notification;
use App\Http\Controllers\AppBaseController;
use Illuminate\Contracts\Foundation\Application;
use App\Models\Notification as NotificationModel;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaypalController extends AppBaseController
{
    /**
     * @return JsonResponse|void
     *
     * @throws Throwable
     */
    public function onBoard(Request $request)
    {
        $invoiceId = $request->get('invoiceId');
        $payable_amount = $request->get('amount');
        session(['notes' => $request->get('transactionNotes')]);

        /** @var Invoice $invoice */
        $invoice = Invoice::whereId($invoiceId)->first();
        $invoiceCurrencyId = $invoice->currency_id;

        if (! in_array(strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId)), getPayPalSupportedCurrencies())) {

            if ($request->ajax()) {

                return $this->sendError(getInvoiceCurrencyCode($invoiceCurrencyId) . ' is not currently supported.');
            }
            Notification::make()->danger()->title(getInvoiceCurrencyCode($invoiceCurrencyId) . ' is not currently supported.')->send();

            return redirect()->route('filament.client.resources.invoices.index');
        }

        try {
            $paypalClientID = getSettingValue('paypal_client_id');
            $paypalSecret = getSettingValue('paypal_secret');

            $clientId = $paypalClientID ?? config('payments.paypal.client_id');
            $clientSecret = $paypalSecret ?? config('payments.paypal.client_secret');
            $mode = config('payments.paypal.mode');

            config([
                'paypal.mode' => $mode,
                'paypal.sandbox.client_id' => $clientId,
                'paypal.sandbox.client_secret' => $clientSecret,
                'paypal.live.client_id' => $clientId,
                'paypal.live.client_secret' => $clientSecret,
            ]);

            $provider = new PayPalClient();
            $provider->getAccessToken();

            $data = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $invoiceId,
                        'amount' => [
                            'value' => $payable_amount,
                            'currency_code' => getInvoiceCurrencyCode($invoiceCurrencyId),
                        ],
                    ],
                ],
                'application_context' => [
                    'cancel_url' => route('paypal.failed'),
                    'return_url' => route('paypal.success'),
                ],
            ];


            $order = $provider->createOrder($data);

            if ($request->ajax()) {
                return response()->json($order);
            }

            return redirect($order['links'][1]['href']);
        } catch (Exception $exception) {
            if ($request->ajax()) {
                return response()->json(['error' => $exception->getMessage()], 422);
            }
            Notification::make()->danger()->title($exception->getMessage())->send();
            return redirect()->route('filament.client.resources.invoices.index');
        }
    }

    public function failed(Request $request): RedirectResponse
    {
        Notification::make()->danger()->title('Your Payment is Cancelled')->send();
        return redirect()->route('filament.client.resources.invoices.index');
    }

    /**
     * @return Application|RedirectResponse|Redirector|void
     *
     * @throws Throwable
     */
    public function success(Request $request): RedirectResponse
    {
        $clientId = getSettingValue('paypal_client_id');
        $clientSecret = getSettingValue('paypal_secret');
        $mode = config('payments.paypal.mode');

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPalClient();
        $provider->getAccessToken();
        $token = $request->get('token');
        $orderInfo = $provider->showOrderDetails($token);

        try {
            // Call API with your client and get a response for your call
            $response = $provider->capturePaymentOrder($token);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            $invoiceId = $response['purchase_units'][0]['reference_id'];
            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $transactionId = $response['id'];
            $invoice = Invoice::whereId($invoiceId)->first();
            $client = Client::with('user')->whereId($invoice->client_id)->first();

            $transactionDetails = [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'user_id' => $client->user->id,
                'status' => 'paid',
                'meta' => json_encode($response),
            ];

            $transaction = Transaction::create($transactionDetails);

            $invoice = Invoice::with('payments')->findOrFail($invoiceId);
            if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
            } else {
                $totalAmount = $invoice->final_amount;
            }

            $transactionNotes = session('notes');

            $PaymentData = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_mode' => Payment::PAYPAL,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transaction->id,
                'notes' => $transactionNotes,
                'is_approved' => Payment::APPROVED,
            ];

            $invoicePayment = Payment::create($PaymentData);
            session()->forget('notes');

            $invoiceData = [];
            $invoiceData['amount'] = $invoicePayment['amount'];
            $invoiceData['payment_date'] = $invoicePayment['payment_date'];
            $invoiceData['invoice_id'] = $invoicePayment['invoice_id'];
            $invoiceData['invoice'] = $invoicePayment->invoice;
            if (getSettingValue('mail_notification')) {
                Mail::to(getAdminUser()->email)->send(new ClientMakePaymentMail($invoiceData));
            }
            if (round($totalAmount, 2) == $amount) {
                $invoice->status = Payment::FULLPAYMENT;
                $invoice->save();
            } else {
                if (round($totalAmount, 2) != $amount) {
                    $invoice->status = Payment::PARTIALLYPAYMENT;
                    $invoice->save();
                }
            }

            $adminUserId = getAdminUser()->id;
            $invoice = Invoice::find($invoiceId);
            $title = 'Payment ' . getInvoiceCurrencyIcon($invoice->currency_id) . $amount . ' received successfully for #' . $invoice->invoice_id . '.';
            addNotification([
                NotificationModel::NOTIFICATION_TYPE['Invoice Payment'],
                $adminUserId,
                $title,
            ]);

            if (! Auth()->check()) {
                session()->flash('success', 'Payment successfully done.');
                return redirect(route('invoice-show-url', $invoice->invoice_id));
            }
            Notification::make()->success()->title('Payment successfully done.')->send();
            return redirect()->route('filament.client.resources.invoices.index');
        } catch (HttpException $ex) {
            print_r($ex->getMessage());
            if (! Auth()->check()) {
                return $this->sendError($ex->getMessage());
            }
            Notification::make()->danger()->title($ex->getMessage())->send();
            return redirect()->route('filament.client.resources.invoices.index');
        }
    }
}
