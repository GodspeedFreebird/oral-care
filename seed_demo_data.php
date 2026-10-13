<?php
/**
 * OAUTHC Oral Health Management System — Demo Data Seeder
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'oauthc_dental_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DEMO_PASSWORD', 'Password123!');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

$passwordHash = password_hash(DEMO_PASSWORD, PASSWORD_BCRYPT);

$departments = [
    ['name' => 'Child Dental Health', 'description' => 'Orthodontics and Paediatric Dentistry'],
    ['name' => 'Oral and Maxillofacial Surgery', 'description' => 'Surgical extractions, trauma, jaw surgery'],
    ['name' => 'Oral Medicine and Oral Pathology', 'description' => 'Diagnosis of oral mucosal and systemic-linked conditions'],
    ['name' => 'Restorative Dentistry', 'description' => 'Fillings, crowns, root canal treatment, prosthodontics'],
    ['name' => 'Preventive Dentistry', 'description' => 'Community Dentistry and Periodontics'],
];

$maleFirstNames = ['Adewale', 'Oluwaseun', 'Emeka', 'Ibrahim', 'Chidi', 'Tunde', 'Segun', 'Femi', 'Yusuf', 'Chukwuemeka', 'Babatunde', 'Kelechi', 'Musa', 'Damilare', 'Obinna'];
$femaleFirstNames = ['Funmilayo', 'Ngozi', 'Aisha', 'Bimpe', 'Chiamaka', 'Folake', 'Amina', 'Yetunde', 'Ifeoma', 'Titilayo', 'Blessing', 'Halima', 'Adaeze', 'Simisola', 'Grace'];
$surnames = ['Adeyemi', 'Ogunleye', 'Fashola', 'Oyelaran', 'Ogundele', 'Fagbenle', 'Balogun', 'Adewale', 'Okonkwo', 'Eze', 'Mohammed', 'Bello', 'Chukwu', 'Nnamdi', 'Afolabi', 'Igwe', 'Suleiman', 'Adebayo', 'Okafor', 'Yakubu'];

$practitionerSpecialisations = [
    'Restorative Dentistry' => 'Restorative Dentistry',
    'Oral and Maxillofacial Surgery' => 'Oral & Maxillofacial Surgery',
    'Oral Medicine and Oral Pathology' => 'Oral Medicine',
    'Child Dental Health' => 'Paediatric Dentistry',
    'Preventive Dentistry' => 'Periodontics',
];

$fdiCodes = [];
foreach ([1, 2, 3, 4] as $quadrant) {
    for ($tooth = 1; $tooth <= 8; $tooth++) {
        $fdiCodes[] = $quadrant . $tooth;
    }
}

$toothToneName = function (string $code): string {
    $quadrant = (int) $code[0];
    $position = (int) $code[1];
    $side = in_array($quadrant, [1, 4]) ? 'right' : 'left';
    $arch = in_array($quadrant, [1, 2]) ? 'Upper' : 'Lower';
    $namesByPos = [1 => 'Central Incisor', 2 => 'Lateral Incisor', 3 => 'Canine', 4 => '1st Premolar', 5 => '2nd Premolar', 6 => '1st Molar', 7 => '2nd Molar', 8 => '3rd Molar'];
    return $namesByPos[$position] . " ($arch $side)";
};

$conditionTreatmentMap = [
    'Deep caries' => ['Filling', 'Root Canal Treatment (RCT)'],
    'Enamel Fracture' => ['Crown Placement', 'Composite Bonding'],
    'Pulpal necrosis' => ['Root Canal Treatment (RCT)'],
    'Irreversible pulpitis' => ['Root Canal Treatment (RCT)', 'Crown Placement'],
    'Missing tooth' => ['Implant', 'Bridge'],
    'Periodontal disease' => ['Scaling and Root Planing'],
    'Impacted tooth' => ['Surgical Extraction'],
    'Severe fracture' => ['Extraction'],
];

$treatmentStepTemplates = [
    'Root Canal Treatment (RCT)' => ['Initial Examination & X-Ray', 'Pulp Removal & Canal Shaping', 'Canal Irrigation & Medication', 'Obturation - Canal Filling', 'Crown Placement & Final Restoration'],
    'Crown Placement' => ['Tooth Preparation', 'Impression Taking', 'Crown Fitting & Cementation'],
    'Implant' => ['Site Assessment & Planning', 'Implant Placement Surgery', 'Healing Period Review', 'Abutment & Crown Fitting'],
    'Bridge' => ['Abutment Tooth Preparation', 'Impression Taking', 'Bridge Fitting & Cementation'],
    'Scaling and Root Planing' => ['Initial Scaling', 'Root Planing', 'Follow-up Review'],
    'Surgical Extraction' => ['Pre-op Assessment & X-Ray', 'Surgical Extraction', 'Post-op Review'],
    'Extraction' => ['Pre-op Assessment', 'Extraction Procedure'],
    'Filling' => ['Cavity Preparation', 'Filling Placement'],
    'Composite Bonding' => ['Surface Preparation', 'Bonding & Shaping'],
];

$drugs = [
    ['name' => 'Amoxicillin 500mg', 'dosage' => '500mg', 'frequency' => '3 times daily'],
    ['name' => 'Metronidazole 400mg', 'dosage' => '400mg', 'frequency' => '3 times daily'],
    ['name' => 'Paracetamol 500mg', 'dosage' => '500mg', 'frequency' => 'Every 6 hours as needed'],
    ['name' => 'Ibuprofen 400mg', 'dosage' => '400mg', 'frequency' => 'Twice daily after meals'],
    ['name' => 'Chlorhexidine Mouthwash 0.2%', 'dosage' => '10ml', 'frequency' => 'Twice daily as mouth rinse'],
];

$complaints = [
    'Severe throbbing pain in the upper right molar for 3 days',
    'Sensitivity to cold and sweet food on the lower left side',
    'Swelling around the gum near the lower jaw',
    'Broken tooth from a fall, sharp edge cutting the tongue',
    'Persistent bad breath and bleeding gums when brushing',
    'Routine check-up, no specific complaint',
    'Pain while chewing on the right side for one week',
    'Loose tooth following a minor accident',
];

function randEl(array $arr) { return $arr[array_rand($arr)]; }
function randDateWithin(int $daysBack, int $daysForward = 0): string {
    $offset = rand(-$daysBack, $daysForward);
    return date('Y-m-d', strtotime("$offset days"));
}
function randTime(): string {
    $slots = ['08:00:00', '08:30:00', '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00', '13:00:00', '13:30:00', '14:00:00', '14:30:00'];
    return randEl($slots);
}

function createUser(PDO $pdo, string $username, string $email, string $role, string $hash): int {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, is_active, created_at)
                            VALUES (:u, :e, :h, :r, 1, NOW())");
    $stmt->execute(['u' => $username, 'e' => $email, 'h' => $hash, 'r' => $role]);
    return (int) $pdo->lastInsertId();
}

$pdo->beginTransaction();

try {
    echo "Seeding departments...\n";
    $deptIds = [];
    $stmt = $pdo->prepare("INSERT INTO departments (name, description) VALUES (:n, :d)");
    foreach ($departments as $d) {
        $stmt->execute(['n' => $d['name'], 'd' => $d['description']]);
        $deptIds[$d['name']] = (int) $pdo->lastInsertId();
    }

    echo "Seeding admin account...\n";
    $adminUserId = createUser($pdo, 'admin2', 'admin2@oauthc.edu.ng', 'admin', $passwordHash);
    $pdo->prepare("INSERT INTO admins (user_id, full_name, access_level) VALUES (:u, :n, 'super')")
        ->execute(['u' => $adminUserId, 'n' => 'System Administrator 2']);

    echo "Seeding practitioners...\n";
    $practitionerIds = [];
    $practitionerUserIds = [];
    $deptNames = array_keys($deptIds);
    $licenceCounter = 1000;
    foreach (range(1, 6) as $i) {
        $isMale = (bool) rand(0, 1);
        $first = $isMale ? randEl($maleFirstNames) : randEl($femaleFirstNames);
        $last = randEl($surnames);
        $fullName = "Dr. $first $last";
        $username = strtolower($first . '.' . $last . $i);
        $email = strtolower($first . '.' . $last . $i) . '@oauthc.edu.ng';
        $dept = $deptNames[$i % count($deptNames)];

        $userId = createUser($pdo, $username, $email, 'practitioner', $passwordHash);
        $stmt = $pdo->prepare("INSERT INTO practitioners (user_id, full_name, specialisation, department_id, licence_no, phone, hire_date)
                                VALUES (:u, :n, :s, :d, :l, :p, :h)");
        $stmt->execute([
            'u' => $userId, 'n' => $fullName, 's' => $practitionerSpecialisations[$dept],
            'd' => $deptIds[$dept], 'l' => 'MDCN/' . ($licenceCounter++), 'p' => '080' . rand(10000000, 99999999),
            'h' => randDateWithin(1500, -365),
        ]);
        $practitionerId = (int) $pdo->lastInsertId();
        $practitionerIds[$practitionerId] = $dept;
        $practitionerUserIds[$practitionerId] = $userId;

        $availStmt = $pdo->prepare("INSERT INTO practitioner_availability (practitioner_id, day_of_week, start_time, end_time, slot_length_mins)
                                     VALUES (:p, :d, '08:00:00', '16:00:00', 30)");
        foreach (['mon', 'tue', 'wed', 'thu', 'fri'] as $day) {
            $availStmt->execute(['p' => $practitionerId, 'd' => $day]);
        }
    }

    echo "Seeding receptionists...\n";
    $receptionistUserIds = [];
    foreach (range(1, 2) as $i) {
        $isMale = (bool) rand(0, 1);
        $first = $isMale ? randEl($maleFirstNames) : randEl($femaleFirstNames);
        $last = randEl($surnames);
        $userId = createUser($pdo, strtolower($first . '.reception' . $i), strtolower($first . '.reception' . $i) . '@oauthc.edu.ng', 'receptionist', $passwordHash);
        $pdo->prepare("INSERT INTO receptionists (user_id, full_name, shift) VALUES (:u, :n, :s)")
            ->execute(['u' => $userId, 'n' => "$first $last", 's' => $i === 1 ? 'morning' : 'afternoon']);
        $receptionistUserIds[] = $userId;
    }

    echo "Seeding patients...\n";
    $patients = []; 
    for ($i = 1; $i <= 28; $i++) {
        $isMale = (bool) rand(0, 1);
        $first = $isMale ? randEl($maleFirstNames) : randEl($femaleFirstNames);
        $last = randEl($surnames);
        $fullName = "$first $last";
        $username = strtolower($first . $last . $i);
        $email = strtolower($first . '.' . $last . $i) . '@gmail.com';
        $dob = date('Y-m-d', strtotime('-' . rand(6, 70) . ' years -' . rand(0, 365) . ' days'));

        $userId = createUser($pdo, $username, $email, 'patient', $passwordHash);
        $patientNumber = 'OAUTHC/DEN/2026/' . str_pad($i, 5, '0', STR_PAD_LEFT);

        $stmt = $pdo->prepare("INSERT INTO patients
            (user_id, patient_number, full_name, date_of_birth, gender, phone, address, blood_group, emergency_contact_name, emergency_contact_phone, created_at)
            VALUES (:u, :pn, :n, :dob, :g, :ph, :ad, :bg, :ecn, :ecp, :ca)");
        $stmt->execute([
            'u' => $userId, 'pn' => $patientNumber, 'n' => $fullName, 'dob' => $dob,
            'g' => $isMale ? 'male' : 'female', 'ph' => '070' . rand(10000000, 99999999),
            'ad' => rand(1, 40) . ' ' . randEl(['Ife Road', 'Mayfair Road', 'Lagere Street', 'Sabo Layout', 'Modakeke Road']) . ', Ile-Ife, Osun State',
            'bg' => randEl(['O+', 'O-', 'A+', 'A-', 'B+', 'AB+']),
            'ecn' => randEl($surnames) . ' ' . randEl($firstNamesAll = array_merge($maleFirstNames, $femaleFirstNames)),
            'ecp' => '081' . rand(10000000, 99999999),
            'ca' => randDateWithin(700, 0),
        ]);
        $patientId = (int) $pdo->lastInsertId();
        $patients[$patientId] = ['user_id' => $userId, 'full_name' => $fullName, 'email' => $email];
    }

    echo "Seeding dental records...\n";
    $patientIds = array_keys($patients);
    $emptyStatePatientIds = array_slice($patientIds, -4);
    $patientsWithRecords = array_diff($patientIds, $emptyStatePatientIds);

    $recordIdByPatient = [];
    $medicalHistories = ['No known chronic conditions', 'Hypertensive, on medication', 'Type 2 Diabetic', 'No significant medical history', 'Asthmatic, uses inhaler occasionally'];
    $allergyOptions = ['None known', 'Penicillin allergy', 'No known drug allergies', 'Allergic to sulfa drugs'];
    $medOptions = ['None', 'Amlodipine 5mg daily', 'Metformin 500mg twice daily', 'None currently'];

    $recStmt = $pdo->prepare("INSERT INTO dental_records (patient_id, medical_history, drug_allergies, current_medications, dental_history, created_at)
                               VALUES (:p, :mh, :al, :med, :dh, :ca)");
    foreach ($patientsWithRecords as $pid) {
        $recStmt->execute([
            'p' => $pid, 'mh' => randEl($medicalHistories), 'al' => randEl($allergyOptions),
            'med' => randEl($medOptions), 'dh' => randEl(['First visit to OAUTHC Dental Centre', 'Regular attendee, last visit over a year ago', 'Referred from General Outpatient Dept']),
            'ca' => randDateWithin(700, 0),
        ]);
        $recordIdByPatient[$pid] = (int) $pdo->lastInsertId();
    }

    echo "Seeding appointments, vitals, consultation notes...\n";

    $apptStmt = $pdo->prepare("INSERT INTO appointments (patient_id, practitioner_id, booked_by_user_id, appointment_date, appointment_time, status, reason, notes, created_at)
                                VALUES (:pat, :prac, :bb, :d, :t, :st, :r, :n, :ca)");
    $vitalsStmt = $pdo->prepare("INSERT INTO vitals (appointment_id, blood_pressure, glucose_mgdl, heart_rate_bpm, weight_kg, recorded_at)
                                  VALUES (:a, :bp, :g, :hr, :w, :r)");
    $noteStmt = $pdo->prepare("INSERT INTO consultation_notes
        (record_id, appointment_id, practitioner_id, presenting_complaint, clinical_findings, diagnosis, treatment_performed, treatment_plan, follow_up_instructions, created_at)
        VALUES (:rec, :appt, :prac, :pc, :cf, :dx, :tp, :plan, :fu, :ca)");
    $toothStmt = $pdo->prepare("INSERT INTO tooth_chart_entries (record_id, note_id, tooth_code, condition_code, surface, notes, updated_at)
                                 VALUES (:rec, :note, :tc, :cc, :sf, :n, :ua)");
    
    $hasTreatmentPlans = $pdo->query("SHOW TABLES LIKE 'treatment_plans'")->rowCount() > 0;
    
    if ($hasTreatmentPlans) {
        $planStmt = $pdo->prepare("INSERT INTO treatment_plans
            (record_id, tooth_code, procedure_name, status, total_sessions, current_session, start_date, target_end_date, estimated_cost, created_by, created_at)
            VALUES (:rec, :tc, :proc, :status, :total, :current, :start, :end, :cost, :prac, :ca)");
        $stepStmt = $pdo->prepare("INSERT INTO treatment_plan_steps (plan_id, step_name, sequence_order, status, scheduled_date, completed_date)
                                    VALUES (:plan, :name, :seq, :status, :sched, :comp)");
    }

    $rxStmt = $pdo->prepare("INSERT INTO prescriptions (note_id, drug_name, dosage, frequency, duration, special_instructions, created_at)
                              VALUES (:note, :drug, :dose, :freq, :dur, :instr, :ca)");
    $notifStmt = $pdo->prepare("INSERT INTO notifications (appointment_id, type, recipient_email, message, status, scheduled_at, sent_at)
                                 VALUES (:a, :type, :email, :msg, :status, :sched, :sent)");

    $practitionerIdList = array_keys($practitionerIds);
    $usedSlots = [];

    foreach ($patientsWithRecords as $pid) {
        $recordId = $recordIdByPatient[$pid];
        $numPastAppts = rand(1, 3);

        for ($v = 0; $v < $numPastAppts; $v++) {
            $practitionerId = randEl($practitionerIdList);

            do {
                $date = randDateWithin(365, -1);
                $time = randTime();
                $slotKey = "$practitionerId|$date|$time";
            } while (isset($usedSlots[$slotKey]));
            $usedSlots[$slotKey] = true;

            $complaint = randEl($complaints);
            $apptStmt->execute([
                'pat' => $pid, 'prac' => $practitionerId, 'bb' => $patients[$pid]['user_id'],
                'd' => $date, 't' => $time, 'st' => 'completed', 'r' => $complaint, 'n' => null,
                'ca' => date('Y-m-d H:i:s', strtotime("$date -3 days")),
            ]);
            $appointmentId = (int) $pdo->lastInsertId();

            $vitalsStmt->execute([
                'a' => $appointmentId, 'bp' => rand(105, 140) . '/' . rand(65, 90),
                'g' => rand(70, 140) . '.0', 'hr' => rand(60, 95), 'w' => rand(45, 95) . '.' . rand(0, 9),
                'r' => "$date $time",
            ]);

            $teethThisVisit = (array) array_rand(array_flip($fdiCodes), rand(1, 2));
            $conditionByTooth = [];
            $recommendedTreatments = [];
            foreach ($teethThisVisit as $tc) {
                $condition = array_rand($conditionTreatmentMap);
                $conditionByTooth[$tc] = $condition;
                $recommendedTreatments[$tc] = randEl($conditionTreatmentMap[$condition]);
            }
            $findingsSummary = [];
            foreach ($teethThisVisit as $tc) {
                $findingsSummary[] = $conditionByTooth[$tc] . ' on tooth ' . $tc;
            }

            $noteStmt->execute([
                'rec' => $recordId, 'appt' => $appointmentId, 'prac' => $practitionerId,
                'pc' => $complaint,
                'cf' => 'Clinical examination revealed ' . implode('; ', $findingsSummary) . '.',
                'dx' => implode('; ', array_unique(array_values($conditionByTooth))),
                'tp' => implode(', ', array_unique(array_values($recommendedTreatments))) . ' recommended.',
                'plan' => 'Continue with recommended treatment plan and review in 2-4 weeks.',
                'fu' => randEl(['Review in 2 weeks', 'Return if pain persists', 'Routine 6-month recall advised']),
                'ca' => "$date $time",
            ]);
            $noteId = (int) $pdo->lastInsertId();

            foreach ($teethThisVisit as $tc) {
                $conditionLabel = $conditionByTooth[$tc];
                $toothStmt->execute([
                    'rec' => $recordId, 'note' => $noteId, 'tc' => $tc,
                    'cc' => strtolower(str_replace(' ', '_', $conditionLabel)),
                    'sf' => randEl(['occlusal', 'mesial', 'distal', null]),
                    'n' => "$conditionLabel noted on " . $toothToneName($tc) . '.',
                    'ua' => "$date $time",
                ]);

                if ($hasTreatmentPlans && rand(0, 1) === 1) {
                    $procedure = $recommendedTreatments[$tc];
                    $steps = $treatmentStepTemplates[$procedure] ?? ['Procedure'];
                    $totalSessions = count($steps);
                    $currentSession = rand(1, $totalSessions);
                    $status = $currentSession >= $totalSessions ? 'completed' : 'in_progress';

                    $planStmt->execute([
                        'rec' => $recordId, 'tc' => $tc, 'proc' => $procedure, 'status' => $status,
                        'total' => $totalSessions, 'current' => $currentSession,
                        'start' => $date, 'end' => date('Y-m-d', strtotime("$date +" . ($totalSessions * 14) . ' days')),
                        'cost' => rand(5, 60) * 1000, 'prac' => $practitionerId, 'ca' => "$date $time",
                    ]);
                    $planId = (int) $pdo->lastInsertId();

                    foreach ($steps as $idx => $stepName) {
                        $seq = $idx + 1;
                        if ($seq < $currentSession) {
                            $stepStatus = 'completed';
                            $sched = date('Y-m-d', strtotime("$date +" . ($seq * 10) . ' days'));
                            $comp = $sched;
                        } elseif ($seq === $currentSession) {
                            $stepStatus = $status === 'completed' ? 'completed' : 'in_progress';
                            $sched = date('Y-m-d', strtotime("$date +" . ($seq * 10) . ' days'));
                            $comp = $status === 'completed' ? $sched : null;
                        } else {
                            $stepStatus = 'pending';
                            $sched = null;
                            $comp = null;
                        }
                        $stepStmt->execute(['plan' => $planId, 'name' => $stepName, 'seq' => $seq, 'status' => $stepStatus, 'sched' => $sched, 'comp' => $comp]);
                    }
                }
            }

            if (rand(1, 10) <= 6) {
                $numDrugs = rand(1, 2);
                $chosen = (array) array_rand(array_flip(array_column($drugs, 'name')), $numDrugs);
                foreach ($chosen as $drugName) {
                    $drug = current(array_filter($drugs, fn($d) => $d['name'] === $drugName));
                    $rxStmt->execute([
                        'note' => $noteId, 'drug' => $drug['name'], 'dose' => $drug['dosage'], 'freq' => $drug['frequency'],
                        'dur' => randEl(['5 days', '7 days', '3 days', '2 weeks']),
                        'instr' => randEl(['Take with food', 'Complete full course', 'Avoid alcohol while on medication', null]),
                        'ca' => "$date $time",
                    ]);
                }
            }
        }

        if (rand(0, 1) === 1) {
            $practitionerId = randEl($practitionerIdList);
            do {
                $date = randDateWithin(0, 30);
                $time = randTime();
                $slotKey = "$practitionerId|$date|$time";
            } while (isset($usedSlots[$slotKey]));
            $usedSlots[$slotKey] = true;

            $apptStmt->execute([
                'pat' => $pid, 'prac' => $practitionerId, 'bb' => $patients[$pid]['user_id'],
                'd' => $date, 't' => $time, 'st' => rand(0, 1) ? 'confirmed' : 'pending',
                'r' => randEl($complaints), 'n' => null, 'ca' => date('Y-m-d H:i:s'),
            ]);
            $futureApptId = (int) $pdo->lastInsertId();

            $notifStmt->execute([
                'a' => $futureApptId, 'type' => 'booking_confirmation', 'email' => $patients[$pid]['email'],
                'msg' => "Your appointment on $date at $time has been confirmed.",
                'status' => 'sent', 'sched' => date('Y-m-d H:i:s'), 'sent' => date('Y-m-d H:i:s'),
            ]);
            $notifStmt->execute([
                'a' => $futureApptId, 'type' => 'reminder_24h', 'email' => $patients[$pid]['email'],
                'msg' => "Reminder: your appointment is tomorrow at $time.",
                'status' => 'pending', 'sched' => date('Y-m-d H:i:s', strtotime("$date -1 day")), 'sent' => null,
            ]);
        }
    }

    foreach ($emptyStatePatientIds as $pid) {
        $practitionerId = randEl($practitionerIdList);
        do {
            $date = randDateWithin(0, 20);
            $time = randTime();
            $slotKey = "$practitionerId|$date|$time";
        } while (isset($usedSlots[$slotKey]));
        $usedSlots[$slotKey] = true;

        $apptStmt->execute([
            'pat' => $pid, 'prac' => $practitionerId, 'bb' => $patients[$pid]['user_id'],
            'd' => $date, 't' => $time, 'st' => 'confirmed', 'r' => 'First-time visit, general check-up', 'n' => null,
            'ca' => date('Y-m-d H:i:s'),
        ]);
    }

    echo "Seeding audit log entries...\n";
    $auditStmt = $pdo->prepare("INSERT INTO audit_log (user_id, action, table_affected, record_id, details, ip_address, created_at)
                                 VALUES (:u, :a, :t, :r, :d, :ip, :ca)");
    $allUserIdsForAudit = array_merge([$adminUserId], $practitionerUserIds, $receptionistUserIds, array_column($patients, 'user_id'));
    $actions = [
        ['action' => 'LOGIN', 'table' => null],
        ['action' => 'CREATE_APPOINTMENT', 'table' => 'appointments'],
        ['action' => 'EDIT_CONSULTATION_NOTE', 'table' => 'consultation_notes'],
        ['action' => 'CREATE_PRESCRIPTION', 'table' => 'prescriptions'],
        ['action' => 'FAILED_LOGIN_ATTEMPT', 'table' => null],
    ];
    foreach (range(1, 40) as $i) {
        $a = randEl($actions);
        $auditStmt->execute([
            'u' => $a['action'] === 'FAILED_LOGIN_ATTEMPT' ? null : randEl($allUserIdsForAudit),
            'a' => $a['action'], 't' => $a['table'], 'r' => $a['table'] ? rand(1, 50) : null,
            'd' => $a['action'] === 'FAILED_LOGIN_ATTEMPT' ? 'Invalid password attempt' : null,
            'ip' => '105.112.' . rand(0, 255) . '.' . rand(0, 255),
            'ca' => date('Y-m-d H:i:s', strtotime('-' . rand(0, 60) . ' days -' . rand(0, 86000) . ' seconds')),
        ]);
    }

    $pdo->commit();

    echo "\nDone.\n";
    echo "Seeded: " . count($departments) . " departments, 1 admin, " . count($practitionerIds) . " practitioners, "
        . count($receptionistUserIds) . " receptionists, " . count($patients) . " patients "
        . '(' . count($emptyStatePatientIds) . " with no dental history — use these to test the empty state).\n";
    echo "Every account password is: " . DEMO_PASSWORD . "\n";
    echo "Admin login: admin2@oauthc.edu.ng\n";

} catch (Throwable $e) {
    $pdo->rollBack();
    echo "Seeding failed, rolled back: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
