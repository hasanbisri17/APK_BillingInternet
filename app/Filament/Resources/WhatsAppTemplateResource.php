<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppTemplateResource\Pages;
use App\Models\WhatsAppTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WhatsAppTemplateResource extends Resource
{
    protected static ?string $model = WhatsAppTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Template Pesan';

    protected static ?string $modelLabel = 'Template Pesan';

    protected static ?string $navigationGroup = 'WhatsApp';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Template')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Template')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('content')
                    ->label('Isi Pesan')
                    ->required()
                    ->maxLength(65535)
                    ->helperText('Gunakan {variable} untuk memasukkan variabel dinamis'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->maxLength(65535),
                Forms\Components\TagsInput::make('variables')
                    ->label('Variabel')
                    ->helperText('Variabel yang dapat digunakan dalam template (tanpa kurung kurawal)')
                    ->placeholder('Tambahkan variabel'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
                Forms\Components\Section::make('Preview')
                    ->description('Contoh pesan dengan variabel yang diisi')
                    ->schema([
                        Forms\Components\Placeholder::make('preview')
                            ->label('Preview Pesan')
                            ->content(function ($record) {
                                if (!$record) return 'Simpan template terlebih dahulu untuk melihat preview';
                                
                                $content = $record->content;
                                $variables = $record->variables ?? [];
                                
                                foreach ($variables as $var) {
                                    $content = str_replace("{{$var}}", "<{$var}>", $content);
                                }
                                
                                return nl2br(e($content));
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageWhatsAppTemplates::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return WhatsAppTemplate::where('is_active', true)->count();
    }
}
