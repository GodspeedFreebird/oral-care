<?php
class AuthService {
    
    /**
     * Initializes the session and enforces the 30-minute inactivity timeout.
     */
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('OAUTHC_DENTAL_SESSION'); // Isolate session from other localhost projects
            session_start();
        }

        $timeout_duration = 1800; // 30 minutes
        
        if (isset($_SESSION['last_activity'])) {
            $elapsed_time = time() - $_SESSION['last_activity'];
            if ($elapsed_time > $timeout_duration) {
                self::logout();
                // We use redirect helper defined in helpers.php
                redirect('login?error=timeout');
            }
        }
        // Update last activity timestamp
        $_SESSION['last_activity'] = time();
    }

    /**
     * Generates a CSRF token for forms.
     */
    public static function generateCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validates a submitted CSRF token.
     */
    public static function validateCSRF($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            die("Security error: Invalid CSRF token. Please go back and try again.");
        }
        return true;
    }

    /**
     * Logs the user in by regenerating the session ID and storing basic info.
     */
    public static function loginUser($user) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['force_password_change'] = $user['force_password_change'] ?? 0;
    }

    /**
     * Destroys the session securely.
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('OAUTHC_DENTAL_SESSION');
            session_start();
        }
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    
    /**
     * Checks if the user is logged in.
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Enforces that the logged-in user has the specified role (or is an admin).
     * Redirects to login with an error if unauthorized.
     */
    public static function requireRole($requiredRole) {
        if (!self::isLoggedIn()) {
            redirect('login');
        }
        
        if (isset($_SESSION['force_password_change']) && $_SESSION['force_password_change'] == 1) {
            if (strpos($_SERVER['REQUEST_URI'], 'change-password') === false) {
                $_SESSION['error'] = "You must change your password before continuing.";
                redirect('change-password');
            }
        }
        
        $role = $_SESSION['role'] ?? '';
        if ($role !== $requiredRole && $role !== 'admin') {
            $_SESSION['error'] = "Unauthorized access.";
            redirect('login');
        }
    }
}
