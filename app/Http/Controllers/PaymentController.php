<?php

namespace App\Http\Controllers;

use App\Exports\AdminTransactionsExport;
use App\Models\Payment;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentController extends Controller
{
    public function exportTransactionsExcel(): BinaryFileResponse
    {
        return Excel::download(new AdminTransactionsExport(), 'transaction.xlsx');
    }

    public function exportTransactionsPdf(): Response
    {
        ini_set('max_execution_time', 36000000);
        $data['payments'] = Payment::with(['invoice.client.user'])->orderBy('created_at', 'desc')->get();
        $transactionsPdf = Pdf::loadView('transactions.export_transactions_pdf', $data);

        return $transactionsPdf->download('Transactions.pdf');
    }

    public function downloadAttachment($transactionId)
    {
        /** @var Payment $transaction */
        $transaction = Payment::with('media')->findOrFail($transactionId);
        $attachment = $transaction->media->first();

        if ($attachment) {
            return $attachment;
        }

        return null;
    }
}
