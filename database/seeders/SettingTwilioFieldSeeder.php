<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingTwilioFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create(['key' => 'send_whatsapp_invoice', 'value' => 0]);
        Setting::create(['key' => 'twilio_sid', 'value' => '']);
        Setting::create(['key' => 'twilio_token', 'value' => '']);
        Setting::create(['key' => 'twilio_from_number', 'value' => '']);
    }
}
