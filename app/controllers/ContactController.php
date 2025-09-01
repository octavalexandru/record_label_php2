<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/MailService.php';

class ContactController extends Controller {
    public static function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            $body = "Message from $name <$email>:\n\n$message";

            if (MailService::send('admin@example.com', 'New Contact Message', $body)) {
                return self::view('contact', ['success' => 'Message sent!']);
            } else {
                return self::view('contact', ['error' => 'Failed to send message.']);
            }
        }
        self::view('contact');
    }
}
