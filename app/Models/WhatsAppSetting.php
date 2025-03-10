<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppSetting extends Model
{
    protected $table = 'whatsapp_settings';

    protected $fillable = [
        'api_token',
        'api_url',
        'default_country_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the current active settings
     */
    public static function getCurrentSettings(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Update settings and ensure only one active record
     */
    public static function updateSettings(array $data): self
    {
        $settings = static::getCurrentSettings() ?? new static();
        
        // Deactivate all other settings
        if ($data['is_active'] ?? true) {
            static::where('id', '!=', $settings->id)->update(['is_active' => false]);
        }
        
        $settings->fill($data);
        $settings->save();
        
        return $settings;
    }
}
