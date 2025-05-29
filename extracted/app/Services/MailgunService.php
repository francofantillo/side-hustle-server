<?php

namespace App\Services;

use Mailgun\Mailgun;
use Exception;

/**
 * Mailgun Service Class
 * 
 * This class provides direct Mailgun API integration for sending emails
 * Use this if you need more control than Laravel's built-in mail system
 */
class MailgunService
{
    protected $mailgun;
    protected $domain;
    protected $fromEmail;
    protected $fromName;

    public function __construct()
    {
        $apiKey = env('MAILGUN_SECRET');
        $this->domain = env('MAILGUN_DOMAIN');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@yourdomain.com');
        $this->fromName = env('MAIL_FROM_NAME', 'Side Hustle');

        if (!$apiKey) {
            throw new Exception('MAILGUN_SECRET environment variable is required');
        }

        if (!$this->domain) {
            throw new Exception('MAILGUN_DOMAIN environment variable is required');
        }

        // Create Mailgun instance
        $this->mailgun = Mailgun::create($apiKey);
        
        // If using EU domain, uncomment this line:
        // $this->mailgun = Mailgun::create($apiKey, 'https://api.eu.mailgun.net');
    }

    /**
     * Send OTP email using Mailgun API directly
     */
    public function sendOtpEmail($toEmail, $toName, $otp)
    {
        try {
            $subject = $this->fromName . ' - Verification Code';
            
            $textMessage = "Hello {$toName}!\n\n" .
                          "Your verification code is: {$otp}\n\n" .
                          "This code will expire in 10 minutes.\n\n" .
                          "If you did not request this code, please ignore this email.\n\n" .
                          "Thank you for using {$this->fromName}!";

            $htmlMessage = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #333;'>Hello {$toName}!</h2>
                    <p>Your verification code is:</p>
                    <div style='background: #f4f4f4; padding: 20px; text-align: center; margin: 20px 0;'>
                        <h1 style='color: #2563eb; font-size: 32px; margin: 0; letter-spacing: 3px;'>{$otp}</h1>
                    </div>
                    <p style='color: #666;'>This code will expire in 10 minutes.</p>
                    <p style='color: #666;'>If you did not request this code, please ignore this email.</p>
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                    <p style='color: #999; font-size: 12px;'>Thank you for using {$this->fromName}!</p>
                </div>
            ";

            $result = $this->mailgun->messages()->send($this->domain, [
                'from' => "{$this->fromName} <{$this->fromEmail}>",
                'to' => "{$toName} <{$toEmail}>",
                'subject' => $subject,
                'text' => $textMessage,
                'html' => $htmlMessage
            ]);

            return [
                'success' => true,
                'message' => 'OTP email sent successfully',
                'messageId' => $result->getId()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send custom email using Mailgun API
     */
    public function sendEmail($toEmail, $toName, $subject, $textMessage, $htmlMessage = null)
    {
        try {
            $messageData = [
                'from' => "{$this->fromName} <{$this->fromEmail}>",
                'to' => "{$toName} <{$toEmail}>",
                'subject' => $subject,
                'text' => $textMessage
            ];

            if ($htmlMessage) {
                $messageData['html'] = $htmlMessage;
            }

            $result = $this->mailgun->messages()->send($this->domain, $messageData);

            return [
                'success' => true,
                'message' => 'Email sent successfully',
                'messageId' => $result->getId()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test the Mailgun connection
     */
    public function testConnection($testEmail = null)
    {
        try {
            $testEmail = $testEmail ?: 'test@example.com';
            
            $result = $this->sendEmail(
                $testEmail,
                'Test User',
                'Mailgun Connection Test',
                'This is a test email to verify Mailgun integration is working correctly.'
            );

            return $result;

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
