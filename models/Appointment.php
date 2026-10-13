<?php
class Appointment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getNextUpcomingByPatientId($patientId) {
        $stmt = $this->db->prepare("
            SELECT a.*, p.full_name AS practitioner_name, p.specialisation, d.name AS department_name
            FROM appointments a
            JOIN practitioners p ON a.practitioner_id = p.practitioner_id
            JOIN departments d ON p.department_id = d.department_id
            WHERE a.patient_id = :patient_id 
              AND a.status IN ('pending', 'confirmed') 
              AND (a.appointment_date > CURDATE() OR (a.appointment_date = CURDATE() AND a.appointment_time >= CURTIME()))
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
            LIMIT 1
        ");
        $stmt->execute(['patient_id' => $patientId]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO appointments 
            (patient_id, practitioner_id, booked_by_user_id, appointment_date, appointment_time, status) 
            VALUES (:patient_id, :practitioner_id, :booked_by, :date, :time, 'pending')
        ");
        
        // This will throw a PDOException if the UNIQUE KEY on (practitioner_id, appointment_date, appointment_time) is violated
        $stmt->execute([
            'patient_id' => $data['patient_id'],
            'practitioner_id' => $data['practitioner_id'],
            'booked_by' => $data['booked_by_user_id'],
            'date' => $data['appointment_date'],
            'time' => $data['appointment_time']
        ]);
        
        return $this->db->lastInsertId();
    }

    public function findByPatientId($patientId) {
        $stmt = $this->db->prepare("
            SELECT a.*, p.full_name AS practitioner_name, d.name AS department_name
            FROM appointments a
            JOIN practitioners p ON a.practitioner_id = p.practitioner_id
            JOIN departments d ON p.department_id = d.department_id
            WHERE a.patient_id = :patient_id
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
        ");
        $stmt->execute(['patient_id' => $patientId]);
        return $stmt->fetchAll();
    }

    public function cancel($appointmentId, $patientId) {
        $stmt = $this->db->prepare("
            UPDATE appointments 
            SET status = 'cancelled' 
            WHERE appointment_id = :id AND patient_id = :patient_id AND status IN ('pending', 'confirmed')
        ");
        return $stmt->execute(['id' => $appointmentId, 'patient_id' => $patientId]);
    }

    public function findById($appointmentId) {
        $stmt = $this->db->prepare("
            SELECT a.*, p.user_id, p.full_name AS patient_name, u.email,
                   prac.full_name AS practitioner_name, d.name AS department_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            JOIN users u ON p.user_id = u.user_id
            JOIN practitioners prac ON a.practitioner_id = prac.practitioner_id
            JOIN departments d ON prac.department_id = d.department_id
            WHERE a.appointment_id = :id
        ");
        $stmt->execute(['id' => $appointmentId]);
        return $stmt->fetch();
    }

    public function findUpcomingAppointments() {
        $stmt = $this->db->prepare("
            SELECT a.*, p.patient_number, p.full_name AS patient_name,
                   prac.full_name AS practitioner_name, d.name AS department_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            JOIN practitioners prac ON a.practitioner_id = prac.practitioner_id
            JOIN departments d ON prac.department_id = d.department_id
            WHERE a.appointment_date >= CURDATE()
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByPractitionerId($practitionerId, $upcomingOnly = true) {
        $dateCondition = $upcomingOnly ? "AND a.appointment_date >= CURDATE()" : "";
        $stmt = $this->db->prepare("
            SELECT a.*, p.patient_number, p.full_name AS patient_name, p.profile_picture,
                   d.name AS department_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            JOIN practitioners prac ON a.practitioner_id = prac.practitioner_id
            JOIN departments d ON prac.department_id = d.department_id
            WHERE a.practitioner_id = :practitioner_id $dateCondition
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
        ");
        $stmt->execute(['practitioner_id' => $practitionerId]);
        return $stmt->fetchAll();
    }

    public function findByPractitionerIdAndDateRange($practitionerId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT a.*, p.patient_number, p.full_name AS patient_name, p.profile_picture,
                   d.name AS department_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            JOIN practitioners prac ON a.practitioner_id = prac.practitioner_id
            JOIN departments d ON prac.department_id = d.department_id
            WHERE a.practitioner_id = :practitioner_id 
              AND a.appointment_date >= :start_date 
              AND a.appointment_date <= :end_date
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
        ");
        $stmt->execute([
            'practitioner_id' => $practitionerId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        return $stmt->fetchAll();
    }

    public function findAllByDateRange($startDate, $endDate, $practitionerId = null) {
        $sql = "
            SELECT a.*, pat.full_name as patient_name, pat.patient_number, pat.profile_picture,
                   prac.full_name as practitioner_name, dept.name as department_name
            FROM appointments a
            JOIN patients pat ON a.patient_id = pat.patient_id
            JOIN practitioners prac ON a.practitioner_id = prac.practitioner_id
            LEFT JOIN departments dept ON prac.department_id = dept.department_id
            WHERE a.appointment_date BETWEEN ? AND ?
        ";
        
        $params = [$startDate, $endDate];
        
        if ($practitionerId) {
            $sql .= " AND a.practitioner_id = ?";
            $params[] = $practitionerId;
        }
        
        $sql .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
