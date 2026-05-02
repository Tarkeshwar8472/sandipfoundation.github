<?php
require_once __DIR__ . '/config.php';
sandip_require_admin();

$records = [];
$sourceLabel = 'JSON fallback storage';

try {
    $pdo = sandip_get_db_connection();
    if ($pdo instanceof PDO) {
        $query = $pdo->query('SELECT * FROM admissions ORDER BY id DESC');
        $records = $query->fetchAll(PDO::FETCH_ASSOC);
        $sourceLabel = 'MySQL database';
    }
} catch (Throwable $exception) {
    $records = sandip_read_json_records();
}

if (empty($records)) {
    $records = sandip_read_json_records();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sandip Foundation Shri Ram Polytechnic | Admin Records</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="college-style.css" />
</head>
<body class="page-admin">
  <header class="subpage-hero">
    <nav class="navbar container">
      <a class="brand-mark" href="indexx.html" aria-label="Sandip Foundation Shri Ram Polytechnic home">
        <img src="logo.png" alt="Sandip Foundation Shri Ram Polytechnic logo" />
        <span class="brand-copy">
          <strong>Sandip Foundation Shri Ram Polytechnic</strong>
          <small>Admin Records View</small>
        </span>
      </a>
      <ul class="menu menu-static">
        <li><a href="indexx.html">Page 1</a></li>
        <li><a href="college-details.html">Page 2</a></li>
        <li><a href="admission.php">Page 3</a></li>
        <li><a href="admin-dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <div class="container subpage-copy">
      <p class="tag">Admin Records</p>
      <h1>Submitted admission entries for office review.</h1>
      <p>Current data source: <?php echo htmlspecialchars($sourceLabel, ENT_QUOTES, 'UTF-8'); ?>.</p>
    </div>
  </header>

  <main>
    <section class="section container">
      <div class="section-heading-block">
        <p class="mini-tag">Admission Records</p>
        <h2>Backend submissions are visible here for review.</h2>
      </div>

      <?php if (empty($records)): ?>
        <div class="card">No admission record is available yet.</div>
      <?php else: ?>
        <div class="records-grid">
          <?php foreach ($records as $record): ?>
            <article class="card record-card">
              <p class="mini-tag">Student Record</p>
              <h3><?php echo htmlspecialchars($record['student_name'] ?? $record['studentName'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></h3>
              <p><strong>Program:</strong> <?php echo htmlspecialchars($record['program_name'] ?? $record['program'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
              <p><strong>Branch:</strong> <?php echo htmlspecialchars($record['branch_name'] ?? $record['branch'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
              <p><strong>Mobile:</strong> <?php echo htmlspecialchars($record['mobile'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
              <p><strong>City / State:</strong> <?php echo htmlspecialchars(($record['city_name'] ?? $record['city'] ?? '-') . ' / ' . ($record['state_name'] ?? $record['state'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></p>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($record['email_address'] ?? $record['email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
              <p><strong>Submitted:</strong> <?php echo htmlspecialchars($record['created_at'] ?? $record['createdAt'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
