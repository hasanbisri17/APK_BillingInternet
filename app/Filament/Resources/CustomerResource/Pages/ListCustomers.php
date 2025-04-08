<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Services\WhatsAppService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('sendServiceNotification')
                ->label('Send Service Notification')
                ->form([
                    Select::make('notification_type')
                        ->label('Notification Type')
                        ->options([
                            'maintenance' => 'Maintenance Notice',
                            'disruption' => 'Service Disruption',
                            'resolved' => 'Issue Resolved',
                        ])
                        ->required(),
                    DateTimePicker::make('schedule_at')
                        ->label('Schedule For (Optional)')
                        ->helperText('Leave empty to send immediately'),
                ])
                ->action(function (array $data): void {
                    $whatsapp = new WhatsAppService();
                    
                    try {
                        $selectedRecords = $this->getSelectedTableRecords();
                        $customerIds = $selectedRecords ? $selectedRecords->pluck('id')->toArray() : [];
                        
                        $result = $whatsapp->sendBulkServiceNotification(
                            $customerIds,
                            $data['notification_type'],
                            $data['schedule_at'] ?? null
                        );

                        $message = $data['schedule_at'] 
                            ? 'Service notification scheduled successfully' 
                            : "Service notification sent successfully to {$result['sent']} customers";

                        if ($result['failed'] > 0) {
                            $message .= " ({$result['failed']} failed)";
                        }

                        Notification::make()
                            ->title($message)
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to send service notification')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Send Service Notification')
                ->modalDescription('This will send a WhatsApp notification to all selected customers. If no customers are selected, it will send to all customers.')
                ->modalSubmitActionLabel('Send Notification')
                ->color('warning')
                ->icon('heroicon-o-bell-alert'),
        ];
    }
}
