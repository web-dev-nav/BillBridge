<div id="defaultTemplate" class="p-6 bg-white shadow-lg rounded-lg">
    {{-- <div class="container mx-auto">
        <div class="mb-8">
            <img src="{{ getLogoUrl() }}" class="h-16" width="100" height="100" alt="logo">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-left">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 w-1/3 font-bold">{{ __('messages.common.from') }}</th>
                        <th class="py-2 px-4 w-1/3 font-bold">{{ __('messages.common.to') }}</th>
                        <th class="py-2 px-4 w-1/3 font-bold">{{ __('messages.common.invoice') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="py-2 px-4">
                            <p><strong>{{ __('messages.common.name') }}:</strong> {{ $companyName }}</p>
                            <p><strong>{{ __('messages.common.address') }}:</strong> {{ $companyAddress }}</p>
                            <p><strong>{{ __('messages.user.phone') }}:</strong> {{ $companyPhone }}</p>
                            <p><strong>{{ getVatNoLabel() }}:</strong> {{ $gstNo }}</p>
                        </td>
                        <td class="py-2 px-4">
                            <p>{{ __('messages.invoice.client_name') }}</p>
                            <p>{{ __('messages.invoice.client_email') }}</p>
                            <p>{{ __('messages.client_address') }}</p>
                            <p>{{ getVatNoLabel() }}</p>
                        </td>
                        <td class="py-2 px-4">
                            <p><b>{{ __('messages.invoice.invoice_id') }}:</b> #9CQ5X7</p>
                            <p><b>{{ __('messages.invoice.invoice_date') }}:</b> 25/09/2020</p>
                            <p><b>{{ __('messages.invoice.due_date') }}:</b> 26/09/2020</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="overflow-x-auto mt-6">
            <table class="w-full border border-gray-300 text-left">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4">#</th>
                        <th class="py-2 px-4">{{ __('messages.item') }}</th>
                        <th class="py-2 px-4 text-center">{{ __('messages.invoice.qty') }}</th>
                        <th class="py-2 px-4 text-center">{{ __('messages.product.unit_price') }}</th>
                        <th class="py-2 px-4 text-center">{{ __('messages.invoice.tax') }} (%)</th>
                        <th class="py-2 px-4">{{ __('messages.invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 3; $i++) <tr class="border-t">
                        <td class="py-2 px-4">{{ $i }}</td>
                        <td class="py-2 px-4">{{ __('messages.item') }} {{ $i }}</td>
                        <td class="py-2 px-4 text-center">1</td>
                        <td class="py-2 px-4 text-center">{{ getCurrencyAmount(100, true) }}</td>
                        <td class="py-2 px-4 text-center">N/A</td>
                        <td class="py-2 px-4">{{ getCurrencyAmount(100, true) }}</td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>

        <div class="flex justify-between mt-10">
            <div>
                <p class="font-bold text-lg">{{ __('messages.payment_qr_codes.payment_qr_code') }}</p>
                <img class="mt-2" src="{{ asset('images/qrcode.png') }}" height="110" width="110">
            </div>
            <div>
                <table class="text-right">
                    <tr>
                        <td class="font-bold">{{ __('messages.invoice.amount') }}:</td>
                        <td>{{ getCurrencyAmount(300, true) }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">{{ __('messages.invoice.discount') }}:</td>
                        <td>{{ getCurrencyAmount(50, true) }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">{{ __('messages.invoice.tax') }}:</td>
                        <td>0%</td>
                    </tr>
                    <tr>
                        <td class="font-bold">{{ __('messages.invoice.total') }}:</td>
                        <td>{{ getCurrencyAmount(250, true) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="bg-gray-100 p-4 mt-6 rounded-lg">
            <h4 class="font-bold">{{ __('messages.client.notes') }}:</h4>
            <p>Paypal, Stripe & manual payment methods accepted.<br>Net 10 – Payment due in 10 days from invoice
                date.<br>Net 30 – Payment due in 30 days from invoice date.</p>
        </div>

        <div class="mt-6">
            <h4 class="font-bold">{{ __('messages.invoice.terms') }}:</h4>
            <p>Invoice payment {{ __('messages.invoice.total') }}; 1% 10 Net 30, 1% discount if payment received within
                10 days otherwise payment is due 30 days after the invoice date.</p>
        </div>
    </div> --}}
    <div class="w-full mx-auto pdf">
        <div class="w-full">
            <div class="logo-img mb-8 h-full w-full">
                <img src="{{ getLogoUrl() }}" alt="images" class="h-full w-full object-cover" />
            </div>
            <div>
                <div class="overflow-auto w-full mb-60px">
                    <table class="table w-full  table-auto border-collapse border border-gray-200 align-top">
                        <thead>
                            <tr>
                                <th class="text-start bg-white-100 border border-gray-200  py-1 px-21px align-top">
                                    <strong
                                        class="text-gray-100 text-sm font-bold">{{ __('messages.common.from') }}</strong>
                                </th>
                                <th
                                    class="text-start bg-white-100 border border-gray-200 text-gray-100 text-sm font-bold py-1 px-21px align-top">
                                    <strong
                                        class="text-gray-100 text-sm font-bold">{{ __('messages.common.to') }}</strong>
                                </th>
                                <th
                                    class="text-start bg-white-100 border border-gray-200 text-gray-100 text-sm font-bold py-1 px-21px uppercase align-top">
                                    <strong
                                        class="text-gray-100 text-sm font-bold">{{ __('messages.common.invoice') }}</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-1 px-21px border border-gray-200 align-top">
                                    <p class="mb-5px text-nowrap"><strong
                                            class="text-sm text-gray-100 leading-18px">{{ __('messages.common.name') }}:</strong>
                                        <span class="text-sm text-gray-100 leading-18px">{{ $companyName }}</span>
                                    </p>
                                    <p class="mb-5px text-nowrap"><strong
                                            class="text-sm text-gray-100 leading-18px">{{ __('messages.common.address') }}:</strong>
                                        <span class="text-sm text-gray-100 leading-18px">{{ $companyAddress }}</span>
                                    </p>
                                    <p class="mb-5px text-nowrap"><strong
                                            class="text-sm text-gray-100 leading-18px">{{ __('messages.user.phone') }}:</strong>
                                        <span class="text-sm text-gray-100 leading-18px">{{ $companyPhone }}</span>
                                    </p>
                                    <p class="mb-5px text-nowrap"><strong
                                            class="text-sm text-gray-100 leading-18px">{{ getVatNoLabel() }}:</strong>
                                        <span class="text-sm text-gray-100 leading-18px">{{ $gstNo }}</span>
                                    </p>
                                </td>
                                <td class="py-1 px-21px border border-gray-200 align-top">
                                    <p class="mb-3 text-sm text-gray-100 leading-18px text-nowrap">
                                        &lt;{{ __('messages.invoice.client_name') }}&gt;
                                    </p>
                                    <p class="text-sm text-gray-100 leading-18px mb-3 text-nowrap">
                                        &lt;{{ __('messages.invoice.client_email') }}
                                        Email&gt;</p>
                                    <p class="text-sm text-gray-100 leading-18px mb-3 text-nowrap">
                                        &lt;{{ __('messages.client_address') }}
                                        Address&gt;</p>
                                    <p class="text-sm text-gray-100 leading-18px mb-3 text-nowrap">
                                        &lt;{{ getVatNoLabel() }}&gt;</p>
                                </td>
                                <td class="py-1 px-21px border border-gray-200 align-top">
                                    <p class="text-sm text-gray-300 leading-18px mb-3 text-nowrap">
                                        <strong>{{ __('messages.invoice.invoice_id') }}:</strong>
                                        #9CQ5X7
                                    </p>
                                    <p class="text-sm text-gray-300 leading-18px mb-3 text-nowrap">
                                        <strong>{{ __('messages.invoice.invoice_date') }}:
                                        </strong>25/09/2020
                                    </p>
                                    <p class="text-sm text-gray-300 leading-18px mb-3 text-nowrap">
                                        <strong>{{ __('messages.invoice.due_date') }}:
                                        </strong>
                                        26/09/2020
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="overflow-auto shadow-1 mb-1">
                    <table class="w-full ">
                        <thead>
                            <tr>
                                <th class="py-3 ps-30px pe-1 border-b border-gray-200 bg-white-100 text-start leading-14 rounded-tl-10px text-nowrap"
                                    style="width: 6%;">
                                    <strong class="text-gray-100">#</strong>
                                </th>
                                <th class="py-3 ps-30px pe-1 border-b border-gray-200 uppercase text-start bg-white-100 leading-14 text-nowrap"
                                    style="width: 40%;">
                                    <strong class="text-gray-100">{{ __('messages.item') }}</strong>
                                </th>
                                <th class="py-3 ps-30px pe-1 border-b border-gray-200 uppercase bg-white-100 leading-14 text-nowrap"
                                    style="width:12%;">
                                    <strong class="text-gray-100">{{ __('messages.invoice.qty') }}</strong>
                                </th>
                                <th class="py-3 ps-30px pe-1 border-b border-gray-200 uppercase bg-white-100 leading-14 text-nowrap"
                                    style="width:14%">
                                    <strong class="text-gray-100">{{ __('messages.product.unit_price') }}</strong>
                                </th>
                                <th class="py-3 ps-30px pe-1 border-b border-gray-200 uppercase bg-white-100 leading-14 text-nowrap"
                                    style="width:14%">
                                    <strong class="text-gray-100">{{ __('messages.invoice.tax') }}(%)</strong>
                                </th>
                                <th class="py-3 ps-30px pe-1 border-b border-gray-200 uppercase text-start bg-white-100 leading-14 rounded-tr-10px text-nowrap"
                                    style="width:14%">
                                    <strong class="text-gray-100">{{ __('messages.invoice.amount') }}</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 3; $i++)
                                <tr>
                                    <td class="py-2.5 ps-30px pe-1 border-b border-gray-200 align-middle">
                                        <span class="text-sm text-gray-100 !leading-normal">{{ $i }}</span>
                                    </td>
                                    <td
                                        class="py-2.5 ps-30px pe-1 border-b border-gray-200 align-middle text-sm text-gray-100">
                                        <p class="text-sm text-gray-100 !leading-normal">{{ __('messages.item') }}
                                            {{ $i }}</p>
                                    </td>
                                    <td
                                        class="border-b border-gray-200 !leading-normal py-2.5 ps-30px pe-1 text-center text-sm text-gray-100 align-middle">
                                        1</td>
                                    <td
                                        class="border-b border-gray-200 !leading-normal py-2.5 ps-30px pe-1 text-center text-sm text-gray-100 align-middle">
                                        {{ getCurrencyAmount(100, true) }}</td>
                                    <td
                                        class="border-b border-gray-200 !leading-normal py-2.5 ps-30px pe-1 text-center text-sm text-gray-100 align-middle">
                                        N/A</td>
                                    <td
                                        class="border-b border-gray-200 !leading-normal py-2.5 ps-30px pe-1 text-sm text-gray-100 align-middle">
                                        {{ getCurrencyAmount(100, true) }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="mt-5 mb-9">
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td style="width: 75%;">
                                    <div>
                                        <div>
                                            <p class="text-15px mb-3px text-gray-700">
                                                <b>{{ __('messages.payment_qr_codes.payment_qr_code') }}</b>
                                            </p>
                                            <div class="h-full w-full qr-img">
                                                <img src="../assets/images/qrcode.png" alt="qr-code"
                                                    class="h-full w-full object-cover mt-2">
                                            </div>
                                        </div>
                                    </div>

                                </td>
                                <td class="text-end" style="width: 25%;">
                                    <table class="ms-auto w-full">
                                        <tbody class="text-end">
                                            <tr>
                                                <td>
                                                    <strong
                                                        class="text-sm text-gray-700 text-nowrap">{{ __('messages.invoice.amount') }}:</strong>
                                                </td>
                                                <td class="text-sm text-gray-700 text-nowrap">
                                                    {{ getCurrencyAmount(300, true) }} </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong
                                                        class="text-sm text-gray-700 text-nowrap">{{ __('messages.invoice.discount') }}:</strong>
                                                </td>
                                                <td class="text-sm text-gray-700 text-nowrap">
                                                    {{ getCurrencyAmount(50, true) }} </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong
                                                        class="text-sm text-gray-700 text-nowrap">{{ __('messages.invoice.tax') }}:</strong>
                                                </td>
                                                <td class="text-sm text-gray-700 text-nowrap">0%</td>
                                            </tr>
                                            <tr>
                                                <td><strong
                                                        class="text-sm text-gray-700 text-nowrap">{{ __('messages.invoice.total') }}:</strong>
                                                </td>
                                                <td class="text-nowrap text-sm text-gray-700">
                                                    {{ getCurrencyAmount(250, true) }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="py-3 px-15px mb-10 bg-gray-400 border-transparent text-gray-100 border rounded-10px">
                    <h4 class="text-base text-gray-100 mb-5px font-medium">{{ __('messages.client.notes') }}:</h4>
                    <p class="text-sm text-gray-300 mb-4 font-normal">
                        Paypal , Stripe &amp; manual payment method accept.<br>
                        Net 10 – Payment due in 10 days from invoice date.<br>
                        Net 30 – Payment due in 30 days from invoice date.
                    </p>
                </div>
                <div>
                    <h4 class="text-gray-100 mb-5px txt-sm font-medium">{{ __('messages.invoice.terms') }}:</h4>
                    <p class="text-gray-300 text-sm mb-4 font-normal">{{ __('messages.invoice.total') }} ; 1% 10 Net
                        30, 1% discount
                        if payment
                        received within 10 days otherwise payment 30 days after invoice date.</p>
                </div>
            </div>
        </div>
    </div>
</div>
