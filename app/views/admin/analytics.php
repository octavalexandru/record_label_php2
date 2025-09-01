<h1>Site Analytics</h1>

<?php if (!empty($logs)): ?>
  <canvas id="routeChart" width="400" height="200"></canvas>

  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>IP</th>
        <th>Path</th>
        <th>Action</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
        <tr>
          <td><?= htmlspecialchars($log['id']) ?></td>
          <td><?= $log['user_id'] ? htmlspecialchars($log['user_id']) : 'guest' ?></td>
          <td><?= htmlspecialchars($log['ip'] ?? '') ?></td>
          <td><?= htmlspecialchars($log['path'] ?? '') ?></td>
          <td><?= htmlspecialchars($log['action'] ?? '') ?></td>
          <td><?= htmlspecialchars($log['created_at'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  <?php
    $routeCounts = [];
    foreach ($logs as $log) {
        $route = $log['path'] ?? 'unknown';
        if (!isset($routeCounts[$route])) $routeCounts[$route] = 0;
        $routeCounts[$route]++;
    }
  ?>

  const ctx = document.getElementById('routeChart').getContext('2d');
  const routeChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: <?= json_encode(array_keys($routeCounts)) ?>,
          datasets: [{
              label: 'Page Hits',
              data: <?= json_encode(array_values($routeCounts)) ?>,
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: { display: false },
              title: {
                  display: true,
                  text: 'Most Visited Pages'
              }
          },
          scales: {
              y: {
                  beginAtZero: true,
                  ticks: { stepSize: 1 }
              }
          }
      }
  });
  </script>
<?php else: ?>
  <p>No analytics data found.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
