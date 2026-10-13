<?php
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/services/AvailabilityService.php';

$service = new AvailabilityService();
$practitionerId = 1;
// July 27, 2026 is a Monday. 
$date = '2026-07-27';

$slots = $service->getAvailableSlots($practitionerId, $date);
print_r($slots);
