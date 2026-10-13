<?php
require_once BASE_PATH . '/models/Patient.php';
require_once BASE_PATH . '/models/Appointment.php';
require_once BASE_PATH . '/services/AuthService.php';

class PatientController {
    private $patientModel;
    private $appointmentModel;
    private $userId;

    public function __construct() {
        AuthService::requireRole('patient');
        $this->patientModel = new Patient();
        $this->appointmentModel = new Appointment();
        $this->userId = $_SESSION['user_id'];
    }

    public function dashboard() {
        $patient = $this->patientModel->findByUserId($this->userId);
        $nextAppointment = $this->appointmentModel->getNextUpcomingByPatientId($patient['patient_id']);
        
        $current_page = 'dashboard';
        require_once VIEWS_PATH . '/patient/dashboard.php';
    }

    public function notifications() {
        $patient = $this->patientModel->findByUserId($this->userId);
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT n.*, a.appointment_date, a.appointment_time 
            FROM notifications n
            JOIN appointments a ON n.appointment_id = a.appointment_id
            WHERE a.patient_id = ?
            ORDER BY n.scheduled_at DESC
        ");
        $stmt->execute([$patient['patient_id']]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $current_page = 'notifications';
        require_once VIEWS_PATH . '/patient/notifications.php';
    }

    public function doctors() {
        $patient = $this->patientModel->findByUserId($this->userId);
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT p.*, d.name as department_name, u.email 
            FROM practitioners p 
            JOIN users u ON p.user_id = u.user_id 
            LEFT JOIN departments d ON p.department_id = d.department_id
        ");
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $current_page = 'doctors';
        require_once VIEWS_PATH . '/patient/doctors.php';
    }

    public function records() {
        $patient = $this->patientModel->findByUserId($this->userId);
        
        $db = Database::getInstance()->getConnection();
        
        // Fetch dental record
        $stmt = $db->prepare("SELECT * FROM dental_records WHERE patient_id = ?");
        $stmt->execute([$patient['patient_id']]);
        $dental_record = $stmt->fetch(PDO::FETCH_ASSOC);

        $consultations = [];
        if ($dental_record) {
            $stmt = $db->prepare("
                SELECT c.*, p.full_name as practitioner_name 
                FROM consultation_notes c
                LEFT JOIN practitioners p ON c.practitioner_id = p.practitioner_id
                WHERE c.record_id = ?
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([$dental_record['record_id']]);
            $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $current_page = 'records';
        require_once VIEWS_PATH . '/patient/records.php';
    }

    public function profile() {
        $patient = $this->patientModel->findByUserId($this->userId);
        $current_page = 'profile';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            AuthService::validateCSRF($_POST['csrf_token'] ?? '');
            
            // Handle Profile Picture Upload
            $profilePicturePath = $patient['profile_picture']; // keep old by default
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = BASE_PATH . '/public/assets/uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = uniqid('prof_') . '_' . basename($_FILES['profile_picture']['name']);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                    $profilePicturePath = 'assets/uploads/profiles/' . $filename;
                    // Delete old picture if exists and not default
                    if ($patient['profile_picture'] && file_exists(BASE_PATH . '/public/' . $patient['profile_picture'])) {
                        unlink(BASE_PATH . '/public/' . $patient['profile_picture']);
                    }
                }
            }

            $data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'date_of_birth' => $_POST['date_of_birth'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'emergency_contact_name' => trim($_POST['emergency_contact_name'] ?? ''),
                'emergency_contact_phone' => trim($_POST['emergency_contact_phone'] ?? ''),
                'profile_picture' => $profilePicturePath
            ];

            if ($this->patientModel->updateProfile($this->userId, $data)) {
                $success = "Profile updated successfully.";
                // Refresh data
                $patient = $this->patientModel->findByUserId($this->userId);
            } else {
                $error = "Failed to update profile.";
            }
        }

        $csrf_token = AuthService::generateCSRF();
        require_once VIEWS_PATH . '/patient/profile.php';
    }
}
