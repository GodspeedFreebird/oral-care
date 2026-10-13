<?php
require_once BASE_PATH . '/services/AuthService.php';

class MessageController {

    public function __construct() {
        if (!AuthService::isLoggedIn()) {
            redirect('login');
        }
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $current_user_id = $_SESSION['user_id'];
        $current_user_role = $_SESSION['role'];

        // Get recent contacts: anyone we sent a message to or received a message from
        $contactsStmt = $db->prepare("
            SELECT u.user_id, u.role, u.username,
                   COALESCE(p.full_name, pr.full_name, a.full_name, r.full_name, u.username) as full_name,
                   MAX(m.created_at) as last_interaction,
                   (SELECT COUNT(*) FROM messages m2 WHERE m2.sender_id = u.user_id AND m2.receiver_id = ? AND m2.is_read = 0) as unread_count,
                   (SELECT content FROM messages m3 WHERE (m3.sender_id = u.user_id AND m3.receiver_id = ?) OR (m3.sender_id = ? AND m3.receiver_id = u.user_id) ORDER BY created_at DESC LIMIT 1) as last_message
            FROM users u
            JOIN messages m ON (m.sender_id = u.user_id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = u.user_id)
            LEFT JOIN patients p ON u.user_id = p.user_id
            LEFT JOIN practitioners pr ON u.user_id = pr.user_id
            LEFT JOIN admins a ON u.user_id = a.user_id
            LEFT JOIN receptionists r ON u.user_id = r.user_id
            WHERE u.user_id != ?
            GROUP BY u.user_id
            ORDER BY last_interaction DESC
        ");
        $contactsStmt->execute([$current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id]);
        $contacts = $contactsStmt->fetchAll(PDO::FETCH_ASSOC);

        $patient = null;
        if ($current_user_role === 'patient') {
            $stmt = $db->prepare("SELECT * FROM patients WHERE user_id = ?");
            $stmt->execute([$current_user_id]);
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        }


        require_once BASE_PATH . '/views/messages/index.php';
    }

    public function load() {
        header('Content-Type: application/json');
        $db = Database::getInstance()->getConnection();
        $current_user_id = $_SESSION['user_id'];
        $contact_id = $_GET['contact_id'] ?? 0;

        if (!$contact_id) {
            echo json_encode(['error' => 'Invalid contact']);
            exit;
        }

        // Mark as read
        $markReadStmt = $db->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
        $markReadStmt->execute([$contact_id, $current_user_id]);

        // Fetch messages
        $msgStmt = $db->prepare("
            SELECT m.*, u.role, u.username,
                   COALESCE(p.full_name, pr.full_name, a.full_name, r.full_name) as sender_name
            FROM messages m
            JOIN users u ON m.sender_id = u.user_id
            LEFT JOIN patients p ON u.user_id = p.user_id
            LEFT JOIN practitioners pr ON u.user_id = pr.user_id
            LEFT JOIN admins a ON u.user_id = a.user_id
            LEFT JOIN receptionists r ON u.user_id = r.user_id
            WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC
        ");
        $msgStmt->execute([$current_user_id, $contact_id, $contact_id, $current_user_id]);
        $messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch contact details for header
        $contactStmt = $db->prepare("
            SELECT u.user_id, u.role, u.username,
                   COALESCE(p.full_name, pr.full_name, a.full_name, r.full_name, u.username) as full_name
            FROM users u
            LEFT JOIN patients p ON u.user_id = p.user_id
            LEFT JOIN practitioners pr ON u.user_id = pr.user_id
            LEFT JOIN admins a ON u.user_id = a.user_id
            LEFT JOIN receptionists r ON u.user_id = r.user_id
            WHERE u.user_id = ?
        ");
        $contactStmt->execute([$contact_id]);
        $contact = $contactStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'contact' => $contact,
            'messages' => $messages
        ]);
        exit;
    }

    public function send() {
        header('Content-Type: application/json');
        
        // Basic CSRF Check via Header instead of form to simplify AJAX
        if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['csrf_token']) {
            echo json_encode(['error' => 'CSRF Token Mismatch']);
            exit;
        }

        $db = Database::getInstance()->getConnection();
        $current_user_id = $_SESSION['user_id'];
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $receiver_id = $data['receiver_id'] ?? 0;
        $content = trim($data['content'] ?? '');

        if (!$receiver_id || empty($content)) {
            echo json_encode(['error' => 'Invalid data']);
            exit;
        }

        $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$current_user_id, $receiver_id, $content]);

        echo json_encode(['success' => true]);
        exit;
    }

    public function search() {
        header('Content-Type: application/json');
        $db = Database::getInstance()->getConnection();
        $current_user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $q = '%' . ($_GET['q'] ?? '') . '%';

        $role_filter = "";
        if ($role === 'patient') {
            // Patients can only message staff, not other patients
            $role_filter = "AND u.role IN ('receptionist', 'practitioner', 'admin')";
        }

        $stmt = $db->prepare("
            SELECT u.user_id, u.role, u.username,
                   COALESCE(p.full_name, pr.full_name, a.full_name, r.full_name, u.username) as full_name
            FROM users u
            LEFT JOIN patients p ON u.user_id = p.user_id
            LEFT JOIN practitioners pr ON u.user_id = pr.user_id
            LEFT JOIN admins a ON u.user_id = a.user_id
            LEFT JOIN receptionists r ON u.user_id = r.user_id
            WHERE u.user_id != ? 
            $role_filter
            AND (u.username LIKE ? OR p.full_name LIKE ? OR pr.full_name LIKE ? OR a.full_name LIKE ? OR r.full_name LIKE ? OR u.role LIKE ?)
            ORDER BY u.role = 'receptionist' DESC, u.role = 'admin' DESC, u.role = 'practitioner' DESC, full_name ASC
            LIMIT 15
        ");
        $stmt->execute([$current_user_id, $q, $q, $q, $q, $q, $q]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($users);
        exit;
    }
}
