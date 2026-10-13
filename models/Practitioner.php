<?php

class Practitioner {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllDepartments() {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT p.*, d.name as department_name 
            FROM practitioners p
            JOIN departments d ON p.department_id = d.department_id
            ORDER BY p.full_name ASC
        ");
        return $stmt->fetchAll();
    }

    public function getByDepartment($departmentId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.email, d.name as department_name, pa.profile_picture
            FROM practitioners p
            JOIN users u ON p.user_id = u.user_id
            JOIN departments d ON p.department_id = d.department_id
            LEFT JOIN (SELECT practitioner_id, 'dummy.jpg' as profile_picture FROM practitioners) as pa ON pa.practitioner_id = p.practitioner_id
            WHERE p.department_id = :dept_id
        ");
        $stmt->execute(['dept_id' => $departmentId]);
        return $stmt->fetchAll();
    }

    public function getById($practitionerId) {
        $stmt = $this->db->prepare("
            SELECT p.*, d.name as department_name 
            FROM practitioners p
            JOIN departments d ON p.department_id = d.department_id
            WHERE p.practitioner_id = :id
        ");
        $stmt->execute(['id' => $practitionerId]);
        return $stmt->fetch();
    }

    public function updateProfile($practitionerId, $data) {
        $sql = "UPDATE practitioners 
            SET full_name = :full_name, 
                phone = :phone, 
                specialisation = :specialisation, 
                about = :about";
        $params = [
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
            'specialisation' => $data['specialisation'] ?? null,
            'about' => $data['about'] ?? null,
            'id' => $practitionerId
        ];
        
        if (isset($data['profile_picture'])) {
            $sql .= ", profile_picture = :profile_picture";
            $params['profile_picture'] = $data['profile_picture'];
        }
        
        $sql .= " WHERE practitioner_id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
