<?php
require_once BASE_PATH . '/config/database.php';

class Consultation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createNote($data) {
        $stmt = $this->db->prepare("
            INSERT INTO consultation_notes 
            (record_id, appointment_id, practitioner_id, presenting_complaint, clinical_findings, diagnosis, treatment_performed, treatment_plan, follow_up_instructions, photo_before, photo_after, blood_pressure, heart_rate, glucose, weight, last_cleaning_date, gum_health, plaque_index, brushing_freq, flossing_freq)
            VALUES (:record_id, :appointment_id, :practitioner_id, :presenting_complaint, :clinical_findings, :diagnosis, :treatment_performed, :treatment_plan, :follow_up_instructions, :photo_before, :photo_after, :blood_pressure, :heart_rate, :glucose, :weight, :last_cleaning_date, :gum_health, :plaque_index, :brushing_freq, :flossing_freq)
        ");
        $stmt->execute([
            'record_id' => $data['record_id'],
            'appointment_id' => $data['appointment_id'],
            'practitioner_id' => $data['practitioner_id'],
            'presenting_complaint' => $data['presenting_complaint'],
            'clinical_findings' => $data['clinical_findings'] ?? null,
            'diagnosis' => $data['diagnosis'],
            'treatment_performed' => $data['treatment_performed'] ?? null,
            'treatment_plan' => $data['treatment_plan'] ?? null,
            'follow_up_instructions' => $data['follow_up_instructions'] ?? null,
            'photo_before' => $data['photo_before'] ?? null,
            'photo_after' => $data['photo_after'] ?? null,
            'blood_pressure' => $data['blood_pressure'] ?? null,
            'heart_rate' => $data['heart_rate'] ?? null,
            'glucose' => $data['glucose'] ?? null,
            'weight' => $data['weight'] ?? null,
            'last_cleaning_date' => $data['last_cleaning_date'] ?? null,
            'gum_health' => $data['gum_health'] ?? null,
            'plaque_index' => $data['plaque_index'] ?? null,
            'brushing_freq' => $data['brushing_freq'] ?? null,
            'flossing_freq' => $data['flossing_freq'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function findByRecordId($recordId) {
        $stmt = $this->db->prepare("
            SELECT cn.*, prac.full_name AS practitioner_name, a.appointment_date, a.appointment_time
            FROM consultation_notes cn
            JOIN practitioners prac ON cn.practitioner_id = prac.practitioner_id
            JOIN appointments a ON cn.appointment_id = a.appointment_id
            WHERE cn.record_id = :record_id
            ORDER BY cn.created_at DESC
        ");
        $stmt->execute(['record_id' => $recordId]);
        return $stmt->fetchAll();
    }

    public function createMilestone($data) {
        $stmt = $this->db->prepare("
            INSERT INTO treatment_milestones 
            (record_id, note_id, procedure_name, step_name, status, scheduled_date, completed_date, tooth_code, step_order)
            VALUES (:record_id, :note_id, :procedure_name, :step_name, :status, :scheduled_date, :completed_date, :tooth_code, :step_order)
        ");
        $stmt->execute([
            'record_id' => $data['record_id'],
            'note_id' => $data['note_id'],
            'procedure_name' => $data['procedure_name'],
            'step_name' => $data['step_name'],
            'status' => $data['status'],
            'scheduled_date' => $data['scheduled_date'] ?: null,
            'completed_date' => $data['completed_date'] ?: null,
            'tooth_code' => $data['tooth_code'] ?? null,
            'step_order' => $data['step_order']
        ]);
        return $this->db->lastInsertId();
    }

    public function getMilestonesByRecordId($recordId) {
        $stmt = $this->db->prepare("
            SELECT * FROM treatment_milestones 
            WHERE record_id = :record_id 
            ORDER BY created_at ASC, step_order ASC
        ");
        $stmt->execute(['record_id' => $recordId]);
        return $stmt->fetchAll();
    }

    public function updateMilestone($milestoneId, $data) {
        $stmt = $this->db->prepare("
            UPDATE treatment_milestones 
            SET status = :status, completed_date = :completed_date 
            WHERE milestone_id = :milestone_id
        ");
        return $stmt->execute([
            'status' => $data['status'],
            'completed_date' => $data['completed_date'] ?: null,
            'milestone_id' => $milestoneId
        ]);
    }

    public function updateNote($noteId, $data) {
        // Construct base update query
        $sql = "UPDATE consultation_notes SET 
                presenting_complaint = :presenting_complaint, 
                clinical_findings = :clinical_findings, 
                diagnosis = :diagnosis, 
                treatment_performed = :treatment_performed, 
                treatment_plan = :treatment_plan, 
                follow_up_instructions = :follow_up_instructions,
                blood_pressure = :blood_pressure,
                heart_rate = :heart_rate,
                glucose = :glucose,
                weight = :weight,
                last_cleaning_date = :last_cleaning_date,
                gum_health = :gum_health,
                plaque_index = :plaque_index,
                brushing_freq = :brushing_freq,
                flossing_freq = :flossing_freq";
        
        $params = [
            'presenting_complaint' => $data['presenting_complaint'],
            'clinical_findings' => $data['clinical_findings'] ?? null,
            'diagnosis' => $data['diagnosis'],
            'treatment_performed' => $data['treatment_performed'] ?? null,
            'treatment_plan' => $data['treatment_plan'] ?? null,
            'follow_up_instructions' => $data['follow_up_instructions'] ?? null,
            'blood_pressure' => $data['blood_pressure'] ?? null,
            'heart_rate' => $data['heart_rate'] ?? null,
            'glucose' => $data['glucose'] ?? null,
            'weight' => $data['weight'] ?? null,
            'last_cleaning_date' => $data['last_cleaning_date'] ?? null,
            'gum_health' => $data['gum_health'] ?? null,
            'plaque_index' => $data['plaque_index'] ?? null,
            'brushing_freq' => $data['brushing_freq'] ?? null,
            'flossing_freq' => $data['flossing_freq'] ?? null,
            'note_id' => $noteId
        ];

        // Only update photos if new ones were provided
        if (array_key_exists('photo_before', $data) && $data['photo_before'] !== null) {
            $sql .= ", photo_before = :photo_before";
            $params['photo_before'] = $data['photo_before'];
        }
        
        if (array_key_exists('photo_after', $data) && $data['photo_after'] !== null) {
            $sql .= ", photo_after = :photo_after";
            $params['photo_after'] = $data['photo_after'];
        }

        $sql .= " WHERE note_id = :note_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
