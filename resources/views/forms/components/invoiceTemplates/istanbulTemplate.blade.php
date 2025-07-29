<div class="preview-main client-preview istanbul-template pdf">
    <div class="d" id="boxes">
        <div class="d-inner">
            <div>
                <div class="invoice-header">
                    <table class="overflow-hidden w-full">
                        <tr>
                            <td class="heading-text p-10" style="width:30%;">
                                <div class="relative z-10" >
                                    <h1 class="m-0 leading-12 pt-2 text-32 font-bold letter-spacing-2 text-white">{{ __('messages.common.invoice') }}</h1>
                                </div>
                            </td>
                            <td>
                                <div class="px-10 text-end">
                                    <div class="logo-img w-full h-full ms-auto">
                                        <img src="{{ getLogoUrl() }}" class="img-logo h-full w-full" alt="logo">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="px-10">
                    <table class="mt-5 w-full">
                        <tr>
                            <td class="text-end">
                                <p class="mb-1 text-gray-600 text-sm"><strong class="text-gray-900 font-semibold">{{ __('messages.invoice.invoice_id') }}: </strong>#9CP5X7</p>
                                <p class="mb-1 text-gray-600 text-sm"><strong class="text-gray-900 font-semibold">{{ __('messages.invoice.invoice_date') }}: </strong>01/01/2024</p>
                                <p class="mb-1 text-gray-600 text-sm"><strong class="text-gray-900 font-semibold">{{ __('messages.invoice.due_date') }}: </strong>15/01/2024</p>
                            </td>
                        </tr>
                    </table>
                    <div class="overflow-auto mt-5">
                        <table class="w-full">
                            <tbody>
                                <tr style="vertical-align:top;">
                                    <td class="pe-3 px">
                                        <p class="text-sm mb-2 text-gray-900"><b>{{ __('messages.common.to') }}:</b></p>
                                        <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ __('messages.common.name') }}: </strong>&lt;{{ __('messages.invoice.client_name') }}&gt;</p>
                                        <p class="mb-1 text-sm text-black font-medium" style="white-space:nowrap;"><strong class="text-gray-600">{{ __('messages.common.email') }}: </strong>&lt;{{ __('messages.invoice.client_email') }}&gt;</p>
                                        <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ __('messages.common.address') }}: </strong>&lt;{{ __('messages.client_address') }}&gt;</p>
                                        <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ getVatNoLabel() }}: </strong>&lt;{{ getVatNoLabel() }}&gt;</p>
                                    </td>
                                    <td>
                                        <div style="width:200px" class="ms-auto">
                                            <p class="text-sm mb-2 text-gray-900"><b>{{ __('messages.common.from') }}:</b></p>
                                            <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ __('messages.common.name') }}: </strong>{{ $companyName }}</p>
                                            <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ __('messages.common.address') }}: </strong>{{ $companyAddress }}</p>
                                            <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ __('messages.user.phone') }}: </strong>{{ $companyPhone }}</p>
                                            <p class="mb-1 text-sm text-black font-medium"><strong class="text-gray-600">{{ getVatNoLabel() }}: </strong>{{ $gstNo }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow-auto mt-10">
                        <table class="invoice-table w-full border-b border-gray-300">
                            <thead class="bgColor" style="background-color: {{ $invColor }};color: #fff;">
                                <tr>
                                    <th class="p-10px text-13 text-white"><b>#</b></th>
                                    <th class="p-10px  text-nowrap text-13 text-white" style="width:50%"><b>{{ __('messages.product.product') }}</b></th>
                                    <th class="p-10px text-center text-13 text-white"><b>{{ __('messages.invoice.qty') }}</b></th>
                                    <th class="p-10px text-center text-nowrap text-13 text-white"><b>{{ __('messages.product.unit_price') }}</b></th>
                                    <th class="p-10px text-center text-nowrap text-13 text-white"><b>{{ __('messages.invoice.tax') }} (in %)</b></th>
                                    <th class="p-10px text-end text-nowrap text-13 text-white"><b>{{ __('messages.invoice.amount') }}</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="vertical-align:top;">
                                    <td class="p-10px text-13 text-gray-900"><span>1</span></td>
                                    <td class="p-10px text-13 text-gray-600 in-w-2">
                                        <p class="font-semibold mb-0 text-gray-900">Item 1</p>{{ __('messages.Description') }}
                                    </td>
                                    <td class="p-10px text-13 text-gray-600 text-center">1</td>
                                    <td class="p-10px text-13 text-gray-600 text-center text-nowrap">$ 100.00</td>
                                    <td class="p-10px text-13 text-gray-600 text-center">N/A</td>
                                    <td class="p-10px text-13 text-gray-600 text-end text-nowrap">$ 100.00</td>
                                </tr>
                                <tr style="vertical-align:top;">
                                    <td class="p-10px text-13 text-gray-900"><span>2</span></td>
                                    <td class="p-10px text-13 text-gray-600 in-w-2">
                                        <p class="font-semibold mb-0 text-gray-900">Item 2</p>{{ __('messages.Description') }}
                                    </td>
                                    <td class="p-10px text-13 text-gray-600 text-center">1</td>
                                    <td class="p-10px text-13 text-gray-600 text-center text-nowrap">$ 100.00</td>
                                    <td class="p-10px text-13 text-gray-600 text-center">N/A</td>
                                    <td class="p-10px text-13 text-gray-600 text-end text-nowrap">$ 100.00</td>
                                </tr>
                                <tr style="vertical-align:top;">
                                    <td class="p-10px text-13 text-gray-900"><span>3</span></td>
                                    <td class="p-10px text-13 text-gray-600 in-w-2">
                                        <p class="font-semibold mb-0 text-gray-900">Item 3</p>{{ __('messages.Description') }}
                                    </td>
                                    <td class="p-10px text-13 text-gray-600 text-center">1</td>
                                    <td class="p-10px text-13 text-gray-600 text-center text-nowrap">$ 100.00</td>
                                    <td class="p-10px text-13 text-gray-600 text-center">N/A</td>
                                    <td class="p-10px text-13 text-gray-600 text-end text-nowrap">$ 100.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="my-10">
                        <table class="w-full">
                            <tr>
                                <td style="width:60%;">
                                    <svg xmlns="http://www.w3.org/1000/svg" width="70" height="70" viewBox="0 0 70 70" fill="none">
                                        <path d="M0 0V3.33333V6.66667V10V13.3333V16.6667V20V23.3333H3.33333H6.66667H10H13.3333H16.6667H20H23.3333V20V16.6667V13.3333V10V6.66667V3.33333V0H20H16.6667H13.3333H10H6.66667H3.33333H0ZM26.6667 0V3.33333V6.66667H30V3.33333H33.3333V0H30H26.6667ZM33.3333 3.33333V6.66667H36.6667V3.33333H33.3333ZM36.6667 6.66667V10H40H43.3333V6.66667V3.33333V0H40V3.33333V6.66667H36.6667ZM36.6667 10H33.3333H30H26.6667V13.3333H30H33.3333V16.6667H30V20H33.3333V23.3333H36.6667V20H40V23.3333H36.6667V26.6667H33.3333V30H36.6667H40V33.3333H43.3333V30V26.6667V23.3333V20V16.6667H40V13.3333H36.6667V10ZM40 33.3333H36.6667V36.6667H33.3333V40V43.3333H36.6667V46.6667V50H33.3333H30V46.6667H33.3333V43.3333H30V40V36.6667H26.6667V33.3333H30V36.6667H33.3333V33.3333V30H30H26.6667V26.6667H23.3333H20H16.6667V30H13.3333V33.3333H10V30H13.3333V26.6667H10H6.66667V30H3.33333V33.3333V36.6667V40H6.66667V36.6667H10H13.3333H16.6667V33.3333H20V30H23.3333V33.3333H20V36.6667H23.3333V40H20V43.3333H23.3333H26.6667V46.6667V50V53.3333H30V56.6667H33.3333H36.6667H40V60H36.6667H33.3333H30V56.6667H26.6667V60V63.3333H30V66.6667H26.6667V70H30H33.3333V66.6667H36.6667V70H40H43.3333H46.6667H50V66.6667H53.3333V70H56.6667H60H63.3333V66.6667H60H56.6667V63.3333H53.3333V60H50V63.3333H46.6667H43.3333V66.6667H40V63.3333H43.3333V60V56.6667V53.3333H46.6667V50H43.3333H40V46.6667H43.3333V43.3333V40V36.6667H40V33.3333ZM50 60V56.6667H46.6667V60H50ZM56.6667 63.3333H60H63.3333V60H60H56.6667V63.3333ZM63.3333 60H66.6667V56.6667V53.3333V50V46.6667H63.3333H60H56.6667V43.3333H53.3333V46.6667H50V50V53.3333H53.3333V50H56.6667V53.3333V56.6667H60H63.3333V60ZM53.3333 43.3333V40H50H46.6667V43.3333H50H53.3333ZM56.6667 43.3333H60V40V36.6667V33.3333H63.3333V36.6667H66.6667V40H70V36.6667V33.3333H66.6667V30H70V26.6667H66.6667H63.3333V30H60V26.6667H56.6667V30H53.3333V33.3333H56.6667V36.6667V40V43.3333ZM53.3333 33.3333H50V36.6667H53.3333V33.3333ZM66.6667 40H63.3333V43.3333H66.6667V40ZM20 40V36.6667H16.6667V40H20ZM16.6667 40H13.3333H10H6.66667V43.3333H10H13.3333H16.6667V40ZM3.33333 40H0V43.3333H3.33333V40ZM3.33333 30V26.6667H0V30H3.33333ZM26.6667 26.6667H30V23.3333V20H26.6667V23.3333V26.6667ZM46.6667 0V3.33333V6.66667V10V13.3333V16.6667V20V23.3333H50H53.3333H56.6667H60H63.3333H66.6667H70V20V16.6667V13.3333V10V6.66667V3.33333V0H66.6667H63.3333H60H56.6667H53.3333H50H46.6667ZM3.33333 3.33333H6.66667H10H13.3333H16.6667H20V6.66667V10V13.3333V16.6667V20H16.6667H13.3333H10H6.66667H3.33333V16.6667V13.3333V10V6.66667V3.33333ZM50 3.33333H53.3333H56.6667H60H63.3333H66.6667V6.66667V10V13.3333V16.6667V20H63.3333H60H56.6667H53.3333H50V16.6667V13.3333V10V6.66667V3.33333ZM6.66667 6.66667V10V13.3333V16.6667H10H13.3333H16.6667V13.3333V10V6.66667H13.3333H10H6.66667ZM53.3333 6.66667V10V13.3333V16.6667H56.6667H60H63.3333V13.3333V10V6.66667H60H56.6667H53.3333ZM46.6667 26.6667V30H50V26.6667H46.6667ZM0 46.6667V50V53.3333V56.6667V60V63.3333V66.6667V70H3.33333H6.66667H10H13.3333H16.6667H20H23.3333V66.6667V63.3333V60V56.6667V53.3333V50V46.6667H20H16.6667H13.3333H10H6.66667H3.33333H0ZM3.33333 50H6.66667H10H13.3333H16.6667H20V53.3333V56.6667V60V63.3333V66.6667H16.6667H13.3333H10H6.66667H3.33333V63.3333V60V56.6667V53.3333V50ZM6.66667 53.3333V56.6667V60V63.3333H10H13.3333H16.6667V60V56.6667V53.3333H13.3333H10H6.66667ZM66.6667 63.3333V66.6667H70V63.3333H66.6667Z" style="fill:#1A1C21;" />
                                    </svg>
                                </td>
                                <td style="vertical-align:top;">
                                    <table class="w-full">
                                        <tbody>
                                            <tr>
                                                <td class="pb-2 text-13 fontColor" style="color:{{ $invColor }}">
                                                    <strong>{{ __('messages.invoice.sub_total') }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end text-gray-600 pb-2 font-medium text-13">
                                                    $ 300.00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pb-2 text-13 fontColor" style="color:{{ $invColor }}">
                                                    <strong>{{ __('messages.invoice.discount') }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end text-gray-600 pb-2 font-medium text-13">
                                                    $ 50.00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pb-2 text-13 fontColor" style="color:{{ $invColor }}">
                                                    <strong>{{ __('messages.invoice.tax') }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end text-gray-600 pb-2 font-medium text-13">
                                                    $ 0.00
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="border-t border-gray-300">
                                            <tr>
                                                <td class="py-2 text-13 fontColor" style="color:{{ $invColor }}">
                                                    <strong>Total</strong>
                                                </td>
                                                <td class="text-nowrap text-end text-gray-600 py-2 font-medium text-13">
                                                    $ 250.00
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-20">
                        <table class="w-full">
                            <tr>
                                <td style="width:50%;">
                                    <div class="mb-5">
                                        <h6 class="text-gray-900 mb-5px font-semibold text-sm">{{ __('messages.client.notes') }}:</h6>
                                        <p class="text-gray-600 text-13 mb-4">Please pay within 15 days of receiving this invoice.</p>
                                    </div>
                                    <div>
                                        <h6 class="text-gray-900 mb-5px font-semibold text-sm">{{ __('messages.invoice.terms') }}:</h6>
                                        <p class="text-gray-600 text-13 mb-4">Invoice payment total; 1% 10 Net 30, 1% discount if payment received within 10 days otherwise payment 30 days after invoice date.</p>
                                    </div>
                                </td>
                                <td class="text-end" style="vertical-align:bottom; width:25%;">
                                    <div>
                                        <h6 class="mb-5px text-sm fontColor" style="color:{{ $invColor }}">{{ __('messages.setting.regards') }}:</h6>
                                        <p class="text-gray-900 font-bold text-13 mb-4"><b>{{ $companyName }}</b></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>