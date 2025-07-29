@php
if(auth()->user()->hasRole('admin'))
{
$url = url()->current() == route('filament.admin.pages.dashboard') || url()->current() ==
route('filament.admin.pages.currency-report') ? $classUrl = route('filament.admin.pages.currency-report') : '';
}
elseif(auth()->user()->hasRole('client'))
{
$url = url()->current() == route('filament.client.pages.dashboard') || url()->current() ==
route('filament.client.pages.currency-report') ? $classUrl = route('filament.client.pages.currency-report') : '';
}
@endphp
@if($url)
<a href="{{ $url }}" color="secondary" wire:navigate
    class="fi-tabs-item group flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5"
    aria-selected="aria-selected" role="tab">
    <span
        class="fi-tabs-item-label transition duration-75 {{ url()->current() == $classUrl ? 'text-primary-600 dark:text-primary-400' : '' }}">
        {{ __('messages.currency_reports') }}
    </span>
</a>
@endif
