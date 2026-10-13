<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmailOrUsername($identifier) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1");
        $stmt->execute([
            'email' => $identifier,
            'username' => $identifier
        ]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE user_id = :id");
        $stmt->execute(['id' => $userId]);
    }

    public function logAudit($userId, $action, $details = null) {
        $stmt = $this->db->prepare("INSERT INTO audit_log (user_id, action, details) VALUES (:user_id, :action, :details)");
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details
        ]);
    }

    /**
     * Registers a new patient. Wraps users and patients inserts in a transaction.
     */
    public function registerPatient($userData, $patientData) {
        try {
            $this->db->beginTransaction();

            // 1. Insert into users table
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, 'patient')");
            $stmt->execute([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'password_hash' => $userData['password_hash']
            ]);
            $userId = $this->db->lastInsertId();

            // 2. Generate a unique patient_number (e.g., OAUTHC/DEN/2026/00001)
            $year = date('Y');
            $countStmt = $this->db->prepare("SELECT COUNT(*) FROM patients WHERE patient_number LIKE ?");
            $countStmt->execute(["%/$year/%"]);
            $count = $countStmt->fetchColumn() + 1;
            $patientNumber = "OAUTHC/DEN/$year/" . str_pad($count, 5, '0', STR_PAD_LEFT);

            // 3. Insert into patients table
            $stmt = $this->db->prepare("INSERT INTO patients (user_id, patient_number, full_name, date_of_birth, gender, phone, address, profile_picture) VALUES (:user_id, :patient_number, :full_name, :dob, :gender, :phone, :address, :profile_picture)");
            $stmt->execute([
                'user_id' => $userId,
                'patient_number' => $patientNumber,
                'full_name' => $patientData['full_name'],
                'dob' => $patientData['date_of_birth'],
                'gender' => $patientData['gender'],
                'phone' => $patientData['phone'],
                'address' => $patientData['address'],
                'profile_picture' => $patientData['profile_picture'] ?? null
            ]);

            // Commit transaction
            $this->db->commit();
            
            return $userId;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            error_log("Registration Transaction Error: " . $e->getMessage());
            return false;
        }
    }
}
