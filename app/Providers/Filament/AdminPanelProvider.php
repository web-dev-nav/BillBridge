<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Widgets;
use Livewire\Livewire;
use App\Models\Setting;
use Filament\PanelProvider;
use App\Livewire\Notification;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\View\PanelsRenderHook;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Client\Pages\CurrencyReport;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Http\Middleware\StoreUserLanguage;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => Auth::user()->full_name)
                    ->icon(fn() => Auth::user()->profile),
            ])
            ->passwordReset(RequestPasswordReset::class)
            ->profile(EditProfile::class, isSimple: false)
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'secondary' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::hex('#6571ff'),
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'white' => Color::hex('#ffffff'),
            ])
            // ->spa()
            ->brandLogo(fn() => view('layout.logo'))
            ->favicon((function () {
                try {
                    DB::connection()->getPdo();
                    return Setting::where('key', 'favicon_icon')->first()?->value ?? asset('assets/images/billbridge.png');
                } catch (\Exception $e) {
                    return asset('assets/images/billbridge.png');
                }
            })())
            ->spaUrlExceptions(fn() => [
                url(route('filament.admin.pages.invoice-templates')),
            ])
            // Render Hooks Start ---
            ->renderHook(PanelsRenderHook::USER_MENU_AFTER, fn() => Blade::render("@livewire('change-password')"))
            ->renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER, fn() => view('layout.change-password-btn'))
            ->renderHook(PanelsRenderHook::SCRIPTS_AFTER, fn() => view('layout.scripts'))
            ->renderHook(PanelsRenderHook::TOPBAR_START, fn() => view('layout.quick-links'))
            ->renderHook(PanelsRenderHook::TOPBAR_START, fn() => view('layout.currency-report'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn() => view('layout.new-invoice'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn() => view('layout.full-screen'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn() => Blade::render("@livewire('notification')"))
            ->renderHook(PanelsRenderHook::FOOTER, fn() => view('layout.footer'))
            // Render Hooks End ---
            ->sidebarCollapsibleOnDesktop()
            ->font('Inter')
            ->breadcrumbs(false)
            ->maxContentWidth(MaxWidth::Full)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                StoreUserLanguage::class,
                \App\Http\Middleware\RedirectIfNotInstalled::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                RoleMiddleware::class . ':admin',
            ]);
    }
}
