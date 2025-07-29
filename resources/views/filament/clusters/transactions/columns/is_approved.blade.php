<div>
        @if ($getRecord()->is_approved == \App\Models\Payment::PENDING && $getRecord()->is_approved == \App\Models\Payment::MANUAL)
            <div
                class="flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500">
                <div class="min-w-0 flex-1">
                    <select wire:change="changeStatus($event.target.value, {{ $getRecord()->id }})"
                        class="block w-full border-none bg-transparent py-1.5 pe-8 text-base text-gray-950 transition duration-75 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 [&amp;_optgroup]:bg-white [&amp;_optgroup]:dark:bg-gray-900 [&amp;_option]:bg-white [&amp;_option]:dark:bg-gray-900 ps-3">
                        <option value="{{\App\Models\Payment::APPROVED}}">{{__('messages.setting.approved')}}</option>
                        <option value="{{\App\Models\Payment::REJECTED}}">{{__('messages.setting.denied')}}</option>
                    </select>
                </div>
            </div>
        @elseif ($getRecord()->is_approved == \App\Models\Payment::APPROVED)
            <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);"
                class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success">
                {{__('messages.setting.approved')}}
            </span>
        @elseif ($getRecord()->is_approved == \App\Models\Payment::REJECTED)
            <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
                class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                {{__('messages.setting.denied')}}
            </span>
        @else
            <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
            class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color"> N/A</span>
        @endif
</div>
