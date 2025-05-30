<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\SendPushNotification;
use App\Notifications\SendOtpEmail;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Exception;
use Twilio\Rest\Client;
use Mailgun\Mailgun;


trait GeneralHelperTrait
{    protected function sendOtpEmail($user, $otp){
        try {
            $user->notify(new SendOtpEmail($otp));
            return array('success'=> true, 'message' => 'OTP Email Sent Successfully');
        } catch (Exception $e) {
            return array('success'=> false, 'error' => $e->getMessage());
        }
    }

    protected function sendMessageToClient($receiverNumber, $message){
        try {
            $account_sid  = env('TWILIO_ACCOUNT_SID');
            $auth_token   = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_PHONE_NUMBER');

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);

            return array('success'=> true, 'message' => 'Message Sent Successfully');

        } catch (Exception $e) {
            return array('success'=> false, 'error' => $e->getMessage());
        }
    }
    protected function sendPushNotification($title, $message, $clickAction=null, $additionalData=null,
                                            $fcmTokens=array(), $userIds=array()) {
        try {
            if (empty($fcmTokens) && !empty($userIds)){
                $fcmTokens = User::whereIn('id', $userIds)->whereNotNull('fcm_token')
                    ->pluck('fcm_token')->toArray();
            }else if(empty($fcmTokens) && empty($userIds)){
                $fcmTokens = User::whereNotNull('fcm_token')
                    ->pluck('fcm_token')->toArray();
            }

            if (!empty($fcmTokens)){
                Notification::send(null, new SendPushNotification($title, $message, $fcmTokens));

                return array('success'=> true, 'message' => 'Notification Sent Successfully');
            }else{
                return array('success'=> false, 'message' => 'Firebase tokens not found');
            }

        } catch (Exception $ex) {
            return array('success'=> false, 'error' => $ex->getMessage());
        }
    }

    /**
     * Send OTP email using Mailgun SDK directly
     * Alternative to sendOtpEmail() for more control
     */
    protected function sendOtpEmailViaMailgun($user, $otp){
        try {
            $apiKey = env('MAILGUN_SECRET');
            $domain = env('MAILGUN_DOMAIN');
            $fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@yourdomain.com');
            $fromName = env('MAIL_FROM_NAME', 'Phase 1');

            if (!$apiKey || !$domain) {
                throw new Exception('Mailgun configuration missing. Please set MAILGUN_SECRET and MAILGUN_DOMAIN environment variables.');
            }

            // Create Mailgun instance
            $mg = Mailgun::create($apiKey);
            
            // Prepare email content
            $subject = $fromName . ' - Verification Code';
            
            $textMessage = "Hello {$user->name}!\n\n" .
                          "Your verification code is: {$otp}\n\n" .
                          "This code will expire in 10 minutes.\n\n" .
                          "If you did not request this code, please ignore this email.\n\n" .
                          "Thank you for using {$fromName}!";

            $htmlMessage = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #333;'>Hello {$user->name}!</h2>
                    <p>Your verification code is:</p>
                    <div style='background: #f4f4f4; padding: 20px; text-align: center; margin: 20px 0;'>
                        <h1 style='color: #2563eb; font-size: 32px; margin: 0; letter-spacing: 3px;'>{$otp}</h1>
                    </div>
                    <p style='color: #666;'>This code will expire in 10 minutes.</p>
                    <p style='color: #666;'>If you did not request this code, please ignore this email.</p>
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                    <p style='color: #999; font-size: 12px;'>Thank you for using {$fromName}!</p>
                </div>
            ";

            // Send email via Mailgun API
            $result = $mg->messages()->send($domain, [
                'from' => "{$fromName} <{$fromEmail}>",
                'to' => "{$user->name} <{$user->email}>",
                'subject' => $subject,
                'text' => $textMessage,
                'html' => $htmlMessage
            ]);

            return array(
                'success' => true, 
                'message' => 'OTP Email Sent Successfully via Mailgun',
                'messageId' => $result->getId()
            );

        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

}
