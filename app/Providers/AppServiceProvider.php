<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('helpers', function ($app) {
            return require app_path('helpers.php');
        });

        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Add middleware to protect Filament panels until installation is complete
        Filament::serving(function () {
            if (!file_exists(storage_path('installed'))) {
                redirect()->route('installer')->send();
            }
        });
        
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->userPreferredLocale(function () {
                    return auth()->user()->language ?? 'en';
                })
                ->locales(['ar', 'en', 'fr', 'de', 'es', 'pt', 'ru', 'tr', 'zh'])
                ->flags([
                    'ar' => asset('images/flags/jordan.png'),
                    'en' => asset('images/flags/united-states.png'),
                    'fr' => asset('images/flags/france.png'),
                    'de' => asset('images/flags/germany.png'),
                    'es' => asset('images/flags/spain.png'),
                    'pt' => asset('images/flags/portugal.png'),
                    'it' => asset('images/flags/italy.png'),
                    'ru' => asset('images/flags/russia.png'),
                    'tr' => asset('images/flags/turkey.png'),
                    'zh' => asset('images/flags/china.png'),
                ])
                ->visible(outsidePanels: true)
                ->outsidePanelRoutes([
                    'auth.login',
                    'auth.register',
                    'auth.password-reset',
                ]);
        });
    }
}
