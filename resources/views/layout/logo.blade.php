<div class="flex items-center gap-4">
    <a href="{{ url('/') }}" class="flex items-center gap-4">
        <img src="{{ asset(\App\Models\Setting::where('key', 'app_logo')->first()->value ?? asset('assets/images/infyom.png')) }}"
            alt="{{ getAppName() }}" width="50" height="50">
        <span class="font-bold"
            x-tooltip="{
            content: '{{ getAppName() }}',
            theme: $store.theme,
            placement: 'bottom'
        }">{{ str()->limit(getAppName(), 15) }}</span>
    </a>
</div>
