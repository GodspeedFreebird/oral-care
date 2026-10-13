<?php

require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/services/AuthService.php';

class AuthController {
    
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        if (AuthService::isLoggedIn()) {
            $this->redirectByRole($_SESSION['role']);
        }
        $csrf_token = AuthService::generateCSRF();
        require_once VIEWS_PATH . '/auth/login.php';
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('login');
        
        AuthService::validateCSRF($_POST['csrf_token'] ?? '');

        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $error = "Both fields are required.";
            $csrf_token = AuthService::generateCSRF();
            require_once VIEWS_PATH . '/auth/login.php';
            return;
        }

        $user = $this->userModel->findByEmailOrUsername($identifier);

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['is_active'] == 0) {
                $error = "Account is disabled. Please contact support.";
                $this->userModel->logAudit($user['user_id'], 'login_failed', 'Account disabled');
                $csrf_token = AuthService::generateCSRF();
                require_once VIEWS_PATH . '/auth/login.php';
                return;
            }

            // Success
            AuthService::loginUser($user);
            $this->userModel->updateLastLogin($user['user_id']);
            $this->userModel->logAudit($user['user_id'], 'login_success', 'IP: ' . $_SERVER['REMOTE_ADDR']);
            
            $this->redirectByRole($user['role']);
        } else {
            // Log failure even if user not found (we pass null for user_id if not found)
            $userId = $user ? $user['user_id'] : null;
            $this->userModel->logAudit($userId, 'login_failed', 'Invalid credentials for: ' . $identifier);
            
            $error = "Invalid email or password";
            $csrf_token = AuthService::generateCSRF();
            require_once VIEWS_PATH . '/auth/login.php';
        }
    }

    public function showChangePassword() {
        if (!AuthService::isLoggedIn()) {
            redirect('login');
        }
        $current_page = 'change_password';
        require_once VIEWS_PATH . '/auth/change_password.php';
    }

    public function processChangePassword() {
        if (!AuthService::isLoggedIn()) {
            redirect('login');
        }
        
        AuthService::validateCSRF($_POST['csrf_token'] ?? '');
        
        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = "All fields are required.";
            redirect('change-password');
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "New passwords do not match.";
            redirect('change-password');
        }
        
        // Verify current password
        $user = $this->userModel->findById($userId);
        if (!password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            redirect('change-password');
        }
        
        // Update password and clear force_password_change flag
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET password_hash = ?, force_password_change = 0 WHERE user_id = ?");
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
        
        // Log the action
        $stmt = $db->prepare("INSERT INTO audit_log (user_id, action, table_affected, record_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, 'PASSWORD_CHANGE', 'users', $userId, 'User changed their password', $_SERVER['REMOTE_ADDR']]);
        
        // Update session to clear force flag
        $_SESSION['force_password_change'] = 0;
        
        $_SESSION['success'] = "Password changed successfully! You may now proceed.";
        
        // Redirect based on role
        if ($_SESSION['role'] === 'admin') redirect('admin/dashboard');
        if ($_SESSION['role'] === 'practitioner') redirect('doctor/dashboard');
        if ($_SESSION['role'] === 'receptionist') redirect('reception/dashboard');
        if ($_SESSION['role'] === 'patient') redirect('patient/dashboard');
        
        redirect('home');
    }

    public function showRegister() {
        if (AuthService::isLoggedIn()) {
            $this->redirectByRole($_SESSION['role']);
        }
        $csrf_token = AuthService::generateCSRF();
        require_once VIEWS_PATH . '/auth/register.php';
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('register');
        
        AuthService::validateCSRF($_POST['csrf_token'] ?? '');

        // Gather and sanitize input
        $fullName = trim($_POST['full_name'] ?? '');
        $dob = $_POST['date_of_birth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($fullName) || empty($dob) || empty($gender) || empty($phone) || empty($address) || empty($email) || empty($username) || empty($password)) {
            $errors[] = "All fields are required.";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($errors)) {
            // Check if username/email exists
            if ($this->userModel->findByEmailOrUsername($username)) {
                $errors[] = "Username is already taken.";
            } elseif ($this->userModel->findByEmailOrUsername($email)) {
                $errors[] = "Email is already registered.";
            } else {
                // Execute Registration Transaction
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT)
                ];
                
                // Handle Profile Picture Upload
                $profilePicturePath = null;
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = BASE_PATH . '/public/assets/uploads/profiles/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $filename = uniqid('prof_') . '_' . basename($_FILES['profile_picture']['name']);
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                        $profilePicturePath = 'assets/uploads/profiles/' . $filename;
                    }
                }

                $patientData = [
                    'full_name' => $fullName,
                    'date_of_birth' => $dob,
                    'gender' => $gender,
                    'phone' => $phone,
                    'address' => $address,
                    'profile_picture' => $profilePicturePath
                ];

                $newUserId = $this->userModel->registerPatient($userData, $patientData);

                if ($newUserId) {
                    $this->userModel->logAudit($newUserId, 'registration', 'Patient self-registered');
                    // Automatically log them in
                    $user = $this->userModel->findById($newUserId);
                    AuthService::loginUser($user);
                    $this->redirectByRole('patient');
                } else {
                    $errors[] = "A system error occurred during registration. Please try again.";
                }
            }
        }

        // Render form with errors
        $csrf_token = AuthService::generateCSRF();
        require_once VIEWS_PATH . '/auth/register.php';
    }

    public function logout() {
        if (AuthService::isLoggedIn()) {
            $this->userModel->logAudit($_SESSION['user_id'], 'logout');
        }
        AuthService::logout();
        redirect('login');
    }

    public function showForgotPassword() {
        $csrf_token = AuthService::generateCSRF();
        require_once VIEWS_PATH . '/auth/forgot_password.php';
    }

    public function showResetPassword() {
        $csrf_token = AuthService::generateCSRF();
        require_once VIEWS_PATH . '/auth/reset_password.php';
    }

    private function redirectByRole($role) {
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'practitioner':
                redirect('doctor/dashboard');
                break;
            case 'receptionist':
                redirect('reception/dashboard');
                break;
            case 'patient':
            default:
                redirect('patient/dashboard');
                break;
        }
    }
}
