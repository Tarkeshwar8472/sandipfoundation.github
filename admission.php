<?php
require_once __DIR__ . '/config.php';

$formValues = [
    'studentName' => '',
    'mobile' => '',
    'dob' => '',
    'program' => '',
    'branch' => '',
    'city' => '',
    'state' => '',
    'fatherName' => '',
    'motherName' => '',
    'email' => '',
    'gender' => '',
    'category' => '',
    'lastQualification' => '',
    'tenthMarks' => '',
    'twelfthMarks' => '',
    'address' => '',
    'hostelRequired' => '',
    'parentMobile' => '',
    'whatsapp' => '',
    'sessionYear' => '2026-27',
];

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formValues as $key => $value) {
        $formValues[$key] = trim((string)($_POST[$key] ?? ''));
    }

    $requiredFields = ['studentName', 'mobile', 'dob', 'program', 'branch', 'city', 'state', 'fatherName', 'motherName', 'email', 'gender', 'tenthMarks', 'address'];
    foreach ($requiredFields as $field) {
        if ($formValues[$field] === '') {
            $errorMessage = 'Please complete all required fields before submitting the admission record.';
            break;
        }
    }

    if ($errorMessage === '' && !filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address.';
    }

    if ($errorMessage === '') {
        $record = $formValues;
        $record['createdAt'] = date('Y-m-d H:i:s');

        try {
            $pdo = sandip_get_db_connection();

            if ($pdo instanceof PDO) {
                $statement = $pdo->prepare(
                    'INSERT INTO admissions (
                        student_name, mobile, dob, program_name, branch_name, city_name, state_name,
                        father_name, mother_name, email_address, gender, category_name, last_qualification,
                        tenth_marks, twelfth_marks, address_text, hostel_required, parent_mobile, whatsapp_number,
                        session_year, created_at
                    ) VALUES (
                        :student_name, :mobile, :dob, :program_name, :branch_name, :city_name, :state_name,
                        :father_name, :mother_name, :email_address, :gender, :category_name, :last_qualification,
                        :tenth_marks, :twelfth_marks, :address_text, :hostel_required, :parent_mobile, :whatsapp_number,
                        :session_year, :created_at
                    )'
                );

                $statement->execute([
                    ':student_name' => $record['studentName'],
                    ':mobile' => $record['mobile'],
                    ':dob' => $record['dob'],
                    ':program_name' => $record['program'],
                    ':branch_name' => $record['branch'],
                    ':city_name' => $record['city'],
                    ':state_name' => $record['state'],
                    ':father_name' => $record['fatherName'],
                    ':mother_name' => $record['motherName'],
                    ':email_address' => $record['email'],
                    ':gender' => $record['gender'],
                    ':category_name' => $record['category'],
                    ':last_qualification' => $record['lastQualification'],
                    ':tenth_marks' => $record['tenthMarks'],
                    ':twelfth_marks' => $record['twelfthMarks'],
                    ':address_text' => $record['address'],
                    ':hostel_required' => $record['hostelRequired'],
                    ':parent_mobile' => $record['parentMobile'],
                    ':whatsapp_number' => $record['whatsapp'],
                    ':session_year' => $record['sessionYear'],
                    ':created_at' => $record['createdAt'],
                ]);
            }

            sandip_save_json_record($record);
            $successMessage = 'Admission record has been submitted successfully.';

            foreach ($formValues as $key => $value) {
                $formValues[$key] = $key === 'sessionYear' ? '2026-27' : '';
            }
        } catch (Throwable $exception) {
            $errorMessage = 'The record could not be saved. Please check the backend configuration and try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="description"
    content="Sandip Foundation Shri Ram Polytechnic final admission page with backend record storage and admin-ready workflow."
  />
  <title>Sandip Foundation Shri Ram Polytechnic | Final Admission</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="college-style.css" />
</head>
<body class="page-admission">
  <header class="subpage-hero">
    <nav class="navbar container">
      <a class="brand-mark" href="indexx.html" aria-label="Sandip Foundation Shri Ram Polytechnic home">
        <img src="logo.png" alt="Sandip Foundation Shri Ram Polytechnic logo" />
        <span class="brand-copy">
          <strong>Sandip Foundation Shri Ram Polytechnic</strong>
          <small>Step 3 of 3 | Final Admission Form</small>
        </span>
      </a>
      <ul class="menu menu-static">
        <li><a href="indexx.html">Page 1</a></li>
        <li><a href="college-details.html">Page 2</a></li>
        <li><a href="student-dashboard.php">Student Portal</a></li>
        <li><a href="admin-records.php">Admin Records</a></li>
      </ul>
    </nav>

    <div class="container subpage-copy">
      <p class="tag">Step 3 of 3 | Backend Submission</p>
      <h1>Complete the final admission form and store the record through the backend.</h1>
      <p id="prefillMessage">Basic details from the first page can be carried here and the final record will be stored for the admin side.</p>
    </div>
  </header>

  <main>
    <section class="section container">
      <div class="admission-top-grid">
        <article class="card" data-reveal>
          <p class="mini-tag">Backend Ready</p>
          <h2>Admission records are prepared for PHP, MySQL and admin review.</h2>
          <p>
            This form is designed for server-side submission. Records can be stored in MySQL and also mirrored
            into a local JSON file for quick local inspection.
          </p>
        </article>
        <article class="card" data-reveal>
          <p class="mini-tag">Admin Access</p>
          <h3>View Saved Records</h3>
          <p>The admin page lists admission records stored by the backend so the office can review submissions.</p>
          <a class="btn" href="admin-records.php">Open Admin Records</a>
        </article>
      </div>

      <?php if ($successMessage !== ''): ?>
        <div class="status-banner success-banner" data-reveal><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <?php if ($errorMessage !== ''): ?>
        <div class="status-banner error-banner" data-reveal><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <div class="admission-page-layout">
        <form class="card admission-form-panel" method="post" action="admission.php" data-reveal>
          <div class="section-heading">
            <p class="mini-tag">Admission Form</p>
            <h2>Complete student and parent details</h2>
          </div>

          <div class="form-grid">
            <label>
              Student Name <span class="required-mark">*</span>
              <input id="studentNameField" type="text" name="studentName" value="<?php echo htmlspecialchars($formValues['studentName'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Mobile Number <span class="required-mark">*</span>
              <input id="mobileField" type="tel" name="mobile" value="<?php echo htmlspecialchars($formValues['mobile'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Date of Birth <span class="required-mark">*</span>
              <input id="dobField" type="date" name="dob" value="<?php echo htmlspecialchars($formValues['dob'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Program <span class="required-mark">*</span>
              <input id="programField" type="text" name="program" value="<?php echo htmlspecialchars($formValues['program'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Branch <span class="required-mark">*</span>
              <input id="branchField" type="text" name="branch" value="<?php echo htmlspecialchars($formValues['branch'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              City <span class="required-mark">*</span>
              <input id="cityField" type="text" name="city" value="<?php echo htmlspecialchars($formValues['city'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              State <span class="required-mark">*</span>
              <input id="stateField" type="text" name="state" value="<?php echo htmlspecialchars($formValues['state'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Father's Name <span class="required-mark">*</span>
              <input type="text" name="fatherName" value="<?php echo htmlspecialchars($formValues['fatherName'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Mother's Name <span class="required-mark">*</span>
              <input type="text" name="motherName" value="<?php echo htmlspecialchars($formValues['motherName'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Email Address <span class="required-mark">*</span>
              <input type="email" name="email" value="<?php echo htmlspecialchars($formValues['email'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              Gender <span class="required-mark">*</span>
              <select name="gender" required>
                <option value="">Select gender</option>
                <option value="Male" <?php echo $formValues['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $formValues['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $formValues['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
              </select>
            </label>
            <label>
              Category
              <select name="category">
                <option value="">Select category</option>
                <option value="General" <?php echo $formValues['category'] === 'General' ? 'selected' : ''; ?>>General</option>
                <option value="OBC" <?php echo $formValues['category'] === 'OBC' ? 'selected' : ''; ?>>OBC</option>
                <option value="SC" <?php echo $formValues['category'] === 'SC' ? 'selected' : ''; ?>>SC</option>
                <option value="ST" <?php echo $formValues['category'] === 'ST' ? 'selected' : ''; ?>>ST</option>
                <option value="EWS" <?php echo $formValues['category'] === 'EWS' ? 'selected' : ''; ?>>EWS</option>
              </select>
            </label>
            <label>
              10th Percentage <span class="required-mark">*</span>
              <input type="text" name="tenthMarks" value="<?php echo htmlspecialchars($formValues['tenthMarks'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </label>
            <label>
              12th or Last Qualification
              <input type="text" name="lastQualification" value="<?php echo htmlspecialchars($formValues['lastQualification'], ENT_QUOTES, 'UTF-8'); ?>" />
            </label>
            <label>
              12th Percentage
              <input type="text" name="twelfthMarks" value="<?php echo htmlspecialchars($formValues['twelfthMarks'], ENT_QUOTES, 'UTF-8'); ?>" />
            </label>
            <label>
              Parent Mobile
              <input type="tel" name="parentMobile" value="<?php echo htmlspecialchars($formValues['parentMobile'], ENT_QUOTES, 'UTF-8'); ?>" />
            </label>
            <label>
              WhatsApp Number
              <input type="tel" name="whatsapp" value="<?php echo htmlspecialchars($formValues['whatsapp'], ENT_QUOTES, 'UTF-8'); ?>" />
            </label>
            <label>
              Hostel Required
              <select name="hostelRequired">
                <option value="">Select option</option>
                <option value="Yes" <?php echo $formValues['hostelRequired'] === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                <option value="No" <?php echo $formValues['hostelRequired'] === 'No' ? 'selected' : ''; ?>>No</option>
              </select>
            </label>
            <label>
              Session
              <input type="text" name="sessionYear" value="<?php echo htmlspecialchars($formValues['sessionYear'], ENT_QUOTES, 'UTF-8'); ?>" />
            </label>
            <label class="full-width">
              Full Address <span class="required-mark">*</span>
              <textarea name="address" rows="4" required><?php echo htmlspecialchars($formValues['address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </label>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn">Submit Admission Record</button>
            <a class="btn btn-light" href="college-details.html">Back to Page 2</a>
          </div>
        </form>

        <aside class="card preview-card" data-reveal>
          <div class="section-heading">
            <p class="mini-tag">Final Preview</p>
            <h3>Student summary before backend submission</h3>
          </div>
          <div id="admissionPreview" class="preview-placeholder">
            The complete preview appears here while the admission form is being filled.
          </div>
        </aside>
      </div>
    </section>
  </main>

  <footer>
    <div class="container footer-wrap">
      <p>&copy; <span id="current-year">2026</span> Sandip Foundation Shri Ram Polytechnic. All rights reserved.</p>
      <a href="admin-records.php">Admin Records</a>
    </div>
  </footer>

  <script>
    const admissionForm = document.querySelector(".admission-form-panel");
    const admissionPreview = document.getElementById("admissionPreview");
    const prefillMessage = document.getElementById("prefillMessage");
    const basicStorageKey = "sandipStudentBasicProfile";
    const savedProfile = JSON.parse(sessionStorage.getItem(basicStorageKey) || "null");

    if (savedProfile) {
      const map = {
        studentName: "studentNameField",
        mobile: "mobileField",
        dob: "dobField",
        program: "programField",
        branch: "branchField",
        city: "cityField",
        state: "stateField"
      };

      Object.entries(map).forEach(([key, id]) => {
        const field = document.getElementById(id);
        if (field && !field.value && savedProfile[key]) {
          field.value = savedProfile[key];
        }
      });

      if (savedProfile.studentName) {
        prefillMessage.textContent = `${savedProfile.studentName}, your basic details were brought from the first page. Complete the final admission form and submit the backend record.`;
      }
    }

    const updateAdmissionPreview = () => {
      const values = Object.fromEntries(new FormData(admissionForm).entries());
      const hasContent = Object.values(values).some((value) => value && value.trim() !== "");

      if (!hasContent) {
        admissionPreview.className = "preview-placeholder";
        admissionPreview.textContent = "The complete preview appears here while the admission form is being filled.";
        return;
      }

      admissionPreview.className = "preview-list";
      admissionPreview.innerHTML = `
        <div><strong>Student Name:</strong> ${values.studentName || "-"}</div>
        <div><strong>Program:</strong> ${values.program || "-"}</div>
        <div><strong>Branch:</strong> ${values.branch || "-"}</div>
        <div><strong>City / State:</strong> ${values.city || "-"} / ${values.state || "-"}</div>
        <div><strong>Parents:</strong> ${values.fatherName || "-"} / ${values.motherName || "-"}</div>
        <div><strong>Email:</strong> ${values.email || "-"}</div>
        <div><strong>10th Percentage:</strong> ${values.tenthMarks || "-"}</div>
        <div><strong>Hostel Required:</strong> ${values.hostelRequired || "-"}</div>
        <div><strong>Session:</strong> ${values.sessionYear || "-"}</div>
        <div><strong>Address:</strong> ${values.address || "-"}</div>
      `;
    };

    admissionForm.addEventListener("input", updateAdmissionPreview);
    updateAdmissionPreview();

    const currentYear = document.getElementById("current-year");
    if (currentYear) {
      currentYear.textContent = new Date().getFullYear();
    }
  </script>
</body>
</html>
