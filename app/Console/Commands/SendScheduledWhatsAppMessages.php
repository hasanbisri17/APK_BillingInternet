<?php

namespace App\Console\Commands;

use App\Models\WhatsAppMessage;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendScheduledWhatsAppMessages extends Command
{
    protected $signature = 'whatsapp:send-scheduled';
    protected $description = 'Send scheduled WhatsApp messages';

    public function handle()
    {
        // Get messages that are scheduled for now or in the past and still pending
        $messages = WhatsAppMessage::query()
            ->where('status', 'pending')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        $whatsapp = new WhatsAppService();
        $sent = 0;
        $failed = 0;

        $this->info("Found {$messages->count()} messages to send...");

        foreach ($messages as $message) {
            try {
                $result = $whatsapp->sendMessage(
                    $message->customer->phone,
                    $message->message
                );

                if ($result['success']) {
                    $message->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                        'response' => $result,
                    ]);
                    $sent++;
                } else {
                    throw new \Exception($result['error'] ?? 'Unknown error');
                }
            } catch (\Exception $e) {
                $message->update([
                    'status' => 'failed',
                    'response' => [
                        'error' => $e->getMessage(),
                    ],
                ]);
                $failed++;

                $this->error("Failed to send message {$message->id}: {$e->getMessage()}");
            }

            // Add delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }

        $this->info("Completed! Sent: {$sent}, Failed: {$failed}");
    }
}
