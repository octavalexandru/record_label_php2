<h1>Register</h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<form method="post" action="/auth/register">
<div class="g-recaptcha" data-sitekey="<?= $_ENV['RECAPTCHA_SITE_KEY'] ?>"></div>

  <label>Username:
<?php if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="text" name="username" required>
  </label>
  <br>

  <label>Email:
    <input type="email" name="email" required>
  </label>
  <br>

  <label>Password:
    <input type="password" name="password" required>
  </label>
  <br>

  <label>Confirm Password:
    <input type="password" name="confirm_password" required>
  </label>
  <br>

  <button type="submit">Register</button>
</form>
