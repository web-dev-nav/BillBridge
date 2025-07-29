<div class="preview-main client-preview tokyo-template">
    <div class="d" id="boxes">
        <div class="d-inner">
            <div>
                <div>
                    <table class="mb-12 w-full">
                        <tr>
                            <td>
                                <div class="logo-img">
                                    <img src="{{ getLogoUrl() }}" class="img-logo" alt="logo">
                                </div>
                            </td>
                            <td class="heading-text">
                                <div class="text-end">
                                    <h1 class="m-0 font-light text-36 letter-spacing-4px leading-12 fontColor"
                                        style="color:{{ $invColor }};">
                                        {{ __('messages.common.invoice') }}
                                    </h1>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="px-4">
                        <div class="my-10 overflow-auto">
                            <table class="w-full">
                                <tbody>
                                    <tr style="vertical-align:top;">
                                        <td width="43.33%;">
                                            <p class="mb-2 text-gray-900 font-medium text-sm">
                                                <strong>{{ __('messages.common.to') }}:</strong></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">
                                                {{ __('messages.common.name') }}: <span
                                                    class="text-gray-900">&lt;{{ __('messages.invoice.client_name') }}&gt;</span>
                                            </p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">
                                                {{ __('messages.common.email') }}: <span
                                                    class="text-gray-900">&lt;{{ __('messages.invoice.client_email') }}&gt;</span>
                                            </p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">
                                                {{ __('messages.common.address') }}: <span
                                                    class="text-gray-900">&lt;{{ __('messages.client_address') }}&gt;</span>
                                            </p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">{{ getVatNoLabel() }}:
                                                <span class="text-gray-900">&lt;{{ getVatNoLabel() }}&gt;</span></p>
                                        </td>
                                        <td width="23.33%;">
                                            <p class="mb-2 text-gray-900 font-medium text-sm">
                                                <strong>{{ __('messages.common.from') }}:</strong></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">
                                                {{ __('messages.common.name') }}: <span
                                                    class="text-gray-900">{{ $companyName }}</span></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">
                                                {{ __('messages.common.address') }}: <span
                                                    class="text-gray-900">{{ $companyAddress }}</span></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">
                                                {{ __('messages.user.phone') }}: <span
                                                    class="text-gray-900">{{ $companyPhone }}</span></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm">{{ getVatNoLabel() }}:
                                                <span class="text-gray-900">{{ $gstNo }}</span></p>
                                        </td>
                                        <td width="33.33%;" class="text-end pt-7">
                                            <p class="mb-1 text-gray-100 font-medium text-sm"><strong
                                                    class="text-gray-900">{{ __('messages.invoice.invoice_id') }}:
                                                </strong><strong>#3TS4U7</strong></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm"><strong
                                                    class="text-gray-900">{{ __('messages.invoice.invoice_date') }}:
                                                </strong><strong>01-08-2023</strong></p>
                                            <p class="mb-1 text-gray-100 font-medium text-sm"><strong
                                                    class="text-gray-900">{{ __('messages.invoice.due_date') }}:
                                                </strong><strong>15-08-2023</strong></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="overflow-auto" style="margin-top: 30px;">
                            <table class="invoice-table w-full">
                                <thead class="bgColor" style="background-color: {{ $invColor }};color: #fff;">
                                    <tr>
                                        <th class="p-2 text-center text-13 text-white uppercase"><b>#</b></th>
                                        <th class="p-2 text-start text-13 text-white uppercase" style="width: 50%;">
                                            <b>{{ __('messages.item') }}</b></th>
                                        <th class="p-2 text-center text-nowrap text-13 text-white uppercase">
                                            <b>{{ __('messages.invoice.qty') }}</b></th>
                                        <th class="p-2 text-center text-nowrap text-13 text-white uppercase">
                                            <b>{{ __('messages.product.unit_price') }}</b></th>
                                        <th class="p-2 text-center text-nowrap text-13 text-white uppercase">
                                            <b>{{ __('messages.invoice.tax') }} (in %)</b></th>
                                        <th class="p-2 text-end text-nowrap text-13 text-white uppercase">
                                            <b>{{ __('messages.invoice.amount') }}</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center text-13 font-semibold p-10px text-black"><span>1</span>
                                        </td>
                                        <td class=" text-13 font-semibold p-10px text-black">
                                            <p class="font-bold mb-0">Item 1</p>{{ __('messages.Description') }}
                                        </td>
                                        <td class="text-center text-13 font-semibold p-10px text-black">1</td>
                                        <td class="text-center text-nowrap text-13 font-semibold p-10px text-black">
                                            {{ getCurrencyAmount(100, true) }}</td>
                                        <td class="text-center text-13 font-semibold p-10px text-black">N/A</td>
                                        <td class="text-end text-nowrap text-13 font-semibold p-10px text-black">
                                            {{ getCurrencyAmount(100, true) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-13 font-semibold p-10px text-black"><span>2</span>
                                        </td>
                                        <td class=" text-13 font-semibold p-10px text-black">
                                            <p class="font-bold mb-0 text-13 font-semibold p-10px text-black">Item 2</p>
                                            {{ __('messages.Description') }}
                                        </td>
                                        <td class="text-center text-13 font-semibold p-10px text-black">1</td>
                                        <td class="text-center text-nowrap text-13 font-semibold p-10px text-black">
                                            {{ getCurrencyAmount(100, true) }}</td>
                                        <td class="text-center text-13 font-semibold p-10px text-black">N/A</td>
                                        <td class="text-end text-nowrap text-13 font-semibold p-10px text-black">
                                            {{ getCurrencyAmount(100, true) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-13 font-semibold p-10px text-black"><span>3</span>
                                        </td>
                                        <td class=" text-13 font-semibold p-10px text-black">
                                            <p class="font-bold mb-0 text-13 font-semibold p-10px text-black">Item 3</p>
                                            {{ __('messages.Description') }}
                                        </td>
                                        <td class="text-center text-13 font-semibold p-10px text-black">1</td>
                                        <td class="text-center text-nowrap text-13 font-semibold p-10px text-black">
                                            {{ getCurrencyAmount(100, true) }}</td>
                                        <td class="text-center text-13 font-semibold p-10px text-black">N/A</td>
                                        <td class="text-end text-nowrap text-13 font-semibold p-10px text-black">
                                            {{ getCurrencyAmount(100, true) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-10" style="margin-top: 30px;">
                            <table class="ms-auto mb-10" style="width:300px;">
                                <tr>
                                    <td>
                                        <table class="w-full total-amount">
                                            <tbody>
                                                <tr>
                                                    <td class="text-nowrap py-1 px-0 text-dark-gray text-13">
                                                        <strong>{{ __('messages.invoice.amount') }}</strong>
                                                    </td>
                                                    <td class="text-end text-gray-600 py-1 px-0 fw-medium text-13">
                                                        {{ getCurrencyAmount(300, true) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-nowrap py-1 px-0 text-dark-gray text-13">
                                                        <strong>{{ __('messages.invoice.discount') }}</strong>
                                                    </td>
                                                    <td class="text-end text-gray-600 py-1 px-0 fw-medium text-13">
                                                        {{ getCurrencyAmount(50, true) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-nowrap pt-1 pb-2 px-0 text-dark-gray text-13">
                                                        <strong>{{ __('messages.invoice.tax') }}</strong>
                                                    </td>
                                                    <td class="text-end text-gray-600 pt-1 pb-2 px-0 fw-medium text-13">
                                                        $0.00
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="total-amount border-t border-gray-300">
                                                <tr>
                                                    <td class="py-2 text-13 text-dark-gray">
                                                        <strong>{{ __('messages.invoice.total') }}</strong>
                                                    </td>
                                                    <td class="text-end text-13 text-dark-gray py-2 fw-medium">
                                                        {{ getCurrencyAmount(250, true) }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <div style="vertical-align:bottom; width:60%;">
                                <img src="{{ asset('images/qrcode.png') }}" height="100" width="100">
                            </div>
                        </div>
                        <div class="mt-20">
                            <div class="mb-5 pt-10">
                                <h6 class="text-gray-900 text-sm mb-5px"><b>{{ __('messages.client.notes') }}:</b></h6>
                                <p class="text-gray-600 text-13 mb-4">Paypal, Stripe & manual payment method accept. Net
                                    10 – Payment due in 10 days from invoice date. Net 30 – Payment due in 30 days from
                                    invoice date.</p>
                            </div>
                            <table class="mb-3">
                                <tr>
                                    <td style="width: 50%;">
                                        <div>
                                            <h6 class="text-gray-900 text-sm mb-5px">
                                                <b>{{ __('messages.invoice.terms') }}:</b></h6>
                                            <p class="text-gray-600 text-13 mb-0">Invoice payment Total; 1% 10 Net 30,
                                                1% discount if payment received within 10 days otherwise payment 30 days
                                                after invoice date.</p>
                                        </div>
                                    </td>
                                    <td class="text-end" style="width:25%;">
                                        <div>
                                            <h5 class="text-indigo mb-5px text-sm">
                                                <b>{{ __('messages.setting.regards') }}:</b></h5>
                                            <p class="text-13 font-normal fontColor"
                                                style="color:{{ $invColor }};">{{ $companyName }}</p>
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
</div>
