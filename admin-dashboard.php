<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

sandip_require_admin();

$statusMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim((string)($_POST['action'] ?? ''));

    if ($action === 'add_notice') {
        $label = trim((string)($_POST['label'] ?? ''));
        $title = trim((string)($_POST['title'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));
        $author = trim((string)($_POST['author'] ?? ''));

        if ($title === '' || $message === '' || $author === '') {
            $errorMessage = 'Please complete all notice fields.';
        } else {
            $items = sandip_storage_read(SANDIP_NOTICES_STORE, []);
            array_unshift($items, [
                'id' => 'NOTICE' . random_int(100, 999),
                'label' => $label !== '' ? $label : 'Notice',
                'title' => $title,
                'message' => $message,
                'author' => $author,
                'createdAt' => date('Y-m-d H:i:s'),
            ]);
            sandip_storage_write(SANDIP_NOTICES_STORE, $items);
            $statusMessage = 'Notice published successfully.';
        }
    } elseif ($action === 'add_event') {
        $day = trim((string)($_POST['day'] ?? ''));
        $month = trim((string)($_POST['month'] ?? ''));
        $title = trim((string)($_POST['title'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($day === '' || $month === '' || $title === '' || $message === '') {
            $errorMessage = 'Please complete all event fields.';
        } else {
            $items = sandip_storage_read(SANDIP_EVENTS_STORE, []);
            array_unshift($items, [
                'id' => 'EVENT' . random_int(100, 999),
                'day' => $day,
                'month' => $month,
                'title' => $title,
                'message' => $message,
                'createdAt' => date('Y-m-d H:i:s'),
            ]);
            sandip_storage_write(SANDIP_EVENTS_STORE, $items);
            $statusMessage = 'Event added successfully.';
        }
    } elseif ($action === 'add_result') {
        $rollNumber = trim((string)($_POST['rollNumber'] ?? ''));
        $studentName = trim((string)($_POST['studentName'] ?? ''));
        $semester = trim((string)($_POST['semester'] ?? ''));
        $status = trim((string)($_POST['status'] ?? ''));
        $percentage = trim((string)($_POST['percentage'] ?? ''));

        if ($rollNumber === '' || $studentName === '' || $semester === '' || $status === '' || $percentage === '') {
            $errorMessage = 'Please complete all result fields.';
        } else {
            $items = sandip_storage_read(SANDIP_RESULTS_STORE, []);
            array_unshift($items, [
                'id' => 'RESULT' . random_int(100, 999),
                'rollNumber' => $rollNumber,
                'studentName' => $studentName,
                'semester' => $semester,
                'status' => $status,
                'percentage' => $percentage,
                'publishedAt' => date('Y-m-d'),
            ]);
            sandip_storage_write(SANDIP_RESULTS_STORE, $items);
            $statusMessage = 'Result published successfully.';
        }
    }
}

$notices = array_slice(sandip_storage_read(SANDIP_NOTICES_STORE, []), 0, 6);
$events = array_slice(sandip_storage_read(SANDIP_EVENTS_STORE, []), 0, 6);
$results = array_slice(sandip_storage_read(SANDIP_RESULTS_STORE, []), 0, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Sandip Foundation Shri Ram Polytechnic</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="college-style.css" />
</head>
<body class="page-admin">
  <header class="subpage-hero">
    <nav class="navbar container">
      <a class="brand-mark" href="indexx.html" aria-label="Home">
        <img src="logo.png" alt="Sandip Foundation Shri Ram Polytechnic logo" />
        <span class="brand-copy">
          <strong>Sandip Foundation Shri Ram Polytechnic</strong>
          <small>Admin Dashboard</small>
        </span>
      </a>
      <ul class="menu menu-static">
        <li><a href="indexx.html">Home</a></li>
        <li><a href="admin-records.php">Admission Records</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <div class="container subpage-copy">
      <p class="tag">Admin Portal</p>
      <h1>Manage notices, events and student results.</h1>
      <p>Use this dashboard to keep the homepage content and result lookup dynamic.</p>
    </div>
  </header>

  <main>
    <section class="section container">
      <?php if ($statusMessage !== ''): ?>
        <div class="status-banner success-banner"><?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>
      <?php if ($errorMessage !== ''): ?>
        <div class="status-banner error-banner"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <div class="updates-grid">
        <form class="card" method="post" action="admin-dashboard.php">
          <input type="hidden" name="action" value="add_notice" />
          <p class="mini-tag">Publish Notice</p>
          <div class="form-grid">
            <label>
              Label
              <input type="text" name="label" placeholder="Important / Scholarship" />
            </label>
            <label>
              Author
              <input type="text" name="author" placeholder="Admission Cell" required />
            </label>
            <label class="full-width">
              Title
              <input type="text" name="title" placeholder="Notice title" required />
            </label>
            <label class="full-width">
              Message
              <textarea name="message" rows="4" placeholder="Notice message" required></textarea>
            </label>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn">Publish Notice</button>
          </div>
        </form>

        <form class="card" method="post" action="admin-dashboard.php">
          <input type="hidden" name="action" value="add_event" />
          <p class="mini-tag">Add Event</p>
          <div class="form-grid">
            <label>
              Day
              <input type="text" name="day" placeholder="12" required />
            </label>
            <label>
              Month
              <input type="text" name="month" placeholder="May" required />
            </label>
            <label class="full-width">
              Title
              <input type="text" name="title" placeholder="Event title" required />
            </label>
            <label class="full-width">
              Message
              <textarea name="message" rows="4" placeholder="Event message" required></textarea>
            </label>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn">Add Event</button>
          </div>
        </form>

        <form class="card" method="post" action="admin-dashboard.php">
          <input type="hidden" name="action" value="add_result" />
          <p class="mini-tag">Publish Result</p>
          <div class="form-grid">
            <label>
              Roll Number
              <input type="text" name="rollNumber" placeholder="SRP1001" required />
            </label>
            <label>
              Student Name
              <input type="text" name="studentName" placeholder="Student name" required />
            </label>
            <label>
              Semester
              <select name="semester" required>
                <option value="">Select semester</option>
                <option>Semester 1</option>
                <option>Semester 2</option>
                <option>Semester 3</option>
                <option>Semester 4</option>
                <option>Semester 5</option>
                <option>Semester 6</option>
              </select>
            </label>
            <label>
              Status
              <input type="text" name="status" placeholder="Pass / Reappear" required />
            </label>
            <label class="full-width">
              Percentage
              <input type="text" name="percentage" placeholder="78%" required />
            </label>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn">Publish Result</button>
          </div>
        </form>
      </div>

      <div class="section-heading-block" style="margin-top: 2rem;">
        <p class="mini-tag">Recent Content</p>
        <h2>Latest notices, events and results</h2>
      </div>

      <div class="updates-grid">
        <article class="card">
          <h3>Notices</h3>
          <div class="feature-stack">
            <?php foreach ($notices as $notice): ?>
              <article>
                <strong><?php echo htmlspecialchars((string)($notice['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                <span><?php echo htmlspecialchars((string)($notice['author'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </article>
        <article class="card">
          <h3>Events</h3>
          <div class="feature-stack">
            <?php foreach ($events as $event): ?>
              <article>
                <strong><?php echo htmlspecialchars((string)($event['day'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars((string)($event['month'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                <span><?php echo htmlspecialchars((string)($event['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </article>
        <article class="card">
          <h3>Results</h3>
          <div class="feature-stack">
            <?php foreach ($results as $result): ?>
              <article>
                <strong><?php echo htmlspecialchars((string)($result['rollNumber'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                <span><?php echo htmlspecialchars((string)($result['semester'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> | <?php echo htmlspecialchars((string)($result['status'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </article>
      </div>
    </section>
  </main>
</body>
</html>
