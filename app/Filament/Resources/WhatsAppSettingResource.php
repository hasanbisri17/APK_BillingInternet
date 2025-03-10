<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppSettingResource\Pages;
use App\Models\WhatsAppSetting;
use App\Services\WhatsAppService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WhatsAppSettingResource extends Resource
{
    protected static ?string $model = WhatsAppSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan WhatsApp';

    protected static ?string $modelLabel = 'Pengaturan WhatsApp';

    protected static ?string $navigationGroup = 'WhatsApp';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_token')
                    ->label('API Token')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_url')
                    ->label('API URL')
                    ->default('https://api.fonnte.com')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('default_country_code')
                    ->label('Kode Negara Default')
                    ->default('62')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('test')
                        ->label('Test Koneksi')
                        ->action(function (WhatsAppSetting $record, array $data) {
                            try {
                                $whatsapp = new WhatsAppService($record);
                                
                                $result = $whatsapp->sendMessage(
                                    $data['test_number'],
                                    "Test koneksi WhatsApp berhasil!\n\nJika Anda menerima pesan ini, berarti pengaturan WhatsApp sudah benar."
                                );

                                if ($result['success']) {
                                    \Filament\Notifications\Notification::make()
                                        ->success()
                                        ->title('Test Berhasil')
                                        ->body('Koneksi ke WhatsApp Gateway berhasil!')
                                        ->send();
                                } else {
                                    throw new \Exception($result['error']);
                                }
                            } catch (\Exception $e) {
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Test Gagal')
                                    ->body('Error: ' . $e->getMessage())
                                    ->send();
                            }
                        })
                        ->color('success')
                        ->icon('heroicon-o-paper-airplane')
                        ->requiresConfirmation()
                        ->modalHeading('Test Koneksi WhatsApp')
                        ->modalDescription('Sistem akan mengirim pesan test ke nomor yang Anda tentukan.')
                        ->form([
                            Forms\Components\TextInput::make('test_number')
                                ->label('Nomor WhatsApp')
                                ->placeholder('621234567890')
                                ->required(),
                        ])
                        ->visible(fn ($record) => $record && $record->exists),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('api_url')
                    ->label('API URL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('default_country_code')
                    ->label('Kode Negara')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('test')
                    ->label('Test Koneksi')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Test Koneksi WhatsApp')
                    ->modalDescription('Sistem akan mengirim pesan test ke nomor yang Anda tentukan.')
                    ->form([
                        Forms\Components\TextInput::make('test_number')
                            ->label('Nomor WhatsApp')
                            ->placeholder('621234567890')
                            ->required(),
                    ])
                    ->action(function (WhatsAppSetting $record, array $data) {
                        try {
                            $whatsapp = new WhatsAppService($record);
                            
                            $result = $whatsapp->sendMessage(
                                $data['test_number'],
                                "Test koneksi WhatsApp berhasil!\n\nJika Anda menerima pesan ini, berarti pengaturan WhatsApp sudah benar."
                            );

                            if ($result['success']) {
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Test Berhasil')
                                    ->body('Koneksi ke WhatsApp Gateway berhasil!')
                                    ->send();
                            } else {
                                throw new \Exception($result['error']);
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Test Gagal')
                                ->body('Error: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWhatsAppSettings::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return WhatsAppSetting::where('is_active', true)->exists() ? '✓' : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return WhatsAppSetting::where('is_active', true)->exists() ? 'success' : 'danger';
    }
}
