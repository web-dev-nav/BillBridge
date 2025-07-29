<?php

/**
 * Apply Canadian Settings to BillBridge
 * This script updates the database directly with Canadian regional settings
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Models\User;

echo "🇨🇦 Applying Canadian settings to BillBridge...\n\n";

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
    echo "✅ {$key}: {$value}\n";
}

// Update admin user
$adminUser = User::where('email', 'admin@infy-invoices.com')->first();
if ($adminUser) {
    $adminUser->update(['email' => 'admin@billbridge.com']);
    echo "✅ Admin email updated to: admin@billbridge.com\n";
}

echo "\n🎉 All Canadian settings applied successfully!\n";
echo "\n🔑 Login: admin@billbridge.com / 123456\n";
echo "🌐 Admin: http://127.0.0.1:8000/admin\n\n";