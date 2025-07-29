<x-filament::button tag="a" :href="route('filament.admin.resources.invoices.create')"
    tooltip="{{ __('messages.invoice.new_invoice') }}" class="bg-primary-600 dark:bg-primary-500 hidden lg:block">
    {{ __('messages.invoice.new_invoice') }}
</x-filament::button>


{{-- * for Mobile View Button --}}
<x-filament::icon-button tag="a" :href="route('filament.admin.resources.invoices.create')"
    tooltip="{{ __('messages.invoice.new_invoice') }}" icon="heroicon-o-document-text" color="white"
    class="bg-primary-600 dark:bg-primary-500 lg:hidden">
    {{ __('messages.invoice.new_invoice') }}
</x-filament::icon-button>
