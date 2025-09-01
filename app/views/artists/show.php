<h1><?php echo htmlspecialchars($artist['name']); ?></h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php if (!empty($artist['bio'])): ?>
  <p><?php echo htmlspecialchars(nl2br(htmlspecialchars($artist['bio']))); ?></p>
<?php endif; ?>

<h2>Albums</h2>
<?php if (!empty($albums)): ?>
  <ul>
    <?php foreach ($albums as $album): ?>
      <li>
        <a href="/albums/show?id=<?php echo htmlspecialchars($album['album_id']); ?>">
          <?php echo htmlspecialchars($album['title']); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>No albums for this artist.</p>
<?php endif; ?>

<h2>Merchandise</h2>
<?php if (!empty($merch)): ?>
  <ul>
    <?php foreach ($merch as $item): ?>
      <li>
        <a href="/merch/show?id=<?php echo htmlspecialchars($item['merchandise_id']); ?>">
          <?php echo htmlspecialchars($item['name']); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>No merchandise available for this artist.</p>
<?php endif; ?>
