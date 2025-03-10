<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'provider',
        'account_number',
        'account_name',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const TYPES = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'e_wallet' => 'E-Wallet'
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
