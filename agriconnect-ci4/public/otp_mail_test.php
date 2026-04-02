<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function loadEnv(string $path): array
{
    $env = [];
    if (!is_file($path)) {
        return $env;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return $env;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $value = trim($parts[1]);
        $value = trim($value, "\"'");
        $env[$key] = $value;
    }

    return $env;
}

function sendOtpEmail(string $toEmail, string $otp, array $env): array
{
    $smtpHost = $env['SMTP_HOST'] ?? 'smtp.gmail.com';
    $smtpPort = (int) ($env['SMTP_PORT'] ?? 587);
    $smtpUser = $env['SMTP_USERNAME'] ?? '23-72068@g.batstate-u.edu.ph';
    $smtpPass = $env['SMTP_PASSWORD'] ?? '';
    $smtpPass = preg_replace('/\s+/', '', $smtpPass ?? '');
    $fromEmail = $env['SMTP_FROM_EMAIL'] ?? '23-72068@g.batstate-u.edu.ph';
    $fromName = $env['SMTP_FROM_NAME'] ?? 'Farmart OTP';

    if ($smtpPass === '') {
        return [false, 'SMTP_PASSWORD is empty. Set it in .env first.'];
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpPort;
        $mail->Timeout = 20;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Farmart OTP Verification Code';
        $mail->Body = 'Welcome to Farmart! Thank you for joining us, here is your code: <b>' . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . '</b><br>This code will expire in 10 minutes.';
        $mail->AltBody = 'Welcome to Farmart! Thank you for joining us, here is your code: ' . $otp . '. This code will expire in 10 minutes.';

        $mail->send();

        return [true, 'OTP sent successfully to ' . $toEmail . '.'];
    } catch (Exception $e) {
        return [false, 'Mailer Error: ' . $mail->ErrorInfo];
    }
}

$env = loadEnv(__DIR__ . '/../.env');
$message = '';
$error = '';
$step = 'send';
$email = '';

if (isset($_SESSION['otp_test_email'])) {
    $email = (string) $_SESSION['otp_test_email'];
    $step = 'verify';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'send') {
        $email = trim((string) ($_POST['email'] ?? ''));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $otp = (string) random_int(100000, 999999);
            $_SESSION['otp_test_code'] = $otp;
            $_SESSION['otp_test_email'] = $email;
            $_SESSION['otp_test_expiry'] = time() + 600;

            [$ok, $info] = sendOtpEmail($email, $otp, $env);

            if ($ok) {
                $message = $info;
                $step = 'verify';
            } else {
                $error = $info;
                $step = 'send';
            }
        }
    }

    if ($action === 'verify') {
        $inputCode = trim((string) ($_POST['otp'] ?? ''));
        $savedCode = $_SESSION['otp_test_code'] ?? null;
        $expiry = (int) ($_SESSION['otp_test_expiry'] ?? 0);

        if ($savedCode === null || !isset($_SESSION['otp_test_email'])) {
            $error = 'No OTP session found. Please send a new code.';
            $step = 'send';
        } elseif (time() > $expiry) {
            $error = 'OTP expired. Please send a new code.';
            $step = 'send';
            unset($_SESSION['otp_test_code'], $_SESSION['otp_test_email'], $_SESSION['otp_test_expiry']);
        } elseif (!preg_match('/^[0-9]{6}$/', $inputCode)) {
            $error = 'OTP must be a 6-digit number.';
            $step = 'verify';
        } elseif (hash_equals((string) $savedCode, $inputCode)) {
            $message = 'OTP verified successfully. Email sending and verification are working.';
            $step = 'send';
            unset($_SESSION['otp_test_code'], $_SESSION['otp_test_email'], $_SESSION['otp_test_expiry']);
            $email = '';
        } else {
            $error = 'Incorrect OTP. Please try again.';
            $step = 'verify';
        }
    }

    if ($action === 'reset') {
        unset($_SESSION['otp_test_code'], $_SESSION['otp_test_email'], $_SESSION['otp_test_expiry']);
        $message = 'OTP test session reset.';
        $step = 'send';
        $email = '';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Farmart OTP Mail Test</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 24px; }
    .card { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 20px rgba(0,0,0,.08); }
    h1 { margin-top: 0; font-size: 24px; }
    .help { color: #4b5563; font-size: 14px; margin-bottom: 16px; }
    .msg { padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; }
    .ok { background: #dcfce7; color: #166534; }
    .err { background: #fee2e2; color: #991b1b; }
    label { display: block; margin: 10px 0 6px; font-weight: bold; }
    input[type=email], input[type=text] { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; }
    .row { display: flex; gap: 10px; margin-top: 14px; }
    button { border: 0; border-radius: 8px; padding: 10px 14px; cursor: pointer; }
    .primary { background: #166534; color: #fff; }
    .muted { background: #e5e7eb; color: #111827; }
    .small { margin-top: 12px; font-size: 12px; color: #6b7280; }
    code { background: #f3f4f6; padding: 1px 4px; border-radius: 4px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>OTP Email Test Page</h1>
    <p class="help">This page sends an OTP email and lets you verify it before wiring the final registration flow.</p>

    <?php if ($message !== ''): ?>
      <div class="msg ok"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
      <div class="msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($step === 'send'): ?>
      <form method="post">
        <input type="hidden" name="action" value="send">
        <label for="email">Recipient Email</label>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" placeholder="you@example.com" required>
        <div class="row">
          <button class="primary" type="submit">Send OTP</button>
          <button class="muted" type="submit" name="action" value="reset">Reset</button>
        </div>
      </form>
    <?php else: ?>
      <form method="post">
        <input type="hidden" name="action" value="verify">
        <label for="otp">Enter 6-digit OTP</label>
        <input id="otp" type="text" name="otp" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required>
        <div class="row">
          <button class="primary" type="submit">Verify OTP</button>
          <button class="muted" type="submit" name="action" value="reset">Start Over</button>
        </div>
      </form>
    <?php endif; ?>

    <p class="small">Uses <code>SMTP_USERNAME</code> and <code>SMTP_PASSWORD</code> from <code>agriconnect-ci4/.env</code>. Password spaces are removed automatically for Gmail app password compatibility.</p>
  </div>
</body>
</html>
