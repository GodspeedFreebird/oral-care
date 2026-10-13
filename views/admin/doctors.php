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
    <title>Doctors - Admin Dashboard</title>
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
            border-radius: 20px;
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

        /* Content Container */
        .content-container {
            padding: 0 2rem 2rem 2rem;
        }
        
        .main-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #f3f4f6;
            padding: 1.5rem;
        }

        /* Toolbar */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .toolbar-left {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .search-box {
            position: relative;
            width: 300px;
        }
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .search-box input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 0.875rem;
            background-color: #f9fafb;
        }
        .filter-select {
            padding: 0.5rem 2rem 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 0.875rem;
            color: #6b7280;
            background-color: #f9fafb;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Table */
        .table-responsive {
            margin-bottom: 1rem;
        }
        .doctors-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .doctors-table th {
            color: #9ca3af;
            font-weight: 500;
            font-size: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            text-align: left;
            white-space: nowrap;
        }
        .doctors-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
            color: #4b5563;
        }
        .doctors-table tr:hover td {
            background-color: #f8fafc;
        }
        
        .doctor-name-col {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .doctor-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .doctor-name-text {
            font-weight: 600;
            color: #111827;
        }

        /* Badges */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-available { background-color: #ccfbf1; color: #0f766e; }
        .status-unavailable { background-color: #ffe4e6; color: #be123c; border: 1px solid #fecdd3;}
        
        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }
        .action-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        .action-btn:hover { background-color: #f3f4f6; color: #111827; }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
        }
        .showing-text {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .showing-select {
            padding: 0.25rem 1.5rem 0.25rem 0.5rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-size: 0.875rem;
            margin: 0 0.5rem;
            background-color: #f9fafb;
        }
        .page-link-custom {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            text-decoration: none;
            font-size: 0.875rem;
            margin-left: 0.25rem;
            background: white;
        }
        .page-link-custom.active {
            background-color: #111827;
            color: white;
            border-color: #111827;
        }
        .page-link-custom:hover:not(.active) { background-color: #f3f4f6; }
        
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'doctors'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">Doctors</h1>
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

        <div class="content-container">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= escape($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= escape($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="main-card">
                <!-- Toolbar -->
                <div class="toolbar">
                    <div class="toolbar-left">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="searchInput" placeholder="Search name, ID, department, etc">
                        </div>
                        <select class="filter-select">
                            <option value="">Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= escape($dept['name']) ?>"><?= escape($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="filter-select">
                            <option value="">Specialist</option>
                        </select>
                        <select class="filter-select">
                            <option value="">Status</option>
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                    <button class="btn btn-dark fw-bold px-4" style="border-radius: 8px; background-color: #1f2937;" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                        + Add Doctor
                    </button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="doctors-table">
                        <thead>
                            <tr>
                                <th>Name <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>ID <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>Department <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>Specialist <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>Total Patients <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>Today's Appointment <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>Availability Status <i class="bi bi-chevron-expand ms-1 text-muted"></i></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="doctorsBody">
                            <?php if(empty($doctors)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No doctors found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($doctors as $doc): ?>
                                <tr class="doc-row" style="cursor:pointer;" onclick="window.location.href='admin/doctor/details?id=<?= $doc['practitioner_id'] ?>'">
                                    <td>
                                        <div class="doctor-name-col">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode(str_replace('Dr. ', '', $doc['name'])) ?>&background=random" class="doctor-avatar">
                                            <span class="doctor-name-text">Dr. <?= escape(str_replace('Dr. ', '', $doc['name'])) ?></span>
                                        </div>
                                    </td>
                                    <td><?= escape($doc['licence_no'] ?: 'WNH-GM-001') ?></td>
                                    <td><?= escape($doc['department_name'] ?: 'General Medicine') ?></td>
                                    <td><?= escape($doc['specialisation'] ?: 'Routine Check-Ups') ?></td>
                                    <td><?= $doc['total_patients'] ?></td>
                                    <td><?= $doc['todays_appointments'] ?></td>
                                    <td>
                                        <?php if ($doc['is_active']): ?>
                                            <span class="status-badge status-available">Available</span>
                                        <?php else: ?>
                                            <span class="status-badge status-unavailable">Unavailable</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn"><i class="bi bi-pencil-square"></i></button>
                                            <button class="action-btn"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="showing-text">
                        Showing 
                        <select class="showing-select">
                            <option>12</option>
                            <option>24</option>
                            <option>50</option>
                        </select> 
                        out of <?= count($doctors) ?>
                    </div>
                    <div class="d-flex">
                        <a href="#" class="page-link-custom"><i class="bi bi-chevron-left"></i></a>
                        <a href="#" class="page-link-custom active">1</a>
                        <a href="#" class="page-link-custom">2</a>
                        <a href="#" class="page-link-custom">3</a>
                        <a href="#" class="page-link-custom"><i class="bi bi-three-dots"></i></a>
                        <a href="#" class="page-link-custom">5</a>
                        <a href="#" class="page-link-custom"><i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between pt-4 mt-2 pb-4" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
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
    </main>

    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" style="color: #111827;">Add New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form action="admin/doctors" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="action" value="add_doctor">
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 500;">Full Name</label>
                            <input type="text" class="form-control" name="full_name" required style="border-radius: 8px;">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.875rem; font-weight: 500;">Username</label>
                                <input type="text" class="form-control" name="username" required style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.875rem; font-weight: 500;">Email</label>
                                <input type="email" class="form-control" name="email" required style="border-radius: 8px;">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.875rem; font-weight: 500;">Licence No (ID)</label>
                                <input type="text" class="form-control" name="licence_no" required style="border-radius: 8px; font-family: monospace;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.875rem; font-weight: 500;">Department</label>
                                <select class="form-select" name="department_id" required style="border-radius: 8px;">
                                    <option value="">Select Dept</option>
                                    <?php foreach ($departments as $d): ?>
                                        <option value="<?= $d['department_id'] ?>"><?= escape($d['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 500;">Specialisation</label>
                            <input type="text" class="form-control" name="specialisation" required style="border-radius: 8px;" placeholder="e.g. Routine Check-Ups">
                        </div>
                        
                        <button type="submit" class="btn btn-dark w-100 fw-bold py-2" style="border-radius: 8px; background-color: #1f2937;">Register Doctor</button>
                        <div class="text-center mt-3" style="font-size: 0.75rem; color: #6b7280;">
                            A secure temporary password will be generated automatically.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.doc-row');
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
