<span class="flex">
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('filament.admin.resources.clients.view', $record->client_id) }}">
        <span class="p-1">{{ $record->client->user->full_name }}</span>
    </a>
    @endif
    @if (auth()->user()->hasRole('admin'))
    <a href="{{ route('filament.admin.resources.invoices.view', $record->id) }}">
        <x-filament::badge color="info">
            {{ $record->invoice_id }}
        </x-filament::badge>
    </a>
    @else
        <a href="{{ route('filament.client.resources.invoices.view', $record->id) }}">
        <x-filament::badge color="info">
            {{ $record->invoice_id }}
        </x-filament::badge>
    </a>
    @endif
</span>
