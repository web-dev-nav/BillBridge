<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AdminQuotesExport;
use Illuminate\Support\Facades\App;
use App\Exports\ClientQuotesExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\QuoteRepository;

class QuoteController extends Controller
{
    public $quoteRepository;

    public function __construct(QuoteRepository $quoteRepo)
    {
        $this->quoteRepository = $quoteRepo;
    }
    public function convertToPdf(Quote $quote)
    {
        ini_set('max_execution_time', 36000000);
        $quote->load('client.user', 'invoiceTemplate', 'quoteItems.product', 'quoteItems');
        $quoteData = $this->quoteRepository->getPdfData($quote);
        $invoiceTemplate = $this->getDefaultTemplate($quote);
        $locale = session('locale', 'en');
        if ($locale === 'zh') {
            App::setLocale('en');
        } else {
            App::setLocale($locale);
        }
        $pdf = Pdf::loadView("quotes.quote_template_pdf.$invoiceTemplate", $quoteData);

        return $pdf->stream('quote.pdf');
    }

    public function getPublicQuotePdf($quoteId)
    {
        $quote = Quote::whereQuoteId($quoteId)->firstOrFail();
        $quote->load('client.user', 'invoiceTemplate', 'quoteItems.product', 'quoteItems');

        $quoteData = $this->quoteRepository->getPdfData($quote);
        $invoiceTemplate = $this->quoteRepository->getDefaultTemplate($quote);
        $pdf = Pdf::loadView("quotes.quote_template_pdf.$invoiceTemplate", $quoteData);

        return $pdf->stream('quote.pdf');
    }



    public function exportQuotesExcel()
    {
        return Excel::download(new AdminQuotesExport(), 'quote-excel.xlsx');
    }

    public function exportQuotesPdf()
    {
        ini_set('max_execution_time', 36000000);
        $data['quotes'] = Quote::with('client.user')->orderBy('created_at', 'desc')->get();
        $quotesPdf = Pdf::loadView('quotes.export_quotes_pdf', $data);

        return $quotesPdf->download('Quotes.pdf');
    }

    public function showPublicQuote($quoteId)
    {
        $quote = Quote::with('client.user')->whereQuoteId($quoteId)->first();
        $quoteData = $this->quoteRepository->getQuoteData($quote);
        $quoteData['statusArr'] = Quote::STATUS_ARR;
        $quoteData['status'] = $quote->status;
        $quoteData['userLang'] = $quote->client->user->language;
        $language = $quote->client->user->language ?? 'en';
        App::setLocale($language == 'zh' ? 'en' : $language);

        return view('quotes.public-quote.public_view')->with($quoteData);
    }

    public function getDefaultTemplate($quote): mixed
    {
        $data['invoice_template_name'] = $quote->invoiceTemplate->key;

        return $data['invoice_template_name'];
    }

    //? client
    public function clientExportQuotesExcel()
    {
        return Excel::download(new ClientQuotesExport(), 'quote-excel.xlsx');
    }

    public function clientExportQuotesPdf()
    {
        $data['quotes'] = Quote::with('client.user')->where('client_id', Auth::user()
            ->client->id)->orderBy('created_at', 'desc')->get();
        $clientQuotesPdf = Pdf::loadView('quotes.export_quotes_pdf', $data);

        return $clientQuotesPdf->download('Client-Quotes.pdf');
    }

    public function clientConvertToPdf(Quote $quote)
    {
        $clientId = Auth::user()->client->id;
        ini_set('max_execution_time', 36000000);
        $quote->load('client.user', 'invoiceTemplate', 'quoteItems.product', 'quoteItems');

        if ($clientId != $quote->client_id) {
            abort(404);
        }

        $quoteData = $this->quoteRepository->getPdfData($quote);
        $quoteTemplate = $this->getDefaultTemplate($quote);
        $pdf = Pdf::loadView("quotes.quote_template_pdf.$quoteTemplate", $quoteData);

        return $pdf->stream('quote.pdf');
    }
}
