<div class="flex">
    <a href="{{ route('filament.admin.resources.clients.view', $record->invoice->client_id) }}">
        <span class="p-1">{{ $record->invoice->client->user->full_name }}</span>
    </a>
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('filament.admin.resources.invoices.view', $record->invoice_id) }}">
        <x-filament::badge :color="'info'">
            {{ $record->invoice->invoice_id }}
        </x-filament::badge>
    </a>
    @endif
</div>
