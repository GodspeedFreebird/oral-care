<?php

/**
 * send_reminders.php
 * 
 * Standalone script intended to be executed via a scheduled cron job (e.g., every 15 minutes).
 * Queries the database for appointments happening in roughly 24 hours, and sends email reminders.
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/Notification.php';
require_once BASE_PATH . '/services/NotificationService.php';

echo "Starting reminder job: " . date('Y-m-d H:i:s') . "\n";

$notificationModel = new Notification();
$notificationService = new NotificationService();

$appointments = $notificationModel->findAppointmentsNeedingReminders();

if (empty($appointments)) {
    echo "No pending reminders to send at this time.\n";
} else {
    foreach ($appointments as $appointment) {
        echo "Sending reminder for Appointment ID: {$appointment['appointment_id']} to {$appointment['email']}... ";
        
        $success = $notificationService->sendAppointmentEmail(
            'reminder_24h', 
            $appointment, 
            $appointment['email'], 
            $appointment['user_id']
        );

        if ($success) {
            echo "Success!\n";
        } else {
            echo "Failed (Check logs).\n";
        }
    }
}

echo "Reminder job completed.\n";
