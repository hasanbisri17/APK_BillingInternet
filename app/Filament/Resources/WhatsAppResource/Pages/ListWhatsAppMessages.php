<?php

namespace App\Filament\Resources\WhatsAppResource\Pages;

use App\Filament\Resources\WhatsAppResource;
use App\Models\Customer;
use App\Services\WhatsAppService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class ListWhatsAppMessages extends ListRecords
{
    protected static string $resource = WhatsAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('broadcast')
                ->label('Kirim Broadcast')
                ->icon('heroicon-m-megaphone')
                ->modalWidth(MaxWidth::Large)
                ->modalHeading('Kirim Pesan Broadcast')
                ->modalDescription('Kirim pesan WhatsApp ke beberapa pelanggan sekaligus.')
                ->modalSubmitActionLabel('Kirim')
                ->form([
                    Select::make('customer_ids')
                        ->label('Pelanggan')
                        ->multiple()
                        ->options(function() {
                            $options = Customer::orderBy('name')->pluck('name', 'id')->toArray();
                            return ['all' => 'Semua Pelanggan'] + $options;
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('message')
                        ->label('Pesan')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->action(function (array $data): void {
                    try {
                        $whatsapp = new WhatsAppService();

                        // If "all" is selected, get all customer IDs
                        if (in_array('all', $data['customer_ids'])) {
                            $data['customer_ids'] = Customer::pluck('id')->toArray();
                        }

                        $result = $whatsapp->sendBroadcast($data['customer_ids'], $data['message']);
                        
                        Notification::make()
                            ->title('Pesan Broadcast')
                            ->body("Terkirim: {$result['sent']}, Gagal: {$result['failed']}, Total: {$result['total']}")
                            ->success()
                            ->send();

                        $this->getTable()->getRecords();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
