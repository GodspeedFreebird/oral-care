<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$monthNum = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('m');
$yearNum = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');

$firstDayOfMonth = sprintf('%04d-%02d-01', $yearNum, $monthNum);
$daysInMonth = (int)date('t', strtotime($firstDayOfMonth));
$dayOfWeek = (int)date('w', strtotime($firstDayOfMonth)); // 0 (Sun) to 6 (Sat)
$monthName = date('F', strtotime($firstDayOfMonth));

// Calculate previous month/year
$prevMonth = $monthNum - 1;
$prevYear = $yearNum;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

// Calculate next month/year
$nextMonth = $monthNum + 1;
$nextYear = $yearNum;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

// For previous month's filler days
$prevMonthDays = (int)date('t', strtotime(sprintf('%04d-%02d-01', $prevYear, $prevMonth)));

$totalCells = 42; // 6 rows * 7 columns

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors' Schedule - OAUTHC Dental</title>
    <?php
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') $base_url .= '/';
    ?>
    <base href="<?= htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff; 
            color: #111827;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #f3f4f6;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
            z-index: 100;
        }
        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            padding-left: 0.5rem;
        }
        .sidebar-brand i { color: #0f172a; font-size: 1.5rem; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex: 1; }
        .sidebar-item { margin-bottom: 0.25rem; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #a5f3fc;
            color: #155e75;
        }
        .sidebar-link i { font-size: 1.1rem; }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding: 1.5rem 2rem;
        }

        /* Top Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .search-bar {
            width: 400px;
            position: relative;
        }
        .search-bar input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            font-size: 0.9rem;
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
        
        /* Calendar Header */
        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .month-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .nav-arrows {
            display: flex;
            gap: 0.25rem;
            background-color: #f3f4f6;
            border-radius: 20px;
            padding: 0.25rem;
        }
        .nav-arrow {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            text-decoration: none;
            color: #4b5563;
        }
        .nav-arrow:hover { background-color: #e5e7eb; }
        
        .view-toggles {
            display: flex;
            background-color: #f3f4f6;
            border-radius: 20px;
            padding: 0.25rem;
            font-size: 0.85rem;
        }
        .view-toggle {
            padding: 0.4rem 1rem;
            border-radius: 16px;
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
        }
        .view-toggle.active {
            background-color: #1e3a8a; /* Dark blue from reference */
            color: white;
        }
        
        /* Calendar Grid */
        .calendar-container {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .calendar-header-cell {
            padding: 0.75rem;
            text-align: center;
            font-size: 0.85rem;
            font-weight: 500;
            color: #6b7280;
            border-right: 1px solid #e5e7eb;
        }
        .calendar-header-cell:last-child { border-right: none; }
        
        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-template-rows: repeat(6, 120px);
        }
        .calendar-cell {
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            overflow-y: auto;
            position: relative;
        }
        /* Remove borders for last column and bottom row */
        .calendar-cell:nth-child(7n) { border-right: none; }
        .calendar-cell:nth-last-child(-n+7) { border-bottom: none; }
        
        .day-number {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f2937;
            align-self: flex-end;
            margin-bottom: 0.25rem;
        }
        .day-number.sunday { color: #ef4444; } /* Red for Sunday dates based on ref */
        
        .calendar-cell.striped {
            background: repeating-linear-gradient(
                -45deg,
                #f9fafb,
                #f9fafb 10px,
                #ffffff 10px,
                #ffffff 20px
            );
            color: #9ca3af;
        }
        .calendar-cell.striped .day-number { color: #d1d5db; }
        
        /* Event Blocks */
        .event-block {
            background-color: #a5f3fc; /* Cyan base */
            padding: 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            line-height: 1.3;
        }
        .event-block.teal { background-color: #99f6e4; }
        .event-block.blue { background-color: #bfdbfe; }
        
        .event-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.15rem;
        }
        .event-desc {
            color: #4b5563;
            margin-bottom: 0.25rem;
            /* clamp text to 2 lines */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
        .event-time {
            color: #6b7280;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'schedule'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search anything">
            </div>
            
            <div class="header-actions">
                <a href="admin/settings" class="icon-btn" title="Settings" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; color: #4b5563; text-decoration: none;"><i class="bi bi-gear"></i></a>
                <div class="dropdown d-inline-block">
                    <a href="#" class="icon-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; color: #4b5563; text-decoration: none;">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" style="width: 250px;">
                        <li class="px-3 py-2 border-bottom fw-bold">Notifications</li>
                        <li class="px-3 py-3 text-center text-muted small">No new notifications</li>
                    </ul>
                </div>
                <div class="user-profile dropdown d-inline-block">
                    <div data-bs-toggle="dropdown" aria-expanded="false" style="display: flex; align-items: center; gap: 0.75rem; background-color: #f3f4f6; padding: 0.5rem 1rem 0.5rem 0.5rem; border-radius: 30px; cursor: pointer;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=0D8ABC&color=fff" alt="User" class="user-avatar" style="width: 32px; height: 32px; border-radius: 50%;">
                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;"><?= escape($_SESSION['username'] ?? 'Administrator') ?> <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem;"></i></span>
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
        <!-- Timeline Header -->
        <?php
            $view = $_GET['view'] ?? 'week';
            $currentDate = $_GET['date'] ?? date('Y-m-d');
            $doctorId = isset($_GET['doctor_id']) && $_GET['doctor_id'] !== '' ? (int)$_GET['doctor_id'] : null;
            
            try {
                $baseDate = new DateTime($currentDate);
            } catch (Exception $e) {
                $baseDate = new DateTime();
            }
            
            // Calculate navigation links
            if ($view === 'day') {
                $prev = clone $baseDate; $prev->modify('-1 day');
                $next = clone $baseDate; $next->modify('+1 day');
                
                $titleStr = $baseDate->format('l, d M Y');
                $weekDays = [$baseDate];
            } elseif ($view === 'month') {
                $prev = clone $baseDate; $prev->modify('-1 month');
                $next = clone $baseDate; $next->modify('+1 month');
                
                $titleStr = $baseDate->format('F Y');
            } else { // week
                $prev = clone $baseDate; $prev->modify('-1 week');
                $next = clone $baseDate; $next->modify('+1 week');
                
                $monday = clone $baseDate;
                if ($monday->format('w') != 1) { // 1 is Monday
                    $monday->modify('last monday');
                }
                $weekDays = [];
                for ($i = 0; $i < 7; $i++) {
                    $weekDays[] = clone $monday;
                    $monday->modify('+1 day');
                }
                $weekStartStr = $weekDays[0]->format('M d');
                $weekEndStr = $weekDays[6]->format('M d, Y');
                $titleStr = $weekStartStr . ' - ' . $weekEndStr;
            }
            
            $prevDateStr = $prev->format('Y-m-d');
            $nextDateStr = $next->format('Y-m-d');
            
            $startHour = 8;
            $endHour = 18;
            $hourHeight = 90; // bigger cells for spreadsheet look
            
            $colors = ['#3b82f6', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6'];
        ?>
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom bg-white rounded-top shadow-sm mt-4">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-week text-primary me-2"></i><?= $titleStr ?></h5>
            <div class="d-flex gap-2 align-items-center">
                
                <!-- Doctor Filter -->
                <form action="admin/schedule" method="GET" class="d-flex align-items-center me-3" id="doctorFilterForm">
                    <input type="hidden" name="view" value="<?= htmlspecialchars($view) ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($currentDate) ?>">
                    <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden border">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-person-badge"></i></span>
                        <select name="doctor_id" class="form-select border-0 fw-bold text-muted shadow-none" onchange="document.getElementById('doctorFilterForm').submit()" style="cursor:pointer; width: 180px;">
                            <option value="">All Doctors</option>
                            <?php foreach ($doctors as $doc): ?>
                                <option value="<?= $doc['practitioner_id'] ?>" <?= $doctorId === (int)$doc['practitioner_id'] ? 'selected' : '' ?>>
                                    Dr. <?= htmlspecialchars($doc['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <a href="admin/schedule?date=<?= $prevDateStr ?>&view=<?= htmlspecialchars($view) ?><?= $doctorId ? '&doctor_id='.$doctorId : '' ?>" class="btn btn-sm btn-light border rounded-pill text-muted fw-bold px-3 shadow-sm"><i class="bi bi-chevron-left"></i></a>
                <a href="admin/schedule?date=<?= $nextDateStr ?>&view=<?= htmlspecialchars($view) ?><?= $doctorId ? '&doctor_id='.$doctorId : '' ?>" class="btn btn-sm btn-light border rounded-pill text-muted fw-bold px-3 shadow-sm"><i class="bi bi-chevron-right"></i></a>
                
                <select class="form-select form-select-sm border rounded-pill text-muted fw-bold px-3 shadow-sm ms-2" style="width: auto;" onchange="window.location.href='admin/schedule?date=<?= $currentDate ?><?= $doctorId ? '&doctor_id='.$doctorId : '' ?>&view=' + this.value">
                    <option value="day" <?= $view === 'day' ? 'selected' : '' ?>>Day</option>
                    <option value="week" <?= $view === 'week' ? 'selected' : '' ?>>Week</option>
                    <option value="month" <?= $view === 'month' ? 'selected' : '' ?>>Month</option>
                </select>
                
                <button class="btn btn-sm btn-primary rounded-pill shadow-sm px-3 fw-bold ms-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal"><i class="bi bi-plus me-1"></i> Add New</button>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm rounded-bottom rounded-0 h-100 bg-white mb-4">
            <div class="card-body p-0">
                <?php if ($view === 'month'): ?>
                    <style>
                        .calendar-grid {
                            display: grid;
                            grid-template-columns: repeat(7, 1fr);
                            gap: 1px;
                            background-color: #e5e7eb;
                            border: 1px solid #e5e7eb;
                            overflow: hidden;
                        }
                        .calendar-header-day {
                            background-color: #f8fafc;
                            padding: 0.75rem;
                            text-align: center;
                            font-weight: 600;
                            font-size: 0.85rem;
                            color: #64748b;
                        }
                        .calendar-cell {
                            background-color: #ffffff;
                            min-height: 120px;
                            padding: 0.5rem;
                        }
                        .calendar-cell.striped {
                            background-image: repeating-linear-gradient(45deg, #f8fafc 0, #f8fafc 10px, #ffffff 10px, #ffffff 20px);
                        }
                        .day-number {
                            font-size: 0.85rem;
                            font-weight: 600;
                            color: #334155;
                            margin-bottom: 0.5rem;
                        }
                        .day-number.sunday { color: #ef4444; }
                        .event-block {
                            font-size: 0.7rem;
                            padding: 0.25rem 0.4rem;
                            border-radius: 0.25rem;
                            margin-bottom: 0.25rem;
                            color: #1e293b;
                            cursor: pointer;
                        }
                    </style>
                    <div class="p-4 bg-white" style="height: 700px; overflow-y: auto;">
                        <div class="calendar-grid shadow-sm rounded-3">
                            <?php 
                                $daysOfWeekStr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                foreach ($daysOfWeekStr as $dow) {
                                    echo '<div class="calendar-header-day">'.$dow.'</div>';
                                }
                                
                                $daysInMonthObj = clone $baseDate; $daysInMonthObj->modify('last day of this month');
                                $totalDays = (int)$daysInMonthObj->format('t');
                                
                                $firstDayOfMonthObj = clone $baseDate; $firstDayOfMonthObj->modify('first day of this month');
                                $dayOfWeekNum = (int)$firstDayOfMonthObj->format('N'); 
                                $offset = $dayOfWeekNum - 1; 
                                
                                $totalCells = ceil(($totalDays + $offset) / 7) * 7;
                                
                                // Group appointments by day
                                $eventsByDayAdmin = [];
                                foreach ($appointments as $appt) {
                                    $d = (int)date('j', strtotime($appt['appointment_date']));
                                    $eventsByDayAdmin[$d][] = $appt;
                                }
                                
                                for ($i = 0; $i < $totalCells; $i++) {
                                    if ($i < $offset) {
                                        echo '<div class="calendar-cell striped"></div>';
                                    } elseif ($i >= $offset && $i < ($totalDays + $offset)) {
                                        $dayNum = $i - $offset + 1;
                                        $isSunday = (($i + 1) % 7 == 0) ? 'sunday' : '';
                                        
                                        echo '<div class="calendar-cell">';
                                        echo '<div class="day-number '.$isSunday.'">'.$dayNum.'</div>';
                                        
                                        if (isset($eventsByDayAdmin[$dayNum])) {
                                            foreach ($eventsByDayAdmin[$dayNum] as $event) {
                                                $bgColor = $colors[$event['appointment_id'] % count($colors)];
                                                $timeStr = date('h:i A', strtotime($event['appointment_time']));
                                                $patientName = htmlspecialchars($event['patient_name']);
                                                $drName = htmlspecialchars($event['practitioner_name'] ?? 'Doctor');
                                                
                                                echo '<div class="event-block text-white" style="background-color: '.$bgColor.';" onclick="openPatientRecord('.$event['patient_id'].')">';
                                                echo '<strong>'.$timeStr.'</strong> - '.$patientName.' (Dr. '.$drName.')';
                                                echo '</div>';
                                            }
                                        }
                                        
                                        echo '</div>';
                                    } else {
                                        echo '<div class="calendar-cell striped"></div>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Spreadsheet Grid Header -->
                <div class="d-flex border-bottom bg-light">
                    <div style="width: 70px;" class="flex-shrink-0 border-end"></div>
                    <?php foreach ($weekDays as $day): ?>
                        <div class="flex-fill border-end text-center py-3" style="width: 0;">
                            <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <?= $day->format('D') ?>
                            </div>
                            <div class="fs-4 fw-bold text-dark <?php echo ($day->format('Y-m-d') == date('Y-m-d')) ? 'text-primary' : ''; ?>">
                                <?= $day->format('d') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="position-relative" style="height: <?= ($endHour - $startHour + 1) * $hourHeight ?>px; overflow-y: auto;">
                    <!-- Grid Lines -->
                    <?php for ($hour = $startHour; $hour <= $endHour; $hour++): ?>
                        <div class="d-flex position-absolute w-100" style="top: <?= ($hour - $startHour) * $hourHeight ?>px; height: <?= $hourHeight ?>px;">
                            <!-- Time Label -->
                            <div style="width: 70px;" class="flex-shrink-0 border-end border-bottom bg-white text-end pe-2 py-2">
                                <span class="text-muted fw-bold" style="font-size: 0.75rem;"><?= str_pad($hour > 12 ? $hour - 12 : $hour, 2, '0', STR_PAD_LEFT) ?> <?= $hour >= 12 ? 'PM' : 'AM' ?></span>
                            </div>
                            <!-- Day Columns -->
                            <?php foreach ($weekDays as $day): ?>
                                <div class="flex-fill border-end border-bottom position-relative" style="width: 0;"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endfor; ?>

                    <!-- Appointments Container -->
                    <div class="position-absolute w-100 h-100" style="top: 0; left: 0; pointer-events: none;">
                        <div class="d-flex w-100 h-100">
                            <!-- spacer for time column -->
                            <div style="width: 70px;" class="flex-shrink-0"></div>
                            
                            <!-- columns for events -->
                            <?php foreach ($weekDays as $day): ?>
                                <div class="flex-fill position-relative h-100" style="width: 0; pointer-events: auto;">
                                <?php
                                    $dateKey = $day->format('Y-m-d');
                                    // Filter appointments for this date
                                    $dayAppts = array_filter($appointments, function($a) use ($dateKey) {
                                        return strpos($a['appointment_date'], $dateKey) === 0;
                                    });
                                    
                                    // Group overlapping appointments
                                    $overlappingGroups = [];
                                    foreach ($dayAppts as $appt) {
                                        $timeStr = date('H:i', strtotime($appt['appointment_time']));
                                        if (!isset($overlappingGroups[$timeStr])) {
                                            $overlappingGroups[$timeStr] = [];
                                        }
                                        $overlappingGroups[$timeStr][] = $appt;
                                    }
                                    
                                    foreach ($overlappingGroups as $timeStr => $apptsGroup):
                                        $timeParts = explode(':', $timeStr);
                                        $h = (int)$timeParts[0];
                                        $m = (int)$timeParts[1];
                                        
                                        if ($h >= $startHour && $h <= $endHour):
                                            $topOffset = round((($h - $startHour) + ($m / 60)) * $hourHeight);
                                            $durationMinutes = 60; // Assume 1 hour for UI sake
                                            $blockHeight = round(($durationMinutes / 60) * $hourHeight);
                                            
                                            $count = count($apptsGroup);
                                            $widthPercent = 100 / $count;
                                            
                                            foreach ($apptsGroup as $index => $appt): 
                                                $leftPercent = $index * $widthPercent;
                                                $color = $colors[$appt['appointment_id'] % count($colors)];
                                ?>
                                    <div class="position-absolute px-1" style="top: <?= $topOffset ?>px; left: <?= $leftPercent ?>%; width: <?= $widthPercent ?>%; height: <?= $blockHeight ?>px; z-index: 10;">
                                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden appointment-card" 
                                             style="border-top: 3px solid <?= $color ?> !important; cursor: pointer; transition: transform 0.2s; background-color: #ffffff;"
                                             onclick="openPatientRecord(<?= (int)$appt['patient_id'] ?>)"
                                             onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                            <div class="card-body p-2 d-flex flex-column justify-content-center">
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="rounded-circle overflow-hidden flex-shrink-0 bg-light text-primary d-flex align-items-center justify-content-center fw-bold me-2 border" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                        <?php if (!empty($appt['profile_picture'])): ?>
                                                            <img src="<?= htmlspecialchars($appt['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                                                        <?php else: ?>
                                                            <?= substr($appt['patient_name'], 0, 1) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;"><?= htmlspecialchars($appt['patient_name']) ?></div>
                                                </div>
                                                <div class="text-muted text-truncate" style="font-size: 0.7rem;">
                                                    <i class="bi bi-clock me-1"></i> <?= date('h:i A', strtotime($timeStr)) ?>
                                                </div>
                                                <div class="text-muted text-truncate mt-1" style="font-size: 0.7rem;">
                                                    <i class="bi bi-person-badge me-1"></i> Dr. <?= htmlspecialchars($appt['practitioner_name'] ?? 'Unknown') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                            endforeach;
                                        endif;
                                    endforeach;
                                ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between pt-5 mt-4 pb-2" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
            <div>Copyright &copy; <?= date('Y') ?> OAUTHC Dental</div>
            <div class="d-flex gap-4">
                <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Term and conditions</a>
                <a href="#" class="text-muted text-decoration-none">Contact</a>
            </div>
        </div>

    </main>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form method="POST" action="admin/appointments/add">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Add Schedule / Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Patient</label>
                    <select class="form-select" name="patient_id" required>
                        <option value="">Select Patient...</option>
                        <?php foreach($patients as $pat): ?>
                            <option value="<?= $pat['patient_id'] ?>"><?= htmlspecialchars($pat['full_name']) ?> (<?= htmlspecialchars($pat['patient_number']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Doctor</label>
                    <select class="form-select" name="practitioner_id" required>
                        <option value="">Select Doctor...</option>
                        <?php foreach($doctors as $doc): ?>
                            <option value="<?= $doc['practitioner_id'] ?>">Dr. <?= htmlspecialchars($doc['full_name']) ?> (<?= htmlspecialchars($doc['specialisation']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Date</label>
                        <input type="date" class="form-control" name="appointment_date" value="<?= htmlspecialchars($currentDate) ?>" required>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Time</label>
                        <input type="time" class="form-control" name="appointment_time" required>
                     </div>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Reason for Visit</label>
                    <input type="text" class="form-control" name="reason" placeholder="e.g. Routine Check-Up">
                 </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Create Appointment</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Patient Record Offcanvas -->
    <div class="offcanvas offcanvas-end shadow" tabindex="-1" id="patientRecordOffcanvas" aria-labelledby="patientRecordOffcanvasLabel" style="width: 85%;">
      <div class="offcanvas-header bg-primary text-white border-bottom border-light border-opacity-25" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);">
        <h5 class="offcanvas-title fw-bold" id="patientRecordOffcanvasLabel"><i class="bi bi-person-lines-fill me-2"></i>Patient Record</h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body p-0 bg-white" id="patientRecordContent">
        <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted">
          <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
          <div class="fw-medium">Loading patient record...</div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showConsultation(id) {
        if(!id) return;
        const tabLink = document.querySelector('button[data-bs-target="#history-tab-pane"]');
        if (tabLink) { new bootstrap.Tab(tabLink).show(); }
        
        document.querySelectorAll('.consultation-item').forEach(el => {
            el.classList.remove('bg-primary');
            el.classList.remove('text-white');
            el.classList.add('bg-light');
        });
        
        document.querySelectorAll('.consultation-pane').forEach(el => {
            el.classList.remove('show', 'active');
        });
        
        const selectedItem = document.getElementById('consultation-item-' + id);
        if(selectedItem) {
            selectedItem.classList.remove('bg-light');
            selectedItem.classList.add('bg-primary');
            selectedItem.classList.add('text-white');
        }
        
        const selectedPane = document.getElementById('consultation-pane-' + id);
        if(selectedPane) {
            selectedPane.classList.add('show', 'active');
        }
    }

    function initHistorySearch() {
        const searchInput = document.getElementById('historySearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const items = document.querySelectorAll('.consultation-item');
                items.forEach(item => {
                    const date = item.querySelector('.fw-bold').textContent.toLowerCase();
                    if (date.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    }
    
    function openPatientRecord(patientId) {
        const offcanvasEl = document.getElementById('patientRecordOffcanvas');
        if (!offcanvasEl) return;
        
        const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
        const contentDiv = document.getElementById('patientRecordContent');
        
        contentDiv.innerHTML = `
            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                <div class="fw-medium">Loading patient record...</div>
            </div>
        `;
        
        bsOffcanvas.show();
        
        fetch(`<?= isset($base_url) ? $base_url : '' ?>admin/patients/${patientId}/partial`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                contentDiv.innerHTML = html;
                if (typeof initHistorySearch === 'function') {
                    initHistorySearch();
                }
            })
            .catch(error => {
                console.error('Error fetching patient record:', error);
                contentDiv.innerHTML = `
                    <div class="p-5 text-center text-danger">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <p class="mt-3 fw-bold">Error loading record</p>
                        <p class="small text-muted">${error.message}</p>
                    </div>
                `;
            });
    }
    </script>
</body>
</html>
