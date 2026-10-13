<?php
require_once BASE_PATH . '/config/database.php';

class ClinicalRecord {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByPatientId($patientId) {
        $stmt = $this->db->prepare("SELECT * FROM dental_records WHERE patient_id = :patient_id");
        $stmt->execute(['patient_id' => $patientId]);
        $record = $stmt->fetch();

        // Auto-create if it doesn't exist
        if (!$record) {
            $stmt = $this->db->prepare("INSERT INTO dental_records (patient_id) VALUES (:patient_id)");
            $stmt->execute(['patient_id' => $patientId]);
            $recordId = $this->db->lastInsertId();
            return $this->findById($recordId);
        }

        return $record;
    }

    public function findById($recordId) {
        $stmt = $this->db->prepare("SELECT * FROM dental_records WHERE record_id = :record_id");
        $stmt->execute(['record_id' => $recordId]);
        return $stmt->fetch();
    }

    public function updateRecord($recordId, $data) {
        $stmt = $this->db->prepare("
            UPDATE dental_records 
            SET medical_history = :medical_history, 
                drug_allergies = :drug_allergies, 
                current_medications = :current_medications, 
                dental_history = :dental_history
            WHERE record_id = :record_id
        ");
        return $stmt->execute([
            'medical_history' => $data['medical_history'] ?? null,
            'drug_allergies' => $data['drug_allergies'] ?? null,
            'current_medications' => $data['current_medications'] ?? null,
            'dental_history' => $data['dental_history'] ?? null,
            'record_id' => $recordId
        ]);
    }
}
