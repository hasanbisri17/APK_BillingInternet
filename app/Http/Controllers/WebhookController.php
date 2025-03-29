<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Get secret from environment variable
        $secret = env('GITHUB_WEBHOOK_SECRET');

        if (empty($secret)) {
            Log::error('Webhook secret not configured');
            return response('Webhook not configured', 500);
        }

        // Get GitHub signature
        $signature = $request->header('X-Hub-Signature');

        if (empty($signature)) {
            Log::error('No signature provided');
            return response('No signature provided', 400);
        }

        // Get payload
        $payload = $request->getContent();

        // Verify signature
        $hash = 'sha1=' . hash_hmac('sha1', $payload, $secret);

        if (!hash_equals($hash, $signature)) {
            Log::error('Invalid signature');
            return response('Invalid signature', 401);
        }

        // Execute deployment script
        $output = shell_exec(base_path('deploy.sh') . ' 2>&1');

        // Log deployment
        $log = date('Y-m-d H:i:s') . " - Deployment executed\n" . $output . "\n";
        file_put_contents(storage_path('logs/deploy.log'), $log, FILE_APPEND);

        Log::info('Deployment completed successfully');
        return response('Deployment completed successfully');
    }
}
