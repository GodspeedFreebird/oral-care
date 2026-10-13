<?php
require_once __DIR__ . '/public/index.php'; // Bootstraps config and helpers
require_once BASE_PATH . '/config/database.php';

$db = Database::getInstance()->getConnection();

try {
    $db->beginTransaction();

    // 1. Clear existing dummy data if any (be careful not to wipe real users, so only delete specific departments)
    $db->exec("DELETE FROM departments WHERE name IN ('Oral Medicine', 'Restorative Dentistry', 'Preventive Dentistry', 'Child Dental Health', 'Oral & Maxillofacial Surgery')");
    $db->exec("DELETE FROM users WHERE role = 'practitioner'"); // Assuming no real practitioners exist yet

    // 2. Insert Departments
    $departments = [
        ['name' => 'Oral Medicine', 'desc' => 'Diagnosis and management of oral mucosal diseases'],
        ['name' => 'Restorative Dentistry', 'desc' => 'Restoration of diseased or damaged teeth'],
        ['name' => 'Preventive Dentistry', 'desc' => 'Prevention of oral diseases'],
        ['name' => 'Child Dental Health', 'desc' => 'Pediatric dentistry'],
        ['name' => 'Oral & Maxillofacial Surgery', 'desc' => 'Surgical treatment of diseases, injuries and defects']
    ];
    $deptIds = [];
    $stmt = $db->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
    foreach ($departments as $dept) {
        $stmt->execute([$dept['name'], $dept['desc']]);
        $deptIds[$dept['name']] = $db->lastInsertId();
    }

    // 3. Insert Practitioners & Availability
    $practitioners = [
        ['name' => 'Dr. Amanda Clara', 'spec' => 'Pediatric Specialist', 'dept' => 'Child Dental Health', 'username' => 'amanda_c', 'gender' => 'female'],
        ['name' => 'Dr. Jason Shatsky', 'spec' => 'Restorative Expert', 'dept' => 'Restorative Dentistry', 'username' => 'jason_s', 'gender' => 'male'],
        ['name' => 'Dr. Jessie Dux', 'spec' => 'Oral Surgeon', 'dept' => 'Oral & Maxillofacial Surgery', 'username' => 'jessie_d', 'gender' => 'female'],
        ['name' => 'Dr. Cameron Williamson', 'spec' => 'Preventive Dentist', 'dept' => 'Preventive Dentistry', 'username' => 'cameron_w', 'gender' => 'male'],
        ['name' => 'Dr. Jane Cooper', 'spec' => 'Oral Medicine Specialist', 'dept' => 'Oral Medicine', 'username' => 'jane_c', 'gender' => 'female']
    ];

    $userStmt = $db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'practitioner')");
    $pracStmt = $db->prepare("INSERT INTO practitioners (user_id, full_name, specialisation, department_id, licence_no, phone) VALUES (?, ?, ?, ?, ?, ?)");
    $availStmt = $db->prepare("INSERT INTO practitioner_availability (practitioner_id, day_of_week, start_time, end_time, slot_length_mins) VALUES (?, ?, '09:00:00', '16:00:00', 30)");

    $days = ['mon', 'tue', 'wed', 'thu', 'fri'];

    foreach ($practitioners as $i => $prac) {
        // Create User
        $userStmt->execute([
            $prac['username'],
            $prac['username'] . '@oauthc.edu.ng',
            password_hash('password123', PASSWORD_BCRYPT)
        ]);
        $userId = $db->lastInsertId();

        // Create Practitioner
        $pracStmt->execute([
            $userId,
            $prac['name'],
            $prac['spec'],
            $deptIds[$prac['dept']],
            'LIC-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            '080' . rand(10000000, 99999999)
        ]);
        $pracId = $db->lastInsertId();

        // Create Availability (Mon-Fri)
        foreach ($days as $day) {
            $availStmt->execute([$pracId, $day]);
        }
    }

    $db->commit();
    echo "Successfully seeded database with departments, practitioners, and availability.";
} catch (Exception $e) {
    $db->rollBack();
    echo "Failed to seed database: " . $e->getMessage();
}
