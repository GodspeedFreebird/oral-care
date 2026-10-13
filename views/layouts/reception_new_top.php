<?php
// Shared top layout for the new Receptionist UI
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Receptionist' ?></title>
    <?php
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') {
            $base_url .= '/';
        }
    ?>
    <base href="<?php echo htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #1976d2;
            --bg-light: #f4f6f8;
            --text-dark: #2c3e50;
            --text-muted: #828d99;
        }
        body {
            background-color: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            overflow: hidden;
            margin: 0;
            color: var(--text-dark);
        }
        
        /* Layout Wrapper */
        .dashboard-wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* 1. Left Sidebar */
        .sidebar {
            width: 80px;
            background-color: var(--primary-teal);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            z-index: 10;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .sidebar-icon {
            color: rgba(255,255,255,0.6);
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-decoration: none;
            transition: 0.3s;
            position: relative;
        }
        .sidebar-icon:hover {
            color: white;
        }
        .sidebar-icon.active {
            color: var(--primary-teal);
            background-color: white;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            margin-bottom: 30px;
        }

        /* 2. Main Center Content */
        .main-content {
            flex-grow: 1;
            background-color: white;
            margin: 15px 0 15px -15px; /* Pull it slightly under the sidebar */
            border-radius: 20px;
            padding: 30px 40px;
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            display: block;
        }

        /* Header (Search & Add Patient) */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .search-box {
            background-color: var(--bg-light);
            border-radius: 10px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            width: 300px;
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            margin-left: 10px;
            color: var(--text-dark);
        }
    </style>
    <!-- Additional page-specific styles can be injected here if needed -->
    <?php if(isset($extra_css)) echo $extra_css; ?>
</head>
<body>

<div class="dashboard-wrapper">
    
    <!-- 1. Left Sidebar -->
    <div class="sidebar">
        <a href="reception/dashboard" class="sidebar-icon <?= ($current_page === 'reception_dashboard') ? 'active' : '' ?>" title="Home"><i class="bi bi-house-door-fill"></i></a>
        <a href="reception/patients/search" class="sidebar-icon <?= ($current_page === 'reception_search') ? 'active' : '' ?>" title="Patients"><i class="bi bi-people"></i></a>
        <a href="messages" class="sidebar-icon <?= ($current_page === 'messages') ? 'active' : '' ?>" title="Messages"><i class="bi bi-envelope"></i></a>
        <a href="reception/reports" class="sidebar-icon <?= ($current_page === 'reception_reports') ? 'active' : '' ?>" title="Reports"><i class="bi bi-file-earmark-text"></i></a>
        <a href="reception/calendar" class="sidebar-icon <?= ($current_page === 'reception_calendar') ? 'active' : '' ?>" title="Calendar"><i class="bi bi-calendar3"></i></a>
        
        <a href="reception/profile" class="sidebar-icon <?= ($current_page === 'reception_profile') ? 'active' : '' ?>" style="margin-top: auto; margin-bottom: 30px;" title="Profile"><i class="bi bi-person-circle"></i></a>
        <a href="logout" class="sidebar-icon" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
    </div>
