<?php
require_once BASE_PATH . '/config/database.php';

class ToothChart {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addEntry($data) {
        $stmt = $this->db->prepare("
            INSERT INTO tooth_chart_entries 
            (record_id, note_id, tooth_code, condition_code, surface, notes)
            VALUES (:record_id, :note_id, :tooth_code, :condition_code, :surface, :notes)
        ");
        $stmt->execute([
            'record_id' => $data['record_id'],
            'note_id' => $data['note_id'] ?? null,
            'tooth_code' => $data['tooth_code'],
            'condition_code' => $data['condition_code'],
            'surface' => $data['surface'] ?? null,
            'notes' => $data['notes'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    // Get the most recent status for each tooth to render the current chart state
    public function getCurrentChartState($recordId) {
        $stmt = $this->db->prepare("
            SELECT t1.* 
            FROM tooth_chart_entries t1
            INNER JOIN (
                SELECT tooth_code, MAX(updated_at) as max_date
                FROM tooth_chart_entries
                WHERE record_id = :record_id
                GROUP BY tooth_code
            ) t2 ON t1.tooth_code = t2.tooth_code AND t1.updated_at = t2.max_date
            WHERE t1.record_id = :record_id2
        ");
        $stmt->execute(['record_id' => $recordId, 'record_id2' => $recordId]);
        $results = $stmt->fetchAll();
        
        // Return as key-value map [tooth_code => condition]
        $map = [];
        foreach ($results as $row) {
            $map[$row['tooth_code']] = $row;
        }
        return $map;
    }

    // Get the full history for a specific tooth
    public function getToothHistory($recordId, $toothCode) {
        $stmt = $this->db->prepare("
            SELECT * FROM tooth_chart_entries
            WHERE record_id = :record_id AND tooth_code = :tooth_code
            ORDER BY updated_at DESC
        ");
        $stmt->execute(['record_id' => $recordId, 'tooth_code' => $toothCode]);
        return $stmt->fetchAll();
    }

    public function getEntriesByNoteId($noteId) {
        $stmt = $this->db->prepare("
            SELECT * FROM tooth_chart_entries
            WHERE note_id = :note_id
            ORDER BY tooth_code ASC
        ");
        $stmt->execute(['note_id' => $noteId]);
        return $stmt->fetchAll();
    }
}
