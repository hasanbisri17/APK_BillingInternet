<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Tunai',
                'code' => 'CASH',
                'type' => 'cash',
                'instructions' => 'Pembayaran langsung di kantor',
                'is_active' => true,
            ],
            [
                'name' => 'Transfer Bank BCA',
                'code' => 'BCA',
                'type' => 'bank_transfer',
                'provider' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT Internet Provider',
                'instructions' => 'Transfer ke rekening BCA kami',
                'is_active' => true,
            ],
            [
                'name' => 'DANA',
                'code' => 'DANA',
                'type' => 'e_wallet',
                'provider' => 'DANA',
                'account_number' => '081234567890',
                'account_name' => 'PT Internet Provider',
                'instructions' => 'Pembayaran melalui DANA',
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
