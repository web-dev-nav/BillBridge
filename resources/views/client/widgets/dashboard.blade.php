<x-filament-widgets::widget>
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('filament.client.pages.currency-report') }}">
                <div
                    class="fi-wi-stats-overview-stat relative items-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex justify-between">
                    <div
                        class="flex items-center justify-center w-11 h-11 rounded-lg p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                            class="h-7 w-10 dark:text-gray-200">
                            <path fill="currentColor"
                                d="M64 64C28.7 64 0 92.7 0 128L0 384c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 64zm64 320l-64 0 0-64c35.3 0 64 28.7 64 64zM64 192l0-64 64 0c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64l0 64-64 0zm64-192c-35.3 0-64-28.7-64-64l64 0 0 64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                        </svg>
                    </div>
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-md font-bold text-gray-500 dark:text-gray-400">
                                {{ __('messages.admin_dashboard.total_amount') }}
                            </span>
                        </div>
                        <div class="text-right text-md font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ __('messages.common.click_here') }}
                        </div>
                    </div>
                </div>
            </a>


            <a href="{{ route('filament.client.pages.currency-report') }}">
                <div
                    class="fi-wi-stats-overview-stat relative items-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex justify-between">
                    <div
                        class="flex items-center justify-center w-11 h-11 rounded-lg p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M9 1.5H5.625c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5Zm6.61 10.936a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 14.47a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                          </svg>
                          
                    </div>
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-md font-bold text-gray-500 dark:text-gray-400">
                                {{ __('messages.admin_dashboard.total_paid') }}
                            </span>
                        </div>
                        <div class="text-right text-md font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ __('messages.common.click_here') }}
                        </div>
                    </div>
                </div>
            </a>



            <a href="{{ route('filament.client.pages.currency-report') }}">
                <div
                    class="fi-wi-stats-overview-stat relative items-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex justify-between">
                    <div
                        class="flex items-center justify-center w-11 h-11 rounded-lg p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                          </svg>
                          
                    </div>
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-md font-bold text-gray-500 dark:text-gray-400">
                                {{ __('messages.admin_dashboard.total_due') }}
                            </span>
                        </div>
                        <div class="text-right text-md font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ __('messages.common.click_here') }}
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{route('filament.client.resources.invoices.index')}}">
                <div
                    class="fi-wi-stats-overview-stat relative items-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex justify-between">
                    <div
                        class="flex items-center justify-center w-11 h-11 rounded-lg p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                          </svg>
                          
                    </div>
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-md font-bold text-gray-500 dark:text-gray-400">
                                {{ __('messages.admin_dashboard.total_invoices') }}
                            </span>
                        </div>
                        <div class="text-right text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $totalInvoices }}
                        </div>
                    </div>
                </div>
            </a>



            <a href="{{route('filament.client.resources.invoices.index')}}">
                <div
                    class="fi-wi-stats-overview-stat relative items-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex justify-between">
                    <div
                        class="flex items-center justify-center w-11 h-11 rounded-lg p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375Zm9.586 4.594a.75.75 0 0 0-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 0 0-1.06 1.06l1.5 1.5a.75.75 0 0 0 1.116-.062l3-3.75Z" clip-rule="evenodd" />
                          </svg>
                          
                    </div>
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-md font-bold text-gray-500 dark:text-gray-400">
                                {{ __('messages.admin_dashboard.total_paid_invoices') }}
                            </span>
                        </div>
                        <div class="text-right text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $paidInvoices }}
                        </div>
                    </div>
                </div>
            </a>



            <a href="{{route('filament.client.resources.invoices.index')}}">
                <div
                    class="fi-wi-stats-overview-stat relative items-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex justify-between">
                    <div
                        class="flex items-center justify-center w-11 h-11 rounded-lg p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                          </svg>                              
                    </div>
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-md font-bold text-gray-500 dark:text-gray-400">
                                {{ __('messages.admin_dashboard.total_unpaid_invoices') }}
                            </span>
                        </div>
                        <div class="text-right text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $unpaidInvoices }}
                        </div>
                    </div>
                </div>
            </a>


    </div>
</x-filament-widgets::widget>
