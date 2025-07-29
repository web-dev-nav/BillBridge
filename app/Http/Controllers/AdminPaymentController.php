<?php

namespace App\Http\Controllers;

use App\Exports\AdminPaymentsExport;
use App\Http\Requests\CreateAdminPaymentRequest;
use App\Models\AdminPayment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\AdminPaymentRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminPaymentController extends Controller
{
    /** @var AdminPaymentRepository */
    public $adminPaymentRepository;

    public function __construct(AdminPaymentRepository $adminPaymentRepo)
    {
        $this->adminPaymentRepository = $adminPaymentRepo;
    }

    public function exportAdminPaymentsExcel(Request $request): BinaryFileResponse
    {
        return Excel::download(new AdminPaymentsExport($request->date), 'Payment-Excel.xlsx');
    }

    public function exportAdminPaymentsPDF(Request $request)
    {
        // $timeEntryDate = explode(' - ', $request->date);
        // $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
        // $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
        $data['adminPayments'] = AdminPayment::with(['invoice.client.user'])->get();
        $paymentsPDF = Pdf::loadView('payments.export_pdf', $data);

        return $paymentsPDF->download('payments.pdf');
    }

    public function getCurrentDateFormat()
    {
        return currentDateFormat();
    }
}
