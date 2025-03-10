<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class TestWhatsAppCommand extends Command
{
    protected $signature = 'whatsapp:test {phone}';
    protected $description = 'Test WhatsApp integration by sending a test message';

    public function handle()
    {
        $phone = $this->argument('phone');
        $whatsapp = new WhatsAppService();

        $this->info('Sending test message...');

        try {
            $result = $whatsapp->sendMessage($phone, "This is a test message from your Internet Billing System.\n\nIf you receive this message, the WhatsApp integration is working correctly.");

            if ($result['success']) {
                $this->info('Message sent successfully!');
                $this->info('Response: ' . json_encode($result['response'], JSON_PRETTY_PRINT));
            } else {
                $this->error('Failed to send message!');
                $this->error('Error: ' . $result['error']);
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
