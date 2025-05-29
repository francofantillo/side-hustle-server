<?php

/**
 * Mailgun Direct API Test Script
 * This script tests the Mailgun PHP SDK directly with your configuration
 * 
 * To run this script:
 * 1. Make sure you've run: composer install
 * 2. Configure your .env file with MAILGUN_SECRET and MAILGUN_DOMAIN
 * 3. Run: docker-compose exec app php test-mailgun-direct.php
 * 
 * Features:
 * - Reads configuration from .env file
 * - Tests both text and HTML email formats
 * - Provides detailed logging and error handling
 * - Simulates real OTP email structure
 */

// Include the Autoloader (see "Libraries" for install instructions)
require_once 'vendor/autoload.php';

// Use the Mailgun class from mailgun/mailgun-php v4.2
use Mailgun\Mailgun;

try {
    echo "=== Mailgun Direct API Test ===\n\n";
    
    // Load environment variables from .env file
    $envVars = [];
    if (file_exists('.env')) {
        $envFile = file_get_contents('.env');
        $lines = explode("\n", $envFile);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
                list($key, $value) = explode('=', $line, 2);
                $envVars[trim($key)] = trim($value, '"');
            }
        }
    }
    
    // Get configuration from .env
    $apiKey = $envVars['MAILGUN_SECRET'] ?? getenv('MAILGUN_SECRET') ?? 'your-mailgun-api-key-here';
    $domain = $envVars['MAILGUN_DOMAIN'] ?? getenv('MAILGUN_DOMAIN') ?? 'sandboxbf9730c7f66f4f23bc209e8ed273588e.mailgun.org';
    $endpoint = $envVars['MAILGUN_ENDPOINT'] ?? getenv('MAILGUN_ENDPOINT') ?? 'api.mailgun.net';
    $fromAddress = $envVars['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?? "postmaster@{$domain}";
    $fromName = $envVars['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Side Hustle';
    
    if ($apiKey === 'your-mailgun-api-key-here' || empty($apiKey)) {
        echo "❌ Error: MAILGUN_SECRET not found in .env file or environment\n";
        echo "Make sure your .env file contains: MAILGUN_SECRET=your-actual-api-key\n";
        exit(1);
    }
    
    echo "✅ Configuration loaded from .env:\n";
    echo "   API Key: " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -5) . " (length: " . strlen($apiKey) . ")\n";
    echo "   Domain: {$domain}\n";
    echo "   Endpoint: {$endpoint}\n";
    echo "   From: {$fromName} <{$fromAddress}>\n\n";
    
    // Instantiate the client with proper endpoint
    $baseUrl = "https://{$endpoint}";
    $mg = Mailgun::create($apiKey, $baseUrl);
    
    echo "✅ Mailgun client created successfully\n\n";
    
    echo "📧 Sending test email...\n";
    echo "From domain: $domain\n";
    echo "To: Franco Fantillo <franco.fantillo@gmail.com>\n\n";
    
    // Compose and send your message using environment variables
    $result = $mg->messages()->send(
        $domain,
        [
            'from' => "{$fromName} <{$fromAddress}>",
            'to' => 'Franco Fantillo <franco.fantillo@gmail.com>',
            'subject' => 'Hello Franco Fantillo - OTP Test',
            'text' => "Hello Franco Fantillo,\n\nThis is a test email from your Side Hustle application using Mailgun!\n\nYour OTP verification system is working correctly.\n\nBest regards,\nSide Hustle Team",
            'html' => "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #333;'>Hello Franco Fantillo!</h2>
                    <p>This is a test email from your <strong>Side Hustle</strong> application using Mailgun!</p>
                    <div style='background-color: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 5px;'>
                        <h3 style='color: #007bff; margin: 0;'>✅ Email System Status: WORKING</h3>
                        <p style='margin: 10px 0 0 0;'>Your OTP verification system is now successfully configured with Mailgun.</p>
                    </div>
                    <p>Best regards,<br><strong>Side Hustle Team</strong></p>
                </div>
            "
        ]
    );
    
    echo "✅ Email sent successfully!\n\n";
    echo "Response details:\n";
    echo "Message ID: " . $result->getId() . "\n";
    echo "Status: " . $result->getMessage() . "\n";
    
    // Get the message ID for tracking
    $messageId = $result->getId();
    if ($messageId) {
        echo "📧 You can track this email in Mailgun dashboard with ID: {$messageId}\n";
        echo "🔗 Dashboard: https://app.mailgun.com/app/logs\n";
    }
    
    echo "\n💡 Email delivery tips:\n";
    echo "• Check your spam/junk folder if not in inbox\n";
    echo "• Email delivery can take 1-5 minutes\n";
    echo "• Gmail may delay emails from new senders\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting tips:\n";
    echo "1. Make sure you've run 'composer install' to install mailgun/mailgun-php\n";
    echo "2. Verify MAILGUN_SECRET and MAILGUN_DOMAIN in .env file\n";
    echo "3. If using sandbox domain, make sure franco.fantillo@gmail.com is in authorized recipients\n";
    echo "4. Check your Mailgun dashboard for sending limits and domain status\n";
    echo "5. Verify your API key has the correct permissions\n";
}

echo "\n=== Test Complete ===\n";
