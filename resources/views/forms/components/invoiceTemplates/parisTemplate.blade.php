<div  class="paris-template bg-white">
    <div class="flex items-center justify-center">
        <div style="position: relative; ">
            <div class="bg-img absolute start-0 top-0" style="min-width:220px;">
                            <img src="https://invoices.infyom.com/images/paris-bg-img.png">
                        </div>

            <div class="relative z-10 w-full">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="pe-2 pt-5">
                            <div class="px-10 pb-5">
                                <div class="w-full h-full logo-img">
                                <img width="100" height="100" src="{{ getLogoUrl() }}" alt="logo" style="max-width: 100%; height: auto;">
                            </div>
                            </div>
                        </td>
                        <td  class="px-10 pb-5 align-bottom">
                            <div class="text-end">
                                <h1 class="font-bold text-36 letter-spacing-4px leading-12 fontColor" style="color: {{ $invColor }};">{{ __('messages.common.invoice') }}</h1>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="px-10">
                    <table class="border-collapse w-full">
                        <tr>
                            <td class="text-end">
                                <p class="text-13 text-gray-600"><strong class="text-gray-900 text-13">{{ __('messages.invoice.invoice_id') }}:</strong> #7NA6L2</p>
                                <p class="text-13 text-gray-600"><strong class="text-gray-900 text-13">{{ __('messages.invoice.invoice_date') }}:</strong> 01/03/2024</p>
                                <p class="text-13 text-gray-600"><strong class="text-gray-900 text-13">{{ __('messages.invoice.due_date') }}:</strong> 15/03/2024</p>
                            </td>
                        </tr>
                    </table>
                    <div class="overflow-auto mt-10">
                        <table class="w-full border-collapse">
                            <tbody>
                                <tr class="align-top">
                                    <td style="width: 43.33%;">
                                        <p class="text-13 mb-5px fontColor" style="color: {{ $invColor }};"><strong>{{ __('messages.common.from') . ':' }}</strong></p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ __('messages.common.name') . ':' }} </strong>{{ $companyName }}</p>
                                        <p class="text-13 text-black-900 mb-5px"  style="max-width: 220px;"><strong>{{ __('messages.common.address') . ':' }} </strong> C-303, Atlanta Shopping Mall, Nr. Sudama Chowk, Mota Varachha, Surat - 394101, Gujarat, India.</p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ __('messages.user.phone') . ':' }} </strong> +91 70963 36561</p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ getVatNoLabel() }}: </strong> {{ $gstNo }}</p>
                                    </td>
                                    <td style="width: 23.33%;">
                                        <p class="text-13 mb-5px fontColor" style="color: {{ $invColor }};"><strong>{{ __('messages.common.to') }}:</strong></p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ __('messages.common.name') . ':' }} </strong>&lt{{ __('messages.invoice.client_name') }}&gt</p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ __('messages.common.email') . ':' }} </strong>&lt{{ __('messages.invoice.client_email') }}&gt</p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ __('messages.common.address') . ':' }} </strong>&lt{{ __('messages.client_address') }}&gt</p>
                                        <p class="text-13 text-black-900 mb-5px"><strong>{{ getVatNoLabel() }}: </strong>&lt{{ getVatNoLabel() }}&gt</p>
                                    </td>
                                    <td style="width: 33.33%" class="text-center">
                                        <p class=" mb-5px text-13 fontColor" style="color: {{ $invColor }};"><strong>{{ __('messages.common.scan_to_pay') . ':' }}</strong></p>
                                        <div class="flex items-center justify-center mx-auto">
                                            <img src="{{ asset('images/qrcode.png') }}" height="70" width="70">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-10 overflow-auto w-full">
                        <table class="invoice-table w-full">
                            <thead class="bgColor" style="background-color: {{ $invColor }};">
                                <tr>
                                    <th class="text-13 p-10px text-start text-white"><b>#</b></th>
                                    <th class="text-13 p-10px text-start text-white" style="width: 50%;"><b>{{ __('messages.item') }}</b></th>
                                    <th class="text-13 p-10px text-center text-white"><b>{{ __('messages.invoice.qty') }}</b></th>
                                    <th class="text-13 p-10px text-center text-white"><b>{{ __('messages.product.unit_price') }}</b></th>
                                    <th class="text-13 p-10px text-center text-white"><b>{{ __('messages.invoice.tax') }} (in %)</b></th>
                                    <th class="text-13 p-10px text-end text-white"><b>{{ __('messages.invoice.amount') }}</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-13 p-10px text-gray-600"><span>1</span></td>
                                    <td class="text-13 p-10px text-gray-600">
                                        <p class="text-13 text-black-900 font-semibold">Item 1</p>{{ __('messages.Description') }}
                                    </td>
                                    <td class="text-13 p-10px text-gray-600 text-center">1</td>
                                    <td class="text-13 p-10px text-gray-600 text-center">$ 100.00</td>
                                    <td class="text-13 p-10px text-gray-600 text-center">N/A</td>
                                    <td class="text-13 p-10px text-gray-600 text-end">$ 100.00</td>
                                </tr>
                                <tr>
                                    <td class="text-13 p-10px text-gray-600"><span>2</span></td>
                                    <td class="text-13 p-10px text-gray-600">
                                        <p class="text-13 text-black-900 font-semibold">Item 2</p>{{ __('messages.Description') }}
                                    </td>
                                    <td class="text-13 p-10px text-gray-600 text-center">1</td>
                                    <td class="text-13 p-10px text-gray-600 text-center">$ 100.00</td>
                                    <td class="text-13 p-10px text-gray-600 text-center">N/A</td>
                                    <td class="text-13 p-10px text-gray-600 text-end">$ 100.00</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #cecece;">
                                    <td class="text-13 p-10px text-gray-600"><span>3</span></td>
                                    <td class="text-13 p-10px text-gray-600">
                                        <p class="text-13 text-black-900 font-semibold">Item 3</p>{{ __('messages.Description') }}
                                    </td>
                                    <td class="text-13 p-10px text-gray-600 text-center">1</td>
                                    <td class="text-13 p-10px text-gray-600 text-center">$ 100.00</td>
                                    <td class="text-13 p-10px text-gray-600 text-center">N/A</td>
                                    <td class="text-13 p-10px text-gray-600 text-end">$ 100.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-10">
                        <table style="width: 250px;" class="ms-auto total-amount">
                            <tbody>
                                <tr>
                                    <td class="text-13 pb-2 fontColor" style="color: {{ $invColor }};">
                                        <strong>{{ __('messages.invoice.sub_total') }}</strong>
                                    </td>
                                    <td class="text-13 pb-2 text-gray-600 font-medium text-end">
                                        $ 300.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-13 pb-2 fontColor" style="color: {{ $invColor }};">
                                        <strong>{{ __('messages.invoice.discount') }}</strong>
                                    </td>
                                    <td class="text-13 pb-2 text-gray-600 font-medium text-end">
                                        $ 50.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-13 pb-2 fontColor" style="color: {{ $invColor }};">
                                        <strong>{{ __('messages.invoice.tax') }}</strong>
                                    </td>
                                    <td class="text-13 pb-2 text-gray-600 font-medium text-end">
                                        $ 0.00
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-13 fontColor" style="color: {{ $invColor }};">
                                        <strong>{{ __('messages.invoice.total') }}</strong>
                                    </td>
                                    <td style="text-align: end; padding: 5px;">
                                        $ 250.00
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <table class="mt-15 w-full">
                                <tbody><tr>
                                    <td>
                                        <div class="mb-5 ">
                                            <h6 class="text-gray-900 mb-5px text-sm"><b>Notes:</b></h6>
                                            <p class="text-gray-600 text-13 mb-4">
                                                <span class="me-1 inline-flex">
                                                    <svg width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/1000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 0C0.895431 0 0 0.89543 0 2V8C0 9.10457 0.89543 10 2 10H8C9.10457 10 10 9.10457 10 8V2C10 0.895431 9.10457 0 8 0H2ZM4.72221 2.95508C4.72221 2.7825 4.58145 2.64014 4.41071 2.66555C3.33092 2.82592 2.5 3.80797 2.5 4.99549V7.01758C2.5 7.19016 2.63992 7.33008 2.8125 7.33008H4.40971C4.58229 7.33008 4.72221 7.19016 4.72221 7.01758V5.6021C4.72221 5.42952 4.58229 5.2896 4.40971 5.2896H3.61115V4.95345C3.61115 4.41687 3.95035 3.96422 4.41422 3.82285C4.57924 3.77249 4.72221 3.63715 4.72221 3.4645V2.95508ZM7.5 2.95508C7.5 2.7825 7.35924 2.64014 7.18849 2.66555C6.1087 2.82592 5.27779 3.80797 5.27779 4.99549V7.01758C5.27779 7.19016 5.41771 7.33008 5.59029 7.33008H7.1875C7.36008 7.33008 7.5 7.19016 7.5 7.01758V5.6021C7.5 5.42952 7.36008 5.2896 7.1875 5.2896H6.38885V4.95345C6.38885 4.41695 6.72813 3.96422 7.19193 3.82285C7.35703 3.77249 7.5 3.63715 7.5 3.4645V2.95508Z" fill="#8B919E"></path>
                                                    </svg>
                                                </span>
                                                Please pay within 15 days of receiving this invoice.
                                            </p>
                                        </div>
                                        <div>
                                            <h6 class="text-gray-900 mb-5px text-sm"><b>Terms:</b></h6>
                                            <p class="text-gray-600 mb-0 text-13">Invoice payment total ; 1% 10 Net 30, 1% discount if payment received within 10 days otherwise payment 30 days after invoice date.
                                            </p>
                                        </div>
                                    </td>
                                    <td style=" width:30%;" class="align-bottom text-end">
                                        <div>
                                            <h6 class="text-dark-gray mb-5px pt-3 text-sm">Regards:</h6>
                                            <p class=" mb-0 text-13 fontColor" style="color: {{ $invColor }};">infyom</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody></table>
                </div>
            </div>
        </div>
    </div>
</div>