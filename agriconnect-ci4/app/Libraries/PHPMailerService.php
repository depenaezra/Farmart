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
            // SMTP config (example for Gmail, change for SendGrid or other)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // or smtp.sendgrid.net
            $mail->SMTPAuth = true;
            $mail->Username = 'betitaniel9@gmail.com'; // change to your email
            $mail->Password = 'msqc gcni xpba zbly'; // Gmail App Password or SendGrid API Key
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('betitaniel9@gmail.com', 'Farmart OTP');
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Your Farmart OTP Code';
            $mail->Body = "Your OTP code is: <b>$otp</b><br>This code will expire in 10 minutes.";
            $mail->AltBody = "Your OTP code is: $otp. This code will expire in 10 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }
}
