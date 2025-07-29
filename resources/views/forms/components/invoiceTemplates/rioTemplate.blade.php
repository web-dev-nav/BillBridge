<div class="preview-main client-preview pdf">
    <div class="d" id="boxes">
        <table class="mb-8 w-full">
            <tr>
                <td class="align-top" style="width:30%;">
                    <div class="logo-img w-full h-full">
                        <img src="{{ getLogoUrl() }}" class="img-logo h-full w-full" alt="logo">
                    </div>
                </td>
                <td class="" style="width:30%;">
                    <p class="p-text mb-0 text-13 leading-18 text-black font-extrabold">{{
                        __('messages.invoice.invoice_id') }}:
                        <strong>#9CQ5X7</strong>
                    </p>
                    <p class="p-text mb-0 text-13 leading-18 text-black font-extrabold">{{
                        __('messages.invoice.invoice_date') }}:
                        <strong>2022-01-01</strong>
                    </p>
                    <p class="p-text mb-0 text-13 leading-18 text-black font-extrabold">{{
                        __('messages.invoice.due_date') }}:
                        <strong>2022-01-02</strong>
                    </p>
                </td>
                <td class="bgColor" style="background-color: {{$invColor}};" style="width:20%;">
                    <h1 class="fancy-title tu text-center mb-auto p-3 text-white text-34px mt-23px font-bold">{{
                        __('messages.common.invoice') }}</h1>
                </td>
            </tr>
        </table>
        <table class="mb-8" style="width: 75%">
            <tr>
                <td style="width: 50%;">
                    <p class="text-sm text-black mb-2"><strong>{{ __('messages.common.to') }}:</strong></p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ __('messages.common.name') }}: <span
                            class="text-dark text-sm font-medium">&lt;{{ __('messages.invoice.client_name')
                            }}&gt;</span>
                    </p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ __('messages.common.email') }}: <span
                            class="text-dark text-sm font-medium">&lt;{{ __('messages.invoice.client_email')
                            }}&gt;</span>
                    </p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ __('messages.common.address') }}: <span
                            class="text-dark text-sm font-medium">&lt;{{ __('messages.client_address') }}&gt;</span></p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ getVatNoLabel() }}: <span
                            class="text-dark text-sm font-medium">&lt;{{
                            getVatNoLabel() }}&gt;</span></p>
                </td>
                <td style="width: 50%;">
                    <p class="text-lg mb-2"><strong>From:</strong></p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ __('messages.common.name') }}: <span
                            class="text-dark text-sm font-medium">{{$companyName}}</span></p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ __('messages.common.address') }}: <span
                            class="text-dark text-sm font-medium">{{$companyAddress}}</span></p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ __('messages.user.phone') }}: <span
                            class="text-dark text-sm font-medium">{{$companyPhone}}</span></p>
                    <p class="m-0 text-gray-300 text-sm font-extrabold">{{ getVatNoLabel() }}: <span
                            class="text-dark text-sm font-medium">{{$gstNo}}</span></p>
                </td>
            </tr>
        </table>
        <div class="table-responsive-sm table-striped">
            <table class="w-full">
                <thead class="bgColor" style="background-color: {{$invColor}};">
                    <tr>
                        <th class="px-2 py-1 text-white text-center text-13 font-bold"><strong>#</strong></th>
                        <th class="px-2 py-1 text-white font-bold text-start text-13 uppercase" style="width: 50%;">
                            <strong>{{
                                __('messages.item')
                                }}</strong>
                        </th>
                        <th class="px-2 py-1 text-white text-center font-bold text-13 uppercase text-nowrap"><strong>{{
                                __('messages.invoice.qty') }}</strong></th>
                        <th class="px-2 py-1 text-white text-center font-bold text-13 uppercase text-nowrap"><strong>{{
                                __('messages.product.unit_price') }}</strong></th>
                        <th class="px-2 py-1 text-white text-center font-bold text-13 uppercase text-nowrap"><strong>{{
                                __('messages.invoice.tax') . '(in %)' }}</strong></th>
                        <th class="px-2 py-1 text-white text-end font-bold text-13 uppercase text-nowrap"><strong>{{
                                __('messages.invoice.amount') }}</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-black-300 text-dark">
                        <td class="p-2 text-center bg-white-300 font-medium text-13 text-dark">1</td>
                        <td class="p-2 text-13 font-bold">
                            <p class="font-medium mb-0 text-13 text-dark">{{ __('messages.item') }} 1</p>
                            {{ __('messages.Description') }}
                        </td>
                        <td class="p-2 text-center font-medium text-13 text-dark">1</td>
                        <td class="p-2 text-center bg-white-300 font-medium text-nowrap text-13 text-dark">{{
                            getCurrencyAmount(100,
                            true)
                            }}
                        </td>
                        <td class="p-2 text-center font-medium text-13 text-dark">N/A</td>
                        <td class="p-2 text-end bg-white-300 font-medium text-nowrap text-13 text-dark">{{ getCurrencyAmount(100,
                            true) }}
                        </td>
                    </tr>
                    <tr class="border-b border-gray-300  text-dark">
                        <td class="p-2 text-center bg-white-300 font-medium text-13 text-dark">2</td>
                        <td class="p-2 text-13 font-bold">
                            <p class="font-medium mb-0 text-13 text-dark">{{ __('messages.item') }} 2</p>
                            {{ __('messages.Description') }}
                        </td>
                        <td class="p-2 text-center font-medium text-13 text-dark">1</td>
                        <td class="p-2 text-center bg-white-300 font-medium text-nowrap text-13">{{
                            getCurrencyAmount(100,
                            true)
                            }}
                        </td>
                        <td class="p-2 text-center font-medium text-13 text-dark">N/A</td>
                        <td class="p-2 text-end bg-white-300 font-medium text-nowrap text-13 text-dark">{{ getCurrencyAmount(100,
                            true) }}
                        </td>
                    </tr>
                    <tr class="border-b border-gray-300 text-13 text-dark">
                        <td class="p-2 text-center bg-white-300 font-medium text-13">3</td>
                        <td class="p-2 text-13 font-bold">
                            <p class="font-medium mb-0 text-13">{{ __('messages.item') }} 3</p>
                            {{ __('messages.Description') }}
                        </td>
                        <td class="p-2 text-center font-medium text-13">1</td>
                        <td class="p-2 text-center bg-white-300 font-medium text-nowrap text-13">{{
                            getCurrencyAmount(100,
                            true)
                            }}
                        </td>
                        <td class="p-2 text-center font-medium text-13">N/A</td>
                        <td class="p-2 text-end bg-white-300 font-medium text-nowrap text-13">{{ getCurrencyAmount(100,
                            true) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="text-dark">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center font-medium text-13 text-nowrap">{{ __('messages.invoice.amount') }}
                        </td>
                        <td class="p-2 text-end bg-white-300 font-medium text-13 text-nowrap">{{ getCurrencyAmount(300,
                            true) }}
                        </td>
                    </tr>
                    <tr class="text-dark">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center font-medium text-13 text-nowrap">{{ __('messages.invoice.discount')
                            }}</td>
                        <td class="p-2 text-end bg-white-300 font-medium text-13 text-nowrap">{{ getCurrencyAmount(50,
                            true) }}
                        </td>
                    </tr>
                    <tr class="text-dark">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center font-medium text-13 text-nowrap">{{ __('messages.invoice.tax') }}
                        </td>
                        <td class="p-2 text-end bg-white-300 font-medium text-13 text-nowrap">N/A</td>
                    </tr>
                    <tr class="text-dark">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center font-bold text-nowrap text-13"><strong>{{
                                __('messages.invoice.total')
                                }}</strong></td>
                        <td class="p-2 text-end text-white font-bold text-nowrap text-13 bgColor" style="background-color: {{$invColor}};">{{
                            getCurrencyAmount(250, true) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="relative" style="top:-50px;">
            <p class="m-0 text-sm fontColor" style="color:{{$invColor}};"><b>{{
                    __('messages.payment_qr_codes.payment_qr_code') }}</b></p>
            <div class="qr-img w-full h-full">
                <img class="mt-2 h-full w-full" src="{{ asset('images/qrcode.png') }}">
            </div>
        </div>
        <div class="mb-8">
            <h4 class="font-bold mb-2 text-black text-base font-medium">{{ __('messages.client.notes') }}:</h4>
            <p class="text-gray-300 text-13 font-bold">
                Paypal, Stripe & manual payment method accept. Net 10 – Payment due in 10 days from invoice date. Net 30
                – Payment due in 30 days from invoice date.
            </p>
        </div>
        <table class="w-full">
            <tr>
                <td style="width:75%">
                    <div class="mb-8">
                        <h4 class="font-bold mb-2 text-base text-black font-medium">{{ __('messages.invoice.terms') }}:
                        </h4>
                        <p class="text-gray-300 text-13 font-bold">
                            Invoice payment Total ; 1% 10 Net 30, 1% discount if payment received within 10 days
                            otherwise payment 30 days after invoice date.
                        </p>
                    </div>
                </td>
                <td class=" text-right" style="width:25%">
                    <div>
                        <h4 class="font-bold mb-2 text-base font-medium fontColor" style="color:{{$invColor}};">{{
                            __('messages.setting.regards') }}:</h4>
                        <p class="text-gray-300 text-13 font-bold"><b>{{$companyName}}</b></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>