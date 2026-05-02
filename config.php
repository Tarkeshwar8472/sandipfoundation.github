<?php
declare(strict_types=1);

const SANDIP_DB_HOST = '127.0.0.1';
const SANDIP_DB_PORT = '3306';
const SANDIP_DB_NAME = 'sandip_foundation';
const SANDIP_DB_USER = 'root';
const SANDIP_DB_PASS = '';
const SANDIP_JSON_STORE = __DIR__ . '/storage/admissions.json';
const SANDIP_USERS_STORE = __DIR__ . '/storage/users.json';
const SANDIP_NOTICES_STORE = __DIR__ . '/storage/notices.json';
const SANDIP_EVENTS_STORE = __DIR__ . '/storage/events.json';
const SANDIP_RESULTS_STORE = __DIR__ . '/storage/results.json';

function sandip_get_db_connection(): ?PDO
{
    try {
        $dsn = 'mysql:host=' . SANDIP_DB_HOST . ';port=' . SANDIP_DB_PORT . ';dbname=' . SANDIP_DB_NAME . ';charset=utf8mb4';
        return new PDO($dsn, SANDIP_DB_USER, SANDIP_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $exception) {
        return null;
    }
}

function sandip_read_json_records(): array
{
    if (!file_exists(SANDIP_JSON_STORE)) {
        return [];
    }

    $content = file_get_contents(SANDIP_JSON_STORE);
    if ($content === false || trim($content) === '') {
        return [];
    }

    $decoded = json_decode($content, true);
    return is_array($decoded) ? $decoded : [];
}

function sandip_save_json_record(array $record): void
{
    $directory = dirname(SANDIP_JSON_STORE);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $records = sandip_read_json_records();
    array_unshift($records, $record);
    file_put_contents(SANDIP_JSON_STORE, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function sandip_start_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function sandip_storage_read(string $path, array $default = []): array
{
    if (!file_exists($path)) {
        return $default;
    }

    $content = file_get_contents($path);
    if ($content === false || trim($content) === '') {
        return $default;
    }

    $decoded = json_decode($content, true);
    return is_array($decoded) ? $decoded : $default;
}

function sandip_storage_write(string $path, array $data): void
{
    $directory = dirname($path);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function sandip_seed_defaults(): void
{
    $users = sandip_storage_read(SANDIP_USERS_STORE, []);
    if ($users === []) {
        $users = [
            [
                'id' => 'ADMIN001',
                'name' => 'Admission Admin',
                'email' => 'admin@sandipfoundation.edu.in',
                'mobile' => '9999999999',
                'role' => 'admin',
                'passwordHash' => password_hash('admin123', PASSWORD_DEFAULT),
                'createdAt' => date('Y-m-d H:i:s'),
            ],
        ];
        sandip_storage_write(SANDIP_USERS_STORE, $users);
    }

    $notices = sandip_storage_read(SANDIP_NOTICES_STORE, []);
    if ($notices === []) {
        sandip_storage_write(SANDIP_NOTICES_STORE, [
            [
                'id' => 'NOTICE001',
                'label' => 'Important',
                'title' => 'Admissions Open for Session 2026-28',
                'message' => 'Applications are active for diploma and selected B.Tech programs. Students should complete the online admission form early.',
                'author' => 'Admission Cell',
                'createdAt' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 'NOTICE002',
                'label' => 'Scholarship Notice',
                'title' => 'Merit and Need-Based Support Window',
                'message' => 'Eligible students can submit scholarship interest details during admission verification for review.',
                'author' => 'Academic Support Desk',
                'createdAt' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    $events = sandip_storage_read(SANDIP_EVENTS_STORE, []);
    if ($events === []) {
        sandip_storage_write(SANDIP_EVENTS_STORE, [
            [
                'id' => 'EVENT001',
                'day' => '12',
                'month' => 'May',
                'title' => 'Admission Guidance Seminar',
                'message' => 'Parents and students can meet the admission team for course guidance, fee discussion and eligibility support.',
                'createdAt' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 'EVENT002',
                'day' => '25',
                'month' => 'May',
                'title' => 'Placement Awareness Session',
                'message' => 'Faculty and placement mentors introduce career planning, recruiter expectations and industry preparation.',
                'createdAt' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    $results = sandip_storage_read(SANDIP_RESULTS_STORE, []);
    if ($results === []) {
        sandip_storage_write(SANDIP_RESULTS_STORE, [
            [
                'id' => 'RESULT001',
                'rollNumber' => 'SRP1001',
                'studentName' => 'Demo Student',
                'semester' => 'Semester 1',
                'status' => 'Pass',
                'percentage' => '78%',
                'publishedAt' => date('Y-m-d'),
            ],
        ]);
    }
}

function sandip_find_user_by_email(string $email): ?array
{
    $users = sandip_storage_read(SANDIP_USERS_STORE, []);
    foreach ($users as $user) {
        if (strcasecmp((string)($user['email'] ?? ''), $email) === 0) {
            return $user;
        }
    }

    return null;
}

function sandip_find_admin_by_id(string $id): ?array
{
    $users = sandip_storage_read(SANDIP_USERS_STORE, []);
    foreach ($users as $user) {
        if (($user['role'] ?? '') === 'admin' && strcasecmp((string)($user['id'] ?? ''), $id) === 0) {
            return $user;
        }
    }

    return null;
}

function sandip_save_user(array $user): void
{
    $users = sandip_storage_read(SANDIP_USERS_STORE, []);
    array_unshift($users, $user);
    sandip_storage_write(SANDIP_USERS_STORE, $users);
}

function sandip_require_admin(): void
{
    sandip_start_session();
    if (($_SESSION['sandip_user']['role'] ?? '') !== 'admin') {
        header('Location: indexx.html');
        exit;
    }
}

sandip_seed_defaults();
