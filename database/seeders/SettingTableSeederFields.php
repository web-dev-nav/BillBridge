<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeederFields extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'company_address',
            'value' => '123 Main Street, Suite 100, Toronto, ON M5V 3A8, Canada',
        ]);
        Setting::create(['key' => 'company_phone', 'value' => '+1 (416) 555-0123']);
    }
}
