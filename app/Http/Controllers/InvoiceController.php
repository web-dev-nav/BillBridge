<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use App\Exports\AdminInvoicesExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientInvoicesExport;
use Illuminate\Contracts\View\Factory;
use App\Repositories\PaymentRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function convertToPdf(Invoice $invoice)
    {
        ini_set('max_execution_time', 36000000);
        $invoice->load(['client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax', 'invoiceTaxes', 'paymentQrCode']);
        $invoiceData = $this->getPdfData($invoice);
        $invoiceTemplate = $this->getDefaultTemplate($invoice);
        $locale = session('locale', 'en');

        if ($locale === 'zh') {
            App::setLocale('en');
        } else {
            App::setLocale($locale);
        }
        $pdf = Pdf::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function getDefaultTemplate($invoice)
    {
        $data['invoice_template_name'] = $invoice->invoiceTemplate->key;

        return $data['invoice_template_name'];
    }


    public function showPublicPayment($invoiceId): Factory|View|Application
    {
        /** @var PaymentRepository $paymentRepo */
        $paymentRepo = App::make(PaymentRepository::class);

        /** @var Invoice $invoice */
        $invoice = Invoice::with('client.user')->whereInvoiceId($invoiceId)->firstOrFail();
        $totalPayable = $paymentRepo->getTotalPayable($invoice);
        $paymentType = Payment::PAYMENT_TYPE;
        $paymentMode = $this->getPaymentGateways();
        $userLang = $invoice->client->user->language;

        $stripeKey = getSettingValue('stripe_key');
        if (empty($stripeKey)) {
            $stripeKey = config('services.stripe.key');
        }

        return view(
            'invoices.public-invoice.payment',
            compact('paymentType', 'paymentMode', 'totalPayable', 'stripeKey', 'invoice', 'userLang')
        );
    }


    public function getPdfData($invoice): array
    {
        $data = [];
        $data['invoice'] = $invoice;
        $data['client'] = $invoice->client;
        $invoiceItems = $invoice->invoiceItems;
        $data['invoice_template_color'] = $invoice->invoiceTemplate->template_color;
        $data['totalTax'] = [];
        $imageData = '';
        $imageType = '';
        $base64Image = '';
        
        if ($invoice->paymentQrCode && $invoice->paymentQrCode->qr_image) {
            $qrImagePath = $invoice->paymentQrCode->qr_image;
            
            // Check if it's a URL or a local path
            if (filter_var($qrImagePath, FILTER_VALIDATE_URL)) {
                // If it's a URL, check if it's a local URL
                $localUrl = parse_url($qrImagePath);
                $currentUrl = parse_url(config('app.url'));
                
                if ($localUrl['host'] === $currentUrl['host'] || $localUrl['host'] === '127.0.0.1' || $localUrl['host'] === 'localhost') {
                    // Convert to local file path
                    $relativePath = ltrim($localUrl['path'], '/');
                    $localPath = public_path($relativePath);
                    
                    if (file_exists($localPath)) {
                        $imageData = file_get_contents($localPath);
                        $imageType = pathinfo($localPath, PATHINFO_EXTENSION);
                    }
                } else {
                    // External URL, use HTTP request
                    try {
                        $imageData = Http::timeout(10)->get($qrImagePath)->body();
                        $imageType = pathinfo($qrImagePath, PATHINFO_EXTENSION);
                    } catch (\Exception $e) {
                        // Log error and continue without image
                        \Log::warning('Failed to fetch QR code image: ' . $e->getMessage());
                    }
                }
            } else {
                // It's already a local path
                $localPath = public_path($qrImagePath);
                if (file_exists($localPath)) {
                    $imageData = file_get_contents($localPath);
                    $imageType = pathinfo($localPath, PATHINFO_EXTENSION);
                }
            }
            
            if ($imageData) {
                $base64Image = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
            }
        }

        $data['qrImg'] = $base64Image;

        foreach ($invoiceItems as $keys => $item) {
            $totalTax = $item->invoiceItemTax->sum('tax');
            $data['totalTax'][] = $item['quantity'] * $item['price'] * $totalTax / 100;
        }

        $data['setting'] = Setting::toBase()->pluck('value', 'key')->toArray();

        return $data;
    }

    public function getPublicInvoicePdf($invoiceId)
    {
        $invoice = Invoice::whereInvoiceId($invoiceId)->firstOrFail();
        $invoice->load('client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax');
        $invoiceData = $this->getPdfData($invoice);
        $invoiceTemplate = $this->getDefaultTemplate($invoice);
        $pdf = Pdf::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function showPublicInvoice($invoiceId): View|Factory|Application
    {
        $invoice = Invoice::with('client.user')->whereInvoiceId($invoiceId)->firstOrFail();
        $invoiceData = $this->getInvoiceData($invoice);
        $invoiceData['statusArr'] = Invoice::STATUS_ARR;
        $invoiceData['status'] = $invoice->status;
        unset($invoiceData['statusArr'][Invoice::DRAFT]);
        $invoiceData['paymentType'] = Payment::PAYMENT_TYPE;
        $invoiceData['paymentMode'] = $this->getPaymentGateways();
        $invoiceData['stripeKey'] = getSettingValue('stripe_key');
        $invoiceData['userLang'] = $invoice->client->user->language;
        $language = $invoice->client->user->language ?? 'en';
        App::setLocale($language == 'zh' ? 'en' : $language);

        return view('invoices.public-invoice.public_view')->with($invoiceData);
    }

    public function getInvoiceData($invoice): array
    {
        $data = [];

        $invoice = Invoice::with([
            'client' => function ($query) {
                $query->select(['id', 'user_id', 'address']);
                $query->with([
                    'user' => function ($query) {
                        $query->select(['first_name', 'last_name', 'email', 'id', 'language']);
                    },
                ]);
            },
            'parentInvoice',
            'payments',
            'invoiceItems' => function ($query) {
                $query->with(['product', 'invoiceItemTax']);
            },
            'invoiceTaxes',
        ])->withCount('childInvoices')->whereId($invoice->id)->first();

        $data['invoice'] = $invoice;
        $invoiceItems = $invoice->invoiceItems;
        $data['totalTax'] = [];
        foreach ($invoiceItems as $item) {
            $totalTax = $item->invoiceItemTax->sum('tax');
            $data['totalTax'][] = $item['quantity'] * $item['price'] * $totalTax / 100;
        }

        return $data;
    }

    public function getPaymentGateways(): array
    {
        $paymentMode = Payment::PAYMENT_MODE;
        $availableMode = [
            Payment::PAYPAL => 'paypal_enabled',
            Payment::RAZORPAY => 'razorpay_enabled',
            Payment::STRIPE => 'stripe_enabled',
            Payment::PAYSTACK => 'paystack_enabled',
            Payment::MERCADOPAGO => 'mercadopago_enabled',
        ];
        foreach ($availableMode as $key => $mode) {
            if (! getSettingValue($mode)) {
                unset($paymentMode[$key]);
            }
        }
        unset($paymentMode[Payment::CASH]);
        unset($paymentMode[Payment::ALL]);

        return $paymentMode;
    }

    public function exportInvoicesExcel()
    {
        return Excel::download(new AdminInvoicesExport(), 'invoice-excel.xlsx');
    }

    public function exportInvoicesPdf()
    {
        ini_set('max_execution_time', 3600000000);
        $data['invoices'] = Invoice::with('client.user', 'payments')->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('invoices.export_invoices_pdf', $data);

        return $pdf->download('Invoices.pdf');
    }

    //?client
    public function clientExportInvoicesExcel()
    {
        return Excel::download(new ClientInvoicesExport(), 'invoice-excel.xlsx');
    }

    public function clientExportInvoicesPdf()
    {
        ini_set('max_execution_time', 3600000000);
        ini_set('memory_limit', '512M');
        $data['invoices'] = Invoice::whereClientId(Auth::user()->client->id)
            ->where('status', '!=', Invoice::DRAFT)
            ->with('payments')->orderBy('created_at', 'desc')->get();

        $clientInvoicesPdf = Pdf::loadView('invoices.export_invoices_pdf', $data);

        return $clientInvoicesPdf->download('Client-Invoices.pdf');
    }

    public function clientConvertToPdf(Invoice $invoice)
    { {
            $invoice->load('client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax');
            if (getLogInUserId() != $invoice->client->user->id) {
                abort(404);
            }
            $invoiceData = $this->getPdfData($invoice);
            $invoiceTemplate = $this->getDefaultTemplate($invoice);
            $pdf = Pdf::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

            return $pdf->stream('invoice.pdf');
        }
    }
}
