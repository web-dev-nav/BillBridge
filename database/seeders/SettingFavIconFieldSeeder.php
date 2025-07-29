<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingFavIconFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Setting $companyLogo */
        $companyLogo = Setting::where('key', 'company_logo')->firstOrFail();
        $companyLogo->delete();

        $imageUrl = 'assets/images/billbridge.png';
        Setting::create([
            'key' => 'favicon_icon',
            'value' => $imageUrl,
        ]);
    }
}
