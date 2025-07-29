<div class="preview-main client-preview pdf">
    <div class="d" id="boxes">
        <div>
            <div class="mb-8 p-5 bg-white-200">
                <table class="w-full">
                    <tr>
                        <td class="relative align-top" style="width: 50%">
                            <div class="logo-img w-full h-full">
                                <img src="{{ getLogoUrl() }}" class="img-logo h-full w-full" alt="logo">
                            </div>
                            <div class="absolute bottom-0 left-0 mb-5 qr-img w-full h-full">
                                <img class="mt-2 h-full w-full" src="{{ asset('images/qrcode.png') }}">
                            </div>
                        </td>
                        <td style="width: 50%;">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="font-bold">
                                            <p class=" text-xl mb-4 text-start leading-12 fontColor" style="color: {{$invColor}};"><strong>{{
                                                    __('messages.common.invoice') }}</strong></p>
                                        </th>
                                        <th class="">
                                            <p class=" text-xl mb-4 text-start leading-12 font-medium fontColor" style="color: {{$invColor}};">
                                                #9CQ5X7</p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p class="m-0 font-bold text-sm text-black">{{
                                                __('messages.invoice.invoice_date') }}
                                            </p>
                                            <p class="text-sm text-black mb-4">2022-01-01</p>
                                        </td>
                                        <td>
                                            <p class="m-0 font-bold text-sm text-black">{{
                                                __('messages.invoice.due_date') }}</p>
                                            <p class="text-sm text-black mb-4">2022-01-01</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-top">
                                            <span class="m-0 font-bold text-13 text-black"><strong>{{
                                                    __('messages.common.from')
                                                    }}</strong></span><br>
                                            <span class="text-13 text-black">{{$companyName}}</span><br>
                                            <span class="text-13 text-black">{{$companyAddress}}</span><br>
                                            <span class="text-13 text-black">{{ $companyPhone }}</span><br>
                                            <span class="text-13 text-black">{{$gstNo}}</span>
                                        </td>
                                        <td class="align-top">
                                            <span class="m-0 font-bold text-13"><strong>{{ __('messages.common.to')
                                                    }}</strong></span><br>
                                            <span class="text-13 text-black">&lt;{{ __('messages.invoice.client_name')
                                                }}&gt;</span><br>
                                            <span class="text-13 text-black">&lt;{{ __('messages.invoice.client_email')
                                                }}&gt;</span><br>
                                            <span class="text-13 text-black">&lt;{{ __('messages.client_address')
                                                }}&gt;</span><br>
                                            <span class="text-13 text-black">&lt;{{ getVatNoLabel() }}&gt;</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-responsive-sm p-5">
                <table class="w-full">
                    <thead class="border-b borderColor" style="border-color: {{$invColor}};">
                        <tr>
                            <th class="py-1 text-13 uppercase px-px fontColor" style="color: {{$invColor}};"><strong>#</strong></th>
                            <th class="py-1 text-13 uppercase px-px text-start fontColor" style="color: {{$invColor}};">
                                <strong>{{
                                    __('messages.item') }}</strong>
                            </th>
                            <th class="py-1 text-13 uppercase px-px fontColor" style="color: {{$invColor}};" ><strong>{{
                                    __('messages.invoice.qty') }}</strong></th>
                            <th class="py-1 text-13 uppercase px-px text-cente text-nowrap fontColor" style="color: {{$invColor}};">
                                <strong>{{
                                    __('messages.product.unit_price') }}</strong>
                            </th>
                            <th class="py-1 text-13 uppercase px-px text-cente text-nowrap fontColor" style="color: {{$invColor}};">
                                <strong>{{
                                    __('messages.invoice.tax') . '(in %)'
                                    }}</strong>
                            </th>
                            <th class="py-1 text-13 uppercase px-px text-en text-nowrap fontColor" style="color: {{$invColor}};">
                                <strong>{{
                                    __('messages.invoice.amount') }}</strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-1 px-px text-13 align-center text-black"><span>1</span></td>
                            <td class="py-1 px-px text-13 align-center text-black">
                                <p class="font-bold mb-0">{{ __('messages.item') }} 1</p>
                                {{ __('messages.Description') }}
                            </td>
                            <td class="py-1 px-px text-13 align-center text-black">1</td>
                            <td class="py-1 px-px text-13 align-center text-black text-center text-nowrap">{{
                                getCurrencyAmount(100, true) }}
                            </td>
                            <td class="py-1 px-px text-13 align-center text-black text-center">N/A</td>
                            <td class="py-1 px-px text-13 align-center text-black text-end text-nowrap">{{
                                getCurrencyAmount(100,
                                true) }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-px text-13 align-center text-black"><span>2</span></td>
                            <td class="py-1 px-px text-13 align-center text-black">
                                <p class="font-bold mb-0">{{ __('messages.item') }} 2</p>
                                {{ __('messages.Description') }}
                            </td>
                            <td class="py-1 px-px text-13 align-center text-black">1</td>
                            <td class="py-1 px-px text-13 align-center text-black text-center text-nowrap">{{
                                getCurrencyAmount(100, true) }}
                            </td>
                            <td class="py-1 px-px text-13 align-center text-black text-center">N/A</td>
                            <td class="py-1 px-px text-13 align-center text-black text-end text-nowrap">{{
                                getCurrencyAmount(100,
                                true) }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-px text-13 align-center text-black"><span>3</span></td>
                            <td class="py-1 px-px text-13 align-center text-black">
                                <p class="font-bold mb-0">{{ __('messages.item') }} 3</p>
                                {{ __('messages.Description') }}
                            </td>
                            <td class="py-1 px-px text-13 align-center text-black">1</td>
                            <td class="py-1 px-px text-13 align-center text-black text-center text-nowrap">{{
                                getCurrencyAmount(100, true) }}
                            </td>
                            <td class="py-1 px-px text-13 align-center text-black text-center">N/A</td>
                            <td class="py-1 px-px text-13 align-center text-black text-end text-nowrap">{{
                                getCurrencyAmount(100,
                                true) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="ml-auto mb-5 mr-5 border-t borderColor" style="width: 50%;border-color: {{$invColor}};">
                <tbody>
                    <tr>
                        <td class="py-1 px-px text-13 text-black">
                            <strong>{{ __('messages.invoice.amount') }}</strong>
                        </td>
                        <td class="text-end py-1 px-px text-13 text-black text-nowrap">
                            {{ getCurrencyAmount(300, true) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1 px-px text-13 text-black">
                            <strong>{{ __('messages.invoice.discount') }}</strong>
                        </td>
                        <td class="text-end py-1 px-px text-13 text-black text-nowrap">
                            {{ getCurrencyAmount(50, true) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-px text-13 text-black">
                            <strong>{{ __('messages.invoice.tax') }}</strong>
                        </td>
                        <td class="text-end py-1 px-px text-13 text-black">
                            N/A
                        </td>
                    </tr>
                </tbody>
                <tfoot class="border-t borderColor" style="border-color: {{$invColor}};">
                    <tr>
                        <td class="pt-2 px-px text-black text-13">
                            <strong>{{ __('messages.invoice.total') }}</strong>
                        </td>
                        <td class="text-end pt-2 px-px text-black text-13 text-nowrap">
                            <strong>{{ getCurrencyAmount(250, true) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="p-5">
                <div class="mb-8">
                    <h4 class="font-medium mb-5px text-base text-black">{{ __('messages.client.notes') }}:</h4>
                    <p class="text-gray-300 text-13 mb-4">
                        Paypal, Stripe & manual payment method accept. Net 10 – Payment due in 10 days from invoice
                        date. Net 30 – Payment due in 30 days from invoice date.
                    </p>
                </div>
                <div class="mb-8">
                    <h4 class="font-medium mb-5px text-base text-black">{{ __('messages.invoice.terms') }}:</h4>
                    <p class="text-gray-500 text-13 mb-4">
                        Invoice payment Total ; 1% 10 Net 30, 1% discount if payment received within 10 days otherwise
                        payment 30 days after invoice date.
                    </p>
                </div>
                <div>
                    <h5 class="font-bold mb-5px text-base text-black"><b>{{ __('messages.setting.regards') }}:</b></h5>
                    <p class="font-bold text-13 mb-4 fontColor" style="color: {{$invColor}};">
                        <b>{{$companyName}}</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>