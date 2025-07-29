<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Invoices PDF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <!-- General CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .custom-font-size-pdf {
            font-size: 11px !important;
        }

        .table thead th {
            font-size: 13px !important;
        }

        .text-center {
            text-align: center;
        }

        h4 {
            font-size: 20px !important;
        }

        body {
            font-family: "Lato", DejaVu Sans, sans-serif;
            font-size: 14px;
        }

        * {
            margin-top: 0;
        }

        th,
        td {
            border: 1px solid lightgray;
            padding: 10px;
        }

        th {
            text-align: left;
        }

        .table {
            margin: auto;
            width: 100%;
        }

        .head-title {
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center mb-4">
        <h4 class="text-center">{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Invoices Export Data</h4>
    </div>
    <table class="table table-bordered border-primary">
        <thead>
            <tr>
                <th style="width: 3%"><b>Invoice ID</b></th>
                <th style="word-break: break-all;width: 10%"><b>Client Name</b></th>
                <th style="width: 12%"><b>Invoice Date</b></th>
                <th style="width: 15%"><b>Invoice Amount</b></th>
                <th style="width: 17%"><b>Paid Amount</b></th>
                <th style="width: 18%"><b>Due Amount</b></th>
                <th style="white-space: nowrap;width: 20%"><b>Due Date</b></th>
                <th style="width: 6%"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>
            @if (count($invoices) > 0)
                @foreach ($invoices as $invoice)
                    <tr class="custom-font-size-pdf">
                        <td>{{ $invoice->invoice_id ?? '' }}</td>
                        <td>{{ $invoice->client->user->FullName }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                        </td>
                        <td class="right-align">
                            {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}</td>
                        <td class="right-align">
                            {{ getInvoicePaidAmount($invoice->id) != 0 ? getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) : '0.00' }}
                        </td>
                        <td class="right-align">
                            {{ getInvoiceDueAmount($invoice->id) != 0 ? getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) : '0.00' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}</td>
                        @if ($invoice->status == \App\Models\Invoice::DRAFT)
                            <td> Draft</td>
                        @elseif($invoice->status == \App\Models\Invoice::UNPAID)
                            <td> Unpaid</td>
                        @elseif($invoice->status == \App\Models\Invoice::PAID)
                            <td> Paid</td>
                        @elseif($invoice->status == \App\Models\Invoice::PARTIALLY)
                            <td> Partially Paid</td>
                        @elseif($invoice->status == \App\Models\Invoice::OVERDUE)
                            <td> Overdue</td>
                        @elseif($invoice->status == \App\Models\Invoice::PROCESSING)
                            <td> Processing</td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="8"></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
