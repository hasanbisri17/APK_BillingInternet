<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppScheduledMessage extends Model
{
    protected $fillable = [
        'customer_id',
        'payment_id',
        'message_type',
        'message',
        'scheduled_at',
        'sent_at',
        'status',
        'response',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'response' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'sent' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'cancelled' => 'gray',
            default => 'secondary',
        };
    }

    /**
     * Schedule a reminder for a payment
     */
    public static function schedulePaymentReminder(Payment $payment, string $type, \DateTime $scheduledAt): self
    {
        $template = WhatsAppTemplate::findByCode("billing.{$type}");
        
        if (!$template) {
            throw new \Exception("Template billing.{$type} not found");
        }

        $message = $template->formatMessage([
            'customer_name' => $payment->customer->name,
            'invoice_number' => $payment->invoice_number,
            'amount' => number_format($payment->amount, 0, ',', '.'),
            'due_date' => $payment->due_date->format('d F Y'),
            'period' => $payment->due_date->format('F Y'),
        ]);

        return static::create([
            'customer_id' => $payment->customer_id,
            'payment_id' => $payment->id,
            'message_type' => "billing.{$type}",
            'message' => $message,
            'scheduled_at' => $scheduledAt,
            'status' => 'pending',
        ]);
    }

    /**
     * Schedule reminders for upcoming due dates
     */
    public static function scheduleUpcomingReminders(Payment $payment): void
    {
        // Schedule reminder 3 days before due date
        static::schedulePaymentReminder(
            $payment,
            'reminder',
            $payment->due_date->copy()->subDays(3)
        );

        // Schedule reminder 1 day before due date
        static::schedulePaymentReminder(
            $payment,
            'reminder',
            $payment->due_date->copy()->subDay()
        );
    }

    /**
     * Schedule overdue payment notifications
     */
    public static function scheduleOverdueNotifications(Payment $payment): void
    {
        // Schedule overdue notification 1 day after due date
        static::schedulePaymentReminder(
            $payment,
            'overdue',
            $payment->due_date->copy()->addDay()
        );

        // Schedule overdue notification 3 days after due date
        static::schedulePaymentReminder(
            $payment,
            'overdue',
            $payment->due_date->copy()->addDays(3)
        );

        // Schedule overdue notification 7 days after due date
        static::schedulePaymentReminder(
            $payment,
            'overdue',
            $payment->due_date->copy()->addDays(7)
        );
    }

    /**
     * Get pending messages that are due to be sent
     */
    public static function getPendingMessages(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->get();
    }
}
