<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>Payments PDF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <!-- General CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css"/>
    <style>
         *{
            margin-top: 0;
        }
        .custom-font-size-pdf {
            font-size: 11px !important;
        }

        .table thead th {
            font-size: 12px !important;
        }
        .text-center{
            text-align: center;
        }
        h4{
            font-size: 20px !important;
        }
        body {
          font-family: "Lato", DejaVu Sans, sans-serif;
          font-size: 14px;
        }
        th, td {
            border:1px solid lightgray;
            padding:10px;
        }
        th{
            text-align: left;
        }
        .d-flex{
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        .table{
            margin:auto;
            width: 100%;
            border-collapse: collapse;
        }
        .head-title{
            margin-top:25px;
        }
    </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-center mb-4">
    <h4 class="text-center head-title">Payments Export Data</h4>
</div>
<table class="table table-bordered border-primary">
    <thead>
    <tr>
        <th style="width: 18%"><b>Payment Date</b></th>
        <th style="width: 15%"><b>Invoice ID</b></th>
        <th style="word-break: break-all;width: 20%"><b>Client Name</b></th>
        <th style="width: 27%"><b>Payment Amount</b></th>
        <th style="width: 20%"><b>Payment Method</b></th>
    </tr>
    </thead>
    <tbody>
    @if(count($adminPayments) > 0)
        @foreach($adminPayments as $payment)
            <tr class="custom-font-size-pdf">
                <td>{{ Carbon\Carbon::parse($payment->payment_date)->format(currentDateFormat()) }}</td>
                <td>{{ $payment->invoice->invoice_id }}</td>
                <td>{{ $payment->invoice->client->user->full_name }}</td>
                <td style="text-align: right">{{ getInvoiceCurrencyAmount($payment->amount, $payment->invoice->currency_id, true) }}</td>
                @if($payment->payment_mode == \App\Models\Payment::CASH)
                    <td> Cash</td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" class="text-center">{{ __('messages.no_records_found') }}</td>
        </tr>
    @endif
    </tbody>
</table>
</body>
</html>
