<div class="preview-main client-preview pdf">
    <div class="d" id="boxes">
        <div class="d-inner">
            <div class="london-header-section pt-10 mb-10 relative overflow-hidden bgColor" style="background-color: {{ $invColor }};">
                <table class="w-full">
                    <tr>
                        <td class="bg-white-100  relative z-10">
                            <div class="px-2 sm:px-3">
                                <div class="logo-img w-full h-full">
                                    <img src="{{ getLogoUrl() }}" class="img-logo h-full w-full" alt="logo">
                                </div>
                            </div>
                        </td>
                        <td class="invoice-text text-right relative" style="width:40%;">
                            <h1 class="m-0 p-3 font-semibold text-34 relative z-10 text-white leading-12">{{ __('messages.common.invoice') }}
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-white text-right px-3 py-2 text-xs"><strong>#AB2324</strong></td>
                    </tr>
                </table>
            </div>
            <table class="mb-10 w-full">
                <tbody>
                    <tr class="align-top">
                        <td style="width:43.33%;">
                            <p class="text-sm mb-2 text-black"><strong>{{ __('messages.common.from') }}</strong></p>
                            <p class="m-0 text-gray-300 text-sm font-normal"><strong>{{ __('messages.common.name') }}: </strong>{{
                                $companyName }}</p>
                            <p class="m-0 text-gray-300 text-sm font-normal"><strong>{{ __('messages.common.address') }}:
                                </strong>{{ $companyAddress }}</p>
                            <p class="m-0 text-gray-300 text-sm font-normal"><strong>{{ __('messages.user.phone') }}:
                                </strong>{{ $companyPhone }}</p>
                            <p class="m-0 text-gray-300 text-sm font-normal"><strong>{{ getVatNoLabel() }}: </strong>{{ $gstNo }}
                            </p>
                        </td>
                        <td style="width:23.33%;">
                            <p class="text-sm mb-2 text-black"><strong>{{ __('messages.common.to') }}</strong></p>
                            <p class="m-0 text-gray-300 font-normal text-sm"><strong>{{ __('messages.common.name') }}:
                                </strong>&lt;{{ __('messages.invoice.client_name') }}&gt;</p>
                            <p class="m-0 text-gray-300 font-normal text-sm"><strong>{{ __('messages.common.email') }}:
                                </strong>&lt;{{ __('messages.invoice.client_email') }}&gt;</p>
                            <p class="m-0 text-gray-300 font-normal text-sm"><strong>{{ __('messages.common.address') }}:
                                </strong>&lt;{{ __('messages.client_address') }}&gt;</p>
                            <p class="m-0 text-gray-300 font-normal text-sm"><strong>{{ getVatNoLabel() }}: </strong>&lt;{{
                                getVatNoLabel() }}&gt;</p>
                        </td>
                        <td class="text-right" style="width:33.33%;">
                            <p class="mb-2 text-gray-300 text-sm"><strong>{{ __('messages.invoice.invoice_date') }}:
                                </strong>2022-01-01</p>
                            <p class="mb-4 text-gray-300 text-sm"><strong>{{ __('messages.invoice.due_date') }}:
                                </strong>2022-01-02</p>
                                <div class="mt-4 qr-img w-full h-full ml-auto">
                                    <img class="h-full w-full" src="{{ asset('images/qrcode.png') }}" alt="images">
                                </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="overflow-auto">
                <table class="w-full border-b border-black-300">
                    <thead class="bg-white-100 text-dark">
                        <tr>
                            <th class="p-2 text-13"><strong>#</strong></th>
                            <th class="p-2 uppercase text-start text-13" style="width: 50%;"><strong>{{ __('messages.item') }}</strong></th>
                            <th class="p-2 text-center uppercase text-13"><strong>{{ __('messages.invoice.qty') }}</strong>
                            </th>
                            <th class="p-2 text-center text-nowrap uppercase text-13"><strong>{{
                                    __('messages.product.unit_price') }}</strong></th>
                            <th class="p-2 text-center text-nowrap uppercase text-13"><strong>{{ __('messages.invoice.tax')
                                    }} (in %)</strong></th>
                            <th class="p-2 text-end uppercase text-13"><strong>{{ __('messages.invoice.amount') }}</strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 text-13 text-black"><span>1</span></td>
                            <td class="p-2 text-13 text-black">
                                <p class="text-13 text-black font-medium mb-0">{{ __('messages.item') }} 1</p>{{ __('messages.Description')
                                }}
                            </td>
                            <td class="p-2 text-13 text-black text-center">1</td>
                            <td class="p-2 text-13 text-black text-center">{{ getCurrencyAmount(100, true) }}</td>
                            <td class="p-2 text-13 text-black text-center">N/A</td>
                            <td class="p-2 text-13 text-black text-end">{{ getCurrencyAmount(100, true) }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 text-black text-13"><span>2</span></td>
                            <td class="p-2 text-black text-13">
                                <p class="font-medium text-13 text-black mb-0">{{ __('messages.item') }} 2</p>{{ __('messages.Description')
                                }}
                            </td>
                            <td class="p-2 text-black text-13 text-center">1</td>
                            <td class="p-2 text-black text-13 text-center">{{ getCurrencyAmount(100, true) }}</td>
                            <td class="p-2 text-black text-13 text-center">N/A</td>
                            <td class="p-2 text-black text-13 text-end">{{ getCurrencyAmount(100, true) }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 text-black text-13"><span>3</span></td>
                            <td class="p-2 text-black text-13">
                                <p class="font-bmedium text-13 text-blackmb-0">{{ __('messages.item') }} 3</p>{{ __('messages.Description')
                                }}
                            </td>
                            <td class="p-2 text-black text-13 text-center">1</td>
                            <td class="p-2 text-black text-13 text-center">{{ getCurrencyAmount(100, true) }}</td>
                            <td class="p-2 text-black text-13 text-center">N/A</td>
                            <td class="p-2 text-black text-13 text-end">{{ getCurrencyAmount(100, true) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="ms-auto mb-8" style="width:40%;">
                <tbody>
                    <tr>
                        <td class="py-1 px-2 text-nowrap text-13 text-black">
                            <strong>{{ __('messages.invoice.amount') }}</strong>
                        </td>
                        <td class="text-nowrap text-end text-gray-300 py-1 px-2 font-medium text-13">
                            {{ getCurrencyAmount(300, true) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1 px-2 text-nowrap text-13 text-black">
                            <strong>{{ __('messages.invoice.discount') }}</strong>
                        </td>
                        <td class="text-nowrap text-end text-gray-300 py-1 px-2 font-medium text-13">
                            {{ getCurrencyAmount(50, true) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold py-1 px-2 text-nowrap text-13 text-black">
                            <strong>{{ __('messages.invoice.tax') }}</strong>
                        </td>
                        <td class="text-end py-2 px-3 font-medium text-13 text-black">
                            N/A
                        </td>
                    </tr>
                </tbody>
                <tfoot class="text-white bgColor" style="background-color: {{ $invColor }};">
                    <tr>
                        <td class="p-2 text-13 text-white text-nowrap">
                            <strong>{{ __('messages.invoice.total') }}</strong>
                        </td>
                        <td class="text-end p-2 text-13 text-white text-nowrap">
                            <strong>{{ getCurrencyAmount(250, true) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="mb-8">
                <h4 class="font-medium mb-5px text-base text-black" >{{ __('messages.client.notes') }}:</h4>
                <p class="text-gray-300 text-13 font-normal mb-4">
                    <span class="me-1 inline-flex">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/1000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2 0C0.895431 0 0 0.89543 0 2V8C0 9.10457 0.89543 10 2 10H8C9.10457 10 10 9.10457 10 8V2C10 0.895431 9.10457 0 8 0H2ZM4.72221 2.95508C4.72221 2.7825 4.58145 2.64014 4.41071 2.66555C3.33092 2.82592 2.5 3.80797 2.5 4.99549V7.01758C2.5 7.19016 2.63992 7.33008 2.8125 7.33008H4.40971C4.58229 7.33008 4.72221 7.19016 4.72221 7.01758V5.6021C4.72221 5.42952 4.58229 5.2896 4.40971 5.2896H3.61115V4.95345C3.61115 4.41687 3.95035 3.96422 4.41422 3.82285C4.57924 3.77249 4.72221 3.63715 4.72221 3.4645V2.95508ZM7.5 2.95508C7.5 2.7825 7.35924 2.64014 7.18849 2.66555C6.1087 2.82592 5.27779 3.80797 5.27779 4.99549V7.01758C5.27779 7.19016 5.41771 7.33008 5.59029 7.33008H7.1875C7.36008 7.33008 7.5 7.19016 7.5 7.01758V5.6021C7.5 5.42952 7.36008 5.2896 7.1875 5.2896H6.38885V4.95345C6.38885 4.41695 6.72813 3.96422 7.19193 3.82285C7.35703 3.77249 7.5 3.63715 7.5 3.4645V2.95508Z"
                                fill="#8B919E" />
                        </svg>
                    </span>Paypal, Stripe & manual payment method accept. Net 10 – Payment due in 10 days from invoice date.
                    Net 30 – Payment due in 30 days from invoice date.
                  
                </p>
            </div>
            <table class="w-full">
                <tr>
                    <td style="width:75%;">
                        <div class="mb-8">
                            <h4 class="font-medium text-base text-black mb-5px">{{ __('messages.invoice.terms') }}:</h4>
                            <p class="text-gray-300 text-13 font-normal mb-4">
                                Invoice payment Total; 1% 10 Net 30, 1% discount if payment received within 10 days
                                otherwise payment 30 days after invoice date.
                            </p>
                        </div>
                    </td>
                    <td class="text-right" style="width:25%;">
                        <div>
                            <h4 class="font-medium text-base text-black mb-5px" >{{ __('messages.setting.regards') }}:</h4>
                            <p class="text-13 font-medium mb-4 fontColor" style="color:{{ $invColor }}"><b>{{ $companyName
                                    }}</b></p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>