<?php

require_once BASE_PATH . '/config/database.php';

class TreatmentPhoto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPhotosByPatient($patientId) {
        $stmt = $this->db->prepare("SELECT * FROM treatment_photos WHERE patient_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getPhotosByNote($noteId) {
        $stmt = $this->db->prepare("SELECT * FROM treatment_photos WHERE note_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$noteId]);
        return $stmt->fetchAll();
    }

    public function addPhoto($data) {
        $stmt = $this->db->prepare("
            INSERT INTO treatment_photos (
                patient_id, note_id, tooth_code, photo_type, file_path, description
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['patient_id'],
            $data['note_id'] ?? null,
            $data['tooth_code'] ?? null,
            $data['photo_type'],
            $data['file_path'],
            $data['description'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    public function deletePhoto($photoId) {
        $stmt = $this->db->prepare("DELETE FROM treatment_photos WHERE photo_id = ?");
        return $stmt->execute([$photoId]);
    }
}
