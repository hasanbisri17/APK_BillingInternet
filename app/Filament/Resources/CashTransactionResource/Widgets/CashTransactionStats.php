<?php

namespace App\Filament\Resources\CashTransactionResource\Widgets;

use App\Models\CashTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashTransactionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $income = CashTransaction::where('type', 'income')->sum('amount');
        $expense = CashTransaction::where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($income, 2))
                ->description('Total semua pemasukan')
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($expense, 2))
                ->description('Total semua pengeluaran')
                ->color('danger'),
            Stat::make('Saldo', 'Rp ' . number_format($balance, 2))
                ->description('Saldo saat ini')
                ->color($balance >= 0 ? 'success' : 'danger'),
        ];
    }
}
