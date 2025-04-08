<?php

namespace App\Services;

use App\Models\Customer;
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
            'verify' => false,
        ]);
    }

    public function sendBulkServiceNotification($customerIds, $notificationType, $schedule = null)
    {
        try {
            $customers = Customer::when(!empty($customerIds), function ($query) use ($customerIds) {
                $query->whereIn('id', $customerIds);
            })->get();

            $messageDetails = $this->getServiceNotificationDetails($notificationType);
            $results = ['total' => $customers->count(), 'sent' => 0, 'failed' => 0];

            $template = WhatsAppTemplate::where('code', 'service.notification')->first();
            if (!$template) {
                throw new \Exception('Service notification template not found');
            }

            foreach ($customers as $customer) {
                if (!$customer->phone) {
                    $results['failed']++;
                    continue;
                }

                $message = str_replace(
                    ['{message}', '{time}'],
                    [$messageDetails['message'], $messageDetails['time']],
                    $template->content
                );

                // Create WhatsApp message record
                $whatsappMessage = new WhatsAppMessage([
                    'customer_id' => $customer->id,
                    'message_type' => 'service_notification',
                    'message' => $message,
                    'status' => 'pending',
                    'scheduled_at' => $schedule,
                ]);

                if ($schedule) {
                    $whatsappMessage->save();
                    $results['sent']++;
                    continue;
                }

                // Send message immediately
                $result = $this->sendMessage($customer->phone, $message);

                // Update message status
                $whatsappMessage->status = $result['success'] ? 'sent' : 'failed';
                $whatsappMessage->response = $result;
                $whatsappMessage->sent_at = now();
                $whatsappMessage->save();

                if ($result['success']) {
                    $results['sent']++;
                } else {
                    $results['failed']++;
                }

                // Add delay to avoid rate limiting
                usleep(500000); // 0.5 second delay
            }

            return $results;

        } catch (\Exception $e) {
            Log::error('Bulk Service Notification Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getServiceNotificationDetails($type)
    {
        $now = now()->format('d/m/Y H:i');
        
        $messages = [
            'maintenance' => [
                'message' => 'akan dilakukan pemeliharaan jaringan',
                'time' => $now
            ],
            'disruption' => [
                'message' => 'terjadi gangguan pada layanan internet',
                'time' => $now
            ],
            'resolved' => [
                'message' => 'gangguan telah diperbaiki dan layanan telah normal kembali',
                'time' => $now
            ]
        ];

        return $messages[$type] ?? [
            'message' => 'terdapat informasi penting terkait layanan internet',
            'time' => $now
        ];
    }

    public function sendMessage(string $phone, string $message, array $options = []): array
    {
        try {
            $phone = $this->formatPhone($phone);
            
            $formData = [
                'target' => $phone,
                'message' => $message,
                'delay' => '15',
            ];

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

    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = $this->settings->default_country_code . substr($phone, 1);
        }
        elseif (!str_starts_with($phone, $this->settings->default_country_code)) {
            $phone = $this->settings->default_country_code . $phone;
        }

        return $phone;
    }
}
