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

        table {
            width: 100%;
            border-collapse: collapse;
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

        .pt-5 {
            padding-top: 2.5rem !important;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-white {
            color: #fff !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }
    </style>
</head>

<body style="padding: 0rem 2rem;">
    @php $styleCss = 'style'; @endphp
    <div style="width: 100%;" style="margin-top:-40px !important;">
        <div class="d" id="boxes" style="width: 100%;">
            <table class="mb-8" style="width: 100%;">
                <tr>
                    <td style="vertical-align:top; width: 35%;" class="pt-5">
                        <img width="100px" src="{{ getPDFLogoUrl() }}" alt="">
                    </td>
                    <td style="width: 35%;" class="pt-5">
                        <p class="p-text mb-0">{{ __('messages.quote.quote_id') . ':' }}&nbsp;
                            <strong>#{{ $quote->quote_id }}</strong>
                        </p>
                        <p class="p-text mb-0">{{ __('messages.quote.quote_date') . ':' }}
                            <strong>{{ \Carbon\Carbon::parse($quote->invoice_date)->translatedFormat(currentDateFormat()) }}</strong>
                        </p>
                        <p class="p-text mb-0">{{ __('messages.quote.due_date') . ':' }}&nbsp;
                            <strong>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}</strong>
                        </p>
                    </td>
                    <td class="in-w-4 pt-5"
                        {{ $styleCss }}="background-color: {{ $invoice_template_color }}; width: 30%;">
                        <h1 class="fancy-title tu text-center mb-auto p-3" style="color:white;  font-size: 34px">
                            <b>{{ __('messages.quote.quote_name') }}</b>
                        </h1>
                    </td>
                </tr>
            </table>
            <table style="width:70%;" class="mb-8 mt-5 py-10">
                <tr>
                    <td class="w-50" style="vertical-align:top !important;">
                        <p class="fs-6 mb-2"><strong>{{ __('messages.common.to') . ':' }}</strong></p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.common.name') . ':' }} <span
                                class="text-dark fw-bold">{{ $client->user->full_name }}</span>
                        </p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.common.email') . ':' }} <span
                                class="text-dark fw-bold">{{ $client->user->email }}</span></p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.common.address') . ':' }} <span
                                class="text-dark fw-bold m-2">{{ $client->address }}</span></p>
                        @if (!empty($client->vat_no))
                            <p class="m-0 font-color-gray fs-6">{{ getVatNoLabel() . ':' }} <span
                                    class="text-dark fw-bold">{{ $client->vat_no }}</span></p>
                        @endif
                    </td>
                    <td class="w-50">
                        <p class="fs-6 mb-2"><strong>{{ __('messages.common.from') . ':' }}</strong></p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.setting.company_name') . ':' }}&nbsp;
                            <span class="text-dark fw-bold">{!! $setting['company_name'] !!}
                            </span>
                        </p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.common.address') . ':' }}&nbsp;
                            <span class="text-dark fw-bold">{!! $setting['company_address'] !!}
                            </span>
                        </p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.user.phone') . ':' }}
                            <span class="text-dark fw-bold">{{ $setting['company_phone'] }}</span>
                        </p>
                        @if (!empty($setting['gst_no']))
                            <p class="m-0 font-color-gray fs-6">{{ getVatNoLabel() . ':' }}
                                <span class="text-dark fw-bold">{{ $setting['gst_no'] }}</span>
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
            <div class="table-responsive-sm table-striped mt-5" style="width: 100%;">
                <table style="width: 100%;">
                    <thead {{ $styleCss }}="background-color: {{ $invoice_template_color }}; ">
                        <tr>
                            <th class="px-2 py-1 text-white text-center fw-bold" style="width: 7%;">#</th>
                            <th class="px-2 py-1 text-white text-start in-w-2 fw-bold text-uppercase">
                                {{ __('messages.product.product') }}</th>
                            <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                                style="width: 12%;">
                                {{ __('messages.invoice.qty') }}</th>
                            <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                                style="width: 18%;">
                                {{ __('messages.product.unit_price') }}</th>
                            <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                                style="width: 13%;">
                                {{ __('messages.invoice.tax') . '(in %)' }}</th>
                            <th class="px-2 py-1 text-white text-end fw-bold text-uppercase text-nowrap"
                                style="width: 18%;">
                                {{ __('messages.invoice.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($quote) && !empty($quote))
                            @foreach ($quote->quoteItems as $key => $quoteItems)
                                <tr class="border-b-gray">
                                    <td class="p-2 text-center bg-gray fw-bold">{{ $key + 1 }}</td>
                                    <td class="p-2 in-w-2">
                                        <p class="fw-bold mb-0">
                                            {{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}
                                        </p>
                                        @if (!empty($quoteItems->product->description) && $setting['show_product_description'] == 1)
                                            <span
                                                style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-center fw-bold text-nowrap">
                                        {{ $quoteItems->quantity }}
                                    </td>
                                    <td class="p-2 text-center bg-gray fw-bold text-nowrap euroCurrency">
                                        {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                    </td>
                                    <td class="p-2 text-nowrap text-center fw-bold text-nowrap">
                                        @foreach ($quoteItems->quoteItemTax as $keys => $tax)
                                            {{ $tax->tax ?? '--' }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="p-2 text-end bg-gray fw-bold text-nowrap euroCurrency">
                                        {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="p-2 text-center fw-bold text-nowrap">
                                {{ __('messages.quote.amount') . ':' }}</td>
                            <td class="p-2 text-end bg-gray fw-bold text-nowrap">
                                {{ getCurrencyAmount($quote->amount, true) }}</td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="p-2 text-center fw-bold text-nowrap">{{ __('messages.invoice.discount') . ':' }}
                            </td>
                            <td class="p-2 text-end bg-gray fw-bold text-nowrap">
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
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @php
                                $itemTaxesAmount = $quote->amount + array_sum($totalTax);
                                $quoteTaxesAmount = ($itemTaxesAmount * $quote->qouteTaxes->sum('value')) / 100;
                                $totalTaxes = array_sum($totalTax) + $quoteTaxesAmount;
                            @endphp
                            <td class="p-2 text-center fw-bold text-nowrap">{{ __('messages.invoice.tax') . ':' }}</td>
                            <td class="p-2 text-nowrap number-align bg-gray fw-bold text-nowrap">
                                {!! numberFormat($totalTaxes) != 0
                                    ? '<b class="euroCurrency">' . getCurrencyAmount($totalTaxes, true) . '</b>'
                                    : __('messages.common.n/a') !!}
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="p-2 text-center fw-bold text-nowrap text-nowrap">
                                <strong>{{ __('messages.quote.total') . ':' }}</strong>
                            </td>
                            <td class="p-2 text-end text-white fw-bold text-nowrap euroCurrency"
                                {{ $styleCss }}="background-color: {{ $invoice_template_color }}; ">
                                {{ getCurrencyAmount($quote->final_amount, true) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-5 mb-5">
                <h4 class="d-fancy-title mb5">{{ __('messages.client.notes') . ':' }}</h4>
                <p class="font-color-gray"
                    style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                    {!! nl2br($quote->note ?? __('messages.common.n/a')) !!}</p>
            </div>

            <table class="w-100">
                <tr>
                    <td class="w-75">
                        <div class="mb-8">
                            <h4 class="d-fancy-title mb5">{{ __('messages.invoice.terms') . ':' }}</h4>
                            <p class="font-color-gray">
                                <span
                                    style="word-break: break-all; overflow-wrap: break-word; white-space: normal; display: block;">
                                    {!! nl2br($quote->term ?? __('messages.common.n/a')) !!}
                                </span>
                            </p>
                        </div>
                    </td>
                    <td class="w-25 text-end">
                        <div class="">
                            <h4 class="d-fancy-title mb5" style="color: {{ $invoice_template_color }}">
                                {{ __('messages.setting.regards') . ':' }}
                            </h4>
                            <p class="font-color-gray">
                                <b>{{ $setting['app_name'] }}</b>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

</body>

</html>
