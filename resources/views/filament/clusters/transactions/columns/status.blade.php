<div>
    @if($getRecord()->is_approved == \App\Models\Payment::APPROVED && $getRecord()->payment_mode == 1)
        <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);"
                class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                {{\App\Models\Payment::PAID}}
        </span>
    @elseif($getRecord()->is_approved == \App\Models\Payment::PENDING && $getRecord()->payment_mode == 1)
        <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
                class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                {{\App\Models\Payment::PROCESSING}}
        </span>
    @elseif($getRecord()->is_approved == \App\Models\Payment::REJECTED && $getRecord()->payment_mode == 1)
        <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
                class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                {{\App\Models\Payment::DENIED}}
        </span>
    @else
        <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);"
                class="flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                {{\App\Models\Payment::PAID}}
        </span>
    @endif
</div>




