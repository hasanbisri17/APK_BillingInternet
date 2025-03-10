<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Tagihan'),
            Actions\Action::make('generateMonthlyBills')
                ->label('Generate Tagihan Bulanan')
                ->form([
                    DatePicker::make('month')
                        ->label('Pilih Bulan')
                        ->format('Y-m')
                        ->displayFormat('F Y')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    try {
                        // Convert the date to string format Y-m
                        $month = date('Y-m', strtotime($data['month']));
                        PaymentResource::generateMonthlyBills($month);
                        
                        Notification::make()
                            ->title('Tagihan bulanan berhasil dibuat')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal membuat tagihan')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading('Generate Tagihan Bulanan')
                ->modalDescription('Pilih bulan untuk membuat tagihan baru. Sistem akan melewati pelanggan yang sudah memiliki tagihan di bulan yang dipilih.'),
        ];
    }
}
