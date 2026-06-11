<?php
require_once 'includes/config.php';
$pageTitle = 'OFRS | Report Results';
$reports = [];
$query = trim($_GET['query'] ?? '');
if ($pdo && $query !== '') {
    $stmt = $pdo->prepare("SELECT * FROM tbl_firereport WHERE reference_code LIKE ? OR full_name LIKE ? OR mobile_number LIKE ? OR location_text LIKE ? ORDER BY created_at DESC");
    $like = "%$query%";
    $stmt->execute([$like, $like, $like, $like]);
    $reports = $stmt->fetchAll();
}
include 'includes/header.php';
?>
<section class="page-shell">
  <div class="container page-head">
    <div class="kicker">Search output</div>
    <h1>Incident result view</h1>
    <p>This page presents search results in a more evaluator-friendly format than a plain old table, with status chips and quick-access detail buttons.</p>
  </div>
  <div class="container table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Reference</th><th>Name</th><th>Mobile</th><th>Location</th><th>Severity</th><th>Status</th><th>Time</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reports as $row): ?>
        <tr>
          <td><?= h($row['reference_code']) ?></td>
          <td><?= h($row['full_name']) ?></td>
          <td><?= h($row['mobile_number']) ?></td>
          <td><?= h($row['location_text']) ?></td>
          <td><?= h(ucfirst($row['severity'])) ?></td>
          <td><span class="pill badge-<?= h(formatStatusClass($row['status'])) ?>"><?= h($row['status']) ?></span></td>
          <td><?= h(date('d M Y, h:i A', strtotime($row['created_at']))) ?></td>
          <td><a class="btn-secondary" href="details.php?id=<?= (int)$row['id'] ?>">View</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$reports): ?><tr><td colspan="8">No records found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
