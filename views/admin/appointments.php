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
    <title>Appointments - OAUTHC Dental</title>
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
        
        /* Unlock Card */
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
            background-color: #ffffff;
        }
        
        /* Top Header */
        .top-header {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            background-color: #ffffff;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
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

        /* Appointments Controls */
        .appointments-controls {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .status-tabs {
            display: flex;
            background-color: #f3f4f6;
            border-radius: 30px;
            padding: 0.25rem;
        }
        .status-tab {
            padding: 0.5rem 1.25rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            background: transparent;
        }
        .status-tab.active {
            background-color: #1f2937;
            color: #ffffff;
        }
        .status-tab:hover:not(.active) { color: #111827; }
        
        .right-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .search-bar input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            font-size: 0.875rem;
            width: 250px;
        }
        .search-bar { position: relative; }
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        /* Table Styles */
        .table-container {
            padding: 0 2rem;
        }
        .appointments-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
        }
        .appointments-table th {
            background-color: #f9fafb;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .appointments-table td {
            padding: 1.25rem 1rem;
            font-size: 0.875rem;
            color: #111827;
            font-weight: 500;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            background-color: #ffffff;
        }
        .appointments-table tr:last-child td { border-bottom: none; }
        
        /* Selected Row Highlighting */
        .appointments-table tr.selected td {
            background-color: #f0fdfa; /* light teal */
        }

        .pill-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }
        .pill-badge.confirmed, .pill-badge.completed { background-color: #ccfbf1; color: #0f766e; }
        .pill-badge.pending { background-color: #fee2e2; color: #991b1b; }
        .pill-badge.cancelled, .pill-badge.no_show { background-color: #e5e7eb; color: #374151; }

        .action-link {
            color: #6b7280;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            margin-right: 0.75rem;
        }
        .action-link:hover { color: #111827; }
        
        /* Pagination */
        .pagination-container {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-info { font-size: 0.875rem; color: #6b7280; }
        .page-controls { display: flex; gap: 0.5rem; }
        .page-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
        }
        .page-btn.active { background: #1f2937; color: #ffffff; border-color: #1f2937; }
        
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'appointments'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">Appointments</h1>
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

        <!-- Success Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="px-4 pt-2">
                <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-0" style="font-size:0.9rem;">
                    <i class="bi bi-check-circle-fill me-2"></i><?= escape($_SESSION['success']) ?>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Controls Row -->
        <div class="appointments-controls">
            <div class="status-tabs">
                <button class="status-tab active" data-filter="all">All (<?= $counts['all'] ?>)</button>
                <button class="status-tab" data-filter="confirmed">Confirmed (<?= $counts['confirmed'] ?>)</button>
                <button class="status-tab" data-filter="pending">Pending (<?= $counts['pending'] ?>)</button>
                <button class="status-tab" data-filter="cancelled">Cancelled (<?= $counts['cancelled'] ?>)</button>
            </div>
            
            <div class="right-controls">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search placeholder">
                </div>
                <div class="dropdown">
                    <button class="btn btn-light border px-3" style="border-radius: 8px; font-size: 0.875rem;" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar3 me-2"></i> Today <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                </div>
                <button class="btn btn-dark fw-bold px-4" style="border-radius: 8px; background-color: #1f2937;">Add Appointment</button>
            </div>
        </div>

        <!-- Table Data -->
        <div class="table-container">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input class="form-check-input border-secondary" type="checkbox" id="selectAll"></th>
                        <th>Name <i class="bi bi-arrow-down-up ms-1"></i></th>
                        <th>Date <i class="bi bi-arrow-down-up ms-1"></i></th>
                        <th>Time <i class="bi bi-arrow-down-up ms-1"></i></th>
                        <th>Doctor <i class="bi bi-arrow-down-up ms-1"></i></th>
                        <th>Treatment <i class="bi bi-arrow-down-up ms-1"></i></th>
                        <th>Status <i class="bi bi-arrow-down-up ms-1"></i></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="appointmentsBody">
                    <?php foreach ($appointments as $appt): ?>
                    <?php $status = strtolower($appt['status']); ?>
                    <tr class="appt-row" data-status="<?= $status ?>">
                        <td><input class="form-check-input border-secondary row-checkbox" type="checkbox"></td>
                        <td><?= escape($appt['patient_name']) ?></td>
                        <td><?= date('d M Y', strtotime($appt['appointment_date'])) ?></td>
                        <td><?= date('h:i A', strtotime($appt['appointment_time'])) ?></td>
                        <td>Dr. <?= escape($appt['doctor_name']) ?></td>
                        <td><?= escape($appt['reason'] ?: 'Routine Check-Up') ?></td>
                        <td>
                            <span class="pill-badge <?= $status ?>">
                                <?php
                                    if ($status === 'confirmed' || $status === 'completed') echo 'Confirmed';
                                    elseif ($status === 'pending') echo 'Pending';
                                    else echo 'Cancelled';
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($status !== 'cancelled'): ?>
                            <a href="#" class="action-link btn-reschedule" data-id="<?= $appt['appointment_id'] ?>" data-date="<?= $appt['appointment_date'] ?>" data-time="<?= $appt['appointment_time'] ?>" data-bs-toggle="modal" data-bs-target="#rescheduleModal">Reschedule</a>
                            <a href="#" class="action-link btn-cancel" data-id="<?= $appt['appointment_id'] ?>" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancel</a>
                            <?php else: ?>
                            <span class="text-muted" style="font-size:0.75rem;">Cancelled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($appointments)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No appointments found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            <div class="page-info">
                Showing 
                <select class="form-select form-select-sm d-inline-block border-0 bg-light mx-1" style="width: auto;">
                    <option>10</option>
                    <option selected>13</option>
                </select>
                out of <?= $counts['all'] ?>
            </div>
            <div class="page-controls">
                <a href="#" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <span class="d-flex align-items-end mx-1 text-muted">...</span>
                <a href="#" class="page-btn">10</a>
                <a href="#" class="page-btn"><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
        
        <div class="d-flex justify-content-between px-4 pb-4 mt-2" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
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

    </main>

    <!-- Reschedule Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
          <form action="admin/appointments/reschedule" method="POST">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <input type="hidden" name="appointment_id" id="reschedule_id">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Reschedule Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 pt-3 pb-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">New Date</label>
                    <input type="date" name="appointment_date" id="reschedule_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">New Time</label>
                    <input type="time" name="appointment_time" id="reschedule_time" class="form-control" required>
                </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-dark px-4">Save Changes</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
          <form action="admin/appointments/update-status" method="POST">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <input type="hidden" name="appointment_id" id="cancel_id">
              <input type="hidden" name="status" value="cancelled">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-danger">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                Are you sure you want to cancel this appointment? This action cannot be undone.
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keep It</button>
                <button type="submit" class="btn btn-danger px-4">Yes, Cancel</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Checkbox highlighting
        document.getElementById('selectAll').addEventListener('change', function(e) {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = e.target.checked;
                cb.closest('tr').classList.toggle('selected', cb.checked);
            });
        });
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', function(e) {
                this.closest('tr').classList.toggle('selected', this.checked);
            });
        });

        // Tab Filtering
        document.querySelectorAll('.status-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.status-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                document.querySelectorAll('.appt-row').forEach(row => {
                    const status = row.getAttribute('data-status');
                    if (filter === 'all') {
                        row.style.display = '';
                    } else if (filter === 'confirmed' && (status === 'confirmed' || status === 'completed')) {
                        row.style.display = '';
                    } else if (filter === 'pending' && status === 'pending') {
                        row.style.display = '';
                    } else if (filter === 'cancelled' && (status === 'cancelled' || status === 'no_show')) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Modals Logic
        document.querySelectorAll('.btn-reschedule').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('reschedule_id').value = this.getAttribute('data-id');
                document.getElementById('reschedule_date').value = this.getAttribute('data-date');
                document.getElementById('reschedule_time').value = this.getAttribute('data-time');
            });
        });
        
        document.querySelectorAll('.btn-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('cancel_id').value = this.getAttribute('data-id');
            });
        });
        
        // Search Filter
        document.getElementById('tableSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.appt-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
