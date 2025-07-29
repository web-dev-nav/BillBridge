<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\AppBaseController;
use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\Notification as ModelsNotification;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use WandesCardoso\MercadoPago\DTO\BackUrls;
use WandesCardoso\MercadoPago\DTO\Item;
use WandesCardoso\MercadoPago\DTO\Payer;
use WandesCardoso\MercadoPago\DTO\Preference;
use WandesCardoso\MercadoPago\Facades\MercadoPago;

class MercadopagoController extends AppBaseController
{

    public function __construct()
    {
        config(['mercadopago.public_key' => getSettingValue('mercadopago_key')]);
        config(['mercadopago.access_token' => getSettingValue('mercadopago_secret')]);
    }

    //redirectToGateway
    public function redirectToGateway(Request $request)
    {
        $input = $request->all();
        $invoiceId = $request->get('invoiceId');
        $payable_amount = $request->amount;
        $invoice = Invoice::with('client.user')->whereId($invoiceId)->first();

        $invoiceCurrencyId = $invoice->currency_id;

        if (! in_array(strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId)), getMercadopagoSupportedCurrencies())) {

            if ($request->ajax()) {

                return $this->sendError(getInvoiceCurrencyCode($invoiceCurrencyId) . ' is not currently supported.');
            }
            Notification::make()->danger()->title(getInvoiceCurrencyCode($invoiceCurrencyId) . ' is not currently supported.')->send();

            return redirect()->route('filament.client.resources.invoices.index');
        }

        $payer = new Payer(
            $invoice->client->user->email,
            $invoice->client->user->first_name,
            $invoice->client->user->last_name,
        );
        $item = Item::make()
            ->setTitle($invoice->invoice_id)
            ->setQuantity(1)
            ->setId(Auth::user()->id)
            ->setCategoryId($invoice->id)
            ->setUnitPrice($payable_amount);

        $preference = Preference::make()
            ->setPayer($payer)
            ->addItem($item)
            ->setBackUrls(new BackUrls(
                route('mercadopago.success'),
            ))
            ->setAutoReturn('approved')
            ->setAdditionalInfo(serialize($input));

        $response = MercadoPago::preference()->create($preference);
        if ($response['httpCode'] == 400) {
            Notification::make()->danger()->title($response['body']->message)->send();
            return redirect()->route('filament.client.resources.invoices.index');
        }
        $response['body']->items[0]->currency_id = strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId)) ?? strtoupper(getCurrencyCode());

        return response()->json([
            'id' => $response['body']->id, // Preference ID
            'publicKey' => config('mercadopago.public_key'),
        ]);
    }

    public function success(Request $request)
    {
        $input = $request->all();

        $response = mercadoPago()->payment()->find($input['payment_id']);
        $preference = mercadoPago()->preference()->find($input['preference_id']);
        if ($response['httpCode'] != '200' || $preference['httpCode'] != '200') {
            Notification::make()->danger()->title('Your Payment is Cancelled')->send();
            return redirect()->route('filament.client.resources.invoices.index');
        }
        try {
            DB::beginTransaction();
            $data = unserialize($preference['body']->additional_info);

            $amount = $response['body']->transaction_amount;
            $invoiceId = $response['body']->additional_info->items[0]->category_id;
            $userId = $response['body']->additional_info->items[0]->id;
            $invoice = Invoice::with('payments')->findOrFail($invoiceId);

            if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
            } else {
                $totalAmount = $invoice->final_amount;
            }

            $transactionData = [
                'transaction_id' => $input['payment_id'],
                'amount' => $amount,
                'user_id' => $userId,
                'status' => Payment::APPROVED,
                'meta' => $input,
            ];

            $transaction = Transaction::create($transactionData);
            $PaymentData = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_mode' => Payment::MERCADOPAGO,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transaction->id,
                'notes' => $data['notes'] ?? '',
                'is_approved' => Payment::APPROVED,
            ];

            // update invoice bill

            $invoicePayment = Payment::create($PaymentData);

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
            $currency = getInvoiceCurrencyIcon($invoice->currency_id) ?? getCurrencySymbol();
            $title = 'Payment ' . getInvoiceCurrencyIcon($invoice->currency_id)  . $data['amount'] . ' received successfully for #' . $invoice->invoice_id . '.';
            addNotification([
                ModelsNotification::NOTIFICATION_TYPE['Invoice Payment'],
                $adminUserId,
                $title,
            ]);
            $invoiceData = [];
            $invoiceData['amount'] = $invoicePayment['amount'];
            $invoiceData['payment_date'] = $invoicePayment['payment_date'];
            $invoiceData['invoice_id'] = $invoice->id;
            $invoiceData['invoice'] = $invoice;

            if (getSettingValue('mail_notification')) {
                Mail::to(getAdminUser()->email)->send(new ClientMakePaymentMail($invoiceData));
            }

            DB::commit();
            Notification::make()->success()->title('Payment successfully done.')->send();
            return redirect()->route('filament.client.resources.invoices.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Notification::make()->danger()->title('Something went wrong')->send();
            return redirect()->route('filament.client.resources.invoices.index');
        }
    }
}
