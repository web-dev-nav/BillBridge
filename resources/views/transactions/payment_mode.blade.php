@if ($record->payments_mode === 'Manual')
    <x-filament::badge color="warning">{{ $record->payments_mode }}</x-filament::badge>
@elseif($record->payments_mode === 'Stripe')
    <x-filament::badge color="success">{{ $record->payments_mode }}</x-filament::badge>
@elseif($record->payments_mode === 'Paypal')
    <x-filament::badge color="primary">{{ $record->payments_mode }}</x-filament::badge>
@elseif($record->payments_mode === 'Cash')
    <x-filament::badge color="info">{{ $record->payments_mode }}</x-filament::badge>
@elseif($record->payments_mode === 'Razorpay')
    <x-filament::badge color="danger">{{ $record->payments_mode }}</x-filament::badge>
@elseif($record->payments_mode === 'Paystack')
    <x-filament::badge color="danger">{{ $record->payments_mode }}</x-filament::badge>
@elseif($record->payments_mode === 'Mercadopago')
    <x-filament::badge color="warning">{{ $record->payments_mode }}</x-filament::badge>
@endif
<div class="inline-flex gap-1">
    <span class="text-sm">
        {{ __('messages.client.notes') }}
    </span>
    <x-filament::modal width="lg">
        <x-slot name="heading">
            {{ __('messages.payment.transaction_notes') }}
        </x-slot>
        <x-slot name="trigger">
            <x-filament::icon-button icon="heroicon-s-eye" color="primary" />
        </x-slot>
        <span
            class="border-t pt-4 whitespace-normal">{{ isset($record->notes) && !empty($record->notes) ? $record->notes : __('messages.common.n/a') }}</span>
    </x-filament::modal>
</div>
