<?php

class Notification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Log a new notification attempt.
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (appointment_id, type, recipient_email, message, status, scheduled_at, sent_at) 
            VALUES (:appointment_id, :type, :recipient_email, :message, :status, :scheduled_at, :sent_at)
        ");
        
        $stmt->execute([
            'appointment_id' => $data['appointment_id'] ?? null,
            'type' => $data['type'],
            'recipient_email' => $data['recipient_email'] ?? '',
            'message' => $data['message'] ?? '',
            'status' => $data['status'] ?? 'pending',
            'scheduled_at' => $data['scheduled_at'] ?? date('Y-m-d H:i:s'),
            'sent_at' => $data['status'] === 'sent' ? date('Y-m-d H:i:s') : null
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Update the status of an existing notification log.
     */
    public function updateStatus($id, $status) {
        $sentAt = ($status === 'sent') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->db->prepare("
            UPDATE notifications 
            SET status = :status, sent_at = :sent_at 
            WHERE notification_id = :id
        ");
        return $stmt->execute([
            'status' => $status,
            'sent_at' => $sentAt,
            'id' => $id
        ]);
    }

    /**
     * Find pending reminders that haven't been sent yet.
     * We look for appointments in the next ~24 hours that don't have a 'sent' or 'pending' reminder.
     */
    public function findAppointmentsNeedingReminders() {
        // Appointments between 23 and 25 hours from now
        $startTime = date('Y-m-d H:i:s', strtotime('+23 hours'));
        $endTime = date('Y-m-d H:i:s', strtotime('+25 hours'));

        $stmt = $this->db->prepare("
            SELECT a.*, p.user_id, p.full_name AS patient_name, u.email,
                   prac.full_name AS practitioner_name, d.name AS department_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            JOIN users u ON p.user_id = u.user_id
            JOIN practitioners prac ON a.practitioner_id = prac.practitioner_id
            JOIN departments d ON prac.department_id = d.department_id
            WHERE a.status = 'confirmed'
              AND CONCAT(a.appointment_date, ' ', a.appointment_time) BETWEEN :start_time AND :end_time
              AND NOT EXISTS (
                  SELECT 1 FROM notifications n 
                  WHERE n.appointment_id = a.appointment_id 
                    AND n.type = 'reminder_24h' 
                    AND n.status IN ('sent', 'pending')
              )
        ");
        $stmt->execute([
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
        return $stmt->fetchAll();
    }
}
