<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Income Categories
        $incomeCategories = [
            ['name' => 'Pembayaran Internet', 'type' => 'income', 'description' => 'Pendapatan dari layanan internet'],
            ['name' => 'Pemasangan Baru', 'type' => 'income', 'description' => 'Pendapatan dari pemasangan internet baru'],
            ['name' => 'Lain-lain', 'type' => 'income', 'description' => 'Pendapatan lainnya'],
        ];

        foreach ($incomeCategories as $category) {
            TransactionCategory::create($category);
        }

        // Expense Categories
        $expenseCategories = [
            ['name' => 'Gaji Karyawan', 'type' => 'expense', 'description' => 'Pengeluaran untuk gaji karyawan'],
            ['name' => 'Peralatan', 'type' => 'expense', 'description' => 'Pembelian peralatan dan perlengkapan'],
            ['name' => 'Operasional', 'type' => 'expense', 'description' => 'Biaya operasional harian'],
            ['name' => 'Maintenance', 'type' => 'expense', 'description' => 'Biaya pemeliharaan perangkat'],
            ['name' => 'Lain-lain', 'type' => 'expense', 'description' => 'Pengeluaran lainnya'],
        ];

        foreach ($expenseCategories as $category) {
            TransactionCategory::create($category);
        }
    }
}
