<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

sandip_start_session();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input') ?: '[]', true);
$payload = is_array($payload) ? $payload : [];
$action = (string)($payload['action'] ?? '');

if ($action === 'student_signup') {
    $name = trim((string)($payload['name'] ?? ''));
    $mobile = trim((string)($payload['mobile'] ?? ''));
    $email = trim((string)($payload['email'] ?? ''));
    $password = trim((string)($payload['password'] ?? ''));

    if ($name === '' || $mobile === '' || $email === '' || $password === '') {
        http_response_code(422);
        echo json_encode(['ok' => false, 'message' => 'Please complete all signup fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    if (sandip_find_user_by_email($email)) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'A student account already exists with this email.']);
        exit;
    }

    $user = [
        'id' => 'STU' . random_int(10000, 99999),
        'name' => $name,
        'mobile' => $mobile,
        'email' => $email,
        'role' => 'student',
        'passwordHash' => password_hash($password, PASSWORD_DEFAULT),
        'createdAt' => date('Y-m-d H:i:s'),
    ];

    sandip_save_user($user);
    $_SESSION['sandip_user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    echo json_encode([
        'ok' => true,
        'message' => 'Student account created successfully.',
        'redirect' => 'student-dashboard.php',
    ]);
    exit;
}

if ($action === 'student_login') {
    $email = trim((string)($payload['email'] ?? ''));
    $password = trim((string)($payload['password'] ?? ''));

    $user = sandip_find_user_by_email($email);
    if (!$user || ($user['role'] ?? '') !== 'student' || !password_verify($password, (string)($user['passwordHash'] ?? ''))) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'message' => 'Invalid student login details.']);
        exit;
    }

    $_SESSION['sandip_user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    echo json_encode([
        'ok' => true,
        'message' => 'Student login successful.',
        'redirect' => 'student-dashboard.php',
    ]);
    exit;
}

if ($action === 'admin_login') {
    $id = trim((string)($payload['id'] ?? ''));
    $password = trim((string)($payload['password'] ?? ''));

    $user = sandip_find_admin_by_id($id);
    if (!$user || !password_verify($password, (string)($user['passwordHash'] ?? ''))) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'message' => 'Invalid admin login details.']);
        exit;
    }

    $_SESSION['sandip_user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    echo json_encode([
        'ok' => true,
        'message' => 'Admin login successful.',
        'redirect' => 'admin-dashboard.php',
    ]);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'message' => 'Unsupported action.']);
