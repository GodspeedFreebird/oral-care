<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details - OAUTHC Dental</title>
    <?php
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') $base_url .= '/';
    ?>
    <base href="<?= htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #1f2937;
            overflow-x: hidden;
        }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.25rem;
            color: #111827;
            text-decoration: none;
        }
        .sidebar-brand i { color: #14b8a6; }
        .sidebar-menu {
            list-style: none;
            padding: 0 1rem;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }
        .sidebar-item { margin-bottom: 0.25rem; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover { color: #111827; background-color: #f3f4f6; }
        .sidebar-link.active {
            background-color: #ccfbf1;
            color: #0f766e;
            font-weight: 600;
        }
        
        .unlock-card {
            margin: 1.5rem;
            padding: 1.5rem;
            background: #111827;
            border-radius: 16px;
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 0;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        /* Top Header */
        .top-header {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            background-color: #f8f9fa;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            text-decoration: none;
            position: relative;
        }
        .icon-btn .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 20px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            cursor: pointer;
        }
        .user-profile img { width: 32px; height: 32px; border-radius: 50%; }
        .user-profile .name { font-size: 0.875rem; font-weight: 600; color: #111827; }

        .back-link {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }
        .back-link:hover { color: #111827; }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        /* Layout Grid */
        .details-grid {
            padding: 0 2rem 2rem 2rem;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1.5rem;
        }

        /* Cards */
        .pd-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #f3f4f6;
        }
        .pd-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .pd-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        /* Patient Header Card */
        .patient-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .patient-header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .patient-avatar-lg {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            object-fit: cover;
        }
        .patient-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.25rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .patient-id {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            background-color: #1f2937;
            color: #ffffff;
        }
        .status-badge.inactive { background-color: #ef4444; }
        .patient-header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            text-decoration: none;
            border: 1px solid #e5e7eb;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .info-label {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 0.25rem;
            display: block;
        }
        .info-value {
            font-size: 0.875rem;
            color: #111827;
            font-weight: 500;
            display: block;
        }

        /* Notes Cards */
        .notes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .note-card {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #f3f4f6;
        }
        .note-date {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 0.5rem;
        }
        .note-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .note-desc {
            font-size: 0.8rem;
            color: #6b7280;
            line-height: 1.5;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Medical Info Cards */
        .medical-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .medical-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background-color: #ffffff;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
        }
        .medical-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .bg-teal { background-color: #ccfbf1; color: #0f766e; }
        .bg-blue { background-color: #dbeafe; color: #1d4ed8; }
        .bg-indigo { background-color: #e0e7ff; color: #4338ca; }
        .bg-green { background-color: #dcfce3; color: #15803d; }
        .bg-orange { background-color: #ffedd5; color: #c2410c; }
        .bg-purple { background-color: #f3e8ff; color: #7e22ce; }
        .bg-rose { background-color: #ffe4e6; color: #be123c; }

        .medical-info-text .label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
            display: block;
        }
        .medical-info-text .value {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            display: block;
        }

        /* Member Card */
        .member-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .member-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .mc-header { display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem; margin-bottom: 2rem; }
        .mc-tier { background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 6px; display: inline-block; font-weight: 700; letter-spacing: 1px; font-size: 0.875rem; margin-bottom: 2rem; }
        .mc-footer { display: flex; justify-content: space-between; font-size: 0.75rem; color: #94a3b8; }

        /* Appointments List */
        .appt-item {
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
            margin-bottom: 0.75rem;
        }
        .appt-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .appt-type { font-weight: 600; font-size: 0.875rem; color: #111827; }
        .appt-status {
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }
        .appt-status.confirmed { background-color: #ccfbf1; color: #0f766e; }
        .appt-status.completed { background-color: #1f2937; color: #ffffff; }
        .appt-doc { font-size: 0.875rem; font-weight: 500; color: #111827; margin-bottom: 0.25rem; }
        .appt-time { font-size: 0.75rem; color: #6b7280; display: flex; align-items: center; gap: 0.25rem; }

        /* Bottom Lists */
        .list-section ul { padding-left: 1.25rem; margin: 0; }
        .list-section li { font-size: 0.875rem; color: #4b5563; margin-bottom: 0.5rem; font-weight: 500; }
        
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'patients'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div>
                <a href="admin/patients" class="back-link"><i class="bi bi-arrow-left"></i> Back to Patient List</a>
                <h1 class="page-title">Patient Details</h1>
            </div>
            <div class="header-actions">
                <a href="admin/settings" class="icon-btn" title="Settings"><i class="bi bi-gear"></i></a>
                <div class="dropdown">
                    <a href="#" class="icon-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="badge-dot"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" style="width: 250px;">
                        <li class="px-3 py-2 border-bottom fw-bold">Notifications</li>
                        <li class="px-3 py-3 text-center text-muted small">No new notifications</li>
                    </ul>
                </div>
                <div class="user-profile dropdown">
                    <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=0D8ABC&color=fff" alt="User">
                        <span class="name d-none d-md-block"><?= escape($_SESSION['username'] ?? 'Administrator') ?> <i class="bi bi-chevron-down ms-1 text-muted" style="font-size:0.7rem;"></i></span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                        <li><a class="dropdown-item" href="admin/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="details-grid">
            
            <!-- Left Column -->
            <div class="left-col">
                <!-- Patient Header Card -->
                <div class="pd-card patient-header">
                    <div class="patient-header-left">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($patient['username']) ?>&background=random&size=128" class="patient-avatar-lg">
                        <div>
                            <h2 class="patient-name">
                                <?= escape($patient['username']) ?>
                                <span class="status-badge <?= $patient['is_active'] ? '' : 'inactive' ?>"><?= $patient['is_active'] ? 'Active' : 'Inactive' ?></span>
                            </h2>
                            <p class="patient-id">Patient ID: <span class="fw-bold text-dark"><?= escape(str_replace('OAUTHC/DEN/2026/', '', $patient['patient_number'])) ?></span></p>
                        </div>
                    </div>
                    <div class="patient-header-right">
                        <a href="#" class="action-icon"><i class="bi bi-chat-dots"></i></a>
                        <a href="#" class="action-icon"><i class="bi bi-telephone"></i></a>
                        <button class="btn btn-dark fw-bold px-4 ms-2" style="border-radius: 8px; background-color: #1f2937;">Edit Data</button>
                    </div>
                </div>

                <div class="d-flex gap-4">
                    <!-- Contact Info -->
                    <div class="pd-card" style="flex: 1;">
                        <div class="pd-card-header">
                            <h3 class="pd-card-title">Contact Info</h3>
                            <i class="bi bi-three-dots text-muted"></i>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Phone Number</span>
                            <span class="info-value"><?= escape($patient['phone'] ?: 'N/A') ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Email</span>
                            <span class="info-value"><?= escape($patient['email']) ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Address</span>
                            <span class="info-value"><?= escape($patient['address'] ?: 'N/A') ?></span>
                        </div>
                        <div>
                            <span class="info-label">Emergency Contact</span>
                            <span class="info-value"><?= escape($patient['emergency_contact_name'] ?: 'N/A') ?></span>
                            <span class="info-value text-muted" style="font-weight: 400;"><?= escape($patient['emergency_contact_phone'] ?: '') ?></span>
                        </div>
                    </div>

                    <!-- General Info -->
                    <div class="pd-card" style="flex: 1.5;">
                        <div class="pd-card-header">
                            <h3 class="pd-card-title">General Info</h3>
                            <i class="bi bi-three-dots text-muted"></i>
                        </div>
                        <div class="info-grid">
                            <div>
                                <span class="info-label">Gender</span>
                                <span class="info-value"><?= ucfirst(escape($patient['gender'])) ?></span>
                            </div>
                            <div>
                                <span class="info-label">Occupation</span>
                                <span class="info-value">Software Engineer</span> <!-- Mock -->
                            </div>
                            <div>
                                <span class="info-label">Age</span>
                                <span class="info-value"><?= $patient['age'] ?> years old</span>
                            </div>
                            <div>
                                <span class="info-label">Insurance</span>
                                <span class="info-value">OAUTHC Staff Scheme</span> <!-- Mock -->
                            </div>
                            <div>
                                <span class="info-label">Date of Birth</span>
                                <span class="info-value"><?= $patient['date_of_birth'] ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="pd-card-title">Patient Notes</h3>
                                <a href="#" class="text-muted text-decoration-none" style="font-size:0.75rem; font-weight: 500;">View All</a>
                            </div>
                            <div class="notes-grid">
                                <?php if (empty($notes)): ?>
                                    <div class="text-muted small">No consultation notes available.</div>
                                <?php else: ?>
                                    <?php foreach ($notes as $n): ?>
                                    <div class="note-card">
                                        <div class="note-date"><?= date('M j, Y, g:i A', strtotime($n['created_at'])) ?></div>
                                        <div class="note-title"><?= escape($n['diagnosis'] ?: 'General Consultation') ?></div>
                                        <p class="note-desc"><?= escape($n['treatment_plan'] ?: 'Routine follow-up advised.') ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dental / Medical Info -->
                <div class="pd-card">
                    <div class="pd-card-header">
                        <h3 class="pd-card-title">Dental & Medical Info</h3>
                        <div class="d-flex align-items-center gap-3">
                            <span style="font-size: 0.75rem; color: #9ca3af;">Last Updated on <strong><?= date('d M Y, h:i A') ?></strong></span>
                            <button class="btn btn-light border btn-sm py-0 px-2 rounded-3"><i class="bi bi-three-dots"></i></button>
                        </div>
                    </div>

                    <div class="medical-grid">
                        <div class="medical-card">
                            <div class="medical-icon bg-teal"><i class="bi bi-droplet"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Blood Group</span>
                                <span class="value"><?= escape($patient['blood_group'] ?: 'O+') ?></span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-blue"><i class="bi bi-bandaid"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Active Cavities (Decay)</span>
                                <span class="value"><?= $tooth_stats['cavities'] ?> detected</span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-indigo"><i class="bi bi-x-circle"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Missing Teeth</span>
                                <span class="value"><?= $tooth_stats['missing'] ?> missing</span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-rose"><i class="bi bi-shield-check"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Restorations</span>
                                <span class="value"><?= $tooth_stats['restorations'] ?> existing</span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-green"><i class="bi bi-stars"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Last Cleaning</span>
                                <span class="value"><?= !empty($latest_consult['last_cleaning_date']) ? date('d M Y', strtotime($latest_consult['last_cleaning_date'])) : ($last_cleaning ? date('d M Y', strtotime($last_cleaning)) : 'No Record') ?></span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-orange"><i class="bi bi-heart-pulse"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Gum Health (Perio)</span>
                                <span class="value"><?= !empty($latest_consult['gum_health']) ? htmlspecialchars($latest_consult['gum_health']) : 'No Record' ?></span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-purple"><i class="bi bi-bar-chart"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Plaque Index</span>
                                <span class="value"><?= !empty($latest_consult['plaque_index']) ? htmlspecialchars($latest_consult['plaque_index']) : 'No Record' ?></span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-blue"><i class="bi bi-brush"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Brushing Freq</span>
                                <span class="value"><?= !empty($latest_consult['brushing_freq']) ? htmlspecialchars($latest_consult['brushing_freq']) : 'No Record' ?></span>
                            </div>
                        </div>
                        <div class="medical-card">
                            <div class="medical-icon bg-teal"><i class="bi bi-wind"></i></div>
                            <div class="medical-info-text">
                                <span class="label">Flossing</span>
                                <span class="value"><?= !empty($latest_consult['flossing_freq']) ? htmlspecialchars($latest_consult['flossing_freq']) : 'No Record' ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-md-4 list-section">
                            <span class="info-label mb-3">Allergies</span>
                            <?php 
                                $allergies = $dental_record && $dental_record['drug_allergies'] ? array_map('trim', explode(',', $dental_record['drug_allergies'])) : [];
                            ?>
                            <?php if (empty($allergies) || (count($allergies)===1 && empty($allergies[0]))): ?>
                                <span class="text-muted small ms-2">No known allergies</span>
                            <?php else: ?>
                                <ul>
                                    <?php foreach ($allergies as $a): ?>
                                        <li><?= escape($a) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 list-section border-start">
                            <span class="info-label mb-3 ps-3">Conditions (Medical History)</span>
                            <?php 
                                $conditions = $dental_record && $dental_record['medical_history'] ? array_map('trim', explode(',', $dental_record['medical_history'])) : [];
                            ?>
                            <ul class="ps-5">
                                <?php if (empty($conditions) || (count($conditions)===1 && empty($conditions[0]))): ?>
                                    <span class="text-muted small">None reported</span>
                                <?php else: ?>
                                    <?php foreach ($conditions as $c): ?>
                                        <li><?= escape($c) ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="col-md-4 list-section border-start">
                            <span class="info-label mb-3 ps-3">Medications</span>
                            <?php 
                                $meds = $dental_record && $dental_record['current_medications'] ? array_map('trim', explode(',', $dental_record['current_medications'])) : [];
                            ?>
                            <ul class="ps-5">
                                <?php if (empty($meds) || (count($meds)===1 && empty($meds[0]))): ?>
                                    <span class="text-muted small">Not on medication</span>
                                <?php else: ?>
                                    <?php foreach ($meds as $m): ?>
                                        <li><?= escape($m) ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between pb-4 mt-2" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
                    <div>Copyright &copy; <?= date('Y') ?> OAUTHC Dental</div>
                    <div class="d-flex gap-4">
                        <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                        <a href="#" class="text-muted text-decoration-none">Term and conditions</a>
                        <a href="#" class="text-muted text-decoration-none">Contact</a>
                    </div>
                    <div class="d-flex gap-2">
                        <i class="bi bi-facebook"></i>
                        <i class="bi bi-twitter"></i>
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-linkedin"></i>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-col">
                <!-- Member Card -->
                <div class="member-card">
                    <div class="mc-header">
                        <i class="bi bi-hexagon-fill"></i> OAUTHC Dental
                    </div>
                    <div class="mc-tier">PREMIUM PATIENT</div>
                    <div class="mc-footer">
                        <span><?= escape($patient['username']) ?> | <?= escape(str_replace('OAUTHC/DEN/2026/', '', $patient['patient_number'])) ?></span>
                        <span>Valid till 12/30</span>
                    </div>
                </div>

                <!-- Health Reports -->
                <div class="pd-card py-3 px-4">
                    <div class="pd-card-header mb-3">
                        <h3 class="pd-card-title">Health Reports</h3>
                        <i class="bi bi-three-dots text-muted"></i>
                    </div>
                    
                    <?php if (empty($notes)): ?>
                        <div class="text-muted small text-center py-3">No medical records found.</div>
                    <?php else: ?>
                        <?php foreach ($notes as $index => $n): ?>
                            <?php if ($index >= 3) break; ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-2" style="background: #f9fafb; border: 1px solid #f3f4f6;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-teal text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.8rem; font-weight: 600; color:#111827;">Consultation_<?= date('m_d_Y', strtotime($n['created_at'])) ?>.pdf</div>
                                        <div style="font-size: 0.65rem; color: #9ca3af;">Generated automatically</div>
                                    </div>
                                </div>
                                <i class="bi bi-check-circle text-teal" style="font-size: 0.8rem; color: #0d9488;"></i>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Appointments -->
                <div class="pd-card py-3 px-4">
                    <div class="pd-card-header mb-4">
                        <h3 class="pd-card-title">Appointments</h3>
                        <i class="bi bi-three-dots text-muted"></i>
                    </div>
                    
                    <div class="mb-4">
                        <div class="info-label fw-bold mb-3">Upcoming</div>
                        <?php if (empty($upcoming_appointments)): ?>
                            <div class="text-muted small">No upcoming appointments.</div>
                        <?php else: ?>
                            <?php foreach ($upcoming_appointments as $a): ?>
                            <div class="appt-item">
                                <div class="appt-item-header">
                                    <span class="appt-type"><?= escape($a['reason'] ?: 'Routine Check-Up') ?></span>
                                    <span class="appt-status confirmed">Confirmed</span>
                                </div>
                                <div class="appt-doc">Dr. <?= escape($a['doctor_name']) ?></div>
                                <div class="appt-time"><i class="bi bi-calendar-event"></i> <?= date('D, d M Y', strtotime($a['appointment_date'])) ?> - <?= date('h:i A', strtotime($a['appointment_time'])) ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <div class="info-label fw-bold mb-3">History</div>
                        <?php if (empty($history_appointments)): ?>
                            <div class="text-muted small">No past appointments.</div>
                        <?php else: ?>
                            <?php foreach ($history_appointments as $a): ?>
                            <div class="appt-item border-0 bg-transparent px-0 pt-0">
                                <div class="appt-item-header mb-1">
                                    <span class="appt-type fw-normal text-secondary"><?= escape($a['reason'] ?: 'Consultation') ?></span>
                                    <span class="appt-status completed">Completed</span>
                                </div>
                                <div class="appt-doc mb-1 text-secondary">Dr. <?= escape($a['doctor_name']) ?></div>
                                <div class="appt-time text-secondary"><i class="bi bi-calendar-check"></i> <?= date('D, d M Y', strtotime($a['appointment_date'])) ?> - <?= date('h:i A', strtotime($a['appointment_time'])) ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
