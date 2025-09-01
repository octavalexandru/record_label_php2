<h1>Contact Us</h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<form method="post" action="/record_label/contact/send">
  <label>Name: <input type="text" name="name"></label><br>
<?php if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
  <label>Email: <input type="email" name="email"></label><br>
  <label>Subject: <input type="text" name="subject"></label><br>
  <label>Message:<br><textarea name="message" rows="6" cols="40"></textarea></label><br>
  <button type="submit">Send</button>
</form>
