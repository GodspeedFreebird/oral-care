<?php
$db = new PDO('mysql:host=localhost;dbname=oauthc_dental_db', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $db->exec("ALTER TABLE consultation_notes ADD COLUMN photo_before VARCHAR(255) NULL");
    $db->exec("ALTER TABLE consultation_notes ADD COLUMN photo_after VARCHAR(255) NULL");
    echo "Columns added successfully!\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
