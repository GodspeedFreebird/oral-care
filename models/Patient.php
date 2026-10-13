<?php
class Patient {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.email, u.username
            FROM patients p
            JOIN users u ON p.user_id = u.user_id
            WHERE p.user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function findById($patientId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.email, u.username
            FROM patients p
            JOIN users u ON p.user_id = u.user_id
            WHERE p.patient_id = :patient_id
            LIMIT 1
        ");
        $stmt->execute(['patient_id' => $patientId]);
        return $stmt->fetch();
    }

    public function updateProfile($userId, $data) {
        $stmt = $this->db->prepare("
            UPDATE patients 
            SET full_name = :full_name,
                date_of_birth = :dob,
                gender = :gender,
                phone = :phone,
                address = :address,
                emergency_contact_name = :em_name,
                emergency_contact_phone = :em_phone,
                profile_picture = :profile_picture
            WHERE user_id = :user_id
        ");
        return $stmt->execute([
            'full_name' => $data['full_name'],
            'dob' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'em_name' => $data['emergency_contact_name'],
            'em_phone' => $data['emergency_contact_phone'],
            'profile_picture' => $data['profile_picture'],
            'user_id' => $userId
        ]);
    }

    public function search($query) {
        $searchTerm = "%" . $query . "%";
        $stmt = $this->db->prepare("
            SELECT p.*, u.email, u.username
            FROM patients p
            JOIN users u ON p.user_id = u.user_id
            WHERE p.full_name LIKE :q1 
               OR p.phone LIKE :q2 
               OR p.patient_number LIKE :q3
            ORDER BY p.full_name ASC
            LIMIT 50
        ");
        $stmt->execute([
            'q1' => $searchTerm,
            'q2' => $searchTerm,
            'q3' => $searchTerm
        ]);
        return $stmt->fetchAll();
    }
}
