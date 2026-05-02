<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

sandip_start_session();
$user = $_SESSION['sandip_user'] ?? null;

if (!is_array($user) || ($user['role'] ?? '') !== 'student') {
    header('Location: indexx.html');
    exit;
}

$notices = array_slice(sandip_storage_read(SANDIP_NOTICES_STORE, []), 0, 5);
$events = array_slice(sandip_storage_read(SANDIP_EVENTS_STORE, []), 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard | Sandip Foundation Shri Ram Polytechnic</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="college-style.css" />
</head>
<body>
  <header class="subpage-hero">
    <nav class="navbar container">
      <a class="brand-mark" href="indexx.html" aria-label="Home">
        <img src="logo.png" alt="Sandip Foundation Shri Ram Polytechnic logo" />
        <span class="brand-copy">
          <strong>Sandip Foundation Shri Ram Polytechnic</strong>
          <small>Student Dashboard</small>
        </span>
      </a>
      <ul class="menu menu-static">
        <li><a href="indexx.html">Home</a></li>
        <li><a href="admission.php">Admission Form</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <div class="container subpage-copy">
      <p class="tag">Student Portal</p>
      <h1>Welcome, <?php echo htmlspecialchars((string)$user['name'], ENT_QUOTES, 'UTF-8'); ?>.</h1>
      <p>Your portal is ready for admission progress, notices, campus events and result search.</p>
    </div>
  </header>

  <main>
    <section class="section container">
      <div class="admission-top-grid">
        <article class="card" data-reveal>
          <p class="mini-tag">Student ID</p>
          <h2><?php echo htmlspecialchars((string)$user['id'], ENT_QUOTES, 'UTF-8'); ?></h2>
          <p>Email: <?php echo htmlspecialchars((string)$user['email'], ENT_QUOTES, 'UTF-8'); ?></p>
        </article>
        <article class="card" data-reveal>
          <p class="mini-tag">Next Step</p>
          <h3>Complete your admission form</h3>
          <p>Open the final admission form to submit your full record for office review.</p>
          <a class="btn" href="admission.php">Open Admission Form</a>
        </article>
      </div>

      <div class="portal-layout">
        <article class="card" data-reveal>
          <div class="section-heading-block narrow">
            <p class="mini-tag">Latest Notices</p>
            <h2>Announcements for students</h2>
          </div>
          <div class="updates-grid">
            <?php foreach ($notices as $notice): ?>
              <article class="card update-card notice-card">
                <p class="update-label"><?php echo htmlspecialchars((string)($notice['label'] ?? 'Notice'), ENT_QUOTES, 'UTF-8'); ?></p>
                <h3><?php echo htmlspecialchars((string)($notice['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars((string)($notice['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                <span><?php echo htmlspecialchars((string)($notice['author'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </article>

        <aside class="card portal-side-panel" data-reveal>
          <div class="section-heading">
            <p class="mini-tag">Upcoming Events</p>
            <h3>Campus engagement</h3>
          </div>
          <div class="feature-stack">
            <?php foreach ($events as $event): ?>
              <article>
                <strong><?php echo htmlspecialchars((string)($event['day'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars((string)($event['month'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                <span><?php echo htmlspecialchars((string)($event['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </aside>
      </div>
    </section>
  </main>
</body>
</html>
