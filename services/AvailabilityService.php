<?php

class AvailabilityService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAvailableSlots($practitionerId, $dateStr) {
        $timestamp = strtotime($dateStr);
        if (!$timestamp) return [];

        $dayOfWeek = strtolower(date('D', $timestamp)); // 'mon', 'tue', etc.

        // 1. Get practitioner availability for this day
        $stmt = $this->db->prepare("
            SELECT start_time, end_time, slot_length_mins 
            FROM practitioner_availability 
            WHERE practitioner_id = :pid AND day_of_week = :day
        ");
        $stmt->execute(['pid' => $practitionerId, 'day' => $dayOfWeek]);
        $availability = $stmt->fetch();

        if (!$availability) return []; // Not working this day

        $startTimeStr = $availability['start_time'];
        $endTimeStr = $availability['end_time'];
        $slotLength = (int)$availability['slot_length_mins'];

        // 2. Generate all possible slots
        $slots = [];
        $current = strtotime("$dateStr $startTimeStr");
        $end = strtotime("$dateStr $endTimeStr");

        // Prevent booking in the past
        $now = time();

        while ($current + ($slotLength * 60) <= $end) {
            if ($current > $now) {
                $slots[] = date('H:i:s', $current);
            }
            $current += ($slotLength * 60);
        }

        // 3. Get existing bookings
        $bookedStmt = $this->db->prepare("
            SELECT appointment_time 
            FROM appointments 
            WHERE practitioner_id = :pid 
              AND appointment_date = :date 
              AND status IN ('pending', 'confirmed')
        ");
        $bookedStmt->execute(['pid' => $practitionerId, 'date' => $dateStr]);
        $bookedSlots = $bookedStmt->fetchAll(PDO::FETCH_COLUMN);

        // 4. Subtract booked from available
        return array_values(array_diff($slots, $bookedSlots));
    }
}
