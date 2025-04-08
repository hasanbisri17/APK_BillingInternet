<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Add service notification template
        WhatsAppTemplate::updateOrCreate(
            ['code' => 'service.notification'],
            [
                'name' => 'Service Notification',
                'content' => "Pemberitahuan Layanan Internet\n\nKepada pelanggan yang terhormat,\nKami informasikan bahwa {message} pada {time}.\n\nMohon maaf atas ketidaknyamanannya.\nTerima kasih.",
                'description' => 'Template untuk notifikasi layanan internet (maintenance/gangguan)',
                'variables' => [
                    'message' => 'Pesan notifikasi',
                    'time' => 'Waktu kejadian'
                ],
                'is_active' => true
            ]
        );

        // Add other default templates if needed
        $templates = [
            [
                'code' => 'billing.new',
                'name' => 'New Bill',
                'content' => "Yth. {customer_name}\nTagihan Internet untuk periode {period}:\n\nNo. Invoice: {invoice_number}\nJumlah: Rp {amount}\nJatuh Tempo: {due_date}\n\nTerima kasih.",
                'description' => 'Template untuk tagihan baru',
                'variables' => [
                    'customer_name' => 'Nama pelanggan',
                    'period' => 'Periode tagihan',
                    'invoice_number' => 'Nomor invoice',
                    'amount' => 'Jumlah tagihan',
                    'due_date' => 'Tanggal jatuh tempo'
                ],
                'is_active' => true
            ],
            [
                'code' => 'billing.reminder',
                'name' => 'Bill Reminder',
                'content' => "Yth. {customer_name}\nMohon diingat tagihan Internet Anda untuk periode {period} akan jatuh tempo pada {due_date}.\n\nNo. Invoice: {invoice_number}\nJumlah: Rp {amount}\n\nTerima kasih.",
                'description' => 'Template untuk pengingat tagihan',
                'variables' => [
                    'customer_name' => 'Nama pelanggan',
                    'period' => 'Periode tagihan',
                    'invoice_number' => 'Nomor invoice',
                    'amount' => 'Jumlah tagihan',
                    'due_date' => 'Tanggal jatuh tempo'
                ],
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            WhatsAppTemplate::updateOrCreate(
                ['code' => $template['code']],
                $template
            );
        }
    }
}
