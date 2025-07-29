<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.quote.quote_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
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

        .istanbul-template {
            font-family: "Open Sans", sans-serif;
        }

        .istanbul-template strong {
            font-weight: bold;
        }

        .istanbul-template .invoice-header .heading-text {
            position: relative;
            z-index: 2;
        }

        .istanbul-template .invoice-table {
            border-bottom: 0.5px solid #c6c6c6;
            font-family: "Inter", sans-serif;
        }

        .istanbul-template .invoice-table thead {
            background-color: #fb5c3a;
            color: white;
        }

        .istanbul-template .bottom-line {
            height: 30px;
            width: 100%;
            background-color: #fb3f01;
            position: relative;
            overflow: hidden;
        }

        .istanbul-template .bottom-line::after {
            position: absolute;
            content: "";
            width: 62%;
            height: 100%;
            background-color: #0e1c45;
            top: 0;
            left: -15px;
            transform: skew(35deg);
            z-index: 0;
        }

        .font-gray-900 {
            color: #1a1c21 !important;
        }

        .font-gray-600 {
            color: #5e6470 !important;
        }

        .font-orange {
            color: #fb3f01;
        }

        .border-top-gray {
            border-top: 1px solid #c6c6c6;
        }

        .z-10 {
            z-index: 10;
        }

        .px-10 {
            padding-left: 40px;
            padding-right: 40px;
        }

        .h-25px {
            height: 20px;
        }

        .h-125px {
            height: 100px;
        }

        .mumbai-template {
            font-family: "Open Sans", sans-serif;
            background-color: #000 !important;
        }

        .mumbai-template .top-border {
            width: 100%;
            height: 10px;
            background-color: #3f478b;
        }

        .mumbai-template .bottom-border {
            width: 100%;
            height: 15px;
            background-color: #3f478b;
        }

        .mumbai-template .heading-text {
            background-color: #3f478b;
        }

        .mumbai-template .invoice-table {
            font-family: "Inter", sans-serif;
            border-bottom: 0.5px solid #c6c6c6;
        }

        .mumbai-template .invoice-table thead {
            background-color: #3f478b;
            color: white;
        }

        .mumbai-template .invoice-table thead th:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .mumbai-template .invoice-table thead th:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .mumbai-template .invoice-table tbody tr:nth-child(even) {
            background-color: #ededed;
        }

        .mumbai-template .total-amount {
            background-color: #3f478b;
            color: white;
            border-radius: 6px;
        }

        .mumbai-template .total-amount td:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .mumbai-template .total-amount td:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .text-indigo {
            color: #3f478b;
        }

        .hongkong-template {
            font-family: "Open Sans", sans-serif;
            font-size: 12px;
            font-weight: medium;
        }

        .hongkong-template strong {
            font-weight: bold;
        }

        .hongkong-template .invoice-header .heading-text {
            position: relative;
        }

        .hongkong-template .invoice-header .heading-text h1 {
            color: #008fff;
            position: relative;
        }

        .hongkong-template .invoice-table {
            font-family: "Inter", sans-serif;
            border-bottom: 0.5px solid #c6c6c6;
        }

        .hongkong-template .invoice-table thead {
            background-color: #008fff;
            color: white;
            border: 1px solid transparent;
            overflow: hidden;
        }

        .hongkong-template .invoice-table thead th:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .hongkong-template .invoice-table thead th:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .hongkong-template .invoice-table tbody tr:nth-child(even) {
            background-color: #ededed;
        }

        .hongkong-template .total-amount {
            background-color: #008fff;
            color: white;
            border-radius: 6px;
        }

        .hongkong-template .total-amount td:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .hongkong-template .total-amount td:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .text-yellow {
            color: #f18a1b;
        }

        .text-gray-600 {
            color: #6d6e70;
        }

        .tokyo-template {
            font-family: "Open Sans", sans-serif;
        }

        .tokyo-template strong {
            font-weight: bold;
        }

        .tokyo-template .heading-text h1 {
            font-size: 36px;
            font-weight: 400;
            letter-spacing: 4px;
        }

        @media (max-width: 424px) {
            .tokyo-template .heading-text h1 {
                font-size: 24px;
            }
        }

        .tokyo-template .invoice-table thead {
            text-transform: uppercase;
            background-color: #363b45;
            color: white;
        }

        .tokyo-template .invoice-table thead tr th {
            padding: 10px;
        }

        .tokyo-template .invoice-table tbody tr td {
            border-bottom: 0.5px solid #bbbdbf;
            padding: 10px;
        }

        .tokyo-template .invoice-table tbody tr td:nth-child(1) {
            width: 5%;
        }

        .tokyo-template .invoice-table tbody tr td:nth-child(2) {
            width: 60%;
        }

        .tokyo-template .invoice-table tbody tr td:nth-child(1),
        .tokyo-template .invoice-table tbody tr td:nth-child(4),
        .tokyo-template .invoice-table tbody tr td:nth-child(6) {
            background-color: #eaebec;
        }

        .tokyo-template .total-amount {
            border-top: 1px solid #363b45;
        }

        .font-dark-gray {
            color: #363b45;
        }

        .paris-template {
            font-family: "Inter", sans-serif;
            font-size: 12px;
        }

        .paris-template .heading-text {
            padding: 0;
        }

        .paris-template .heading-text h1 {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 4px;
            display: inline-block;
        }

        @media (max-width: 496px) {
            .paris-template .heading-text h1 {
                font-size: 24px;
            }
        }

        @media (max-width: 424px) {
            .paris-template .heading-text h1 {
                font-size: 24px;
            }
        }

        .paris-template .invoice-table {
            font-family: "Inter", sans-serif;
            border-bottom: 0.5px solid #c6c6c6;
        }

        .paris-template .invoice-table thead {
            background-color: #fab806;
            color: white;
        }

        .paris-template .invoice-table tbody tr:nth-child(even) {
            background-color: #ededed;
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

        .text-start {
            text-align: left;
        }
    </style>
</head>

<body>
    @php $styleCss = 'style'; @endphp
    <div class="container invoice">
        <div class="invoice-header">
            <table width="100%">
                <tr>
                    <td style="vertical-align:top !important;">
                        <div class="companylogo"><img width="100px" src="{{ getLogoUrl() }}" alt=""></div>
                    </td>
                    <td>
                        <div class="invoice-header-inner">
                            <h3 {{ $styleCss }}="color: {{ $invoice_template_color }}">
                                <b>{{ __('messages.quote.quote_name') }}</b>
                            </h3>
                            <span class="text-color">#{{ $quote->quote_id }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="details-section">
            <table class="mt-10 w-100">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td class="invoice-date" style="vertical-align:top !important; width:33.33% !important;">
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.quote.quote_date') . ':' }}</strong>
                                <p class="text-color">
                                    {{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.quote.due_date') . ':' }}</strong>
                                <p class="text-color">
                                    {{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                        </td>
                        <td class="billedto"
                            style="vertical-align:top !important; width:33.33% !important; overflow:hidden; word-wrap: break-word; word-break: break-all;">
                            <b>{{ __('messages.common.to') . ':' }}</b><br>
                            <span><b>{{ __('messages.common.name') . ':' }}&nbsp;</b></span> <span
                                class="text-color">{{ $client->user->full_name }}</span><br>
                            <span><b>{{ __('messages.common.email') . ':' }}&nbsp;</b></span>
                            <span class="text-color">{{ $client->user->email }}</span><br>
                            <span><b>{{ __('messages.common.address') . ':' }}&nbsp;</b></span>
                            <span class="text-color">{{ $client->address }}</span><br>
                            @if (!empty($client->vat_no))
                                <span><b>{{ getVatNoLabel() . ':' }}&nbsp;</b></span>
                                <span class="text-color">{{ $client->vat_no }}</span><br>
                            @endif
                        </td>
                        <td class="from" style="vertical-align:top !important; width:33.33% !important;">
                            <b>{{ __('messages.common.from') . ':' }}</b><br>
                            <b>{{ __('messages.setting.company_name') . ':' }}&nbsp;</b><span
                                class="text-break text-color">{!! $setting['company_name'] !!}</span><br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b><span
                                class="text-break text-color">{!! $setting['company_address'] !!}</span><br>
                            <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b><span
                                class="text-color">{{ $setting['company_phone'] }}</span>
                            @if (!empty($setting['gst_no']))
                                <b class="text-nowrap">{{ getVatNoLabel() . ':' }}&nbsp;</b><span
                                    class="text-color text-nowrap">{{ $setting['gst_no'] }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="content">
            <table class="table product-table w-100"
                {{ $styleCss }}="border-top: 1px solid {{ $invoice_template_color }}
                ;border-bottom: 1px solid {{ $invoice_template_color }}">
                <thead class="bg-light"
                    {{ $styleCss }}="border-top: 1px solid {{ $invoice_template_color }}
                ;border-bottom: 1px solid {{ $invoice_template_color }}">
                    <tr>
                        <th style="width:5%;" class="px-2 text-start">#</th>
                        <th class="text-uppercase px-2 text-start">{{ __('messages.product.product') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:14%;">
                            {{ __('messages.invoice.qty') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:16%;">
                            {{ __('messages.product.unit_price') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:12%;">
                            {{ __('messages.invoice.tax') . '(in %)' }}</th>
                        <th class="text-end text-uppercase text-nowrap px-2" style="width:16%;">
                            {{ __('messages.invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if (isset($quote) && !empty($quote))
                        @foreach ($quote->quoteItems as $key => $quoteItems)
                            <tr>
                                <td>
                                    {{ $key + 1 }}</td>
                                <td>
                                    <b>{{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}</b>
                                    <p style="margin:0;" class="text-color">
                                        @if (!empty($quoteItems->product->description) && $setting['show_product_description'] == 1)
                                            <span
                                                style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="text-center text-color text-nowrap">
                                    {{ $quoteItems->quantity }}</td>
                                <td class="text-center text-color text-nowrap euroCurrency">
                                    {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                </td>
                                <td class="text-center text-color text-nowrap" style="width:12%;">
                                    @foreach ($quoteItems->quoteItemTax as $keys => $tax)
                                        {{ $tax->tax ?? '--' }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td class="number-align text-color euroCurrency text-nowrap">
                                    {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <table class="w-100 mt-4">
            <tr>
                <td class="w-65" style="vertical-align:bottom !important;">

                </td>
                <td class="text-end" style="width:35%;">
                    <table class="total-table table w-100">
                        <tbody class="">
                            <tr class="border-bottom-gray">
                                <td class="left">
                                    <strong>{{ __('messages.quote.amount') . ':' }}</strong>
                                </td>
                                <td class="text-end text-color euroCurrency">
                                    {{ getCurrencyAmount($quote->amount, true) }}
                                </td>
                            </tr>
                            <tr class="border-bottom-gray">
                                <td class="left">
                                    <strong>{{ __('messages.quote.discount') . ':' }}</strong>
                                </td>
                                <td class="text-end text-color">
                                    @if ($quote->discount == 0)
                                        <span>{{ __('messages.common.n/a') }}</span>
                                    @else
                                        @if (isset($quote) && $quote->discount_type == \App\Models\Quote::FIXED)
                                            <b
                                                class="euroCurrency">{{ isset($quote->discount) ? getCurrencyAmount($quote->discount, true) : __('messages.common.n/a') }}</b>
                                        @else
                                            {{ $quote->discount }}<span
                                                {{ $styleCss }}="font-family: DejaVu Sans">&#37;</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom-gray">
                                @php
                                    $itemTaxesAmount = $quote->amount + array_sum($totalTax);
                                    $quoteTaxesAmount = ($itemTaxesAmount * $quote->qouteTaxes->sum('value')) / 100;
                                    $totalTaxes = array_sum($totalTax) + $quoteTaxesAmount;
                                @endphp
                                <td class="left">
                                    <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                </td>

                                <td class="text-end text-color">
                                    {!! numberFormat($totalTaxes) != 0
                                        ? '<b class="euroCurrency">' . getCurrencyAmount($totalTaxes, true) . '</b>'
                                        : __('messages.common.n/a') !!}
                                </td>
                            </tr>
                            <tr class="border-bottom-gray">
                                <td class="font-weight-bold">
                                    {{ __('messages.quote.total') . ':' }}
                                </td>
                                <td class="text-nowrap text-end text-color euroCurrency">
                                    {{ getCurrencyAmount($quote->final_amount, true) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div class="notes-terms">
            <p style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                <b>{{ __('messages.client.notes') . ':' }}</b><br>
                <span class="text-color">
                    {!! nl2br($quote->note ?? __('messages.common.n/a')) !!}</span>
            </p>
            <p style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                <b>{{ __('messages.invoice.terms') . ':' }}</b><br>
                <span class="text-color">
                    {!! nl2br($quote->term ?? __('messages.common.n/a')) !!}</span>
            </p>
        </div>
        <div class="regards">
            <p><b>{{ __('messages.setting.regards') . ':' }}</b><br>
                <b {{ $styleCss }}="color: {{ $invoice_template_color }}">{{ getAppName() }}</b>
            </p>
        </div>
    </div>
</body>

</html>
