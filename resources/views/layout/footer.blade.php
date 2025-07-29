<div>
    <footer class="w-full pt-4 px-6 mt-7 sticky bottom-0">
        <div class="flex text-gray-400 justify-between mb-4 text-sm">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                {{ __('messages.common.all_rights_reserved') }} © <strong>{{ \Carbon\Carbon::now()->year }}</strong>
                <span class="fi-ta-text-item-label text-sm leading-6 text-custom-600 dark:text-custom-400 font-bold"
                    style="--c-400:var(--primary-400);--c-600:var(--primary-600);"><a
                        class="underline text-sm leading-6 text-custom-600 dark:text-custom-400 font-bold"
                        href="{{ url('/') }}" class="hover:underline ">{{
                        getAppName() }}</a></span>
            </span>
            @if (auth()->check())
            <div class="flex items-center">
                <span class="ml-2">v{{ getCurrentVersion() }}</span>
            </div>
            @endif
        </div>
    </footer>
</div>
<style>
    .fi-main-ctn {
        min-height: 100vh;

        .fi-main {
            flex-grow: 1;
        }
    }
</style>
