<x-filament-panels::page>
            {{-- <div class="bg-gray-900 p-5 rounded-lg">
                <div class="flex space-x-6">
                    <!-- Total Amount -->
                    <div class="flex-1">
                        <h2 class="text-dark text-center text-xl font-semibold">{{ __('messages.admin_dashboard.total_amount') }}</h2>
                        <div class="mt-3 flex flex-col space-y-4">
                            @if (count($totalInvoices) > 0)
                                @foreach ($totalInvoices as $currencyId => $amount)
                                    <div style="margin:0 80px 20px 80px;background-color:#0ac074;" class="fi-wi-stats-overview-stat text-center text-white text-xl font-bold rounded-xl p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 ">
                                        {{ getInvoiceCurrencyAmount($amount, $currencyId) }}
                                    </div>
                                @endforeach
                            @else
                                <p class="fs-3 mt-9">{{ __('messages.invoice.nothing_amount_yet') }}</p>
                            @endif
                        </div>
                    </div>
    
                    <!-- Total Paid -->
                    <div class="flex-1">
                        <h2 class="text-dark text-center text-xl font-semibold">{{ __('messages.admin_dashboard.total_paid') }}</h2>
                        <div class="mt-3 flex flex-col space-y-4">
                            @if (count($paidInvoices) > 0)
                                @foreach ($paidInvoices as $currencyId => $amount)
                                    <div style="margin:0 80px 20px 80px;background-color:#0099fb;" class="fi-wi-stats-overview-stat text-center text-white text-xl font-bold rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                        {{ getInvoiceCurrencyAmount($amount, $currencyId) }}
                                    </div>
                                @endforeach
                            @else
                                <p class="fs-3 mt-9">{{ __('messages.invoice.nothing_paid_yet') }}</p>
                            @endif
                        </div>
                    </div>
    
                    <!-- Total Due -->
                    <div class="flex-1">
                        <h2 class="text-dark text-center text-xl font-semibold">{{ __('messages.admin_dashboard.total_due') }}</h2>
                        <div class="mt-3 flex flex-col space-y-4">
                            @if (count($dueInvoices) > 0)
                                @foreach ($dueInvoices as $currencyId => $dueInvoiceAmount)
                                    <div style="margin:0 80px 20px 80px;background-color:#ffb821;" class="fi-wi-stats-overview-stat text-center text-white text-xl font-bold rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                        {{ getInvoiceCurrencyAmount($dueInvoiceAmount, $currencyId) }}
                                    </div>
                                @endforeach
                            @else
                                <p class="fs-3 mt-9">{{ __('messages.invoice.nothing_due_yet') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}
            
            <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-center">Currency</th>
                            <th class="py-3 px-6 text-center">Total Amount</th>
                            <th class="py-3 px-6 text-center">Total Paid</th>
                            <th class="py-3 px-6 text-center">Total Due</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300 text-sm font-light">
                        @if (count($currencyDetails) > 0)
                            @foreach ($currencyDetails as $currencyId => $value)
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="flex w-full disabled:pointer-events-none justify-center text-center">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex gap-1.5 flex-wrap text-center justify-center">
                                                <div class="flex w-max">
                                                    <span style="--c-50:var(--info-50);--c-400:var(--info-400);--c-600:var(--info-600);"
                                                        class="text-center px-3 gap-x-1 rounded-md text-md font-medium ring-1 ring-inset min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success">
                                                        <span class="">{{ getInvoiceCurrencyIcon($currencyId) }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex gap-1.5 flex-wrap text-center justify-center">
                                                <div class="flex w-max">
                                                    <span style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                                                        class="text-center px-3 gap-x-1 rounded-md text-md font-medium ring-1 ring-inset min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success">
                                                        {{ formatTotalAmount($value['total']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex gap-1.5 flex-wrap text-center justify-center">
                                                <div class="flex w-max">
                                                    <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);"
                                                        class="flex items-center px-3 justify-center gap-x-1 rounded-md text-md font-medium ring-1 ring-inset min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success">
                                                        {{ formatTotalAmount($value['paid']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex gap-1.5 flex-wrap text-center justify-center">
                                                <div class="flex w-max">
                                                    <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
                                                        class="flex items-center px-3 justify-center gap-x-1 rounded-md text-md font-medium ring-1 ring-inset min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success">
                                                        {{ formatTotalAmount($value['due']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="py-3 px-6 text-center text-gray-500">
                                    {{ __('messages.invoice.nothing_amount_yet') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            
</x-filament-panels::page>
