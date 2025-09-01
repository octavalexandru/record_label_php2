<?php
// app/controllers/AuthController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/MailService.php';

class AuthController extends Controller
{
    private static function validateRecaptcha(): bool {
        $secret = $_ENV['RECAPTCHA_SECRET_KEY'];
        $response = $_POST['g-recaptcha-response'] ?? '';

        if (!$response) return false;

        $data = [
            'secret' => $secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $result = curl_exec($ch);
        curl_close($ch);


        $decoded = json_decode($result, true);
        return !empty($decoded['success']) && $decoded['success'] === true;
    }

    public static function register()
    {
        self::ensureSessionStarted();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::checkCsrfToken(); 
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            // Basic validation
            if ($username === '' || $email === '' || $password === '' || $confirm === '') {
                return self::view('auth/register', ['error' => 'All fields are required.']);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return self::view('auth/register', ['error' => 'Invalid email address.']);
            }
            if ($password !== $confirm) {
                return self::view('auth/register', ['error' => 'Passwords do not match.']);
            }
            if (strlen($password) < 6) {
                return self::view('auth/register', ['error' => 'Password must be at least 6 characters.']);
            }

            $users = new User();

            // Uniqueness checks
            if ($users->findByEmail($email)) {
                return self::view('auth/register', ['error' => 'Email already in use.']);
            }
            if (method_exists($users, 'findByUsername') && $users->findByUsername($username)) {
                return self::view('auth/register', ['error' => 'Username already in use.']);
            }

            // Create user with verification token
            $token = bin2hex(random_bytes(32));
            $hash  = password_hash($password, PASSWORD_DEFAULT);

            $recaptcha = $_POST['g-recaptcha-response'] ?? '';
            $secret = RECAPTCHA_SECRET_KEY; // From config.php or inline
            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptcha}"
            );
            $result = json_decode($response, true);

            if (!$result['success']) {
                return self::view("auth/register", ["error" => "reCAPTCHA failed. Please try again."]);
            }

            $ok = $users->insert([
                'username'            => $username,
                'email'               => $email,
                'password_hash'       => $hash,
                'verification_token'  => $token,
                // is_verified defaults to 0 in DB
            ]);

            if (!$ok) {
                return self::view('auth/register', ['error' => 'Could not create account. Please try again.']);
            }

            // Build verify link (adjust base if your app is in a subfolder)
            $base       = 'http://' . $_SERVER['HTTP_HOST']; // e.g. http://localhost
            $verifyLink = $base . '/auth/verify?token=' . urlencode($token);

            // Send verification email
            $sent = MailService::sendVerification($email, $username, $verifyLink);

            if (!$sent) {
                // For dev, you can echo the link to test if SMTP is blocked:
                // return self::view('auth/register', ['error' => 'Email sending failed. Here is your link: ' . htmlspecialchars($verifyLink)]);
                return self::view('auth/register', ['error' => 'Verification email could not be sent. Please contact support.']);
            }

            return self::view('auth/login', ['message' => 'Check your email to verify your account.']);
        }

        self::view('auth/register');
    }

    public static function verify()
    {
        $token = $_GET['token'] ?? '';
        if ($token === '') {
            return self::view('auth/verify', ['error' => 'Invalid verification link.']);
        }

        $users = new User();
        $user  = method_exists($users, 'findByToken') ? $users->findByToken($token) : null;

        if (!$user) {
            return self::view('auth/verify', ['error' => 'Verification token not found or already used.']);
        }

        // Your User model earlier had verifyUser($userId)
        $ok = $users->verifyUser((int)$user['user_id']);
        if (!$ok) {
            return self::view('auth/verify', ['error' => 'Could not verify account. Try again later.']);
        }

        return self::view('auth/verify', ['success' => 'Account verified! You may now log in.']);
    }

    public static function login()
    {
        self::ensureSessionStarted();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::checkCsrfToken(); 
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                return self::view('auth/login', ['error' => 'Email and password are required.']);
            }

            $users = new User();
            $user  = $users->findByEmail($email);

            if (!self::validateRecaptcha()) {
                return self::view('auth/login', ['error' => 'reCAPTCHA verification failed.']);
            }

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return self::view('auth/login', ['error' => 'Invalid credentials.']);
            }

            if (empty($user['is_verified'])) {
                return self::view('auth/login', ['error' => 'Please verify your email before logging in.']);
            }

            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set session values
            $_SESSION['user_id']  = (int)$user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'] ?? 'user'; // ✅ Add role to session

            return self::redirect('/');
        }

        self::view('auth/login');
    }


    public static function logout()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        self::checkCsrfToken();
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    self::redirect('/auth/login');
}

}
