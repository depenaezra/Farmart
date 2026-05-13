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
}
