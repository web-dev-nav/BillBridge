<div class="preview-main client-preview mumbai-template pdf">
    <div class="d" id="boxes">
        <div class="d-inner">
            <div>
                <div class="top-border w-full bgColor" style="background-color: {{ $invColor }};"></div>
                <div  class="bgColor" style="background-color: {{ $invColor }};">
                    <table class="pb-10 bg-white w-full">
                        <tr>
                            <td class="p-0 h-32" style="width:66%; overflow:hidden;">
                                <div class="bg-white p-4 pt-10 h-32" style="border-top-right-radius:30px;">
                                    <img width="100" height="100" src="{{ getLogoUrl() }}" class="img-logo" alt="logo">
                                </div>
                            </td>
                            <td class="bg-white p-0 h-32" style="width:33%; border-bottom-left-radius:30px; overflow:hidden;">
                                <div class="text-end p-4 pt-10 h-32 bgColor" style="background-color: {{ $invColor }};">
                                    <h1 class="m-0 text-white pe-2 text-36 font-bold letter-spacing-4px leading-12">{{ __('messages.common.invoice') }}</h1>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="px-4 me-3 bg-white">
                        <div class="pt-6">
                            <table class="mb-10 w-full">
                                <tbody>
                                    <tr style="vertical-align:top;">
                                        <td width="43.33%">
                                            <p class="text-sm mb-2 text-gray-900"><b>{{ __('messages.common.to') }}:</b></p>
                                            <p class="mb-1 text-gray-100 text-sm">{{ __('messages.common.name') }}: <span class="text-gray-900">&lt;{{ __('messages.invoice.client_name') }}&gt;</span></p>
                                            <p class="mb-1 text-gray-100 text-sm">{{ __('messages.common.email') }}: <span class="text-gray-900">&lt;{{ __('messages.invoice.client_email') }}&gt;</span></p>
                                            <p class="mb-1 text-gray-100 text-sm">{{ __('messages.common.address') }}: <span class="text-gray-900">&lt;{{ __('messages.client_address') }}&gt;</span></p>
                                            <p class="mb-1 text-gray-100 text-sm">{{ getVatNoLabel() }}: <span class="text-gray-900">&lt;{{ getVatNoLabel() }}&gt;</span></p>
                                        </td>
                                        <td width="23.33%">
                                            <p class="text-sm mb-2 text-gray-900"><b>{{ __('messages.common.from') }}:</b></p>
                                            <p class="mb-1 text-gray-100 text-sm">{{ __('messages.common.name') }}: <span class="text-gray-900">{{ $companyName }}</span></p>
                                            <p class="mb-1 text-gray-100 fw-bold text-sm">{{ __('messages.common.address') }}: <span class="text-gray-900">{{ $companyAddress }}</span></p>
                                            <p class="mb-1 text-gray-100 fw-bold text-sm">{{ __('messages.user.phone') }}: <span class="text-gray-900">{{ $companyPhone }}</span></p>
                                            <p class="mb-1 text-gray-100 text-sm">{{ getVatNoLabel() }}: <span class="text-gray-900">{{ $gstNo }}</span></p>
                                        </td>
                                        <td width="33.33%;" class="text-end pt-7">
                                            <p class="mb-1 text-gray-100 text-sm"><strong class="text-gray-900">{{ __('messages.invoice.invoice_id') }}: </strong><strong>#5CW2X7</strong></p>
                                            <p class="mb-1 text-gray-100 text-sm"><strong class="text-gray-900">{{ __('messages.invoice.invoice_date') }}: </strong><strong>01-08-2023</strong></p>
                                            <p class="mb-1 text-gray-100 text-sm"><strong class="text-gray-900">{{ __('messages.invoice.due_date') }}: </strong><strong>15-08-2023</strong></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="overflow-auto">
                            <table class="invoice-table w-full">
                                <thead class="bgColor" style="background-color: {{ $invColor }};color: #fff;">
                                    <tr>
                                        <th class="p-2 text-white text-13 uppercase"><b>#</b></th>
                                        <th class="p-2 text-start text-white text-13 uppercase" style="width:50%;"><b>{{ __('messages.item') }}</b></th>
                                        <th class="p-2 text-center text-white text-13 uppercase"><b>{{ __('messages.invoice.qty') }}</b></th>
                                        <th class="p-2 text-center text-white text-13 uppercase text-nowrap"><b>{{ __('messages.product.unit_price') }}</b></th>
                                        <th class="p-2 text-center text-white text-13 uppercase text-nowrap"><b>{{ __('messages.invoice.tax') }} (in %)</b></th>
                                        <th class="p-2 text-end text-white text-13 uppercase text-nowrap"><b>{{ __('messages.invoice.amount') }}</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2 text-13 text-black"><span>1</span></td>
                                        <td class="p-2 text-13 text-black"><p class="font-medium mb-0">{{ __('messages.item') }} 1</p>{{ __('messages.Description') }}</td>
                                        <td class="p-2 text-13 text-black text-center">1</td>
                                        <td class="p-2 text-13 text-black text-center text-nowrap">{{ getCurrencyAmount(100, true) }}</td>
                                        <td class="p-2 text-13 text-black text-center">N/A</td>
                                        <td class="p-2 text-13 text-black text-end text-nowrap">{{ getCurrencyAmount(100, true) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-13 text-black"><span>2</span></td>
                                        <td class="p-2 text-13 text-black"><p class="font-medium mb-0">{{ __('messages.item') }} 2</p>{{ __('messages.Description') }}</td>
                                        <td class="p-2 text-13 text-black text-center">1</td>
                                        <td class="p-2 text-13 text-black text-center text-nowrap">{{ getCurrencyAmount(100, true) }}</td>
                                        <td class="p-2 text-13 text-black text-center">N/A</td>
                                        <td class="p-2 text-13 text-black text-end text-nowrap">{{ getCurrencyAmount(100, true) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-13 text-black"><span>3</span></td>
                                        <td class="p-2 text-13 text-black"><p class="font-medium mb-0">{{ __('messages.item') }} 3</p>{{ __('messages.Description') }}</td>
                                        <td class="p-2 text-13 text-black text-center">1</td>
                                        <td class="p-2 text-13 text-black text-center text-nowrap">{{ getCurrencyAmount(100, true) }}</td>
                                        <td class="p-2 text-13 text-black text-center">N/A</td>
                                        <td class="p-2 text-13 text-black text-end text-nowrap">{{ getCurrencyAmount(100, true) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-10">
                            <table class="w-full total-amount">
                                <tr>
                                    <td style="vertical-align:bottom; width:60%;">
                                        <img class="mt-4" src="{{ asset('images/qrcode.png') }}" height="100" width="100">
                                    </td>
                                    <td style="vertical-align:top; width:40%">
                                        <table class="w-full">
                                            <tbody >
                                                <tr>
                                                    <td class="text-nowrap py-1 px-2 text-13 text-black">
                                                        <strong>{{ __('messages.invoice.amount') }}</strong>
                                                    </td>
                                                    <td class="text-nowrap text-end text-gray-600 py-1 px-2 font-medium text-13">
                                                        {{ getCurrencyAmount(300, true) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-nowrap py-1 px-2 text-13 text-black">
                                                        <strong>{{ __('messages.invoice.discount') }}</strong>
                                                    </td>
                                                    <td class="text-nowrap text-end text-gray-600 py-1 px-2 font-medium text-13">
                                                        {{ getCurrencyAmount(50, true) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-nowrap py-1 px-2 text-13 text-black">
                                                        <strong>{{ __('messages.invoice.tax') }}</strong>
                                                    </td>
                                                    <td class="text-end text-gray-600 py-1 px-2 font-medium text-13">
                                                        0%
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot  class="text-white">
                                                <tr class="bgColor" style="background-color: {{ $invColor }};" >
                                                    <td class="text-nowrap p-2 text-13">
                                                        <strong>{{ __('messages.invoice.total') }}</strong>
                                                    </td>
                                                    <td class="text-nowrap text-end p-2 text-13">
                                                        <strong>{{ getCurrencyAmount(250, true) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="mt-20">
                            <div class="mb-5 pt-10">
                                <h6 class="text-gray-900 mb-5px text-sm font-medium leading-12"><b>{{ __('messages.client.notes') }}:</b></h6>
                                <p class="text-gray-600 text-13 mb-4">Paypal, Stripe & manual payment method accept. Net 10 – Payment due in 10 days from invoice date. Net 30 – Payment due in 30 days from invoice date.</p>
                            </div>
                            <table>
                                <tr>
                                    <td style="width:50%;">
                                        <div>
                                            <h6 class="text-gray-900 mb-5px text-sm font-medium leading-12"><b>{{ __('messages.invoice.terms') }}:</b></h6>
                                            <p class="text-gray-600 mb-4 text-13">Invoice payment Total; 1% 10 Net 30, 1% discount if payment received within 10 days otherwise payment 30 days after invoice date.</p>
                                        </div>
                                    </td>
                                    <td class="text-end" style="width:25%;">
                                        <div class="mb-10 pb-4">
                                            <h5 class="text-indigo mb-5px text-sm font-medium leading-12 fontColor" style="color:{{ $invColor }}"><b>{{ __('messages.setting.regards') }}:</b></h5>
                                            <p class="text-gray-900 mb-4 text-13"><b>{{ $companyName }}</b></p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                  
                </div>
                <table class="bg-white w-full">
                    <tbody><tr>
                        <td class=" p-0 h-25" style="width:80%; overflow:hidden; ">
                            <div class="bg-white p-4 pt-10 h-25" style=" border-bottom-right-radius:30px;">
                            </div>
                        </td>
                        <td class="bg-white p-0 h-25" style="width:20%;  border-top-left-radius:35px; overflow:hidden; ">
                            <div class="text-end  p-4 pt-10 h-25 bgColor" style="background-color: {{ $invColor }};">
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            <div class="top-border w-full"></div>
            </div>
            </div>
        </div>
    </div>
</div>