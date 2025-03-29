<?php
require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get secret from environment variable
$secret = $_ENV['GITHUB_WEBHOOK_SECRET'] ?? null;

if (empty($secret)) {
    http_response_code(500);
    die('Webhook not configured');
}

// Get GitHub signature
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

if (empty($signature)) {
    http_response_code(400);
    die('No signature provided');
}

// Get payload
$payload = file_get_contents('php://input');

// Verify signature
$hash = 'sha1=' . hash_hmac('sha1', $payload, $secret);

if (!hash_equals($hash, $signature)) {
    http_response_code(401);
    die('Invalid signature');
}

// Execute deployment script
$output = shell_exec('./deploy.sh 2>&1');

// Log deployment
$log = date('Y-m-d H:i:s') . " - Deployment executed\n" . $output . "\n";
file_put_contents('deploy.log', $log, FILE_APPEND);

echo "Deployment completed successfully";
