<h1>Login</h1>
<?php if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>

<?php if (!empty($error)): ?>
  <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" action="/auth/login">
  <label>Email:
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="email" name="email" required>
  </label>
  <br>

  <label>Password:
    <input type="password" name="password" required>
  </label>
  <br>

  <div class="g-recaptcha" data-sitekey="<?= $_ENV['RECAPTCHA_SITE_KEY'] ?>"></div>

  <br>
  <button type="submit">Login</button>
</form>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
