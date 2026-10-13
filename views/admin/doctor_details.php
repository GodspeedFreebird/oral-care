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
    <title>Doctor Details - OAUTHC Dental</title>
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
            grid-template-columns: 320px 1fr 300px;
            gap: 1.5rem;
        }
        
        .col-left {
            grid-column: 1;
            grid-row: 1 / span 2;
        }
        .col-center {
            grid-column: 2;
            grid-row: 1;
        }
        .col-right {
            grid-column: 3;
            grid-row: 1;
        }
        .col-bottom {
            grid-column: 2 / span 2;
            grid-row: 2;
            margin-top: -1.5rem; /* pull up closer to stats/schedule */
        }

        /* Cards */
        .pd-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
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

        /* Left Column Profile */
        .col-left .pd-card {
            height: 100%;
        }
        .profile-img-container {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            background-color: #a5f3fc; /* cyan-200 */
            margin: 0 auto 1.5rem auto;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .profile-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 0.25rem;
        }
        .profile-id {
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 1rem;
        }
        .status-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            background-color: #ccfbf1;
            color: #0f766e;
            margin: 0 auto 1.5rem auto;
            border: 1px solid #99f6e4;
        }
        .status-badge.inactive {
            background-color: #ffe4e6;
            color: #be123c;
            border-color: #fecdd3;
        }

        .section-label {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 0.5rem;
            display: block;
        }
        .section-text {
            font-size: 0.875rem;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #4b5563;
        }
        .contact-item i {
            color: #0f766e;
            font-size: 1rem;
            margin-top: 0.1rem;
        }

        .exp-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .exp-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111827;
        }
        .exp-details h6 { font-size: 0.875rem; font-weight: 600; margin: 0 0 0.25rem 0; color: #111827; }
        .exp-details p { font-size: 0.8rem; color: #6b7280; margin: 0 0 0.25rem 0; }
        .exp-details span { font-size: 0.75rem; color: #9ca3af; }

        /* Center Column */
        .kpi-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .kpi-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: #e0f2fe; /* sky-100 */
            color: #0369a1; /* sky-700 */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .kpi-info { flex: 1; margin-left: 1rem; }
        .kpi-label { font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; }
        .kpi-value { font-size: 1.5rem; font-weight: 700; color: #111827; }
        
        /* Chart Section */
        .chart-section {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f3f4f6;
            margin-bottom: 1.5rem;
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .chart-title { font-size: 1rem; font-weight: 700; color: #111827; margin: 0; }
        .chart-filter {
            background-color: #1f2937;
            color: #ffffff;
            border-radius: 8px;
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
        }

        .chart-area {
            height: 200px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding-bottom: 2rem;
            position: relative;
        }
        /* Y axis lines */
        .y-axis {
            position: absolute;
            left: 0; right: 0;
            border-top: 1px dashed #e5e7eb;
            z-index: 1;
        }
        .y-12 { top: 0%; }
        .y-9 { top: 25%; }
        .y-6 { top: 50%; }
        .y-3 { top: 75%; }
        .y-0 { top: 100%; border-top-style: solid; border-color: #d1d5db; }
        
        .y-label {
            position: absolute;
            left: -20px;
            transform: translateY(-50%);
            font-size: 0.7rem;
            color: #9ca3af;
        }

        .bar-group {
            position: relative;
            z-index: 2;
            width: 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 0.25rem;
            align-items: center;
            cursor: pointer;
        }
        .bar-group .chart-tooltip {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 0.65rem;
            color: #4b5563;
            white-space: nowrap;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s;
            z-index: 10;
        }
        .bar-group:hover .chart-tooltip {
            opacity: 1;
            visibility: visible;
            top: -70px;
        }
        .tt-date { font-weight: 700; color: #111827; display: block; margin-bottom: 0.25rem; }
        .tt-row { display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 0.1rem; }
        .tt-row .val { font-weight: 700; color: #111827; }
        .tt-sq { display: inline-block; width: 6px; height: 6px; margin-right: 0.25rem; }
        .tt-sq-new { background-color: #a5f3fc; }
        .tt-sq-fol { background-color: #1f2937; }
        .bar-top {
            width: 100%;
            background-color: #a5f3fc; /* cyan-200 */
            border-radius: 4px;
        }
        .bar-bottom {
            width: 100%;
            background-color: #1f2937; /* gray-800 */
            border-radius: 4px;
        }
        .x-label {
            position: absolute;
            bottom: -25px;
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .chart-stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }
        .cs-box {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .cs-indicator {
            width: 4px;
            height: 32px;
            border-radius: 2px;
        }
        .cs-ind-blue { background-color: #0369a1; }
        .cs-ind-dark { background-color: #1f2937; }
        .cs-ind-cyan { background-color: #a5f3fc; }
        .cs-val { font-size: 1.25rem; font-weight: 700; color: #111827; display: block;}
        .cs-lab { font-size: 0.75rem; color: #9ca3af; display: block;}

        /* Feedback Section */
        .feedback-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .feedback-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #f3f4f6;
            min-height: 240px; /* increased height */
        }
        .fb-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .fb-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .fb-name { font-size: 0.875rem; font-weight: 600; color: #111827; display: block;}
        .fb-date { font-size: 0.7rem; color: #9ca3af; display: block;}
        .fb-text {
            font-size: 0.8rem;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        /* Right Column Schedule */
        .schedule-widget {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f3f4f6;
        }
        
        .calendar-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            background: #f9fafb;
            border-radius: 12px;
            padding: 0.5rem;
        }
        .cal-nav {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            cursor: pointer;
        }
        .cal-dates {
            display: flex;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }
        .cal-day {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            border-radius: 8px;
            min-width: 40px;
        }
        .cal-day.active {
            background-color: #1f2937;
            color: white;
        }
        .cal-day .d { font-size: 0.65rem; font-weight: 500; margin-bottom: 0.2rem; }
        .cal-day .n { font-size: 0.875rem; font-weight: 700; }
        .cal-day.active .d, .cal-day.active .n { color: white; }
        .cal-day:not(.active) .d { color: #9ca3af; }
        .cal-day:not(.active) .n { color: #111827; }

        .schedule-title {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 1rem;
            display: block;
        }
        
        .sched-item {
            padding: 0.5rem 0 1rem 1.25rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
            position: relative;
        }
        .sched-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .sched-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            bottom: 1rem;
            width: 8px;
            border-radius: 4px;
            background-color: #a5f3fc; /* cyan default */
        }
        .sched-item:last-child:before {
            bottom: 0;
        }
        .sched-item.meeting:before { background-color: #fecdd3; /* pink */ }
        .sched-item.task:before { background-color: #e0f2fe; /* light blue */ }

        .sched-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        .sched-details {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .sched-details i {
            font-size: 0.3rem; /* dot separator */
            color: #d1d5db;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'doctors'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div>
                <a href="admin/doctors" class="back-link"><i class="bi bi-arrow-left"></i> Back to Doctor List</a>
                <h1 class="page-title">Doctor Details</h1>
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

        <div class="p-4 pt-4">
            <!-- REAL DASHBOARD STATS INJECTED HERE -->
            <div class="row g-3 mb-4 mx-2">
                <!-- Total Patients -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 6px solid #0052cc !important;">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Patients</div>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2rem;"><?= $totalPatients ?></h2>
                            </div>
                            <div class="rounded-circle bg-primary" style="width: 45px; height: 45px;"></div>
                        </div>
                    </div>
                </div>
                
                <!-- New Patients -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 6px solid #198754 !important;">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">New Patients</div>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2rem;"><?= $newPatients ?></h2>
                                <span class="text-success fw-bold" style="font-size: 0.75rem;">↑ This Month</span>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-person-plus-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Consults Today -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 6px solid #ffc107 !important;">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Consults Today</div>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2rem;"><?= $consultationsToday ?></h2>
                                <span class="text-warning fw-bold" style="font-size: 0.75rem;"><i class="bi bi-calendar-event me-1"></i> <?= count($upcomingAppointments) ?> Upcoming</span>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-clipboard2-check-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END STATS -->

        <div class="details-grid">
            
            <!-- Left Column: Profile -->
            <div class="col-left">
                <div class="pd-card p-4">
                    <div class="profile-img-container">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(str_replace('Dr. ', '', $doctor['full_name'])) ?>&background=random" alt="Avatar">
                </div>
                
                <h2 class="profile-name">Dr. <?= escape(str_replace('Dr. ', '', $doctor['full_name'])) ?></h2>
                <div class="profile-id"><?= escape($doctor['licence_no'] ?: 'WNH-GM-001') ?></div>
                
                <div class="text-center">
                    <?php if ($doctor['is_active']): ?>
                        <span class="status-badge"><i class="bi bi-check-circle me-1"></i> Available</span>
                    <?php else: ?>
                        <span class="status-badge inactive"><i class="bi bi-x-circle me-1"></i> Unavailable</span>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <span class="section-label">Specialist</span>
                    <div class="section-text fw-medium" style="color: #111827;"><?= escape($doctor['specialisation'] ?: 'General Medicine') ?></div>
                </div>

                <div class="mt-3">
                    <span class="section-label">About</span>
                    <div class="section-text" style="text-align: justify;">
                        <?= escape($doctor['about'] ?: 'No biography provided.') ?>
                    </div>
                </div>
                
                <hr style="border-color: #e5e7eb; margin: 1.5rem 0;">

                <div class="contact-item">
                    <i class="bi bi-telephone"></i>
                    <span><?= escape($doctor['phone'] ?: '+1 555-234-5678') ?></span>
                </div>
                <div class="contact-item">
                    <i class="bi bi-envelope"></i>
                    <span><?= escape($doctor['email']) ?></span>
                </div>
                <div class="contact-item">
                    <i class="bi bi-geo-alt"></i>
                    <span>OAUTHC Dental Clinic,<br>Ile-Ife, Osun State</span>
                </div>
                
                <hr style="border-color: #e5e7eb; margin: 1.5rem 0;">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-label mb-0 fw-bold" style="font-size: 0.875rem; color:#111827;">Work Experience</span>
                    <i class="bi bi-three-dots text-muted"></i>
                </div>

                <?php if (empty($experiences)): ?>
                    <div class="text-muted small">No work experience listed.</div>
                <?php else: ?>
                    <?php foreach ($experiences as $exp): ?>
                    <div class="exp-item">
                        <div class="exp-icon"><i class="bi bi-briefcase"></i></div>
                        <div class="exp-details">
                            <h6><?= escape($exp['job_title']) ?></h6>
                            <p><?= escape($exp['hospital_name']) ?></p>
                            <span><?= escape($exp['employment_type']) ?> &middot; <?= escape($exp['duration_text']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                </div>
            </div>

            <!-- Center Column: Analytics -->
            <div class="col-center">
                <!-- KPIs -->
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="d-flex align-items-center">
                            <div class="kpi-icon"><i class="bi bi-person"></i></div>
                            <div class="kpi-info">
                                <div class="kpi-label">Total Patients</div>
                                <div class="kpi-value"><?= $stats['total_patients'] ?></div>
                            </div>
                        </div>
                        <i class="bi bi-three-dots-vertical text-muted"></i>
                    </div>
                    <div class="kpi-card">
                        <div class="d-flex align-items-center">
                            <div class="kpi-icon"><i class="bi bi-calendar-event"></i></div>
                            <div class="kpi-info">
                                <div class="kpi-label">Total Appointments</div>
                                <div class="kpi-value"><?= $stats['total_appointments'] ?></div>
                            </div>
                        </div>
                        <i class="bi bi-three-dots-vertical text-muted"></i>
                    </div>
                </div>

                <!-- Chart Area (Visual Mock for Presentation) -->
                <div class="chart-section">
                    <div class="chart-header">
                        <h3 class="chart-title">Appointment Stats</h3>
                        <select class="chart-filter">
                            <option>This Week</option>
                        </select>
                    </div>

                    <div style="padding-left: 30px;">
                        <div class="chart-area">
                            <div class="y-axis y-12"><span class="y-label">12</span></div>
                            <div class="y-axis y-9"><span class="y-label">9</span></div>
                            <div class="y-axis y-6"><span class="y-label">6</span></div>
                            <div class="y-axis y-3"><span class="y-label">3</span></div>
                            <div class="y-axis y-0"><span class="y-label">0</span></div>

                            <?php
                            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            $maxVal = 12; // Static max for now, can be dynamic
                            foreach($chartData as $i => $data): 
                                $hTop = ($maxVal > 0) ? ($data['new'] / $maxVal * 100) : 0;
                                $hBot = ($maxVal > 0) ? ($data['fol'] / $maxVal * 100) : 0;
                                // Safeguard height over 100%
                                if ($hTop + $hBot > 100) { 
                                    $totalH = $hTop + $hBot;
                                    $hTop = ($hTop / $totalH) * 100; 
                                    $hBot = ($hBot / $totalH) * 100; 
                                }
                            ?>
                            <div class="bar-group">
                                <div class="chart-tooltip">
                                    <span class="tt-date"><?= date('D, d M', strtotime($data['date'])) ?></span>
                                    <div class="tt-row"><span><span class="tt-sq tt-sq-new"></span> New Patient</span><span class="val"><?= $data['new'] ?></span></div>
                                    <div class="tt-row"><span><span class="tt-sq tt-sq-fol"></span> Follow-Up</span><span class="val"><?= $data['fol'] ?></span></div>
                                </div>
                                <div class="bar-top" style="height: <?= $hTop ?>%;"></div>
                                <div class="bar-bottom" style="height: <?= $hBot ?>%;"></div>
                                <span class="x-label"><?= $daysOfWeek[$i] ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="chart-stats-grid">
                        <div class="cs-box">
                            <div class="cs-indicator cs-ind-blue"></div>
                            <div>
                                <span class="cs-val">50</span>
                                <span class="cs-lab">Total Appointments</span>
                            </div>
                        </div>
                        <div class="cs-box">
                            <div class="cs-indicator cs-ind-dark"></div>
                            <div>
                                <span class="cs-val">22</span>
                                <span class="cs-lab">New Patients</span>
                            </div>
                        </div>
                        <div class="cs-box">
                            <div class="cs-indicator cs-ind-cyan"></div>
                            <div>
                                <span class="cs-val">28</span>
                                <span class="cs-lab">Follow-Up Patients</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Schedule -->
            <div class="col-right">
                <div class="schedule-widget">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="chart-title">Schedule</h3>
                        <i class="bi bi-three-dots text-muted"></i>
                    </div>
                    
                    <div class="calendar-strip">
                        <div class="cal-nav">
                            <a href="admin/doctor/details?id=<?= $doctor['practitioner_id'] ?>&date=<?= date('Y-m-d', strtotime($selected_date . ' -1 day')) ?>" style="color: inherit;"><i class="bi bi-chevron-left"></i></a>
                        </div>
                        <div class="cal-dates">
                            <?php foreach ($calendar_days as $cday): ?>
                                <?php $isActive = ($cday === $selected_date); ?>
                                <a href="admin/doctor/details?id=<?= $doctor['practitioner_id'] ?>&date=<?= $cday ?>" class="cal-day <?= $isActive ? 'active' : '' ?>" style="text-decoration: none;">
                                    <span class="d"><?= date('D', strtotime($cday)) ?></span>
                                    <span class="n"><?= date('d', strtotime($cday)) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="cal-nav">
                            <a href="admin/doctor/details?id=<?= $doctor['practitioner_id'] ?>&date=<?= date('Y-m-d', strtotime($selected_date . ' +1 day')) ?>" style="color: inherit;"><i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>

                    <span class="schedule-title"><?= count($appointments) ?> schedule(s) for <?= date('M d', strtotime($selected_date)) ?></span>

                    <div class="schedule-list">
                        <?php if (empty($appointments)): ?>
                            <div class="text-muted small">No schedule items found.</div>
                        <?php else: ?>
                            <?php foreach ($appointments as $a): 
                                $classExt = '';
                                if ($a['category'] === 'task') {
                                    $classExt = $a['type'] === 'meeting' ? ' meeting' : ' task border-0';
                                }
                            ?>
                            <div class="sched-item<?= $classExt ?>">
                                <div class="sched-name text-dark"><?= escape($a['title']) ?></div>
                                <div class="sched-details">
                                    <span style="text-transform: capitalize;"><?= escape($a['details'] ?: 'Routine Check-Up') ?></span>
                                    <i class="bi bi-circle-fill"></i>
                                    <span><?= date('h:i A', strtotime($a['time'])) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Bottom Span: Feedback -->
            <div class="col-bottom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="chart-title">Feedback</h3>
                    <i class="bi bi-three-dots text-muted"></i>
                </div>
                
                <div class="feedback-grid">
                    <?php if (empty($feedbacks)): ?>
                        <div class="text-muted small col-span-4">No feedback available.</div>
                    <?php else: ?>
                        <?php foreach ($feedbacks as $fb): ?>
                        <div class="feedback-card">
                            <div class="fb-header">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($fb['patient_name']) ?>&background=random" class="fb-avatar">
                                <div>
                                    <span class="fb-name"><?= escape($fb['patient_name']) ?></span>
                                    <span class="fb-date"><?= date('Y-m-d', strtotime($fb['feedback_date'])) ?></span>
                                </div>
                            </div>
                            <p class="fb-text">
                                <?= escape($fb['feedback_text']) ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between pt-5 mt-2 pb-4" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
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

        </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
