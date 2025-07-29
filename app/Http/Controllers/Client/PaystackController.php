<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Invoice;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\PaystackRepository;
use Filament\Notifications\Notification;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackController extends Controller
{
    /** @var PaystackRepository $paystackRepository */
    public $paystackRepo;

    public function __construct(PaystackRepository $paystackRepository)
    {
        $paystackKey = getSettingValue('paystack_key');
        $paypalSecretKey = getSettingValue('paystack_secret');

        $publicKey = $paystackKey ?? config('paystack.publicKey');
        $secretKey = $paypalSecretKey ?? config('paystack.secretKey');

        config([
            'paystack.publicKey' => $publicKey,
            'paystack.secretKey' => $secretKey,
            'paystack.paymentUrl' => 'https://api.paystack.co',
        ]);

        $this->paystackRepo = $paystackRepository;
    }

    public function redirectToGateway(Request $request)
    {
        $supportedCurrency = ['NGN', 'USD', 'GHS', 'ZAR', 'KES'];
        $invoiceId = $request->get('invoiceId');
        $amount = $request->get('amount');
        $note = $request->get('note');
        session(['note' => $note]);
        $invoice = Invoice::with('client.user')->find($invoiceId);
        $user = $invoice->client->user;
        $invoiceCurrencyId = $invoice->currency_id;

        if (! in_array(strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId)), $supportedCurrency)) {
            if (! Auth()->check()) {
                session()->flash('error', getInvoiceCurrencyCode($invoiceCurrencyId) . ' is not currently supported.');
                return redirect()->back();
            }
            Notification::make()->danger()->title(getInvoiceCurrencyCode($invoiceCurrencyId) . ' is not currently supported.')->send();
            return redirect(route('filament.client.resources.invoices.index'));
        }

        try {
            $data = [
                'email' => $user->email, // email of recipients
                'amount' => $amount * 100,
                'quantity' => 1,
                "orderID" => rand(10000, 99999), // generate a random order ID for the client
                'currency' => strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId)),
                'reference' => Paystack::genTranxRef(),
                'metadata' => json_encode(['invoiceId' => $invoiceId]), // this should be related data
            ];

            return Paystack::getAuthorizationUrl($data)->redirectNow();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if (! Auth()->check()) {
                session()->flash('error', __('messages.flash.paystack_token_expired'));
                return redirect()->back();
            }
            Notification::make()->danger()->title($e->getMessage())->send();
            return redirect()->route('filament.client.resources.invoices.index');
        }
    }

    public function handleGatewayCallback(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();

        if (! $paymentDetails['status']) {

            Notification::make()
                ->success()
                ->title('Your Payment is Cancelled')
                ->send();


            return redirect()->route('filament.client.resources.invoices.index');
        }

        $invoiceId = $paymentDetails['data']['metadata']['invoiceId'];
        $transactionId = $paymentDetails['data']['reference'];
        $amount = $paymentDetails['data']['amount'] / 100;
        $metaData = json_encode($paymentDetails['data']);

        $this->paystackRepo->paymentSuccess($invoiceId, $transactionId, $amount, $metaData);


        if (! Auth()->check()) {
            $invoice = Invoice::find($invoiceId);
            $invoiceUniqueId = $invoice->invoice_id;
            return redirect(route('invoice-show-url', $invoiceUniqueId))->with('success', 'Payment successfully done.');
        }


        Notification::make()
            ->success()
            ->title('Payment successfully done.')
            ->send();

        return redirect()->route('filament.client.resources.invoices.index');
    }
}
