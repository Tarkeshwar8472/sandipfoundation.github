<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input') ?: '[]', true);
$payload = is_array($payload) ? $payload : [];

$rollNumber = trim((string)($payload['rollNumber'] ?? ''));
$semester = trim((string)($payload['semester'] ?? ''));

if ($rollNumber === '' || $semester === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Roll number and semester are required.']);
    exit;
}

$results = sandip_storage_read(SANDIP_RESULTS_STORE, []);
foreach ($results as $result) {
    if (
        strcasecmp((string)($result['rollNumber'] ?? ''), $rollNumber) === 0 &&
        strcasecmp((string)($result['semester'] ?? ''), $semester) === 0
    ) {
        echo json_encode([
            'ok' => true,
            'result' => $result,
        ]);
        exit;
    }
}

http_response_code(404);
echo json_encode(['ok' => false, 'message' => 'No result found for this roll number and semester.']);
