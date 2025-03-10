<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature = 'whatsapp:payment-reminders';
    protected $description = 'Send WhatsApp reminders for upcoming and overdue payments';

    public function handle()
    {
        $this->info('Sending payment reminders...');
        $whatsapp = new WhatsAppService();

        // Send reminders for payments due in 3 days
        $upcomingPayments = Payment::where('status', 'pending')
            ->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->get();

        $this->info("Found {$upcomingPayments->count()} upcoming payments to remind...");

        foreach ($upcomingPayments as $payment) {
            try {
                $whatsapp->sendBillingNotification($payment, 'reminder');
                $this->info("Sent reminder for payment {$payment->invoice_number}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for payment {$payment->invoice_number}: {$e->getMessage()}");
            }

            // Add delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }

        // Send notifications for overdue payments
        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        $this->info("Found {$overduePayments->count()} overdue payments to notify...");

        foreach ($overduePayments as $payment) {
            try {
                $whatsapp->sendBillingNotification($payment, 'overdue');
                $this->info("Sent overdue notice for payment {$payment->invoice_number}");
            } catch (\Exception $e) {
                $this->error("Failed to send overdue notice for payment {$payment->invoice_number}: {$e->getMessage()}");
            }

            // Add delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }

        $this->info('Payment reminders completed!');
    }
}
