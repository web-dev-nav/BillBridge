<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        @page {
            margin-top: 40px !important;
        }

        .w-100 {
            width: 100%;
        }

        @if (getInvoiceCurrencyIcon($invoice->currency_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif



        .invoice-header {
            text-align: left;
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        .border-top {
            border-top: 1px solid #83b130 !important;
        }

        .border-bottom {
            border-bottom: 1px solid #83b130 !important;
        }

        .border-bottom-gray {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        .border-0 {
            border: 0px solid white !important;
        }

        .product-table,
        .total-table {
            margin: 0;
        }

        .product-table tr th,
        .product-table tr td,
        .total-table tr th,
        .total-table tr td {
            border: 0px solid white !important;
            padding: 6px 0 !important;
        }

        .text-end {
            text-align: right !important;
        }

        .companylogo {
            text-align: left;
            margin: 0;
            padding: 0;
        }

        .invoice-header-inner {
            text-align: right;
        }

        .invoice-header-inner h3 {
            margin: 0;
            padding: 0;
        }

        .details-section {
            margin-bottom: 3rem;
        }

        .invoice-header p {
            color: #555;
            font-size: 16px;
            margin: 5px 0;
        }

        .text-color {
            color: #999999;
        }

        .invoice-date {
            padding: 15px 0;
            border-top: 1px solid #c0c0c0;
            border-right: 1px solid #c0c0c0;
            border-bottom: 1px solid #c0c0c0;
        }

        .billedto {
            padding: 15px 20px;
            border-top: 1px solid #c0c0c0;
            border-right: 1px solid #c0c0c0;
            border-bottom: 1px solid #c0c0c0;
        }

        .from {
            padding: 15px 20px;
            border-top: 1px solid #c0c0c0;
            border-left: 1px solid #c0c0c0;
            border-bottom: 1px solid #c0c0c0;
        }

        .notes-terms {
            margin-top: 3rem;
            padding: 0 15px;
        }

        .regards {
            margin-top: 2rem;
            padding: 0 15px;
        }

        body {
            font-family: "Lato", DejaVu Sans, sans-serif;
            padding: 30px;
            font-size: 14px;
        }

        .font-color-gray {
            color: #7a7a7a;
        }

        .main-heading {
            font-size: 34px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-right {
            text-align: right;
            vertical-align: top;
        }

        .logo,
        .company-name {
            margin-bottom: 8px;
            margin-left: 15px;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .address {
            margin-top: 60px;
        }

        .address tr:first-child td {
            padding-bottom: 10px;
        }

        .d-items-table {
            width: 100%;
            border: 0;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .d-items-table thead {
            background: #2f353a;
            color: #fff;
        }

        .d-items-table td,
        .d-items-table th {
            padding: 8px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            text-align: left;
            vertical-align: top;
        }

        .d-invoice-footer {
            margin-top: 15px;
            width: 80%;
            float: right;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .items-table td,
        .items-table th {
            padding: 8px;
            font-size: 14px;
            text-align: left;
            vertical-align: top;
        }

        .invoice-footer {
            margin-top: 15px;
            width: 100%;
            text-align: right;
        }

        .number-align {
            text-align: right !important;
        }

        .invoice-currency-symbol {
            font-family: "DejaVu Sans";
        }

        .vertical-align-top {
            vertical-align: text-top;
        }

        .tu {
            text-transform: uppercase;
        }

        .l-col-66 {
            width: 100%;
        }

        .thank {
            font-size: 45px;
            line-height: 1.2em;
            text-align: center;
            font-style: italic;
            padding-right: 25px;
        }

        .to-font-size {
            font-size: 15px;
        }

        .from-font-size {
            font-size: 15px;
        }

        .right-align {
            text-align: right !important;
        }

        .border-b {
            border-bottom: 1px solid #000000;
        }

        .border-t {
            border-top: 1px solid #000000;
        }

        .bg-black {
            background-color: #000000;
        }

        .bg-gray {
            background-color: #eaebec;
        }

        .bg-gray-100 {
            background-color: #f2f2f2;
        }

        .bg-danger {
            background-color: #d71920;
        }

        .bg-purple {
            background-color: #b57ebf;
        }

        .text-purple {
            color: #b57ebf;
        }

        .border-b-gray {
            border-bottom: 1px solid #bbbdbf;
        }

        .text-end {
            text-align: right !important;
        }

        .ps-5rem {
            padding-left: 5rem;
        }

        .header-section {
            position: relative !important;
            overflow: hidden;
        }

        .header-section::after {
            position: absolute;
            content: "";
            width: 116% !important;
            height: 100%;
            background-color: white;
            top: 0;
            left: -53px;
            transform: skew(35deg);
            z-index: 0;
        }

        .header-section table {
            position: relative;
            z-index: 2;
        }

        .header-section .invoice-text {
            position: relative !important;
        }

        .header-section .invoice-text::after {
            position: absolute;
            content: "";
            width: 26%;
            height: 100%;
            background-color: white;
            top: 0;
            left: 93%;
            transform: skew(35deg);
        }

        .p-10px {
            padding: 10px;
        }

        .font-black-900 {
            color: #242424;
        }

        .fw-6 {
            font-weight: bolder;
        }

        .text-yellow-500 {
            color: #fab806;
        }

        .text-green {
            color: #9dc23b;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .img-logo {
            max-width: 100px;
            max-height: 66px;
        }

        @media (max-width: 424px) {
            .img-logo {
                max-width: 75px;
            }
        }

        .w-10 {
            width: 10%;
        }

        .w-30 {
            width: 30% !important;
        }

        .py-10 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }


        .py-2 {
            /* padding-top: 15px !important; */
            padding-bottom: 10px !important;
        }

        .pe-10 {
            padding-right: 2.5rem !important;
        }

        .ps-sm-10 {
            padding-left: 2.5rem !important;
        }

        .fs-5 {
            font-size: 0.938rem !important;
        }

        .my-4 {
            margin-top: 1.5rem !important;
        }

        .pt-2 {
            padding-top: 20px !important;
        }

        .pt-1 {
            padding-top: 10px !important;
        }

        .py-1 {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        .px-2 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .p-2 {
            padding: 10px !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table>thead>tr>th,
        .table>tbody>tr>td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .table>thead>tr>th {
            background-color: #f8f8f8;
        }

        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        .border-none {
            border: none !important;
        }
    </style>
</head>

<body style="padding: 30px 30px !important;">
    @php $styleCss = 'style'; @endphp
    <div>
        <div class="ml-2">
            <div class="logo"><img width="100px" src="{{ getPDFLogoUrl() }}" alt="no-image">
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered w-100">
                <thead class="bg-light">
                    <tr>
                        <th class="py-1 text-uppercase" style="width:33.33% !important;">
                            {{ __('messages.common.from') }}</th>
                        <th class="py-1 text-uppercase" style="width:33.33% !important;">{{ __('messages.common.to') }}
                        </th>
                        <th class="py-1 text-uppercase" style="width:33.33% !important;">
                            {{ __('messages.common.invoice') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-1">
                            <b>{{ __('messages.common.name') . ':' }}&nbsp;</b>{{ $setting['company_name'] }}<br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b><span
                                class="text-break">{!! $setting['company_address'] !!}</span><br>
                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                <div>
                                    {{ $setting['country'] . ', ' . $setting['state'] . ', ' . $setting['city'] . ', ' . $setting['zipcode'] . '.' }}
                                </div>
                            @endif
                            <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b>{{ $setting['company_phone'] }}<br>
                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                <div><b>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</b>{{ $setting['fax_no'] }}
                                </div>
                            @endif
                            @if (!empty($setting['gst_no']))
                                <b>{{ getVatNoLabel() . ':' }}&nbsp;</b>{{ $setting['gst_no'] }}
                            @endif
                        </td>
                        <td class="py-1"
                            style=" overflow:hidden; word-wrap: break-word;
                word-break: break-all;">
                            <b>{{ __('messages.common.name') . ':' }}&nbsp;</b>{{ $client->user->full_name }}<br>
                            <b>{{ __('messages.common.email') . ':' }}&nbsp;</b>
                            <div style="width:200px; word-break: break-all!important; ">
                                {{ $client->user->email }}</div>
                            @if (!empty($client->address))
                                <b>{{ __('messages.common.address') . ':' }}&nbsp;</b>{{ $client->address }}
                            @endif
                            @if (!empty($client->vat_no))
                                <br><b>{{ getVatNoLabel() . ':' }}&nbsp;</b>{{ $client->vat_no }}
                            @endif
                        </td>
                        <td class="py-1">
                            <div class="text-nowrap">
                                <b>{{ __('messages.invoice.invoice_id') . ':' }}</b>
                                &nbsp;#{{ $invoice->invoice_id }}
                            </div>
                            <div>
                                <b>{{ __('messages.invoice.invoice_date') . ':' }}</b>
                                &nbsp;#{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                            </div>
                            <div>
                                <b>{{ __('messages.invoice.due_date') . ':' }}</b>
                                &nbsp;#{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table table-responsive-sm py-10">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-1" style="width:5%;">#</th>
                            <th class="py-1 text-uppercase">{{ __('messages.product.product') }}</th>
                            <th class="py-1 text-uppercase" style="width:9%;">{{ __('messages.invoice.qty') }}</th>
                            <th class="py-1 text-uppercase text-nowrap" style="width:13%;">
                                {{ __('messages.product.unit_price') }}
                            </th>
                            <th class="py-1 text-uppercase text-nowrap" style="width:12%;">
                                {{ __('messages.invoice.tax') . '(in %)' }}</th>
                            <th class="py-1 text-uppercase  number-align text-nowrap" style="width:14%;">
                                {{ __('messages.invoice.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        @if (isset($invoice) && !empty($invoice))
                            @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if (
                                        !empty($invoiceItems->product->description) &&
                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                        <td style="width: 30%!important;" class="py-0">
                                        @else
                                        <td>
                                    @endif
                                    <p class="m-0 chinese-text"
                                        style="width:150px!important;word-wrap: break-word;
                                word-break: break-all;">
                                        {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                    </p>
                                    @if (
                                        !empty($invoiceItems->product->description) &&
                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                        <span
                                            style="font-size: 12px; word-break: break-all">{{ $invoiceItems->product->description }}</span>
                                    @endif
                                    </td>
                                    <td class="text-start text-nowrap">{{ number_format($invoiceItems->quantity, 2) }}
                                    </td>
                                    <td class="text-start text-nowrap euroCurrency">
                                        {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                            {{ $tax->tax ?? '--' }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="number-align text-nowrap euroCurrency">
                                        {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <table class="mb-4 w-100 border-none">
                <tr>
                    <td class="w-75">
                        @if (!empty($invoice->paymentQrCode))
                            <div style="">
                                <strong
                                    style="font-size: ; margin-right: 142px"><b>{{ __('messages.payment_qr_codes.payment_qr_code') }}</b></strong><br>
                                <img class="mt-2 ml-3" src="{{ $qrImg }}" height="110" width="110"
                                    alt="qr-code-image">
                            </div>
                        @endif
                    </td>
                    <td class="w-25 text-end">
                        <table>
                            <tbody class="text-end">
                                <tr>
                                    <td class="left" style="padding-right: 30px">
                                        <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                    </td>
                                    <td class="euroCurrency">
                                        {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left" style="padding-right: 30px">
                                        <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                    </td>
                                    <td class="right">
                                        @if ($invoice->discount == 0)
                                            <span>{{ __('messages.common.n/a') }}</span>
                                        @else
                                            @if (isset($invoice) && $invoice->discount_type == \App\Models\Invoice::FIXED)
                                                <b
                                                    class="euroCurrency">{{ isset($invoice->discount) ? getInvoiceCurrencyAmount($invoice->discount, $invoice->currency_id, true) : __('messages.common.n/a') }}</b>
                                            @else
                                                {{ $invoice->discount }}<span
                                                    {{ $styleCss }}="font-family: "Arial-unicode-ms;">&#37;</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    @php
                                        $itemTaxesAmount = $invoice->amount + array_sum($totalTax);

                                        $invoiceTaxesAmount =
                                            ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value')) / 100;
                                        $totalTaxes = array_sum($totalTax) + $invoiceTaxesAmount;
                                    @endphp
                                    <td class="left" style="padding-right: 30px">
                                        <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                    </td>

                                    <td class="right text-nowrap">
                                        {!! numberFormat($totalTaxes) != 0
                                            ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                            : __('messages.common.n/a') !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold" style="padding-right: 30px">
                                        {{ __('messages.invoice.total') . ':' }}</td>
                                    <td class="text-nowrap">
                                        <b
                                            class="euroCurrency">{{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold" style="padding-right: 30px">
                                        {{ __('messages.admin_dashboard.total_due') . ':' }}
                                    </td>
                                    <td class="text-nowrap" {{ $styleCss }}="color: red">
                                        <b
                                            class="euroCurrency">{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-nowrap" style="padding-right: 30px">
                                        {{ __('messages.admin_dashboard.total_paid') . ':' }}</td>
                                    <td class="text-nowrap" {{ $styleCss }}="color: green">
                                        <b
                                            class="euroCurrency">{{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    @if (!empty($invoice->note))
        <div class="alert alert-primary text-muted" role="alert">
            <span style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                <strong style="color: #000000">{{ __('messages.client.notes') . ':' }}</strong>
                {!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
            </span>
        </div>
    @endif

    @if (!empty($invoice->term))
        <div class="alert alert-light text-muted" role="alert">
            <span style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                <strong style="color: #000000">{{ __('messages.invoice.terms') . ':' }}</strong>
                {!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}
            </span>

        </div>
    @endif
</body>

</html>
