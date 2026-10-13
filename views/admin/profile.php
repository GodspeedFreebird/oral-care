<?php
// We won't require the standard header.php to avoid conflicting with the custom layout.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OAUTHC Dental</title>
    <?php
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') $base_url .= '/';
    ?>
    <base href="<?= htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #1f2937;
            overflow-x: hidden;
        }
        
        /* Custom Scrollbar */
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
        .sidebar-item {
            margin-bottom: 0.25rem;
        }
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
        .sidebar-link i { font-size: 1.25rem; }
        
        /* Unlock Card */
        .unlock-card {
            margin: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%);
            border-radius: 16px;
            color: white;
        }
        .unlock-card h6 { font-weight: 700; margin-bottom: 0.5rem; }
        .unlock-card p { font-size: 0.75rem; opacity: 0.8; margin-bottom: 1rem; }
        .unlock-card .btn-light {
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.4rem 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 0;
            min-height: 100vh;
        }
        
        /* Top Header */
        .top-header {
            height: 72px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }
        .search-bar {
            position: relative;
            width: 350px;
        }
        .search-bar input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            font-size: 0.9rem;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        }
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
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
        .icon-btn:hover { background-color: #f3f4f6; }
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
        .user-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-profile .name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
        }

        /* Dashboard Grid Layout */
        .dashboard-container {
            padding: 1.5rem 2rem;
            display: flex;
            gap: 1.5rem;
        }
        .center-col {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .right-col {
            width: 320px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Cards common */
        .c-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 1.25rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .c-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .c-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }
        
        /* Stats row */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .stat-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.25rem;
            position: relative;
        }
        .stat-box .title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }
        .stat-box .value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        .trend-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
        }
        .trend-badge.up { background-color: #d1fae5; color: #059669; }
        .trend-badge.down { background-color: #fee2e2; color: #dc2626; }
        .trend-badge.neutral { background-color: #f3f4f6; color: #6b7280; }

        /* Charts grid */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        /* Mid row grid */
        .mid-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        /* Doctor List */
        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .doc-item:last-child { border-bottom: none; padding-bottom: 0; }
        .doc-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .doc-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0f2fe;
            color: #0369a1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
        }
        .doc-name { font-size: 0.875rem; font-weight: 600; color: #111827; margin: 0; }
        .doc-spec { font-size: 0.75rem; color: #6b7280; margin: 0; }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            border: 1px solid;
        }
        .status-badge.available { color: #059669; background-color: #ecfdf5; border-color: #a7f3d0; }
        .status-badge.unavailable { color: #dc2626; background-color: #fef2f2; border-color: #fecaca; }

        /* Report / Alerts list */
        .report-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            background-color: #f8fafc;
        }
        .report-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .report-text h6 { font-size: 0.85rem; font-weight: 600; margin: 0 0 0.2rem 0; color: #111827; }
        .report-text p { font-size: 0.75rem; color: #6b7280; margin: 0; }

        /* Custom Table */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .custom-table th {
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        .custom-table td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #111827;
            font-weight: 500;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .custom-table tr:last-child td { border-bottom: none; }
        
        .pill-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
        }
        .pill-badge.confirmed { background-color: #ccfbf1; color: #0f766e; }
        .pill-badge.pending { background-color: #fef08a; color: #854d0e; }
        .pill-badge.cancelled { background-color: #e5e7eb; color: #374151; }

        /* Right Sidebar Calendar */
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            gap: 4px;
        }
        .cal-day-name { font-size: 0.7rem; color: #9ca3af; font-weight: 600; margin-bottom: 0.5rem; }
        .cal-day {
            font-size: 0.8rem;
            font-weight: 500;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: auto;
            color: #4b5563;
        }
        .cal-day.active {
            background-color: #111827;
            color: white;
        }
        
        /* Activity Timeline */
        .timeline {
            position: relative;
            padding-left: 1.5rem;
            margin-top: 1rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0.5rem;
            bottom: 0;
            width: 2px;
            background-color: #e5e7eb;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .timeline-item:last-child { margin-bottom: 0; }
        .timeline-icon {
            position: absolute;
            left: -1.8rem;
            top: 0.2rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #14b8a6;
            border: 3px solid #ffffff;
        }
        .timeline-content p { font-size: 0.8rem; color: #111827; margin: 0 0 0.25rem 0; font-weight: 500; line-height: 1.4; }
        .timeline-content span { font-size: 0.7rem; color: #9ca3af; }

    </style>
</head>
<body>

    <?php $current_page = 'dashboard'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search anything">
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

        <div class="dashboard-container"><div class="center-col p-4 bg-white rounded shadow-sm"><h2 class="fw-bold mb-4">Profile</h2><p class="text-muted">Profile management coming soon...</p></div></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body>
</html>
