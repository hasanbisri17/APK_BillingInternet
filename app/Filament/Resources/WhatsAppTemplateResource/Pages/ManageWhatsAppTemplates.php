<?php

namespace App\Filament\Resources\WhatsAppTemplateResource\Pages;

use App\Filament\Resources\WhatsAppTemplateResource;
use App\Models\WhatsAppTemplate;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageWhatsAppTemplates extends ManageRecords
{
    protected static string $resource = WhatsAppTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Template')
                ->modalHeading('Tambah Template Pesan'),
            Actions\Action::make('restore_defaults')
                ->label('Pulihkan Default')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $defaultTemplates = WhatsAppTemplate::getDefaultTemplates();
                    $restored = 0;

                    foreach ($defaultTemplates as $template) {
                        if (!WhatsAppTemplate::where('code', $template['code'])->exists()) {
                            WhatsAppTemplate::create($template);
                            $restored++;
                        }
                    }

                    if ($restored > 0) {
                        Notification::make()
                            ->success()
                            ->title('Template Default Dipulihkan')
                            ->body("{$restored} template berhasil dipulihkan.")
                            ->send();
                    } else {
                        Notification::make()
                            ->info()
                            ->title('Tidak Ada Perubahan')
                            ->body('Semua template default sudah ada.')
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Pulihkan Template Default?')
                ->modalDescription('Tindakan ini akan mengembalikan template default yang mungkin terhapus. Template yang sudah ada tidak akan terpengaruh.')
                ->modalSubmitActionLabel('Ya, Pulihkan')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
