<?php
require_once BASE_PATH . '/config/database.php';

class Prescription {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addPrescription($data) {
        $stmt = $this->db->prepare("
            INSERT INTO prescriptions 
            (note_id, drug_name, dosage, frequency, duration, special_instructions)
            VALUES (:note_id, :drug_name, :dosage, :frequency, :duration, :special_instructions)
        ");
        $stmt->execute([
            'note_id' => $data['note_id'],
            'drug_name' => $data['drug_name'],
            'dosage' => $data['dosage'],
            'frequency' => $data['frequency'],
            'duration' => $data['duration'],
            'special_instructions' => $data['special_instructions'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function findByNoteId($noteId) {
        $stmt = $this->db->prepare("
            SELECT * FROM prescriptions
            WHERE note_id = :note_id
            ORDER BY created_at ASC
        ");
        $stmt->execute(['note_id' => $noteId]);
        return $stmt->fetchAll();
    }
}
