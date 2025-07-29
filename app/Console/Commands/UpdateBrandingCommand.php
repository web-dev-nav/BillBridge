<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateBrandingCommand extends Command
{
    protected $signature = 'branding:update {--force : Force update without confirmation}';
    protected $description = 'Update BillBridge branding in admin and client panels';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will update all branding settings to BillBridge. Continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Updating BillBridge branding...');

        // Update main settings
        $settings = [
            // Regional Settings
            'time_zone' => 'America/Toronto',
            'current_currency' => '7', // Canadian Dollar (CAD)
            'date_format' => 'Y-m-d',
            'country_code' => '+1',
            
            // Address Components
            'country' => 'Canada',
            'state' => 'Ontario', 
            'city' => 'Toronto',
            'zipcode' => 'M5V 3A8',
            'fax_no' => '+1 (416) 555-0124',
            
            // Company Information
            'company_address' => '123 Main Street, Suite 100, Toronto, ON M5V 3A8, Canada',
            'company_phone' => '+1 (416) 555-0123',
            'company_email' => 'contact@billbridge.com',
            
            // Branding
            'app_name' => 'BillBridge',
            'company_name' => 'BillBridge',
            'app_logo' => 'assets/images/billbridge.png',
            'company_logo' => 'assets/images/billbridge.png',
            'favicon_icon' => 'assets/images/billbridge.png',
            
            // Number Formats
            'decimal_separator' => '.',
            'thousand_separator' => ',',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            $this->line("✓ Updated {$key}: {$value}");
        }

        // Update admin user email
        $oldEmail = 'admin@infy-invoices.com';
        $newEmail = 'admin@billbridge.com';
        
        $adminUser = User::where('email', $oldEmail)->first();
        if ($adminUser) {
            $adminUser->update(['email' => $newEmail]);
            $this->line("✓ Updated admin email: {$oldEmail} → {$newEmail}");
        }

        $this->info('✅ Branding update completed!');
        $this->info('Please run the following commands to apply changes:');
        $this->line('php artisan cache:clear');
        $this->line('php artisan config:clear');
        $this->line('php artisan view:clear');
    }
}