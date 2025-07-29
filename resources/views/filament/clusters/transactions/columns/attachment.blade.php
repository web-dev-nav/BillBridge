{{-- @dump($getRecord()->payment_attachment) --}}

@if ($getRecord()->payment_attachment)
    <span class="text-sm leading-6 text-gray-950 dark:text-white  ">
        <a href="{{ route('transaction.attachment', $getRecord()->id) }}" style="color: #6571ff" class="hoverLink"
            target="_blank" download=""> {{ __('messages.invoice.download') }}</a>
    </span>
@else
    <div class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white d-flex align-items-center">
        {{ __('messages.common.n/a') }}
    </div>
@endif
