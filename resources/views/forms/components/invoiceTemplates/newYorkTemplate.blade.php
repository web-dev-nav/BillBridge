<div id="newYorkTemplate" class="pdf">
    <?php $styleCss = 'style'; ?>
    <div class="container">
        <div class="invoice-header flex justify-between">
            <div class="mb-8 align-top">
                <img src="<?php echo getLogoUrl() ?>" class="img-logo logo-img h-full w-full" alt="logo">
            </div>
            <div class="invoice-header-inner">
                <div class="font-bold d-title mb-5 fontColor" style="color:{{ $invColor }};"><strong>
                        <?php echo __('messages.common.invoice') ?>
                    </strong></div>
                <p class="text-end text-sm text-gray-700 leading-normal mb-4">#9B5QX7</p>
            </div>
        </div>
        <div class="details-section">
            <div class="overflow-auto mb-15">
                <table class="w-full mb-15" style="white-space:nowrap;">
                    <tbody>
                        <tr>
                            <td style="width:33.33%" class="align-top px-5 py-15px border-white-200  border-t border-b">
                                <div class="mb-2">
                                    <strong class="text-sm text-gray-700 leading-18">
                                        <?php echo __('messages.invoice.invoice_date') ?>:
                                    </strong>
                                    <p class="text-sm mb-0 text-gray-700 leading-18">2020.09.25</p>
                                </div>
                                <div>
                                    <strong class="text-sm text-gray-700 leading-18">
                                        <?php echo __('messages.invoice.due_date') ?>:
                                    </strong>
                                    <p class="text-sm mb-0 text-gray-700 leading-18">2020.09.26</p>
                                </div>
                            </td>
                            <td style="width:33.33%"
                                class="align-top px-5 py-15px border-white-200 border-t border-b border-l">
                                <p class="text-gray-700 text-sm mb-2 leading-18"><b>
                                        <?php echo __('messages.common.to') ?>:
                                    </b></p>
                                <p class="text-gray-700 text-sm mb-5px leading-18">&lt
                                    <?php echo __('messages.invoice.client_name') ?>&gt
                                </p>
                                <p class="text-gray-700 text-sm mb-5px leading-18">&lt
                                    <?php echo __('messages.invoice.client_email') ?>&gt
                                </p>
                                <p class="text-gray-700 text-sm mb-5px leading-18">&lt
                                    <?php echo __('messages.client_address') ?>&gt
                                </p>
                                <p class="text-gray-700 text-sm mb-5px leading-18">&lt
                                    <?php echo getVatNoLabel() ?>&gt
                                </p>
                            </td>
                            <td style="width:33.33%"
                                class="align-top px-5 py-15px border-white-200 border-l border-t border-b">
                                <p class="text-sm text-gray-700 leading-18 mb-2"><b>
                                        <?php echo __('messages.common.from') ?>:
                                    </b></p>
                                <p class="text-sm text-gray-700 leading-18 mb-1"><strong>
                                        <?php echo __('messages.common.name') ?>:
                                    </strong> <span>{{ $companyName }}</span></p>
                                <p class="text-sm text-gray-700 leading-18 mb-1"><strong>
                                        <?php echo __('messages.common.address') ?>:
                                    </strong> <span>{{ $companyAddress }}</span></p>
                                <p class="text-sm text-gray-700 leading-18 mb-1"><strong>
                                        <?php echo __('messages.user.phone') ?>:
                                    </strong> <span>{{ $companyPhone }}</span></p>
                                <p class="text-sm text-gray-700 leading-18 mb-1"><strong>
                                        <?php echo getVatNoLabel() ?>:
                                    </strong> <span>{{ $gstNo }}</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="overflow-auto w-full">
                <table class="table w-full border-b  mb-4 borderColor" style="border-color:{{ $invColor }};">
                    <thead class="border-t border-b borderColor" style="border-color:{{ $invColor }};">
                        <tr>
                            <th class="py-1 px-21px text-sm text-gray-100 uppercase bg-white-100" style="width: 5%;">
                                <strong>#</strong>
                            </th>
                            <th class="py-1 px-21px text-start text-sm text-gray-100 uppercase bg-white-100"><strong>
                                    <?php echo __('messages.item') ?>
                                </strong></th>
                            <th class="py-1 px-21px text-center text-sm text-gray-100 uppercase bg-white-100"
                                style="width: 8%;">
                                <strong>
                                    <?php echo __('messages.invoice.qty') ?>
                                </strong>
                            </th>
                            <th class="py-1 px-21px text-center text-sm text-gray-100 uppercase bg-white-100"
                                style="width: 12%;">
                                <strong>
                                    <?php echo __('messages.product.unit_price') ?>
                                </strong>
                            </th>
                            <th class="py-1 px-21px text-center text-sm text-gray-100 uppercase bg-white-100"
                                style="width: 12%;">
                                <strong>
                                    <?php echo __('messages.invoice.tax') . '(in %)' ?>
                                </strong>
                            </th>
                            <th class="py-1 px-21px text-end text-sm text-gray-100 uppercase bg-white-100"
                                style="width: 12%;">
                                <strong>
                                    <?php echo __('messages.invoice.amount') ?>
                                </strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 3; $i++) <tr>
                            <td class="py-2 px-21px text-sm text-gray-100 align-top"><span>{{ $i }}</span></td>
                            <td class="py-2 px-21px text-sm text-gray-100 align-top">
                                <p class="mb-0 text-sm text-gray-100 align-top leading-normal">
                                    <?php echo __('messages.item') ?> {{ $i }}
                                </p>
                                <?php echo __('messages.Description') ?>
                            </td>
                            <td class="text-center py-2 px-21px text-sm text-gray-100 align-top">1</td>
                            <td class="text-end text-nowrap py-2 px-21px text-sm text-gray-100 align-top">
                                <?php echo getCurrencyAmount(100, true) ?>
                            </td>
                            <td class="text-center py-2 px-21px text-sm text-gray-100 align-top">N/A</td>
                            <td class="text-end text-nowrap py-2 px-21px text-sm text-gray-100 align-top">
                                <?php echo getCurrencyAmount(100, true) ?>
                            </td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>
            <table class="w-full">
                <tr>
                    <td class="align-bottom" style="width: 65%;">
                        <div>
                            <small class="font-bold text-sm text-gray-700">
                                <?php echo __('messages.payment_qr_codes.payment_qr_code') ?>
                            </small><br>
                            <div class="h-full w-full qr-img ml-2">
                                <img src="../assets/images/qrcode.png" alt="qr-code" class="h-full w-full object-cover">
                            </div>
                        </div>
                    </td>
                    <td class="text-end" style="width: 45%;">
                        <table class="total-table w-full">
                            <tbody>
                                <tr class="border-b borderColor" style="border-color:{{ $invColor }};">
                                    <td class="text-sm text-gray-100 py-2 px-21px"><strong>
                                            <?php echo __('messages.invoice.amount') ?>:
                                        </strong></td>
                                    <td class="text-nowrap text-sm text-gray-100 py-2 px-21px">
                                        <?php echo getCurrencyAmount(300, true) ?>
                                    </td>
                                </tr>
                                <tr class="border-b" style="border-color:{{ $invColor }};">
                                    <td class="text-sm text-gray-100 py-2 px-21px"><strong>
                                            <?php echo __('messages.invoice.discount') ?>:
                                        </strong></td>
                                    <td class="text-nowrap text-sm text-gray-100 py-2 px-21px">
                                        <?php echo getCurrencyAmount(50, true) ?>
                                    </td>
                                </tr>
                                <tr class="border-b borderColor" style="border-color:{{ $invColor }};">
                                    <td class="text-sm text-gray-100 py-2 px-21px"><strong>
                                            <?php echo __('messages.invoice.tax') ?>:
                                        </strong></td>
                                    <td class="text-sm text-gray-100 py-2 px-21px">N/A</td>
                                </tr>
                                <tr class="border-b borderColor" style="border-color:{{ $invColor }};">
                                    <td class="text-sm text-gray-100 py-2 px-21px"><strong>
                                            <?php echo __('messages.invoice.total') ?>:
                                        </strong></td>
                                    <td class="text-nowrap text-sm text-gray-100 py-2 px-21px">
                                        <?php echo getCurrencyAmount(250, true) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="mt-10">
                <p class="text-gray-700 text-sm mb-4"><b>
                        <?php echo __('messages.client.notes') ?>:
                    </b></p>
                <p class="text-gray-300 text-sm mb-4">
                    Paypal, Stripe & manual payment methods accepted. Net 10 – Payment due in 10 days from invoice date.
                    Net 30 – Payment due in 30 days from invoice date.
                </p>
            </div>
            <div>
                <p class="mb-4 text-sm text-gray-700"><b>
                        <?php echo __('messages.invoice.terms') ?>:
                    </b></p>
                <p class="text-gray-300 text-sm mb-4">Invoice payment
                    <?php echo __('messages.invoice.total') ?>; 1% 10 Net 30, 1% discount if payment received within 10
                    days otherwise payment 30 days after invoice date.
                </p>
            </div>
            <div class="regards">
                <p class="text-sm text-gray-700 mb-4"><b>
                        <?php echo __('messages.setting.regards') ?>:
                    </b><br>
                    <b class="text-sm borderColor" style="border-color:{{ $invColor }};">{{ $companyName }}</b>
                </p>
            </div>
        </div>
    </div>
</div>