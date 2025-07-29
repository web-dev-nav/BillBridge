<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            ['key' => 'city', 'value' => 'Toronto'],
            ['key' => 'state', 'value' => 'Ontario'],
            ['key' => 'country', 'value' => 'Canada'],
            ['key' => 'zipcode', 'value' => 'M5V 3A8'],
            ['key' => 'fax_no', 'value' => '+1 (416) 555-0124'],
            ['key' => 'show_additional_address_in_invoice', 'value' => 0],
        ];
        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
