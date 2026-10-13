<?php
// Disable error display in production and log errors server-side
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Define base paths
define('BASE_PATH', dirname(__DIR__));
define('VIEWS_PATH', BASE_PATH . '/views');

// Require core files
require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/PatientController.php';
require_once BASE_PATH . '/controllers/AppointmentController.php';
require_once BASE_PATH . '/controllers/ReceptionController.php';
require_once BASE_PATH . '/controllers/PractitionerController.php';

// Start Session & Enforce Timeout via AuthService
AuthService::startSession();

// Simple Router
// Get the requested URL path
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$method = $_SERVER['REQUEST_METHOD'];

// Instantiate Controllers
$authController = new AuthController();

// Routing logic
switch ($url) {
    case '':
    case 'home':
        require_once VIEWS_PATH . '/layouts/header.php';
        require_once VIEWS_PATH . '/home.php';
        require_once VIEWS_PATH . '/layouts/footer.php';
        break;

    // --- Authentication Routes ---
    case 'login':
        if ($method === 'POST') {
            $authController->processLogin();
        } else {
            $authController->showLogin();
        }
        break;

    case 'change-password':
        if ($method === 'POST') {
            $authController->processChangePassword();
        } else {
            $authController->showChangePassword();
        }
        break;

    case 'register':
        if ($method === 'POST') {
            $authController->processRegister();
        } else {
            $authController->showRegister();
        }
        break;
        
    case 'logout':
        $authController->logout();
        break;

    case 'forgot-password':
        // TODO: Implement POST logic for actually sending emails
        $authController->showForgotPassword();
        break;

    case 'reset-password':
        // TODO: Implement POST logic for verifying tokens and updating passwords
        $authController->showResetPassword();
        break;

    // --- Patient Portal Routes ---
    case 'patient/dashboard':
        $patientController = new PatientController();
        $patientController->dashboard();
        break;

    case 'patient/notifications':
        $patientController = new PatientController();
        $patientController->notifications();
        break;

    case 'patient/profile':
        $patientController = new PatientController();
        $patientController->profile();
        break;

    case 'patient/doctors':
        $patientController = new PatientController();
        $patientController->doctors();
        break;

    case 'patient/records':
        $patientController = new PatientController();
        $patientController->records();
        break;

    case 'patient/appointments':
        $appointmentController = new AppointmentController();
        $appointmentController->patientAppointments();
        break;

    case 'patient/book-appointment':
        $appointmentController = new AppointmentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentController->processBooking();
        } else {
            $appointmentController->bookAppointmentView();
        }
        break;

    case 'patient/cancel-appointment':
        $appointmentController = new AppointmentController();
        $appointmentController->cancelAppointment();
        break;

    // --- Receptionist Routes ---
    case 'reception/dashboard':
        $receptionController = new ReceptionController();
        $receptionController->dashboard();
        break;

    case 'reception/patients/register':
        $receptionController = new ReceptionController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $receptionController->processRegister();
        } else {
            $receptionController->showRegister();
        }
        break;

    case 'reception/patients/search':
        $receptionController = new ReceptionController();
        $receptionController->searchPatients();
        break;

    case 'reception/patient/info':
        $receptionController = new ReceptionController();
        $receptionController->patientInfo();
        break;

    case 'reception/appointments/book':
        $appointmentController = new AppointmentController();
        $appointmentController->bookAppointmentView();
        break;

    case 'reception/appointments/update-status':
        $receptionController = new ReceptionController();
        $receptionController->updateAppointmentStatus();
        break;

    case 'reception/reports':
        $receptionController = new ReceptionController();
        $receptionController->reports();
        break;

    case 'reception/calendar':
        $receptionController = new ReceptionController();
        $receptionController->calendar();
        break;

    case 'reception/profile':
        $receptionController = new ReceptionController();
        $receptionController->profile();
        break;

    case 'reception/profile/update':
        $receptionController = new ReceptionController();
        $receptionController->updateProfile();
        break;

    // --- Practitioner Routes ---
    case 'doctor/dashboard':
        $practitionerController = new PractitionerController();
        $practitionerController->dashboard();
        break;

    case 'doctor/schedule':
        $practitionerController = new PractitionerController();
        $practitionerController->schedule();
        break;

    case 'doctor/schedule/add':
        $practitionerController = new PractitionerController();
        $practitionerController->addAppointment();
        break;

    case 'doctor/appointments/by_date':
        $practitionerController = new PractitionerController();
        $practitionerController->appointmentsByDate();
        break;

    case 'doctor/invoices':
        require_once BASE_PATH . '/controllers/InvoiceController.php';
        $invoiceController = new InvoiceController();
        $invoiceController->index();
        break;

    // --- API Routes for Booking ---
    case 'api/practitioners':
        $appointmentController = new AppointmentController();
        $appointmentController->apiGetPractitioners();
        break;

    case 'api/slots':
        $appointmentController = new AppointmentController();
        $appointmentController->apiGetSlots();
        break;

    case 'api/extend_session':
        header('Content-Type: application/json');
        if (isset($_SESSION['user_id'])) {
            $_SESSION['last_activity'] = time();
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        }
        break;

    case 'reports':
        require_once BASE_PATH . '/controllers/ReportController.php';
        $reportController = new ReportController();
        $reportController->index();
        break;

    // Admin Routes are handled in the default block

    default:
        if (preg_match('#^messages/?$#', $url)) {
            require_once BASE_PATH . '/controllers/MessageController.php';
            $controller = new MessageController();
            $controller->index();
        } elseif (preg_match('#^messages/load/?$#', $url)) {
            require_once BASE_PATH . '/controllers/MessageController.php';
            $controller = new MessageController();
            $controller->load();
        } elseif (preg_match('#^messages/send/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/MessageController.php';
            $controller = new MessageController();
            $controller->send();
        } elseif (preg_match('#^messages/search/?$#', $url)) {
            require_once BASE_PATH . '/controllers/MessageController.php';
            $controller = new MessageController();
            $controller->search();
        } elseif (preg_match('#^admin/profile/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->profile();
        } elseif (preg_match('#^admin/settings/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->settings();
        } elseif (preg_match('#^admin/dashboard/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->dashboard();
        } elseif (preg_match('#^admin/users/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->storeUser();
            } else {
                $controller->users();
            }
        } elseif (preg_match('#^admin/patients/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->patients();
        } elseif (preg_match('#^admin/doctors/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_doctor') {
                $controller->addDoctor();
            } else {
                $controller->doctors();
            }
        } elseif (preg_match('#^admin/doctor/details/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->doctorDetails();
        } elseif (preg_match('#^admin/departments/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->departments();
        } elseif (preg_match('#^admin/department/details/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->departmentDetails();
        } elseif (preg_match('#^admin/department/add/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->addDepartment();
        } elseif (preg_match('#^admin/department/edit/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->editDepartment();
        } elseif (preg_match('#^admin/department/treatment/add/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->addDepartmentTreatment();
        } elseif (preg_match('#^admin/department/treatment/remove/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->removeDepartmentTreatment();
        } elseif (preg_match('#^admin/patient/details/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->patientDetails();
        } elseif (preg_match('#^admin/payments/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->payments();
        } elseif (preg_match('#^admin/payments/add/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->addInvoice();
        } elseif (preg_match('#^admin/payments/edit/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->editInvoice();
        } elseif (preg_match('#^admin/payments/delete/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->deleteInvoice();
        } elseif (preg_match('#^admin/inventory/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->inventory();
        } elseif (preg_match('#^admin/inventory/add/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->addInventoryItem();
        } elseif (preg_match('#^admin/inventory/reorder/?$#', $url) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->reorderInventoryItem();
        } elseif (preg_match('#^admin/schedule/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->schedule();
        } elseif (preg_match('#^admin/appointments/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->appointments();
        } elseif (preg_match('#^admin/appointments/add/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->addAppointment();
            }
        } elseif (preg_match('#^admin/appointments/update-status/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->updateAppointmentStatus();
            }
        } elseif (preg_match('#^admin/appointments/reschedule/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->rescheduleAppointment();
            }
        } elseif (preg_match('#^admin/users/(\d+)/edit$#', $url, $matches)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->updateUser($matches[1]);
            } else {
                $controller->editUser($matches[1]);
            }
        } elseif (preg_match('#^admin/patients/(\d+)/partial$#', $url, $matches)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->patientPartial($matches[1]);
        } elseif (preg_match('#^admin/reports/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->reports();
        } elseif (preg_match('#^admin/audit-log/?$#', $url)) {
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->auditLog();
        } elseif (preg_match('#^doctor/settings/?$#', $url)) {
            require_once BASE_PATH . '/controllers/PractitionerController.php';
            $controller = new PractitionerController();
            $controller->settings();
        } elseif (preg_match('#^doctor/settings/update/?$#', $url)) {
            require_once BASE_PATH . '/controllers/PractitionerController.php';
            $controller = new PractitionerController();
            $controller->updateSettings();
        } elseif (preg_match('#^doctor/patients/?$#', $url)) {
            $practitionerController = new PractitionerController();
            $practitionerController->listPatients();
        } elseif (preg_match('#^doctor/patients/(\d+)(/([a-zA-Z0-9_-]+))?$#', $url, $matches)) {
            $practitionerController = new PractitionerController();
            $patientId = $matches[1];
            $action = $matches[3] ?? 'view';

            switch ($action) {
                case 'view':
                    $practitionerController->viewPatient($patientId);
                    break;
                case 'partial':
                    $practitionerController->viewPatientPartial($patientId);
                    break;
                case 'consult':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $practitionerController->processConsult($patientId);
                    } else {
                        $practitionerController->showConsultForm($patientId);
                    }
                    break;
                case 'edit-consult':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $practitionerController->processEditConsult($patientId);
                    }
                    break;
                case 'tooth-chart':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $practitionerController->updateToothChart($patientId);
                    }
                    break;
                case 'prescribe':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $practitionerController->processPrescription($patientId);
                    } else {
                        $practitionerController->showPrescribeForm($patientId);
                    }
                    break;
                default:
                    http_response_code(404);
                    echo "Action not found";
                    break;
            }
        } elseif (preg_match('#^doctor/invoices/(\d+)/?$#', $url, $matches)) {
            require_once BASE_PATH . '/controllers/InvoiceController.php';
            $invoiceController = new InvoiceController();
            $invoiceController->view($matches[1]);
        } else {
            http_response_code(404);
            require_once VIEWS_PATH . '/layouts/header.php';
            echo '
            <div class="text-center mt-5">
                <h1 class="display-4 text-danger">404</h1>
                <p class="lead">The page you requested does not exist.</p>
                <a href="./" class="btn btn-primary">Return Home</a>
            </div>';
            require_once VIEWS_PATH . '/layouts/footer.php';
        }
        break;
}
