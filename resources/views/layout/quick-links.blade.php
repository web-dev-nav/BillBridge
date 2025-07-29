<x-filament::dropdown placement="bottom-end">
    <x-slot name="trigger">
        <x-filament::icon-button tooltip="Quick Links" icon="heroicon-o-plus" color="white"
            class="bg-primary-600 dark:bg-primary-500" />
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-document-text" icon-color="primary"
            href="{{ route('filament.admin.resources.invoices.index') }}">
            {{ __('messages.invoices') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-paper-clip" icon-color="primary"
            href="{{ route('filament.admin.resources.quotes.index') }}">
            {{ __('messages.quotes') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-cube" icon-color="primary"
            href="{{ route('filament.admin.resources.products.index') }}">
            {{ __('messages.products') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-percent-badge" icon-color="primary"
            href="{{ route('filament.admin.resources.taxes.index') }}">
            {{ __('messages.taxes') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-qr-code" icon-color="primary"
            href="{{ route('filament.admin.resources.payment-qr-codes.index') }}">
            {{ __('messages.payment_qr_codes.payment_qr_codes') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-user-group" icon-color="primary"
            href="{{ route('filament.admin.resources.clients.index') }}">
            {{ __('messages.clients') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-numbered-list" icon-color="primary"
            href="{{ route('filament.admin.resources.transactions.index') }}">
            {{ __('messages.transactions') }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item tag="a" icon="heroicon-o-credit-card" icon-color="primary"
            href="{{ route('filament.admin.resources.payments.index') }}">
            {{ __('messages.payments') }}
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
