<?php

namespace App\Filament\Resources\WhatsAppSettingResource\Pages;

use App\Filament\Resources\WhatsAppSettingResource;
use App\Services\WhatsAppService;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageWhatsAppSettings extends ManageRecords
{
    protected static string $resource = WhatsAppSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pengaturan')
                ->modalHeading('Tambah Pengaturan WhatsApp')
                ->after(function () {
                    // Clear config cache after updating settings
                    \Artisan::call('config:clear');
                }),
            Actions\Action::make('test')
                ->label('Test Koneksi')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Test Koneksi WhatsApp')
                ->modalDescription('Sistem akan mengirim pesan test ke nomor yang Anda tentukan.')
                ->form([
                    \Filament\Forms\Components\TextInput::make('test_number')
                        ->label('Nomor WhatsApp')
                        ->placeholder('621234567890')
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $settings = $this->getModel()::getCurrentSettings();
                        if (!$settings) {
                            throw new \Exception('Pengaturan WhatsApp belum dikonfigurasi');
                        }

                        $whatsapp = new WhatsAppService($settings);
                        
                        $result = $whatsapp->sendMessage(
                            $data['test_number'],
                            "Test koneksi WhatsApp berhasil!\n\nJika Anda menerima pesan ini, berarti pengaturan WhatsApp sudah benar."
                        );

                        if ($result['success']) {
                            Notification::make()
                                ->success()
                                ->title('Test Berhasil')
                                ->body('Koneksi ke WhatsApp Gateway berhasil!')
                                ->send();
                        } else {
                            throw new \Exception($result['error']);
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Test Gagal')
                            ->body('Error: ' . $e->getMessage())
                            ->send();
                    }
                })
                ->visible(fn () => $this->getModel()::where('is_active', true)->exists()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If this is set as active, deactivate all other settings
        if ($data['is_active'] ?? true) {
            $this->getModel()::where('id', '!=', $data['id'] ?? null)
                ->update(['is_active' => false]);
        }

        return $data;
    }
}
