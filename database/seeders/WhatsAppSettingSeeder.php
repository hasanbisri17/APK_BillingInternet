<?php

namespace Database\Seeders;

use App\Models\WhatsAppSetting;
use Illuminate\Database\Seeder;

class WhatsAppSettingSeeder extends Seeder
{
    public function run(): void
    {
        WhatsAppSetting::create([
            'api_token' => '26mGcrLMtKX1!P9WPrXW',
            'api_url' => 'https://api.fonnte.com',
            'default_country_code' => '62',
            'is_active' => true,
        ]);
    }
}
