<h1>Artists</h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<ul>
<?php foreach ($artists as $artist): ?>
  <li>
    <a href="/artists/show?id=<?php echo htmlspecialchars($artist['artist_id']); ?>">
      <?php echo htmlspecialchars($artist['name']); ?>
    </a>
  </li>
<?php endforeach; ?>
</ul>
