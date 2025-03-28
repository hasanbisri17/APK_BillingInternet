<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Tagihan';

    protected static ?string $modelLabel = 'Tagihan';

    protected static ?string $pluralModelLabel = 'Tagihan';

    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Pelanggan')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $customer = \App\Models\Customer::find($state);
                            if ($customer) {
                                $set('internet_package_id', $customer->internet_package_id);
                                $set('amount', $customer->internetPackage?->price ?? 0);
                            }
                        }
                    }),
                Forms\Components\Select::make('internet_package_id')
                    ->label('Paket Internet')
                    ->relationship('internetPackage', 'name')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record && $record->customer) {
                            $component->state($record->customer->internet_package_id);
                        }
                    }),
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->default(fn () => Payment::generateInvoiceNumber())
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah Tagihan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Tanggal Jatuh Tempo')
                    ->required(),
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Lunas',
                        'overdue' => 'Terlambat',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Select::make('payment_method_id')
                    ->label('Metode Pembayaran')
                    ->relationship('paymentMethod', 'name')
                    ->preload()
                    ->searchable()
                    ->nullable()
                    ->hidden(fn ($record) => !$record || $record->status !== 'paid')
                    ->required(fn ($record) => $record && $record->status === 'paid')
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $set('status', 'paid');
                            $set('payment_date', now());
                        }
                    })
                    ->dehydrated(fn ($record) => $record && $record->status === 'paid')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique('payment_methods', 'code'),
                        Forms\Components\Select::make('type')
                            ->options(PaymentMethod::TYPES)
                            ->required(),
                        Forms\Components\TextInput::make('provider')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('account_number')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('account_name')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true),
                    ]),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internetPackage.name')
                    ->label('Paket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tgl Pembayaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'overdue',
                        'warning' => 'pending',
                        'success' => 'paid',
                    ]),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->label('Metode')
                    ->badge()
                    ->color(fn ($record) => match($record?->paymentMethod?->type) {
                        'cash' => 'success',
                        'bank_transfer' => 'info',
                        'e_wallet' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Lunas',
                        'overdue' => 'Terlambat',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method_id')
                    ->label('Metode Pembayaran')
                    ->relationship('paymentMethod', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('download_invoice')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn (Payment $record): string => route('invoice.download', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Payment $record): bool => $record->customer && $record->internetPackage),
                Tables\Actions\Action::make('pay')
                    ->label('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->modalHeading('Pembayaran Tagihan')
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('payment_method_id')
                            ->label('Metode Pembayaran')
                            ->relationship('paymentMethod', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->maxLength(65535),
                    ])
                    ->action(function (Payment $record, array $data): void {
                        // Update payment record
                        $record->update([
                            'payment_date' => $data['payment_date'],
                            'payment_method_id' => $data['payment_method_id'],
                            'notes' => $data['notes'],
                            'status' => 'paid',
                        ]);

                        // Get the internet payment category
                        $category = \App\Models\TransactionCategory::where('type', 'income')
                            ->where('name', 'Pembayaran Internet')
                            ->first();

                        // Create cash transaction
                        \App\Models\CashTransaction::create([
                            'date' => $data['payment_date'],
                            'type' => 'income',
                            'amount' => $record->amount,
                            'description' => "Pembayaran Internet - {$record->customer->name} ({$record->invoice_number})",
                            'category_id' => $category->id,
                        ]);

                        // Send WhatsApp notification for successful payment
                        try {
                            $whatsapp = new WhatsAppService();
                            $whatsapp->sendBillingNotification($record, 'paid');
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('WhatsApp Notification Failed')
                                ->body('Payment recorded successfully, but failed to send WhatsApp notification.')
                                ->send();
                        }
                    })
                    ->visible(fn (Payment $record): bool => $record->status !== 'paid')
                    ->successNotificationTitle('Pembayaran berhasil dicatat')
                    ->successNotification(
                        notification: function (Payment $record) {
                            return \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Pembayaran berhasil dicatat')
                                ->body("Pembayaran telah dicatat dan otomatis ditambahkan ke Kas sebagai pemasukan.\nNomor Invoice: {$record->invoice_number}\nPelanggan: {$record->customer->name}\nJumlah: Rp " . number_format($record->amount, 2));
                        }
                    ),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function generateMonthlyBills(string $month): void
    {
        $whatsapp = new WhatsAppService();
        try {
            // Get all active customers
            $customers = \App\Models\Customer::whereHas('internetPackage', function ($query) {
                $query->where('is_active', true);
            })->get();

            $selectedDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $dueDate = $selectedDate->copy()->addDays(24); // Due date is 25th of the month

            foreach ($customers as $customer) {
                // Check if customer already has a bill for this month
                $existingBill = Payment::where('customer_id', $customer->id)
                    ->whereYear('due_date', $selectedDate->year)
                    ->whereMonth('due_date', $selectedDate->month)
                    ->exists();

                if (!$existingBill) {
                    $payment = Payment::create([
                        'customer_id' => $customer->id,
                        'internet_package_id' => $customer->internet_package_id,
                        'invoice_number' => Payment::generateInvoiceNumber(),
                        'amount' => $customer->internetPackage->price,
                        'due_date' => $dueDate,
                        'status' => 'pending',
                        'payment_method_id' => null, // Allow null for pending payments
                    ]);

                    // Send WhatsApp notification for new bill
                    try {
                        $whatsapp->sendBillingNotification($payment, 'new');
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->warning()
                            ->title('WhatsApp Notification Failed')
                            ->body("Failed to send WhatsApp notification for customer: {$customer->name}")
                            ->send();
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat tagihan bulanan: ' . $e->getMessage());
        }
    }
}
