<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

$notices = array_slice(sandip_storage_read(SANDIP_NOTICES_STORE, []), 0, 6);
$events = array_slice(sandip_storage_read(SANDIP_EVENTS_STORE, []), 0, 6);

echo json_encode([
    'ok' => true,
    'notices' => $notices,
    'events' => $events,
]);
