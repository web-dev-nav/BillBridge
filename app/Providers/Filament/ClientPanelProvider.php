<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Setting;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\View\PanelsRenderHook;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Http\Middleware\CheckPanel;
use App\Http\Middleware\StoreUserLanguage;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class ClientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('client')
            ->path('client')
            ->profile(EditProfile::class, isSimple: false)
            ->passwordReset(RequestPasswordReset::class)
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Gray,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => Auth::user()->full_name)
                    ->icon(fn() => Auth::user()->profile),
            ])
            ->font('Inter')
            ->breadcrumbs(false)
            ->maxContentWidth(MaxWidth::Full)
            ->brandLogo(fn() => view('layout.logo'))
            ->favicon((function () {
                try {
                    DB::connection()->getPdo();
                    return Setting::where('key', 'favicon_icon')->first()?->value ?? asset('assets/images/billbridge.png');
                } catch (\Exception $e) {
                    return asset('assets/images/billbridge.png');
                }
            })())
            //Render Hooks Start ---
            ->renderHook(PanelsRenderHook::USER_MENU_AFTER, fn() => Blade::render("@livewire('change-password')"))
            ->renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER, fn() => view('layout.change-password-btn'))
            ->renderHook(PanelsRenderHook::TOPBAR_START, fn() => view('layout.currency-report'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn() => view('layout.full-screen'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn() => Blade::render("@livewire('notification')"))
            ->renderHook(PanelsRenderHook::SCRIPTS_AFTER, fn() => view('layout.scripts'))
            //Render Hooks End ---
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Client/Widgets'), for: 'App\\Filament\\Client\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // StoreUserLanguage::class,
                CheckPanel::class,
                \App\Http\Middleware\RedirectIfNotInstalled::class,
            ])
            ->authMiddleware([
                Authenticate::class . ':client',
            ]);
    }
}
