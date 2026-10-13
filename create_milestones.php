<?php
$db = new PDO('mysql:host=localhost;dbname=oauthc_dental_db', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "
CREATE TABLE IF NOT EXISTS treatment_milestones (
    milestone_id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    note_id INT NOT NULL,
    procedure_name VARCHAR(150) NOT NULL,
    step_name VARCHAR(150) NOT NULL,
    status ENUM('Completed', 'In Progress', 'Schedule') NOT NULL DEFAULT 'Schedule',
    scheduled_date DATE NULL,
    completed_date DATE NULL,
    tooth_code VARCHAR(2) NULL,
    step_order INT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES dental_records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (note_id) REFERENCES consultation_notes(note_id) ON DELETE CASCADE
) ENGINE=InnoDB;
";

try {
    $db->exec($sql);
    echo "Table treatment_milestones created successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
