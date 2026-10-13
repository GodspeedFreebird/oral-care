<?php

require_once BASE_PATH . '/config/database.php';

class ReportController {
    private $db;
    private $role;
    private $userId;
    private $practitionerId = null;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->role = $_SESSION['role'];
        $this->userId = $_SESSION['user_id'];
        $this->db = Database::getInstance()->getConnection();

        if ($this->role === 'practitioner') {
            $stmt = $this->db->prepare("SELECT practitioner_id FROM practitioners WHERE user_id = ? LIMIT 1");
            $stmt->execute([$this->userId]);
            $prac = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($prac) {
                $this->practitionerId = $prac['practitioner_id'];
            }
        } elseif ($this->role !== 'admin' && $this->role !== 'receptionist') {
            die("Access Denied. You do not have permission to view reports.");
        }
    }

    public function index() {
        $reportType = $_GET['type'] ?? 'appointment_summary';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        $export = $_GET['export'] ?? null;

        $data = [];
        $headers = [];

        switch ($reportType) {
            case 'appointment_summary':
                $data = $this->getAppointmentSummary($startDate, $endDate);
                $headers = ['Department', 'Practitioner', 'Status', 'Count'];
                break;
            case 'patient_registration':
                $data = $this->getPatientRegistration($startDate, $endDate);
                $headers = ['Registration Date', 'Gender', 'Count'];
                break;
            case 'workload':
                $data = $this->getWorkloadReport($startDate, $endDate);
                $headers = ['Practitioner', 'Total Appointments', 'Completed', 'Completion Rate (%)'];
                break;
            case 'non_attendance':
                $data = $this->getNonAttendanceReport($startDate, $endDate);
                $headers = ['Practitioner', 'Total Appointments', 'No Shows', 'DNA Rate (%)'];
                break;
            case 'diagnoses':
                $data = $this->getCommonDiagnoses($startDate, $endDate);
                $headers = ['Diagnosis', 'Frequency'];
                break;
        }

        if ($export === 'csv') {
            $this->exportCsv($reportType, $headers, $data);
            exit;
        }

        require_once BASE_PATH . '/views/reports/index.php';
    }

    private function getAppointmentSummary($startDate, $endDate) {
        $query = "
            SELECT d.name as department_name, pr.full_name as practitioner, a.status, COUNT(*) as count 
            FROM appointments a
            JOIN practitioners pr ON a.practitioner_id = pr.practitioner_id
            JOIN departments d ON pr.department_id = d.department_id
            WHERE a.appointment_date BETWEEN ? AND ?
        ";
        $params = [$startDate, $endDate];

        if ($this->role === 'practitioner') {
            $query .= " AND a.practitioner_id = ?";
            $params[] = $this->practitionerId;
        }

        $query .= " GROUP BY d.name, pr.full_name, a.status ORDER BY d.name, pr.full_name";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPatientRegistration($startDate, $endDate) {
        $query = "
            SELECT DATE(p.created_at) as reg_date, p.gender, COUNT(*) as count 
            FROM patients p
        ";
        
        if ($this->role === 'practitioner') {
            $query = "
                SELECT DATE(p.created_at) as reg_date, p.gender, COUNT(DISTINCT p.patient_id) as count 
                FROM patients p
                JOIN appointments a ON p.patient_id = a.patient_id
                WHERE a.practitioner_id = ? AND p.created_at BETWEEN ? AND ?
            ";
            $params = [$this->practitionerId, $startDate . ' 00:00:00', $endDate . ' 23:59:59'];
        } else {
            $query .= " WHERE p.created_at BETWEEN ? AND ?";
            $params = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];
        }

        $query .= " GROUP BY reg_date, p.gender ORDER BY reg_date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getWorkloadReport($startDate, $endDate) {
        $query = "
            SELECT 
                pr.full_name as practitioner,
                COUNT(a.appointment_id) as total_appointments,
                SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) as completed_count
            FROM appointments a
            JOIN practitioners pr ON a.practitioner_id = pr.practitioner_id
            WHERE a.appointment_date BETWEEN ? AND ?
        ";
        $params = [$startDate, $endDate];

        if ($this->role === 'practitioner') {
            $query .= " AND a.practitioner_id = ?";
            $params[] = $this->practitionerId;
        }

        $query .= " GROUP BY pr.full_name";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$row) {
            $row['completion_rate'] = $row['total_appointments'] > 0 
                ? round(($row['completed_count'] / $row['total_appointments']) * 100, 2) 
                : 0;
        }
        return $results;
    }

    private function getNonAttendanceReport($startDate, $endDate) {
        $query = "
            SELECT 
                pr.full_name as practitioner,
                COUNT(a.appointment_id) as total_appointments,
                SUM(CASE WHEN a.status = 'no_show' THEN 1 ELSE 0 END) as no_shows
            FROM appointments a
            JOIN practitioners pr ON a.practitioner_id = pr.practitioner_id
            WHERE a.appointment_date BETWEEN ? AND ?
        ";
        $params = [$startDate, $endDate];

        if ($this->role === 'practitioner') {
            $query .= " AND a.practitioner_id = ?";
            $params[] = $this->practitionerId;
        }

        $query .= " GROUP BY pr.full_name";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$row) {
            $row['dna_rate'] = $row['total_appointments'] > 0 
                ? round(($row['no_shows'] / $row['total_appointments']) * 100, 2) 
                : 0;
        }
        return $results;
    }

    private function getCommonDiagnoses($startDate, $endDate) {
        $query = "
            SELECT cn.diagnosis, COUNT(*) as count
            FROM consultation_notes cn
            JOIN appointments a ON cn.appointment_id = a.appointment_id
            WHERE a.appointment_date BETWEEN ? AND ?
        ";
        $params = [$startDate, $endDate];

        if ($this->role === 'practitioner') {
            $query .= " AND cn.practitioner_id = ?";
            $params[] = $this->practitionerId;
        }

        $query .= " GROUP BY cn.diagnosis ORDER BY count DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function exportCsv($filename, $headers, $data) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '_report_' . date('Ymd') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        foreach ($data as $row) {
            fputcsv($output, array_values($row));
        }
        fclose($output);
    }
}
