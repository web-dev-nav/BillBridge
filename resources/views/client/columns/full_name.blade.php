<div class="flex">
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('filament.admin.resources.clients.view', $record->client_id) }}">
        <span class="p-1">{{ $record->client->user->full_name }}</span>
    </a>
    @endif
    @if (auth()->user()->hasRole('admin'))
    <a href="{{ route('filament.admin.resources.quotes.view', $record->id) }}">
        <x-filament::badge :color="'info'">
            {{ $record->quote_id }}
        </x-filament::badge>
    </a>
    @else
    <a href="{{ route('filament.client.resources.quotes.view', $record->id) }}">
        <x-filament::badge :color="'info'">
            {{ $record->quote_id }}
        </x-filament::badge>
    </a>
    @endif
</div>
