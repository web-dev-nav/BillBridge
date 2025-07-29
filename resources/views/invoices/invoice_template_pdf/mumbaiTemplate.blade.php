<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
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

        @page {
            margin: 0 !important;
            padding: 0 !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .w-100 {
            width: 100%;
        }

        @if (getInvoiceCurrencyIcon($invoice->currency_id) == '€')
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
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .py-5 {
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

        .text-white {
            color: #fff !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .m-0 {
            margin: 0 !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .px-4 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .pb-3 {
            padding-bottom: 1rem !important;
        }

        .mb-10 {
            margin-bottom: 2.5rem !important;
        }

        .mb-2 {
            margin-bottom: 10px !important;
        }

        .mb-1 {
            margin-bottom: 5px !important;
        }

        .text-start {
            text-align: left !important;
        }

        .my-5 {
            margin-top: 2.5rem !important;
            margin-bottom: 2.5rem !important;
        }

        @media only screen and (max-width: 640px) {
            .client-preview {
                zoom: 1;
            }
        }

        .preview-main {
            flex: 3;
        }
    </style>
</head <body style="padding: 0rem 2rem;">
@php $styleCss = 'style'; @endphp
<div class="preview-main client-preview mumbai-template">
    <div class="d" id="boxes">
        <div class="d-inner">
            <div class="top-border" style="background-color: {{ $invoice_template_color }};"></div>
            <div style="background-color: {{ $invoice_template_color }};">
                <table class="pb-10 bg-white w-100 m-0" style="overflow:hidden;">
                    <tr>
                        <td class="p-0 m-0" style="width:66%; overflow:hidden !important;  ">
                            <div class="bg-white" style=" border-top-right-radius:30px; padding:24.5px;">
                                <img width="100px" height="66px" src="{{ getPDFLogoUrl() }}" class="img-logo"
                                    alt="logo">
                            </div>
                        </td>
                        <td class="bg-white p-0 m-0" style="width:33%;border-bottom-left-radius:30px; overflow:hidden;">
                            <div class="text-end p-4 pt-10" style=" background-color: {{ $invoice_template_color }};">
                                <h1 class="m-0 pt-4 text-white pe-2"
                                    style=" font-size: 40px; font-weight:700; letter-spacing: 4px;">
                                    {{ __('messages.common.invoice') }}</h1>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="px-4 py-4 bg-white" style="margin-right:10px;">
                    <div class="pb-3">
                        <table class="w-100">
                            <tbody>
                                <tr style="vertical-align:top;">
                                    <td width="33.33%">
                                        <p class="fs-6 mb-2 font-gray-900">
                                            <strong>{{ __('messages.common.to') . ':' }}</strong>
                                        </p>
                                        <p class=" mb-1 font-color-gray fs-6">
                                            {{ __('messages.common.name') . ':' }}
                                            <span class="font-gray-900">{{ $client->user->full_name }}</span>
                                        </p>
                                        <p class=" mb-1 font-color-gray fs-6">
                                            {{ __('messages.common.email') . ':' }}
                                            <span class="font-gray-900">{{ $client->user->email }}</span>
                                        </p>
                                        <p class=" mb-1  font-color-gray fs-6">
                                            {{ __('messages.common.address') . ':' }}
                                            <span class="font-gray-900">{{ $client->address }} </span>
                                        </p>
                                        @if (!empty($client->vat_no))
                                            <p class=" mb-1  font-color-gray fs-6">
                                                {{ getVatNoLabel() . ':' }}
                                                <span class="font-gray-900">{{ $client->vat_no }} </span>
                                            </p>
                                        @endif
                                    </td>
                                    <td width="33.33%">
                                        <p class="fs-6 mb-2 font-gray-900">
                                            <strong>{{ __('messages.common.from') . ':' }}</strong>
                                        </p>
                                        <p class="mb-1 font-color-gray fw-bold fs-6">
                                            {{ __('messages.common.name') . ':' }}&nbsp; <span
                                                class="font-gray-900">{!! $setting['company_name'] !!}</span></p>
                                        <p class=" mb-1 font-color-gray fw-bold fs-6">
                                            {{ __('messages.common.address') . ':' }}&nbsp; <span
                                                class="font-gray-900">{!! $setting['company_address'] !!}</span></p>
                                        @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                            <p class=" m-0 font-color-gray fs-6">
                                                {{ $setting['zipcode'] . ', ' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                            </p>
                                        @endif
                                        <p class=" mb-1 font-color-gray  fw-bold fs-6">
                                            {{ __('messages.user.phone') . ':' }}&nbsp; <span
                                                class="font-gray-900">{{ $setting['company_phone'] }}</span></p>
                                        @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                            <p class="mb-1 font-color-gray  fw-bold fs-6">
                                                <strong>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</strong><span
                                                    class="font-gray-900">{{ $setting['fax_no'] }}</span>
                                            <p>
                                        @endif
                                        @if (!empty($setting['gst_no']))
                                            <p class="mb-1 font-color-gray fs-6">
                                                {{ getVatNoLabel() . ':' }}
                                                <span class="font-gray-900">{{ $setting['gst_no'] }} </span>
                                            </p>
                                        @endif
                                    </td>
                                    <td width="33.33%" class="text-end pt-7">
                                        <p class="mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.invoice_id') . ':' }}
                                            </strong>#{{ $invoice->invoice_id }}
                                        </p>
                                        <p class="mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.invoice_date') . ':' }}
                                            </strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                        <p class="mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.due_date') . ':' }}&nbsp;
                                            </strong>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow-auto">
                        <table class="invoice-table w-100">
                            <thead style="background-color: {{ $invoice_template_color }};">
                                <tr>
                                    <th class="py-1 px-2 text-uppercase text-start" style="width:5% !important;">#
                                    </th>
                                    <th class="in-w-2 text-uppercase text-start">
                                        {{ __('messages.product.product') }}
                                    </th>
                                    <th class="text-center text-uppercase" style="width:9% !important;">
                                        {{ __('messages.invoice.qty') }}
                                    </th>
                                    <th class="text-center  text-nowrap text-uppercase" style="width:15% !important;">
                                        {{ __('messages.product.unit_price') }}</th>
                                    <th class="text-center  text-nowrap text-uppercase" style="width:15% !important;">
                                        {{ __('messages.invoice.tax') . '(in %)' }}
                                    </th>
                                    <th class="text-end px-2 text-nowrap text-uppercase" style="width:14% !important;">
                                        {{ __('messages.invoice.amount') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($invoice) && !empty($invoice))
                                    @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                        <tr>
                                            <td class="p-2" style="width:5%;">
                                                <span>{{ $key + 1 }}</span>
                                            </td>
                                            <td class="p-2 in-w-2">
                                                <p class="fw-bold mb-0">
                                                    {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                                </p>
                                                @if (
                                                    !empty($invoiceItems->product->description) &&
                                                        (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                @endif
                                                @if (
                                                    !empty($invoiceItems->product->description) &&
                                                        (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                    <span
                                                        style="font-size: 12px; word-break: break-all !important">{{ $invoiceItems->product->description }}</span>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">
                                                {{ number_format($invoiceItems->quantity, 2) }}
                                            </td>
                                            <td class="p-2 text-center">
                                                {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                            </td>
                                            <td class="p-2 text-center">
                                                @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                                    {{ $tax->tax ?? '--' }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="p-2 text-end">
                                                {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="my-5">
                        <table class="w-100">
                            <tr>
                                @if (!empty($invoice->paymentQrCode))
                                    <td style="vertical-align:bottom; width:60%;">
                                        <img class="mt-4" src="{{ $qrImg }}" height="100" width="100"
                                            alt="qr-code-image">
                                    </td>
                                @endif
                                <td style="vertical-align:top; width:40%;">
                                    <table class="w-100">
                                        <tbody>
                                            <tr>
                                                <td class="py-5 px-2">
                                                    <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                                </td>
                                                <td class="text-end font-gray-600 py-5 px-2 fw-bold">
                                                    {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-5 px-2">
                                                    <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                                </td>
                                                <td class="text-end font-gray-600 py-5 px-2 fw-bold">
                                                    @if ($invoice->discount == 0)
                                                        <span>{{ __('messages.common.n/a') }}</span>
                                                    @else
                                                        @if (isset($invoice) && $invoice->discount_type == \App\Models\Invoice::FIXED)
                                                            <b
                                                                class="euroCurrency">{{ isset($invoice->discount) ? getInvoiceCurrencyAmount($invoice->discount, $invoice->currency_id, true) : __('messages.common.n/a') }}</b>
                                                        @else
                                                            {{ $invoice->discount }}<span
                                                                {{ $styleCss }}="font-family: DejaVu Sans">&#37;</span>
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
                                                <td class="py-5 px-2">
                                                    <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                                </td>
                                                <td class="text-end font-gray-600 py-5 px-2 fw-bold">
                                                    {!! numberFormat($totalTaxes) != 0
                                                        ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                        : __('messages.common.n/a') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-5 px-2">
                                                    <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                                                </td>
                                                <td class="text-end font-gray-600 py-5 px-2 fw-bold">
                                                    {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="total-amount"
                                            {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                            <tr>
                                                <td class="p-2">
                                                    <strong>{{ __('messages.admin_dashboard.total_due') . ':' }}</strong>
                                                </td>
                                                <td class="text-end p-2">
                                                    <strong>
                                                        {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-20">
                        @if (!empty($invoice->note))
                            <div class="mb-5 pt-10">
                                <h4 class="font-gray-900 mb5"><b><?php echo __('messages.client.notes'); ?>:</b></h4>
                                <p class="font-gray-600"
                                    style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                                    {!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
                                </p>
                            </div>
                        @endif
                        <div class="position-relative">
                            @if (!empty($invoice->term))
                                <div class="w-75">
                                    <h4 class="font-gray-900">
                                        <b>{{ __('messages.invoice.terms') . ':' }}</b>
                                    </h4>
                                    <p class="font-gray-600 mb-0"
                                        style="word-break: break-word; overflow-wrap: break-word; white-space: normal; display: block;">
                                        {!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}
                                    </p>
                                </div>
                            @endif
                            <div class="text-end">
                                <h2 class="text-indigo mt-2"
                                    {{ $styleCss }}="color:{{ $invoice_template_color }}">
                                    <b>{{ __('messages.setting.regards') . ':' }}</b>
                                </h2>
                                <p class="fs-6 mb-0"><b> {{ getAppName() }}</b></p>
                            </div>

                        </div>
                    </div>
                </div>
                <div>
                    <table class="w-100 bg-white">
                        <tr>
                            <td class=" p-0 h-25px" style="width:80% !important; overflow:hidden;">
                                <div class="bg-white h-25px" style=" border-bottom-right-radius:30px; padding:26px;">
                                </div>
                            </td>
                            <td class="bg-white p-0 h-25px"
                                style="width:20%; border-top-left-radius:35px; overflow:hidden;">
                                <div class="text-end h-25px"
                                    style="background-color: {{ $invoice_template_color }};  padding:26px;">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="bottom-border" style="background-color: {{ $invoice_template_color }}"></div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
