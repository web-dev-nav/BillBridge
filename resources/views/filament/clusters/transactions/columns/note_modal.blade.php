<span class="flex items-center space-x-1 pt-2">
    <span class="text-gray-600 font-medium dark:text-gray-200">{{ __('messages.client.notes') }}</span>

    <button
        style="--c-300:var(--primary-300);--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
        class="mx-1 fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-1.5 h-8 w-8 fi-color-custom text-custom-500 hover:text-custom-600 focus-visible:ring-custom-600 dark:text-custom-400 dark:hover:text-custom-300 dark:focus-visible:ring-custom-500 fi-color-primary fi-ac-action fi-ac-icon-btn-action"
        type="button" wire:click="$dispatch('open-modal', { id: 'notes-{{ $record->id }}' })">
        <x-heroicon-s-eye class="w-5 h-5" />
    </button>

    {{-- Modal --}}
    <x-filament::modal id="notes-{{ $record->id }}" width="lg">
        <x-slot name="header">
            {{ __('messages.client.notes') }}
        </x-slot>
        <hr>
        <div class="p-2 whitespace-normal">
            {{ (isset($record->notes) && !empty($record->notes)) ? $record->notes : __('messages.common.n/a') }}
        </div>
    </x-filament::modal>
</span>
