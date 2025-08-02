<?php

namespace App\Installer;

use Filament\Facades\Filament;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Shipu\WebInstaller\Concerns\InstallationContract;

class CustomInstallationManager implements InstallationContract
{
    public function run($data): bool
    {
        try {
            Log::info('Starting installation process...');
            
            // Run fresh migrations
            Log::info('Running migrate:fresh...');
            Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);
            Log::info('Migrations completed successfully');

            // Run seeders first to create the default admin
            Log::info('Running database seeders...');
            Artisan::call('db:seed', [
                '--force' => true,
            ]);
            Log::info('Database seeders completed successfully');

            // Update the seeded admin user with installer data
            Log::info('Updating super admin user with installer data...');
            $user = config('installer.user_model');

            $adminName = array_get($data, 'applications.admin.name');
            $nameParts = explode(' ', $adminName, 2);
            
            $adminUser = $user::where('is_default_admin', 1)->first();
            if ($adminUser) {
                $adminUser->update([
                    'first_name' => $nameParts[0] ?? $adminUser->first_name,
                    'last_name'  => $nameParts[1] ?? $adminUser->last_name,
                    'email'      => array_get($data, 'applications.admin.email'),
                    'password'   => array_get($data, 'applications.admin.password'),
                ]);
                Log::info('Super admin user updated successfully: ' . $adminUser->email);
            } else {
                Log::error('Default admin user not found after seeding');
            }

            // Mark as installed
            Log::info('Creating installation marker...');
            file_put_contents(storage_path('installed'), 'installed');
            Log::info('Installation completed successfully');

            return true;
        } catch (\Exception $exception) {
            Log::error('Installation failed: ' . $exception->getMessage());
            Log::error('Stack trace: ' . $exception->getTraceAsString());
            return false;
        }
    }

    public function redirect(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            if (class_exists(Filament::class)) {
                return redirect()->intended(Filament::getUrl());
            }

            return redirect(config('installer.redirect_route'));
        } catch (\Exception $exception) {
            Log::info("route not found...");
            Log::info($exception->getMessage());
            return redirect()->route('installer.success');
        }
    }

    public function dehydrate(): void
    {
        Log::info("installation dehydrate...");
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }
}