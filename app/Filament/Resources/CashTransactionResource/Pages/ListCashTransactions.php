<?php

namespace App\Filament\Resources\CashTransactionResource\Pages;

use App\Filament\Resources\CashTransactionResource;
use App\Filament\Resources\CashTransactionResource\Widgets\CashTransactionStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashTransactions extends ListRecords
{
    protected static string $resource = CashTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Transaksi'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CashTransactionStats::class,
        ];
    }
}
