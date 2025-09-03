<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $mailer;

    public function __construct()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';

        // Load config from file
        $config = require __DIR__ . '/../config/phpmailer_config.php';

        $this->mailer = new PHPMailer(true);

        // Use config values
        $this->mailer->isSMTP();
        $this->mailer->Host       = $config['host'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $config['username'];
        $this->mailer->Password   = $config['password'];
        $this->mailer->SMTPSecure = $config['secure'];
        $this->mailer->Port       = $config['port'];

        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->isHTML(true);

        $this->mailer->setFrom($config['from_email'], $config['from_name']);
    }

    public function send($toEmail, $subject, $htmlBody)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail);

            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = strip_tags($htmlBody);

            return $this->mailer->send(); // PHPMailer's built-in send method
        } catch (Exception $e) {
            error_log("Mail error: " . $e->getMessage());
            return false;
        }
    }

    public function sendOrderConfirmation($toEmail, $items, $cart)
    {
        $subject = "Order Confirmation #" . $cart['cart_id'];

        $body = "<h2>Thanks for your order!</h2>";
        $body .= "<p>Order ID: {$cart['cart_id']}</p><ul>";

        foreach ($items as $item) {
            $name = htmlspecialchars($item['name'] ?? 'Unknown item', ENT_QUOTES, 'UTF-8');
            $qty  = (int)$item['quantity'];
            $body .= "<li>{$name} × {$qty}</li>";
        }

        $body .= "</ul><p>We'll ship your items soon!</p>";

        return $this->send($toEmail, $subject, $body);
    }
}
