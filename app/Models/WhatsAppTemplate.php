<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'name',
        'code',
        'content',
        'description',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Replace variables in template content
     */
    public function formatMessage(array $data): string
    {
        $content = $this->content;

        foreach ($data as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }

        return $content;
    }

    /**
     * Get template by code
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Default templates data
     */
    public static function getDefaultTemplates(): array
    {
        return [
            [
                'name' => 'Tagihan Baru',
                'code' => 'billing.new',
                'content' => "Yth. {customer_name},\n\n".
                            "Tagihan internet Anda untuk periode {period} telah dibuat:\n".
                            "No. Invoice: {invoice_number}\n".
                            "Jumlah: Rp {amount}\n".
                            "Jatuh Tempo: {due_date}\n\n".
                            "Mohon melakukan pembayaran sebelum jatuh tempo.\n".
                            "Terima kasih.",
                'description' => 'Template untuk tagihan baru',
                'variables' => ['customer_name', 'period', 'invoice_number', 'amount', 'due_date'],
                'is_active' => true,
            ],
            [
                'name' => 'Pengingat Tagihan',
                'code' => 'billing.reminder',
                'content' => "Yth. {customer_name},\n\n".
                            "Mengingatkan tagihan internet Anda yang akan jatuh tempo:\n".
                            "No. Invoice: {invoice_number}\n".
                            "Jumlah: Rp {amount}\n".
                            "Jatuh Tempo: {due_date}\n\n".
                            "Mohon segera melakukan pembayaran.\n".
                            "Terima kasih.",
                'description' => 'Template untuk pengingat tagihan',
                'variables' => ['customer_name', 'invoice_number', 'amount', 'due_date'],
                'is_active' => true,
            ],
            [
                'name' => 'Tagihan Terlambat',
                'code' => 'billing.overdue',
                'content' => "Yth. {customer_name},\n\n".
                            "Tagihan internet Anda telah melewati jatuh tempo:\n".
                            "No. Invoice: {invoice_number}\n".
                            "Jumlah: Rp {amount}\n".
                            "Jatuh Tempo: {due_date}\n\n".
                            "Mohon segera melakukan pembayaran untuk menghindari pemutusan layanan.\n".
                            "Terima kasih.",
                'description' => 'Template untuk tagihan terlambat',
                'variables' => ['customer_name', 'invoice_number', 'amount', 'due_date'],
                'is_active' => true,
            ],
            [
                'name' => 'Konfirmasi Pembayaran',
                'code' => 'billing.paid',
                'content' => "Yth. {customer_name},\n\n".
                            "Terima kasih, pembayaran tagihan internet Anda telah kami terima:\n".
                            "No. Invoice: {invoice_number}\n".
                            "Jumlah: Rp {amount}\n".
                            "Tanggal Pembayaran: {payment_date}\n\n".
                            "Terima kasih atas kerjasamanya.",
                'description' => 'Template untuk konfirmasi pembayaran',
                'variables' => ['customer_name', 'invoice_number', 'amount', 'payment_date'],
                'is_active' => true,
            ],
        ];
    }
}
