<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.quote.quote_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <!-- General CSS Files -->

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

        @if (getCurrencySymbol() == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif

        @import url(https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap);
        @import url(https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap);
        @import url(https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap);

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

<body style="padding: 30px 25px !important;">
    @php $styleCss = 'style'; @endphp
    <div>
        <div>
            <div class="logo ml-4"><img width="100px" src="{{ getLogoUrl() }}" alt="logo-image"></div>
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
                            {{ __('messages.quote.quote_name') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-1">
                            <b>{{ __('messages.common.name') . ':' }}&nbsp;</b>{!! $setting['company_name'] !!}<br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b>{!! $setting['company_address'] !!}<br>
                            <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b>{{ $setting['company_phone'] }}<br>
                            @if (!empty($setting['gst_no']))
                                <b>{{ getVatNoLabel() . ':' }}&nbsp;</b>{{ $setting['gst_no'] }}
                            @endif
                        </td>
                        <td class="py-1" style=" overflow:hidden; word-wrap: break-word; word-break: break-all;">
                            <b>{{ __('messages.common.name') . ':' }}&nbsp;</b>{{ $client->user->full_name }}<br>
                            <b>{{ __('messages.common.email') . ':' }}</b>
                            <span style="width:200px; word-break: break-word !important;">
                                {{ $client->user->email }}</span><br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b>{{ $client->address }}
                            @if (!empty($client->vat_no))
                                <br><b>{{ getVatNoLabel() . ':' }}&nbsp;</b>{{ $client->vat_no }}
                            @endif
                        </td>
                        <td class="py-1">
                            <div class="text-nowrap"><b>{{ __('messages.quote.quote_id') . ':' }}</b>
                                &nbsp;#{{ $quote->quote_id }}</div>
                            <div class="text-nowrap">
                                <b>{{ __('messages.quote.quote_date') . ':' }}</b>
                                &nbsp;#{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                            </div>
                            <div class="text-nowrap">
                                <b>{{ __('messages.quote.due_date') . ':' }}</b>
                                &nbsp;#{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table-responsive-sm pt-3">
                <table class="table table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-1" style="width:5%;">#</th>
                            <th class="py-1 text-uppercase">{{ __('messages.product.product') }}</th>
                            <th class="py-1 text-uppercase text-center" style="width:10%;">
                                {{ __('messages.invoice.qty') }}</th>
                            <th class="py-1 text-uppercase text-nowrap text-center" style="width:18%;">
                                {{ __('messages.product.unit_price') }}
                            </th>
                            <th class="py-1 text-uppercase text-nowrap text-center" style="width:12%;">
                                {{ __('messages.invoice.tax') . '(in %)' }}
                            </th>
                            <th class="py-1 text-uppercase  number-align text-nowrap" style="width:18%;">
                                {{ __('messages.invoice.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($quote) && !empty($quote))
                            @foreach ($quote->quoteItems as $key => $quoteItems)
                                <tr>
                                    <td class="py-1">{{ $key + 1 }}</td>
                                    <td class="py-1">
                                        {{ isset($quoteItems->product->name)
                                            ? $quoteItems->product->name
                                            : $quoteItems->product_name ?? __('messages.common.n/a') }}
                                        @if (!empty($quoteItems->product->description) && $setting['show_product_description'] == 1)
                                            <br><span
                                                style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                        @endif
                                    </td>
                                    <td class="py-1 text-center text-nowrap">{{ $quoteItems->quantity }}</td>
                                    <td class="py-1 text-center text-nowrap euroCurrency">
                                        {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                    </td>
                                    <td class="text-center">
                                        @foreach ($quoteItems->quoteItemTax as $keys => $tax)
                                            {{ $tax->tax ?? __('messages.common.tax_n/a') }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="py-1 number-align text-nowrap euroCurrency">
                                        {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <table class="mb-4 w-100">
                <tr>
                    <td class="w-70">
                    </td>
                    <td class="w-30">
                        <table class="w-100">
                            <tbody class="text-end">
                                <tr>
                                    <td>
                                        <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                    </td>
                                    <td class="text-nowrap">
                                        <span class="euroCurrency">{{ getCurrencyAmount($quote->amount, true) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                    </td>
                                    <td class="text-nowrap">
                                        @if ($quote->discount == 0)
                                            <span>{{ __('messages.common.n/a') }}</span>
                                        @else
                                            @if (isset($quote) && $quote->discount_type == \App\Models\Quote::FIXED)
                                                <span
                                                    class="euroCurrency">{{ isset($quote->discount) ? getCurrencyAmount($quote->discount, true) : __('messages.common.n/a') }}</span>
                                            @else
                                                {{ $quote->discount }}<span
                                                    {{ $styleCss }}="font-family: DejaVu Sans">&#37;</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    @php
                                        $itemTaxesAmount = $quote->amount + array_sum($totalTax);
                                        $invoiceTaxesAmount =
                                            ($itemTaxesAmount * $quote->qouteTaxes->sum('value')) / 100;
                                        $totalTaxes = array_sum($totalTax) + $invoiceTaxesAmount;
                                    @endphp
                                    <td class="left">
                                        <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                    </td>

                                    <td class="text-nowrap">
                                        {!! numberFormat($totalTaxes) != 0
                                            ? '<b class="euroCurrency">' . getCurrencyAmount($totalTaxes, true) . '</b>'
                                            : __('messages.common.n/a') !!}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">{{ __('messages.quote.total') . ':' }}</td>
                                    <td class="text-nowrap">
                                        <span
                                            class="euroCurrency">{{ getCurrencyAmount($quote->final_amount, true) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="alert alert-primary text-muted" role="alert">
        <span style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
            <strong class="text-dark">{{ __('messages.client.notes') . ':' }}</strong> {!! nl2br($quote->note ?? __('messages.common.n/a')) !!}
        </span>
    </div>
    <div class="alert alert-light text-muted" role="alert">
        <span style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
            <strong class="text-dark">{{ __('messages.invoice.terms') . ':' }}</strong> {!! nl2br($quote->term ?? __('messages.common.n/a')) !!}
        </span>
    </div>
</body>

</html>
