<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'internet_package_id',
        'invoice_number',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'payment_method_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function internetPackage(): BelongsTo
    {
        return $this->belongsTo(InternetPackage::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $maxAttempts = 5;
        $attempt = 1;
        
        while ($attempt <= $maxAttempts) {
            try {
                return DB::transaction(function () {
                    $prefix = 'INV';
                    $year = date('Y');
                    $month = date('m');
                    
                    // Get the highest invoice number for this month
                    $lastPayment = self::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where('invoice_number', 'LIKE', "{$prefix}-{$year}{$month}-%")
                        ->orderBy('invoice_number', 'desc')
                        ->lockForUpdate()
                        ->first();
                    
                    // Extract the sequence number or start from 0
                    $lastNumber = 0;
                    if ($lastPayment) {
                        preg_match('/(\d+)$/', $lastPayment->invoice_number, $matches);
                        $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
                    }
                    
                    // Format: INV-YYYYMM-XXXX
                    $nextNumber = $lastNumber + 1;
                    $invoiceNumber = sprintf('%s-%s%s-%04d', $prefix, $year, $month, $nextNumber);
                    
                    // Double check uniqueness
                    if (self::where('invoice_number', $invoiceNumber)->exists()) {
                        throw new \Exception('Invoice number already exists');
                    }
                    
                    return $invoiceNumber;
                });
            } catch (\Exception $e) {
                if ($attempt === $maxAttempts) {
                    throw $e;
                }
                $attempt++;
                // Add a small random delay before retrying
                usleep(rand(100000, 500000)); // 0.1 to 0.5 seconds
            }
        }
        
        throw new \Exception('Failed to generate unique invoice number after ' . $maxAttempts . ' attempts');
    }

    public function generatePDF()
    {
        try {
            $pdf = PDF::loadView('invoice-simple', ['payment' => $this])
                ->setPaper('a4')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isFontSubsettingEnabled' => true,
                    'isPhpEnabled' => true,
                    'chroot' => public_path(),
                ]);

            return $pdf;
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }
}
