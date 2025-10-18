<?php

use App\Models\Role;
use App\Livewire\ResetClientPassword;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Client as Client;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminPaymentController;
use Illuminate\Support\Facades\Artisan;


Route::get('/', function () {
    // Check if application is installed
    $isInstalled = file_exists(storage_path('installed')) || file_exists(storage_path('installer.lock'));

    if (!$isInstalled) {
        return redirect()->route('installer.welcome');
    }

    if (!auth()->check()) {
        return redirect()->route('filament.admin.auth.login');
    }
    if (auth()->user()->hasRole(Role::ROLE_ADMIN)) {
        return redirect()->route('filament.admin.pages.dashboard');
    }

    if (auth()->user()->hasRole(Role::ROLE_CLIENT)) {
        return redirect()->route('filament.client.pages.dashboard');
    }
});
Route::get('quote/{quoteId}', [QuoteController::class, 'showPublicQuote'])->name('quote-show-url');
Route::get('invoice/{invoiceId}', [InvoiceController::class, 'showPublicInvoice'])->name('invoice-show-url');
Route::get(
    'quote-pdf/{quote}',
    [QuoteController::class, 'getPublicQuotePdf']
)->name('public-view-quote.pdf');

Route::get(
    'invoice/{invoiceId}/payment',
    [InvoiceController::class, 'showPublicPayment']
)->name('invoices.public-payment');


//? for Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    //? invoice
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'convertToPdf'])->name('pdf');
    });
    Route::get('invoices-pdf', [InvoiceController::class, 'exportInvoicesPdf'])->name('admin.invoices.pdf');
    Route::get('/invoices-excel', [InvoiceController::class, 'exportInvoicesExcel'])->name('admin.invoicesExcel');
    //? quote
    Route::get('/quotes-excel', [QuoteController::class, 'exportQuotesExcel'])->name('admin.quotesExcel');
    Route::get('quotes-pdf', [QuoteController::class, 'exportQuotesPdf'])->name('admin.quotes.pdf');
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'convertToPdf'])->name('quotes.pdf');
});
Route::get('/client-onboard/{id}', ResetClientPassword::class)->name('client.password.reset')->middleware('setLanguageFront');

//? for Client
Route::middleware(['auth', 'role:client'])->group(function () {
    //? invoice
    Route::get('/invoice-excel', [InvoiceController::class, 'clientExportInvoicesExcel'])->name('client.invoicesExcel');
    Route::get('invoice-pdf', [InvoiceController::class, 'clientExportInvoicesPdf'])->name('client.invoices.pdf');
    Route::get('invoice/{invoice}/pdf', [InvoiceController::class, 'clientConvertToPdf'])->name('clients.invoices.pdf');
    //? quote
    Route::get('/quote-excel', [QuoteController::class, 'clientExportQuotesExcel'])->name('client.quotesExcel');
    Route::get('quote-pdf', [QuoteController::class, 'clientExportQuotesPdf'])->name('client.export.quotes.pdf');
    Route::get('quote/{quote}/pdf', [QuoteController::class, 'clientConvertToPdf'])->name('client.quotes.pdf');

    // transactions
    Route::get('client-transactions-excel', [client\PaymentController::class, 'exportTransactionsExcel'])->name('client.transactionsExcel');
    Route::get('client-transactions-pdf', [client\PaymentController::class, 'exportTransactionsPdf'])->name('client.export.transactions.pdf');
});

Route::get('invoice-pdf/{invoice}', [InvoiceController::class, 'getPublicInvoicePdf'])->name('public-view-invoice.pdf');
Route::get('transactions-attachment/{id}', [PaymentController::class, 'downloadAttachment'])->name('transaction.attachment');

// export payments excel admin route
Route::get(
    'admin-payments-excel',
    [AdminPaymentController::class, 'exportAdminPaymentsExcel']
)->name('admin.paymentsExcel');

// export payments pdf admin route
Route::get(
    'admin-payments-pdf',
    [AdminPaymentController::class, 'exportAdminPaymentsPDF']
)->name('admin.payments.pdf');
Route::get(
    'transactions-excel',
    [PaymentController::class, 'exportTransactionsExcel']
)->name('admin.transactionsExcel');
// export transactions pdf admin route
Route::get(
    'transactions-pdf',
    [PaymentController::class, 'exportTransactionsPdf']
)->name('admin.export.transactions.pdf');

Route::prefix('client')->group(function () {

    //Payments
    Route::post('payments', [Client\PaymentController::class, 'store'])->name('clients.payments.store');
    Route::post('stripe-payment', [Client\StripeController::class, 'createSession'])->name('client.stripe-payment');
    Route::get('razorpayonboard', [Client\RazorpayController::class, 'onBoard'])->name('razorpay.init');
    Route::get('paypal-onboard', [Client\PaypalController::class, 'onBoard'])->name('paypal.init');

    Route::get('payment-success', [Client\StripeController::class, 'paymentSuccess'])->name('payment-success');
    Route::get('failed-payment', [Client\StripeController::class, 'handleFailedPayment'])->name('failed-payment');

    Route::get('paypal-payment-success', [Client\PaypalController::class, 'success'])->name('paypal.success');
    Route::get('paypal-payment-failed', [Client\PaypalController::class, 'failed'])->name('paypal.failed');

    Route::get(
        'invoices/{invoice}',
        [Client\InvoiceController::class, 'show']
    )->name('invoices.show');

    // razorpay payment
    Route::post('razorpay-payment-success', [Client\RazorpayController::class, 'paymentSuccess'])
        ->name('razorpay.success');
    Route::get('razorpay-payment-failed', [Client\RazorpayController::class, 'paymentFailed'])
        ->name('razorpay.failed');
    Route::get('razorpay-payment-webhook', [Client\RazorpayController::class, 'paymentSuccessWebHook'])
        ->name('razorpay.webhook');

    // Paystack Payment Route
    Route::get('/paystack-onboard', [Client\PaystackController::class, 'redirectToGateway'])->name('client.paystack.init');
    Route::any(
        '/paystack-payment-success',
        [Client\PaystackController::class, 'handleGatewayCallback']
    )->name('client.paystack.success');

    Route::get('mercadopago-success', [Client\MercadopagoController::class, 'success'])->name('mercadopago.success')->withoutMiddleware('auth');
});

Route::get('/upgrade/database', function () {
    if (config('app.enable_upgrade_route')) {
        Artisan::call('migrate', [
            '--force' => true,
        ]);

        return redirect(route('filament.admin.auth.login'));
    }

    return redirect(route('filament.admin.auth.login'));
});

Route::get(
    'invoices/{invoice}',
    [Client\InvoiceController::class, 'show']
)->name('client.invoices.show');
