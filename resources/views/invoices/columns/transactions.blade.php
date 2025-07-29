@php
    $dueAmount = 0;
    $paid = 0;
    foreach ($record->payments as $payment) {
        if ($payment->payment_mode == \App\Models\Payment::MANUAL && $payment->is_approved !== \App\Models\Payment::APPROVED) {
            continue;
        }
        $paid += $payment->amount;
    }
    $dueAmount = $record->final_amount - $paid;
@endphp

@if ($record->status_label == 'Draft')
    <span class="text-center">{{ __('messages.common.n/a') }}</span>
@else
    @if ($record->final_amount == $paid)
        <x-filament::badge color="success" class="fs-7">
            {{ __('Paid:') }} {{ getInvoiceCurrencyAmount($paid, $record->currency_id, true) }}
        </x-filament::badge>
        <br>
    @elseif($record->status == 3)
        <x-filament::badge color="success" class="fs-7">
            {{ __('Paid:') }} {{ getInvoiceCurrencyAmount($paid, $record->currency_id, true) }}
        </x-filament::badge>
        <br>
        <x-filament::badge color="danger" class="fs-7 mt-1">
            {{ __('Due:') }} {{ getInvoiceCurrencyAmount($dueAmount, $record->currency_id, true) }}
        </x-filament::badge>
    @else
        <x-filament::badge color="danger" class="fs-7">
            {{ __('Due:') }} {{ getInvoiceCurrencyAmount($dueAmount, $record->currency_id, true) }}
        </x-filament::badge>
    @endif
@endif
