<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSetting;
use App\Models\WhatsAppTemplate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $settings;

    public function __construct(?WhatsAppSetting $settings = null)
    {
        $this->settings = $settings ?? WhatsAppSetting::getCurrentSettings();
        
        if (!$this->settings) {
            throw new \Exception('WhatsApp settings not configured');
        }

        // Fonnte API endpoint
        $baseUrl = rtrim($this->settings->api_url, '/') . '/';

        $this->client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => $this->settings->api_token,
            ],
            'verify' => false, // Skip SSL verification for local development
        ]);
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhone(string $phone): string
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If number starts with 0, replace it with country code
        if (str_starts_with($phone, '0')) {
            $phone = $this->settings->default_country_code . substr($phone, 1);
        }
        // If number doesn't start with country code, add it
        elseif (!str_starts_with($phone, $this->settings->default_country_code)) {
            $phone = $this->settings->default_country_code . $phone;
        }

        return $phone;
    }

    /**
     * Send a WhatsApp message
     */
    public function sendMessage(string $phone, string $message, array $options = []): array
    {
        try {
            $phone = $this->formatPhone($phone);
            
            // Build form data according to Fonnte's API
            $formData = [
                'target' => $phone,
                'message' => $message,
                'delay' => '1',
            ];

            // Add any additional options
            $formData = array_merge($formData, $options);

            $response = $this->client->post('send', [
                'form_params' => $formData
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (!($result['status'] ?? false)) {
                throw new \Exception($result['reason'] ?? 'Unknown error');
            }

            return [
                'success' => true,
                'response' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp message sending failed: ' . $e->getMessage(), [
                'phone' => $phone,
                'message' => $message,
                'options' => $options,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send a billing notification using template
     */
    public function sendBillingNotification(Payment $payment, string $type = 'new'): void
    {
        $customer = $payment->customer;
        if (!$customer->phone) {
            Log::warning('Customer has no phone number', ['customer_id' => $customer->id]);
            return;
        }

        $template = WhatsAppTemplate::findByCode("billing.{$type}");
        if (!$template) {
            Log::error('Template not found', ['type' => "billing.{$type}"]);
            return;
        }

        // Format message with template variables
        $message = $template->formatMessage([
            'customer_name' => $customer->name,
            'period' => Carbon::parse($payment->due_date)->format('F Y'),
            'invoice_number' => $payment->invoice_number,
            'amount' => number_format($payment->amount, 0, ',', '.'),
            'due_date' => Carbon::parse($payment->due_date)->format('d F Y'),
            'payment_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d F Y') : '-',
        ]);

        // Create WhatsApp message record
        $whatsappMessage = new WhatsAppMessage([
            'customer_id' => $customer->id,
            'payment_id' => $payment->id,
            'message_type' => "billing.{$type}",
            'message' => $message,
            'status' => 'pending',
        ]);

        // Send message
        $result = $this->sendMessage($customer->phone, $message);

        // Update message status
        $whatsappMessage->status = $result['success'] ? 'sent' : 'failed';
        $whatsappMessage->response = $result;
        $whatsappMessage->sent_at = now();
        $whatsappMessage->save();
    }

    /**
     * Send a broadcast message to multiple customers
     */
    public function sendBroadcast(array $customerIds, string $message): array
    {
        $results = [
            'total' => count($customerIds),
            'sent' => 0,
            'failed' => 0,
        ];

        $customers = Customer::whereIn('id', $customerIds)
            ->whereNotNull('phone')
            ->get();

        foreach ($customers as $customer) {
            // Create WhatsApp message record
            $whatsappMessage = new WhatsAppMessage([
                'customer_id' => $customer->id,
                'message_type' => 'broadcast',
                'message' => $message,
                'status' => 'pending',
            ]);

            // Send message
            $result = $this->sendMessage($customer->phone, $message);

            // Update message status
            $whatsappMessage->status = $result['success'] ? 'sent' : 'failed';
            $whatsappMessage->response = $result;
            $whatsappMessage->sent_at = now();
            $whatsappMessage->save();

            // Update results
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }

            // Add delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }

        return $results;
    }

    /**
     * Schedule a broadcast message to multiple customers
     */
    public function scheduleBroadcast(array $customerIds, string $message, string $scheduledAt): array
    {
        $customers = Customer::whereIn('id', $customerIds)
            ->whereNotNull('phone')
            ->get();

        $scheduled = 0;
        foreach ($customers as $customer) {
            // Create WhatsApp message record
            $whatsappMessage = new WhatsAppMessage([
                'customer_id' => $customer->id,
                'message_type' => 'broadcast',
                'message' => $message,
                'status' => 'pending',
                'scheduled_at' => $scheduledAt,
            ]);
            $whatsappMessage->save();
            $scheduled++;
        }

        return [
            'total' => count($customerIds),
            'scheduled' => $scheduled,
        ];
    }
}
