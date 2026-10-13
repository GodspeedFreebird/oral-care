<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal - OAUTHC Dental</title>
    <?php
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') $base_url .= '/';
        
        $unreadCount = 0;
        if (isset($_SESSION['user_id'])) {
            try {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND is_read = 0");
                $stmt->execute([$_SESSION['user_id']]);
                $unreadCount = $stmt->fetchColumn();
            } catch (Exception $e) {
                // Ignore error
            }
        }
    ?>
    <base href="<?php echo htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', system-ui, sans-serif; 
            color: #333;
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
        }
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }
        .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 0.85rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #ccfbf1;
            color: #0f766e;
        }
        .nav-link i { 
            margin-right: 12px; 
            font-size: 1.1rem;
        }
        .patient-header-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .profile-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #6c757d;
        }
        .custom-tabs .nav-link {
            border: none;
            color: #6c757d;
            padding: 0.6rem 1.25rem;
            margin-right: 0.5rem;
            border-radius: 20px;
            font-size: 0.95rem;
            background: transparent;
        }
        .custom-tabs .nav-link:hover {
            background-color: #f8f9fa;
        }
        .custom-tabs .nav-link.active {
            background-color: #f8f9fa;
            color: #212529;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .info-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: #212529;
        }
        .breadcrumb-text {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .breadcrumb-text a {
            color: #6c757d;
            text-decoration: none;
        }
        .breadcrumb-text .active {
            color: #212529;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column py-4 px-3">
        <a href="patient/dashboard" class="d-flex align-items-center mb-5 text-decoration-none text-dark px-2">
            <i class="bi bi-hospital text-primary fs-3 me-2"></i>
            <span class="fs-5 fw-bold text-primary">OAUTHC Dental</span>
        </a>
        
        <!-- Optional Search Bar placeholder to match reference -->
        <div class="mb-4 px-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Search">
            </div>
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="patient/dashboard" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                    <i class="bi bi-grid"></i> Overview
                </a>
            </li>
            <li>
                <a href="patient/profile" class="nav-link <?php echo ($current_page == 'profile') ? 'active' : ''; ?>">
                    <i class="bi bi-person"></i> My Profile
                </a>
            </li>
            <li>
                <a href="messages" class="nav-link <?php echo ($current_page == 'messages' ?? false) ? 'active' : ''; ?>">
                    <i class="bi bi-chat-dots"></i> Messages
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger rounded-pill float-end mt-1"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="patient/doctors" class="nav-link <?php echo ($current_page == 'doctors') ? 'active' : 'text-muted'; ?>">
                    <i class="bi bi-person-badge"></i> Doctor
                </a>
            </li>
            <li>
                <a href="patient/appointments" class="nav-link <?php echo ($current_page == 'appointments') ? 'active' : 'text-muted'; ?>">
                    <i class="bi bi-calendar-event"></i> Appointment
                </a>
            </li>
            <li>
                <a href="patient/records" class="nav-link <?php echo ($current_page == 'records') ? 'active' : 'text-muted'; ?>">
                    <i class="bi bi-file-medical"></i> Medical Records
                </a>
            </li>
            <li>
                <a href="patient/notifications" class="nav-link <?php echo ($current_page == 'notifications') ? 'active' : 'text-muted'; ?>">
                    <i class="bi bi-bell"></i> Notification History
                </a>
            </li>


            <li>
                <a href="patient/dashboard" class="nav-link text-muted">
                    <i class="bi bi-bar-chart"></i> Analytics
                </a>
            </li>
        </ul>
        <hr class="text-muted">
        <div class="px-2 pb-2">
            <div class="d-flex align-items-center mb-3">
                <?php if (isset($patient) && !empty($patient['profile_picture'])): ?>
                    <img src="<?php echo escape($patient['profile_picture']); ?>" alt="Avatar" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                <?php else: ?>
                    <i class="bi bi-person-circle fs-3 text-secondary me-2"></i>
                <?php endif; ?>
                <div>
                    <div class="fw-bold small lh-1"><?php echo escape($_SESSION['username'] ?? 'Patient'); ?></div>
                    <div class="text-muted" style="font-size: 0.75rem;">Patient Portal</div>
                </div>
            </div>
            <a href="logout" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="main-content">
        <!-- Breadcrumb area matching layout -->
        <div class="mb-4 breadcrumb-text">
            <i class="bi bi-arrow-left me-2"></i>
            <a href="patient/dashboard">Patients</a> / 
            <a href="patient/dashboard">Patient Details</a> / 
            <span class="active"><?php echo escape($patient['full_name'] ?? 'Dashboard'); ?></span>
        </div>

<?php require_once BASE_PATH . '/views/layouts/session_timeout_modal.php'; ?>
