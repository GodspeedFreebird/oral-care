<?php
require_once __DIR__ . '/public/index.php'; // Or just load environment
require_once BASE_PATH . '/services/AvailabilityService.php';
require_once BASE_PATH . '/config/database.php';

try {
    $service = new AvailabilityService();
    $slots = $service->getAvailableSlots(1, '2026-07-27');
    echo json_encode($slots);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
