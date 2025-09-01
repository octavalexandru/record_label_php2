<?php
// app/core/MailService.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public static function sendVerification(string $toEmail, string $toName, string $verifyLink): bool
    {
        // load SMTP config
        $cfg = require __DIR__ . '/../../config/mail.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $cfg['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $cfg['username'];
            $mail->Password   = $cfg['password'];
            $mail->SMTPSecure = $cfg['encryption']; // 'tls' or 'ssl'
            $mail->Port       = (int)$cfg['port'];

            $mail->setFrom($cfg['from_email'], $cfg['from_name']);
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Verify your account';
            $mail->Body    = 'Click <a href="' . htmlspecialchars($verifyLink) . '">here</a> to verify your account.<br>Or paste this link: ' . htmlspecialchars($verifyLink);
            $mail->AltBody = 'Open this link to verify: ' . $verifyLink;

            return $mail->send();
        } catch (Exception $e) {
            error_log('Mail error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
