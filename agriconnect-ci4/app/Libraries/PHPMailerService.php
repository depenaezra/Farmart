<?php
namespace App\Libraries;

// Try both possible autoloader paths
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
} elseif (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    require_once(__DIR__ . '/../../../vendor/autoload.php');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    public static function sendOTP($toEmail, $otp)
    {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new \Exception('PHPMailer not loaded. Check your composer installation and autoloader path.');
        }
        $mail = new PHPMailer(true);
        try {
            $smtpUser = getenv('SMTP_USERNAME') ?: '23-72068@g.batstate-u.edu.ph';
            $smtpPassword = getenv('SMTP_PASSWORD') ?: '';

            if ($smtpPassword === '') {
                return 'SMTP_PASSWORD is not configured in your environment.';
            }

            // SMTP config (example for Gmail, change for SendGrid or other)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // or smtp.sendgrid.net
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('23-72068@g.batstate-u.edu.ph', 'Farmart OTP');
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Farmart OTP Verification Code';
            $mail->Body = "Welcome to Farmart! Thank you for joining us, here is your code: <b>{$otp}</b><br>This code will expire in 10 minutes.";
            $mail->AltBody = "Welcome to Farmart! Thank you for joining us, here is your code: {$otp}. This code will expire in 10 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }

    /**
     * Send security alert email for suspicious login attempts
     *
     * @param string $toEmail Recipient email
     * @param string $userName User's name
     * @param int $attemptCount Number of failed attempts
     * @return bool|string True on success, error message on failure
     */
    public static function sendSecurityAlert($toEmail, $userName, $attemptCount)
    {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new \Exception('PHPMailer not loaded. Check your composer installation and autoloader path.');
        }
        $mail = new PHPMailer(true);
        try {
            $smtpUser = getenv('SMTP_USERNAME') ?: '23-72068@g.batstate-u.edu.ph';
            $smtpPassword = getenv('SMTP_PASSWORD') ?: '';

            if ($smtpPassword === '') {
                return 'SMTP_PASSWORD is not configured in your environment.';
            }

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('23-72068@g.batstate-u.edu.ph', 'Farmart Security Team');
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Important: Suspicious Login Activity on Your Farmart Account';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #e74c3c;'>Security Alert: Unusual Login Attempts</h2>
                    <p>Hello <strong>{$userName}</strong>,</p>
                    <p>We detected multiple failed login attempts on your Farmart account. 
                       There have been <strong>{$attemptCount} unsuccessful password entries</strong>.</p>
                    
                    <div style='background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;'>
                        <h4 style='color: #856404; margin-top: 0;'>What does this mean?</h4>
                        <p>If you were trying to log in, please double-check your password and try again after the 5-minute cooldown period.</p>
                        <p>If <strong>you did not attempt these logins</strong>, someone may have your credentials. We recommend:</p>
                        <ol>
                            <li><strong>Change your password immediately</strong> using the " . base_url('auth/change_password') . " page</li>
                            <li>Enable any available two-factor authentication</li>
                            <li>Contact our support team if you need assistance</li>
                        </ol>
                    </div>
                    
                    <p>For your protection, your account is temporarily locked for <strong>5 minutes</strong> after every 5 failed attempts.</p>
                    <p>If this was not you, please take action to secure your account.</p>
                    
                    <hr style='margin: 30px 0;'>
                    <p style='color: #666; font-size: 12px;'>
                        This is an automated security notification from Farmart.<br>
                        If you believe this is an error, please contact support.
                    </p>
                </div>
            ";
            $mail->AltBody = "
                Security Alert: Unusual Login Activity on Your Farmart Account
                
                Hello {$userName},
                
                We detected multiple failed login attempts on your Farmart account.
                There have been {$attemptCount} unsuccessful password entries.
                
                If you were trying to log in, please double-check your password and try again after the 5-minute cooldown period.
                
                If you did NOT attempt these logins, someone may have your credentials. We recommend:
                1. Change your password immediately using: " . base_url('auth/change_password') . "
                2. Enable two-factor authentication if available
                3. Contact our support team for assistance
                
                Your account is temporarily locked for 5 minutes after every 5 failed attempts.
                
                This is an automated security notification from Farmart.
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }
}
