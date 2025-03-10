<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $navigationLabel = 'Metode Pembayaran';

    protected static ?string $modelLabel = 'Metode Pembayaran';

    protected static ?string $pluralModelLabel = 'Metode Pembayaran';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet'
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state === 'cash') {
                            $set('provider', null);
                            $set('account_number', null);
                            $set('account_name', null);
                        }
                    }),
                Forms\Components\TextInput::make('provider')
                    ->label(fn (Forms\Get $get) => 
                        $get('type') === 'bank_transfer' ? 'Nama Bank' : 
                        ($get('type') === 'e_wallet' ? 'Provider E-Wallet' : 'Provider')
                    )
                    ->required(fn (Forms\Get $get) => $get('type') !== 'cash')
                    ->hidden(fn (Forms\Get $get) => $get('type') === 'cash')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->label('Nomor Rekening/Akun')
                    ->required(fn (Forms\Get $get) => $get('type') !== 'cash')
                    ->hidden(fn (Forms\Get $get) => $get('type') === 'cash')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_name')
                    ->label('Nama Pemilik')
                    ->required(fn (Forms\Get $get) => $get('type') !== 'cash')
                    ->hidden(fn (Forms\Get $get) => $get('type') === 'cash')
                    ->maxLength(255),
                Forms\Components\Textarea::make('instructions')
                    ->label('Instruksi Pembayaran')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->required()
                    ->default(true),
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
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => PaymentMethod::TYPES[$state])
                    ->color(fn (string $state): string => 
                        match ($state) {
                            'cash' => 'success',
                            'bank_transfer' => 'info',
                            'e_wallet' => 'warning',
                        }
                    ),
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet'
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
