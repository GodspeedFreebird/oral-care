<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        <!-- Sidebar -->
        <?php 
            if (isset($isAdminView) && $isAdminView) {
                $current_page = 'schedule';
                require_once VIEWS_PATH . '/layouts/admin_sidebar.php';
            } else {
                $current_page = 'practitioner_schedule';
                require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php';
            }
        ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            
            <!-- Top Header Area -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center bg-white p-3 shadow-sm rounded mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 text-dark">Schedule</h4>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary rounded shadow-sm px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#addAppointmentModal"><i class="bi bi-plus me-1"></i> Add New</button>
                    <button class="btn btn-light rounded-circle shadow-sm ms-2" style="width: 40px; height: 40px;"><i class="bi bi-bell"></i></button>
                </div>
            </div>

            <div class="row g-4">
                <!-- Main Column (Timeline View) -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-0">
                            <!-- Timeline Header -->
                            <!-- Timeline Header -->
                            <?php
                                $view = $_GET['view'] ?? 'week';
                                $currentDate = $_GET['date'] ?? date('Y-m-d');
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
                            <div class="d-flex justify-content-between align-items-center p-4 border-bottom bg-white">
                                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-week text-primary me-2"></i><?= $titleStr ?></h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <a href="doctor/schedule?date=<?= $prevDateStr ?>&view=<?= htmlspecialchars($view) ?>" class="btn btn-sm btn-light border rounded-pill text-muted fw-bold px-3 shadow-sm"><i class="bi bi-chevron-left"></i></a>
                                    <a href="doctor/schedule?date=<?= $nextDateStr ?>&view=<?= htmlspecialchars($view) ?>" class="btn btn-sm btn-light border rounded-pill text-muted fw-bold px-3 shadow-sm"><i class="bi bi-chevron-right"></i></a>
                                    <select class="form-select form-select-sm border rounded-pill text-muted fw-bold px-3 shadow-sm ms-2" style="width: auto;" onchange="window.location.href='doctor/schedule?date=<?= $currentDate ?>&view=' + this.value">
                                        <option value="day" <?= $view === 'day' ? 'selected' : '' ?>>Day</option>
                                        <option value="week" <?= $view === 'week' ? 'selected' : '' ?>>Week</option>
                                        <option value="month" <?= $view === 'month' ? 'selected' : '' ?>>Month</option>
                                    </select>
                                </div>
                            </div>
                            
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
                                    .calendar-header {
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
                                            $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                            foreach ($daysOfWeek as $dow) {
                                                echo '<div class="calendar-header">'.$dow.'</div>';
                                            }
                                            
                                            $daysInMonth = clone $baseDate; $daysInMonth->modify('last day of this month');
                                            $totalDays = (int)$daysInMonth->format('t');
                                            
                                            $firstDayOfMonth = clone $baseDate; $firstDayOfMonth->modify('first day of this month');
                                            // 1 (for Monday) through 7 (for Sunday)
                                            $dayOfWeek = (int)$firstDayOfMonth->format('N'); 
                                            $offset = $dayOfWeek - 1; 
                                            
                                            $totalCells = ceil(($totalDays + $offset) / 7) * 7;
                                            
                                            // Group appointments by day
                                            $eventsByDay = [];
                                            foreach ($appointments as $appt) {
                                                $d = (int)date('j', strtotime($appt['appointment_date']));
                                                $eventsByDay[$d][] = $appt;
                                            }
                                            
                                            for ($i = 0; $i < $totalCells; $i++) {
                                                if ($i < $offset) {
                                                    echo '<div class="calendar-cell striped"></div>';
                                                } elseif ($i >= $offset && $i < ($totalDays + $offset)) {
                                                    $dayNum = $i - $offset + 1;
                                                    $isSunday = (($i + 1) % 7 == 0) ? 'sunday' : '';
                                                    
                                                    echo '<div class="calendar-cell">';
                                                    echo '<div class="day-number '.$isSunday.'">'.$dayNum.'</div>';
                                                    
                                                    if (isset($eventsByDay[$dayNum])) {
                                                        foreach ($eventsByDay[$dayNum] as $event) {
                                                            $bgColor = $colors[$event['appointment_id'] % count($colors)];
                                                            $timeStr = date('h:i A', strtotime($event['appointment_time']));
                                                            $patientName = htmlspecialchars($event['patient_name']);
                                                            
                                                            echo '<div class="event-block text-white" style="background-color: '.$bgColor.';" onclick="openPatientRecord('.$event['patient_id'].')">';
                                                            echo '<strong>'.$timeStr.'</strong> - '.$patientName;
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
                                    <?php $isToday = ($day->format('Y-m-d') === date('Y-m-d')); ?>
                                    <div class="flex-fill text-center py-2 border-end <?= $isToday ? 'bg-primary bg-opacity-10' : '' ?>" style="width: 0;">
                                        <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.7rem;"><?= $day->format('D') ?></div>
                                        <div class="fw-bold fs-5 <?= $isToday ? 'text-primary' : 'text-dark' ?>"><?= $day->format('d') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Spreadsheet Grid Body -->
                            <div class="d-flex position-relative bg-white" style="height: 700px; overflow-y: auto;">
                                <!-- Time Column -->
                                <div style="width: 70px;" class="flex-shrink-0 border-end bg-white z-2 position-sticky start-0">
                                    <?php for ($h = $startHour; $h <= $endHour; $h++): ?>
                                        <div class="text-center text-muted fw-medium" style="height: <?= $hourHeight ?>px; font-size: 0.75rem; transform: translateY(-0.5rem);">
                                            <?= str_pad($h, 2, '0', STR_PAD_LEFT) ?>:00
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                
                                <!-- Day Columns -->
                                <?php foreach ($weekDays as $day): ?>
                                    <?php 
                                        $currentDateStr = $day->format('Y-m-d');
                                        // Filter appointments for this day
                                        $dayAppts = array_filter($appointments, function($a) use ($currentDateStr) {
                                            return date('Y-m-d', strtotime($a['appointment_date'])) === $currentDateStr;
                                        });
                                        
                                        // Group by time
                                        $timeGroups = [];
                                        foreach ($dayAppts as $appt) {
                                            $timeGroups[$appt['appointment_time']][] = $appt;
                                        }
                                    ?>
                                    <div class="flex-fill border-end position-relative" style="width: 0;">
                                        <!-- Grid Lines -->
                                        <?php for ($h = $startHour; $h <= $endHour; $h++): ?>
                                            <div class="border-top" style="height: <?= $hourHeight ?>px; border-color: #f1f5f9 !important;"></div>
                                        <?php endfor; ?>
                                        
                                        <!-- Appointment Cards -->
                                        <?php foreach ($timeGroups as $timeStr => $apptsGroup): ?>
                                            <?php 
                                                $parts = explode(':', $timeStr);
                                                $h = (int)$parts[0];
                                                $m = (int)$parts[1];
                                                
                                                if ($h >= $startHour && $h <= $endHour) {
                                                    $topOffset = round(($h - $startHour) * $hourHeight + ($m / 60) * $hourHeight);
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
                                                                <i class="bi bi-clock me-1"></i> <?= date('H:i', strtotime($timeStr)) ?> - <?= date('H:i', strtotime($timeStr) + $durationMinutes*60) ?>
                                                            </div>
                                                            <div class="text-muted text-truncate mt-1" style="font-size: 0.7rem;">
                                                                <i class="bi bi-clipboard2-pulse me-1"></i> <?= htmlspecialchars($appt['reason'] ?? 'Consultation') ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php 
                                                    endforeach;
                                                } 
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
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

<!-- Add Appointment Modal -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form method="POST" action="doctor/schedule/add">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
          <div class="modal-header border-0 pb-0 pt-4 px-4">
            <h5 class="modal-title fw-bold">Add New Appointment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body px-4 py-3">
             <div class="mb-3">
                <label class="form-label text-muted" style="font-size:0.85rem;">Patient</label>
                <select class="form-select" name="patient_id" required>
                    <option value="">Select Patient...</option>
                    <?php if (isset($patients)): foreach($patients as $pat): ?>
                        <option value="<?= $pat['patient_id'] ?>"><?= htmlspecialchars($pat['full_name']) ?> (<?= htmlspecialchars($pat['patient_number']) ?>)</option>
                    <?php endforeach; endif; ?>
                </select>
             </div>
             <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Date</label>
                    <input type="date" class="form-control" name="appointment_date" value="<?= htmlspecialchars($currentDate ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Time</label>
                    <input type="time" class="form-control" name="appointment_time" required>
                </div>
             </div>
             <div class="mb-3">
                <label class="form-label text-muted" style="font-size:0.85rem;">Reason for Visit</label>
                <input type="text" class="form-control" name="reason" placeholder="e.g. Consultation, Checkup" required>
             </div>
          </div>
          <div class="modal-footer border-0 px-4 pb-4 pt-0">
            <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Save Appointment</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
function openPatientRecord(patientId) {
    const offcanvasEl = document.getElementById('patientRecordOffcanvas');
    if (!offcanvasEl) return;
    
    const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
    const contentDiv = document.getElementById('patientRecordContent');
    
    // Show loading skeleton immediately
    contentDiv.innerHTML = `
        <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <div class="fw-medium">Loading patient record...</div>
        </div>
    `;
    
    bsOffcanvas.show();
    
    // Fetch patient record partial HTML
    fetch(`<?= isset($base_url) ? $base_url : '' ?>doctor/patients/${patientId}/partial`)
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

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
