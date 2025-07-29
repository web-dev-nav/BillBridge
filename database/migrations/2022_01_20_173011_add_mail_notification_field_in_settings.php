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
        Setting::create(['key' => 'mail_notification', 'value' => '1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
