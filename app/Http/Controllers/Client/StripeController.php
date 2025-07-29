<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Invoice;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Repositories\StripeRepository;
use App\Http\Controllers\AppBaseController;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StripeController extends AppBaseController
{
    /**
     * @var StripeRepository
     */
    private $stripeRepository;

    public function __construct(StripeRepository $stripeRepository)
    {
        $this->stripeRepository = $stripeRepository;
    }

    public function createSession(Request $request)
    {
        $amount = $request->get('amount');
        $payable_amount = $request->get('payable_amount');
        $transactionNotes = $request->get('transactionNotes');
        $invoice = Invoice::with('client.user')->where('id', $request->get('invoiceId'))->firstOrFail();
        $invoiceId = $invoice->invoice_id;
        $client = $invoice->client->user;
        $user = $request->user() ?? $client;
        $userEmail = $user->email;

        try {
            setStripeApiKey();
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $userEmail,
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => 'BILL OF PRODUCT #' . $invoiceId,
                                'description' => 'BILL OF PRODUCT #' . $invoiceId,
                            ],
                            'unit_amount' => (getInvoiceCurrencyCode($invoice->currency_id) != 'JPY') ? $amount * 100 : $amount,
                            'currency' => getInvoiceCurrencyCode($invoice->currency_id),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'description' => $transactionNotes,
                ],
                'billing_address_collection' => 'required',
                'client_reference_id' => $request->get('invoiceId'),
                'mode' => 'payment',
                'success_url' => url('client/payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('client/failed-payment?error=payment_cancelled'),
            ]);
            $result = [
                'sessionId' => $session['id'],
                'redirectUrl' => $session['url'],
            ];

            if (! Auth()->check()) {
                return $this->sendResponse($result, 'Session created successfully.');
            }

            return redirect()->away($session['url']);
        } catch (Exception $exception) {
            Log::error($exception);
            if (! Auth()->check()) {

                return $this->sendError($exception->getMessage());
            }
            Notification::make()->danger()->title($exception->getMessage())->send();
            return redirect(route('filament.client.resources.invoices.index'));
        }
    }

    /**
     * @return Application|RedirectResponse|Redirector
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentSuccess(Request $request): RedirectResponse
    {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        $this->stripeRepository->clientPaymentSuccess($sessionId);

        $sessionData = \Stripe\Checkout\Session::retrieve($sessionId);
        $invoiceId = $sessionData->client_reference_id;

        /** @var Invoice $invoice */
        $invoice = Invoice::with(['payments', 'client'])->findOrFail($invoiceId);

        if (! Auth()->check()) {
            return redirect(route('invoice-show-url', $invoice->invoice_id))->with('success', 'Payment successful done.');
        }

        Notification::make()->success()->title('Payment successful done.')->send();
        return redirect()->route('filament.client.resources.invoices.index');
    }

    public function handleFailedPayment(Request $request): RedirectResponse
    {
        Notification::make()->danger()->title('Your Payment is Cancelled')->send();
        return redirect()->route('filament.client.resources.invoices.index');
    }
}
