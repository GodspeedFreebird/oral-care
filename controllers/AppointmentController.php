<?php
require_once BASE_PATH . '/models/Appointment.php';
require_once BASE_PATH . '/models/Practitioner.php';
require_once BASE_PATH . '/models/Patient.php';
require_once BASE_PATH . '/services/AvailabilityService.php';
require_once BASE_PATH . '/services/AuthService.php';
require_once BASE_PATH . '/services/NotificationService.php';

class AppointmentController {
    private $appointmentModel;
    private $practitionerModel;
    private $patientModel;
    private $availabilityService;
    private $notificationService;

    public function __construct() {
        if (!AuthService::isLoggedIn()) {
            redirect('login');
        }
        
        $role = $_SESSION['role'] ?? '';
        if (!in_array($role, ['patient', 'receptionist', 'admin'])) {
            $_SESSION['error'] = "Unauthorized access.";
            redirect('login');
        }

        $this->appointmentModel = new Appointment();
        $this->practitionerModel = new Practitioner();
        $this->patientModel = new Patient();
        $this->availabilityService = new AvailabilityService();
        $this->notificationService = new NotificationService();
    }

    public function bookAppointmentView() {
        // Only patients and receptionists can book
        if ($_SESSION['role'] !== 'patient' && $_SESSION['role'] !== 'receptionist' && $_SESSION['role'] !== 'admin') {
            redirect($_SESSION['role'] . '/dashboard');
        }

        $departments = $this->practitionerModel->getAllDepartments();
        $patient = null;
        if ($_SESSION['role'] === 'patient') {
            $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
        } else {
            // Receptionist or Admin booking for a patient
            $patientId = $_POST['patient_id'] ?? $_SESSION['reception_selected_patient_id'] ?? null;
            if ($patientId) {
                // We need a method to get patient by ID, wait, findByUserId expects user_id.
                // We need findById in Patient model. I'll just use a direct query or update Patient model next.
                // Let's assume we will add findById to Patient model.
                $patient = $this->patientModel->findById($patientId);
                // Clear the session so it doesn't stick forever
                unset($_SESSION['reception_selected_patient_id']);
            }
        }
        
        $current_page = 'book_appointment';
        require_once VIEWS_PATH . '/patient/book_appointment.php';
    }

    public function apiGetPractitioners() {
        header('Content-Type: application/json');
        $deptId = $_GET['department_id'] ?? 0;
        $practitioners = $this->practitionerModel->getByDepartment($deptId);
        echo json_encode($practitioners);
        exit;
    }

    public function apiGetSlots() {
        header('Content-Type: application/json');
        $practitionerId = $_GET['practitioner_id'] ?? 0;
        $date = $_GET['date'] ?? date('Y-m-d');
        
        $slots = $this->availabilityService->getAvailableSlots($practitionerId, $date);
        echo json_encode($slots);
        exit;
    }

    public function processBooking() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('patient/dashboard');
        
        AuthService::validateCSRF($_POST['csrf_token'] ?? '');

        // Determine patient ID
        $patientId = null;
        if ($_SESSION['role'] === 'patient') {
            $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
            $patientId = $patient['patient_id'];
        } else if ($_SESSION['role'] === 'receptionist' || $_SESSION['role'] === 'admin') {
            $patientId = $_POST['patient_id'] ?? null;
        }

        if (!$patientId) {
            $_SESSION['error'] = "Patient context missing. Please select a patient first.";
            redirect($_SESSION['role'] === 'patient' ? 'patient/book-appointment' : 'reception/patients/search');
        }

        $data = [
            'patient_id' => $patientId,
            'practitioner_id' => $_POST['practitioner_id'] ?? 0,
            'booked_by_user_id' => $_SESSION['user_id'],
            'appointment_date' => $_POST['appointment_date'] ?? '',
            'appointment_time' => $_POST['appointment_time'] ?? ''
        ];

        try {
            $appointmentId = $this->appointmentModel->create($data);
            
            // Fetch full context for email
            $appointmentData = $this->appointmentModel->findById($appointmentId);
            if ($appointmentData) {
                // Send email
                $this->notificationService->sendAppointmentEmail('booking_confirmation', $appointmentData, $appointmentData['email'], $appointmentData['user_id']);
            }

            $_SESSION['success'] = "Appointment successfully booked!";
            
            if ($_SESSION['role'] === 'receptionist') {
                redirect('reception/dashboard');
            } else if ($_SESSION['role'] === 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('patient/appointments');
            }
        } catch (PDOException $e) {
            // Preserve patient context on failure
            if (isset($data['patient_id'])) {
                $_SESSION['reception_selected_patient_id'] = $data['patient_id'];
            }
            
            // 23000 is the SQLSTATE code for integrity constraint violation
            if ($e->getCode() == '23000') {
                $_SESSION['error'] = "Sorry, that time slot was just taken by someone else. Please choose another.";
            } else {
                $_SESSION['error'] = "An error occurred while booking. Please try again.";
            }
            
            if ($_SESSION['role'] === 'receptionist') {
                redirect('reception/appointments/book');
            } else {
                redirect('patient/book-appointment');
            }
        }
    }

    public function patientAppointments() {
        if ($_SESSION['role'] !== 'patient') redirect('login');
        
        $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
        $appointments = $this->appointmentModel->findByPatientId($patient['patient_id']);
        
        $current_page = 'appointments';
        require_once VIEWS_PATH . '/patient/appointments.php';
    }

    public function cancelAppointment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('patient/appointments');
        AuthService::validateCSRF($_POST['csrf_token'] ?? '');

        if ($_SESSION['role'] !== 'patient') redirect('login');
        $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
        $appointmentId = $_POST['appointment_id'];

        if ($this->appointmentModel->cancel($appointmentId, $patient['patient_id'])) {
            // Fetch appointment data and send cancellation email
            $appointmentData = $this->appointmentModel->findById($appointmentId);
            if ($appointmentData) {
                $this->notificationService->sendAppointmentEmail('cancellation', $appointmentData, $appointmentData['email'], $appointmentData['user_id']);
            }
            $_SESSION['success'] = "Appointment cancelled successfully.";
        } else {
            $_SESSION['error'] = "Failed to cancel appointment. It may have already been cancelled or completed.";
        }

        redirect('patient/appointments');
    }
}
