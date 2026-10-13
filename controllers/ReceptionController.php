<?php

require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Patient.php';
require_once BASE_PATH . '/models/Appointment.php';
require_once BASE_PATH . '/services/AuthService.php';

class ReceptionController {
    
    private $userModel;
    private $patientModel;
    private $appointmentModel;

    public function __construct() {
        AuthService::requireRole('receptionist');

        $this->userModel = new User();
        $this->patientModel = new Patient();
        $this->appointmentModel = new Appointment();
    }

    public function dashboard() {
        $upcomingAppointments = $this->appointmentModel->findUpcomingAppointments();
        
        require_once BASE_PATH . '/models/Practitioner.php';
        $practitionerModel = new Practitioner();
        $practitioners = $practitionerModel->getAll();
        
        $pendingRequests = array_filter($upcomingAppointments, function($appt) {
            return $appt['status'] === 'pending';
        });
        
        $db = Database::getInstance()->getConnection();
        $stats = [];
        $stats['today_appointments'] = $db->query("SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = CURDATE()")->fetchColumn();
        $stats['total_patients'] = $db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        
        // Fetch patients for booking modal dropdown
        $allPatients = $db->query("SELECT patient_id, full_name, patient_number FROM patients ORDER BY full_name")->fetchAll();
        
        $current_page = 'reception_dashboard';
        require_once VIEWS_PATH . '/reception/dashboard.php';
    }

    public function showRegister() {
        $csrf_token = AuthService::generateCSRF();
        $current_page = 'reception_register';
        require_once VIEWS_PATH . '/reception/register.php';
    }

    public function processRegister() {
        AuthService::validateCSRF($_POST['csrf_token'] ?? '');

        // Gather input
        $fullName = trim($_POST['full_name'] ?? '');
        $dob = $_POST['date_of_birth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        // Enforce default password
        $password = "12345";

        if (empty($fullName) || empty($dob) || empty($gender) || empty($phone) || empty($address) || empty($email) || empty($username)) {
            $errors[] = "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($errors)) {
            if ($this->userModel->findByEmailOrUsername($username)) {
                $errors[] = "Username is already taken.";
            } elseif ($this->userModel->findByEmailOrUsername($email)) {
                $errors[] = "Email is already registered.";
            } else {
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT)
                ];
                
                $patientData = [
                    'full_name' => $fullName,
                    'date_of_birth' => $dob,
                    'gender' => $gender,
                    'phone' => $phone,
                    'address' => $address,
                    'profile_picture' => null
                ];

                $newUserId = $this->userModel->registerPatient($userData, $patientData);

                if ($newUserId) {
                    $this->userModel->logAudit($_SESSION['user_id'], 'receptionist_registered_patient', "Patient ID: $newUserId");
                    
                    // Fetch the patient ID to pass to booking
                    $newPatient = $this->patientModel->findByUserId($newUserId);
                    
                    $_SESSION['success'] = "Walk-in patient registered successfully! You may now book their appointment.";
                    
                    // The user wants to redirect to the book appointment screen for this patient.
                    // To do this, we can redirect to a specific booking route, or pass via session/GET.
                    // Let's redirect to search, but automatically pre-fill or pass the ID.
                    // Wait, AppointmentController->bookAppointmentView() handles receptionist by checking $_POST['patient_id'].
                    // We can just redirect to search and let them click 'Book', OR we can render a form that auto-submits, 
                    // OR we can pass it via session.
                    
                    // Let's use session to pass the pre-selected patient for the book view.
                    $_SESSION['reception_selected_patient_id'] = $newPatient['patient_id'];
                    redirect('reception/appointments/book');
                    return;
                } else {
                    $errors[] = "A system error occurred during registration. Please try again.";
                }
            }
        }

        // Render form with errors
        $csrf_token = AuthService::generateCSRF();
        $current_page = 'reception_register';
        require_once VIEWS_PATH . '/reception/register.php';
    }

    public function searchPatients() {
        $query = trim($_GET['q'] ?? '');
        
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT 
                p.patient_id,
                p.patient_number as id, 
                p.full_name as name, 
                p.gender,
                p.profile_picture,
                TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) as age,
                (
                    SELECT a.reason 
                    FROM appointments a 
                    WHERE a.patient_id = p.patient_id 
                    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                    LIMIT 1
                ) as diagnosis,
                (
                    SELECT d.name 
                    FROM appointments a 
                    JOIN practitioners pr ON a.practitioner_id = pr.practitioner_id
                    JOIN departments d ON pr.department_id = d.department_id
                    WHERE a.patient_id = p.patient_id 
                    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                    LIMIT 1
                ) as doctor_dept,
                (
                    SELECT a.status 
                    FROM appointments a 
                    WHERE a.patient_id = p.patient_id 
                    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                    LIMIT 1
                ) as latest_status
            FROM patients p
            JOIN users u ON p.user_id = u.user_id
        ";
        
        $params = [];
        if (!empty($query)) {
            $sql .= " WHERE p.full_name LIKE :q OR p.patient_number LIKE :q";
            $params['q'] = "%$query%";
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT 50";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        $current_page = 'reception_search';
        require_once VIEWS_PATH . '/reception/search.php';
    }

    public function patientInfo() {
        $patientId = $_GET['id'] ?? null;
        if (!$patientId) {
            $_SESSION['error'] = "No patient selected.";
            redirect('reception/patients/search');
        }
        
        $db = Database::getInstance()->getConnection();
        
        // Fetch patient details
        $stmt = $db->prepare("
            SELECT p.*, u.email,
                   TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) as age
            FROM patients p
            JOIN users u ON p.user_id = u.user_id
            WHERE p.patient_id = :id
        ");
        $stmt->execute(['id' => $patientId]);
        $patient = $stmt->fetch();
        
        if (!$patient) {
            $_SESSION['error'] = "Patient not found.";
            redirect('reception/patients/search');
        }

        // Fetch mock latest diagnosis or real ones from appointments
        $stmt = $db->prepare("
            SELECT a.reason, a.appointment_date
            FROM appointments a 
            WHERE a.patient_id = :id AND a.reason IS NOT NULL AND a.reason != ''
            ORDER BY a.appointment_date DESC 
            LIMIT 4
        ");
        $stmt->execute(['id' => $patientId]);
        $diagnoses = $stmt->fetchAll();
        
        $current_page = 'reception_search'; // Keep this active in sidebar
        require_once VIEWS_PATH . '/reception/patient_info.php';
    }

    public function updateAppointmentStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('reception/dashboard');
        
        $appointment_id = $_POST['appointment_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if ($appointment_id && $status) {
            $db = Database::getInstance()->getConnection();
            
            // Fetch details before update to get the patient's user_id
            $stmt = $db->prepare("
                SELECT a.appointment_date, a.appointment_time, p.user_id as patient_user_id
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                WHERE a.appointment_id = ?
            ");
            $stmt->execute([$appointment_id]);
            $apptDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            // Update the status
            $stmt = $db->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
            if ($stmt->execute([$status, $appointment_id])) {
                $_SESSION['flash_message'] = "Appointment status updated successfully.";
                $_SESSION['flash_type'] = "success";
                
                // Send an automated message to the patient's dashboard
                if ($apptDetails && !empty($apptDetails['patient_user_id'])) {
                    $sender_id = $_SESSION['user_id'] ?? 1; // Fallback to system admin if needed
                    $msgContent = "Update: Your appointment on " . date('M d, Y', strtotime($apptDetails['appointment_date'])) . " at " . date('h:i A', strtotime($apptDetails['appointment_time'])) . " has been marked as " . strtoupper($status) . ".";
                    
                    $msgStmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, NOW())");
                    $msgStmt->execute([$sender_id, $apptDetails['patient_user_id'], $msgContent]);
                }
            } else {
                $_SESSION['error'] = "Failed to update appointment status.";
            }
        }
        $redirect_to = $_SERVER['HTTP_REFERER'] ?? '/reception/dashboard';
        header("Location: $redirect_to");
        exit;
    }

    public function reports() {
        $db = Database::getInstance()->getConnection();

        // 1. Fetch all users
        $stmt = $db->prepare("
            SELECT u.user_id, u.role, u.username, u.email, u.created_at,
                   COALESCE(p.full_name, pr.full_name, a.full_name, r.full_name, u.username) as full_name,
                   p.patient_number as id_no
            FROM users u
            LEFT JOIN patients p ON u.user_id = p.user_id
            LEFT JOIN practitioners pr ON u.user_id = pr.user_id
            LEFT JOIN admins a ON u.user_id = a.user_id
            LEFT JOIN receptionists r ON u.user_id = r.user_id
            ORDER BY u.created_at DESC
        ");
        $stmt->execute();
        $users = $stmt->fetchAll();

        // 2. Fetch stats for the 4 boxes
        $stats = [];
        $stats['total_users'] = count($users);
        $stats['total_patients'] = $db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        $stats['total_doctors'] = $db->query("SELECT COUNT(*) FROM practitioners")->fetchColumn();
        $stats['total_staff'] = $db->query("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'receptionist')")->fetchColumn();

        $current_page = 'reception_reports';
        require_once VIEWS_PATH . '/reception/reports.php';
    }

    public function calendar() {
        $db = Database::getInstance()->getConnection();

        // Fetch all appointments with patient and doctor details
        $stmt = $db->prepare("
            SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, a.reason, a.appointment_type,
                   p.full_name as patient_name,
                   pr.full_name as doctor_name
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.patient_id
            LEFT JOIN practitioners pr ON a.practitioner_id = pr.practitioner_id
        ");
        $stmt->execute();
        $appointments = $stmt->fetchAll();

        $current_page = 'reception_calendar';
        require_once VIEWS_PATH . '/reception/calendar.php';
    }

    public function profile() {
        $db = Database::getInstance()->getConnection();
        $user_id = $_SESSION['user_id'];

        $stmt = $db->prepare("
            SELECT u.username, u.email, u.created_at, r.full_name, r.shift 
            FROM users u
            JOIN receptionists r ON u.user_id = r.user_id
            WHERE u.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        $current_page = 'reception_profile';
        require_once VIEWS_PATH . '/reception/profile.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('reception/profile');
        
        $user_id = $_SESSION['user_id'];
        $full_name = $_POST['full_name'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $shift = $_POST['shift'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($full_name && $username && $email) {
            $db = Database::getInstance()->getConnection();
            
            try {
                $db->beginTransaction();

                // Update users table
                $stmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
                $stmt->execute([$username, $email, $user_id]);

                // Update password if provided
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                    $stmt->execute([$hashed, $user_id]);
                }

                // Update receptionists table
                $stmt = $db->prepare("UPDATE receptionists SET full_name = ?, shift = ? WHERE user_id = ?");
                $stmt->execute([$full_name, $shift, $user_id]);

                $db->commit();
                
                // Update session variables if they changed
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = $full_name;
                
                $_SESSION['flash_message'] = "Profile updated successfully.";
                $_SESSION['flash_type'] = "success";
            } catch (Exception $e) {
                $db->rollBack();
                $_SESSION['error'] = "Failed to update profile: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Please fill in all required fields.";
        }

        redirect('reception/profile');
    }
}
