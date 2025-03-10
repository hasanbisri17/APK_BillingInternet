<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = WhatsAppTemplate::getDefaultTemplates();

        foreach ($templates as $template) {
            if (!WhatsAppTemplate::where('code', $template['code'])->exists()) {
                WhatsAppTemplate::create($template);
            }
        }
    }
}
