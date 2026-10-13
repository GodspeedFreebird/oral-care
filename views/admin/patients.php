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
    <title>Patients - OAUTHC Dental</title>
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

        /* Patients Controls */
        .patients-controls {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .left-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .filter-dropdown {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #4b5563;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .right-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .search-bar input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
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
        .patients-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
        }
        .patients-table th {
            background-color: #f9fafb;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .patients-table td {
            padding: 1rem 1rem;
            font-size: 0.875rem;
            color: #111827;
            font-weight: 500;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            background-color: #ffffff;
        }
        .patients-table tr:hover td {
            background-color: #f8fafc;
        }
        
        .patient-name-col {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .patient-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .pill-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            display: inline-block;
        }
        .pill-badge.active { background-color: #1f2937; color: #ffffff; }
        .pill-badge.inactive { background-color: #ef4444; color: #ffffff; }
        .pill-badge.new { background-color: #ccfbf1; color: #0f766e; }

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
    <?php $current_page = 'patients'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">Patients</h1>
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

        <!-- Controls Row -->
        <div class="patients-controls">
            <div class="left-controls">
                <div class="filter-dropdown">
                    <i class="bi bi-calendar3"></i> 1 July - <?= date('j F Y') ?> <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem"></i>
                </div>
                <div class="filter-dropdown">
                    <i class="bi bi-funnel"></i> All Treatment <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem"></i>
                </div>
                <div class="filter-dropdown">
                    <i class="bi bi-funnel"></i> All Status <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem"></i>
                </div>
            </div>
            
            <div class="right-controls">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search name, ID, age, etc">
                </div>
                <button class="btn btn-light border px-2" style="border-radius: 8px;">
                    <i class="bi bi-sliders"></i>
                </button>
                <button class="btn btn-dark fw-bold px-4" style="border-radius: 8px; background-color: #1f2937;" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="bi bi-plus"></i> Add Patient
                </button>
            </div>
        </div>

        <!-- Table Data -->
        <div class="table-container">
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>Name <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>ID <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>Age <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>Check In <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>Treatment <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>Doctor Assigned <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>Room <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                        <th>Status <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem"></i></th>
                    </tr>
                </thead>
                <tbody id="patientsBody">
                    <?php foreach ($patients as $pat): ?>
                    <tr class="pat-row" style="cursor: pointer;" onclick="window.location.href='admin/patient/details?id=<?= $pat['patient_id'] ?>'">
                        <td>
                            <div class="patient-name-col">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($pat['name']) ?>&background=random" class="patient-avatar">
                                <?= escape($pat['name']) ?>
                            </div>
                        </td>
                        <td><?= escape(str_replace('OAUTHC/DEN/2026/', '', $pat['id'])) ?></td>
                        <td><?= escape($pat['age']) ?></td>
                        <td><?= $pat['check_in'] ? date('d M Y', strtotime($pat['check_in'])) : '-' ?></td>
                        <td><?= escape($pat['treatment'] ?: 'Initial Registration') ?></td>
                        <td><?= $pat['doctor_assigned'] ? 'Dr. ' . escape($pat['doctor_assigned']) : '-' ?></td>
                        <td>-</td>
                        <td>
                            <?php 
                                $badgeClass = 'new';
                                if ($pat['status_label'] === 'Active') $badgeClass = 'active';
                                if ($pat['status_label'] === 'Inactive') $badgeClass = 'inactive';
                            ?>
                            <span class="pill-badge <?= $badgeClass ?>"><?= escape($pat['status_label']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($patients)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No patients found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            <div class="page-info">
                Showing 
                <select class="form-select form-select-sm d-inline-block border-0 bg-light mx-1" style="width: auto;">
                    <option selected>12</option>
                    <option>24</option>
                </select>
                out of <?= $counts['all'] ?>
            </div>
            <div class="page-controls">
                <a href="#" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <span class="d-flex align-items-end mx-1 text-muted">...</span>
                <a href="#" class="page-btn">16</a>
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

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
          <form action="register" method="POST">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <!-- We are mocking the patient registration using the existing /register route which registers patients -->
              <input type="hidden" name="role" value="patient"> 
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Register New Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 pt-3 pb-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Full Name (Username)</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Password (Temporary)</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control">
                </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark px-4">Register Patient</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Live Search Filter
        document.getElementById('tableSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.pat-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
