<?php if (!empty($error)): ?>
  <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php elseif (!empty($success)): ?>
  <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>
