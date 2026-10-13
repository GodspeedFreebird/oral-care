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
    <title>Payments - OAUTHC Dental</title>
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
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e5e7eb;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background-color: #1e293b;
            color: white;
        }
        .stat-info {
            display: flex;
            flex-direction: column;
            margin-left: 1rem;
            flex-grow: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        .stat-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
        }
        .stat-badge.green { background-color: #d1fae5; color: #065f46; }
        .stat-badge.red { background-color: #fee2e2; color: #b91c1c; }

        /* Filter Row */
        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .search-input {
            width: 300px;
            position: relative;
        }
        .search-input input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            font-size: 0.85rem;
        }
        .search-input i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .select-filter {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            font-size: 0.85rem;
            color: #4b5563;
        }

        /* Table */
        .table-responsive {
            background-color: white;
            border-radius: 12px;
            border: none;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 500;
            color: #9ca3af;
            font-size: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.85rem;
            color: #111827;
        }
        .treatment-info {
            display: flex;
            flex-direction: column;
        }
        .treatment-name {
            font-weight: 500;
            color: #111827;
        }
        .treatment-dept {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 0.1rem;
            display: flex;
            align-items: center;
            gap: 0.2rem;
        }
        .status-badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .status-badge.Paid { background-color: #ccfbf1; color: #0f766e; }
        .status-badge.Paid::before { content: '\F26E'; font-family: 'bootstrap-icons'; font-size: 0.7rem; }
        .status-badge.Pending { background-color: #fee2e2; color: #b91c1c; }
        .status-badge.Pending::before { content: '\F28A'; font-family: 'bootstrap-icons'; font-size: 0.7rem; }
        .status-badge.Overdue { background-color: #fef08a; color: #854d0e; }
        .status-badge.Overdue::before { content: '\F333'; font-family: 'bootstrap-icons'; font-size: 0.7rem; }

        .action-btn {
            background: none;
            border: none;
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        .action-btn:hover { background-color: #f3f4f6; color: #111827; }
        
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            font-size: 0.85rem;
            color: #6b7280;
        }
        .page-link {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #4b5563;
            text-decoration: none;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .page-link.active {
            background-color: #1e293b;
            color: white;
            border-color: #1e293b;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'payments'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">Payments</h1>
            
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

        <!-- Success Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-3" style="font-size:0.9rem;">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Total Invoice</span>
                        <h3 class="stat-value"><?= $stats['total_invoices'] ?></h3>
                    </div>
                </div>
                <span class="stat-badge green"><i class="bi bi-arrow-up-right"></i> +2.14%</span>
            </div>
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Paid Invoice</span>
                        <h3 class="stat-value"><?= $stats['paid_invoices'] ?></h3>
                    </div>
                </div>
                <span class="stat-badge green"><i class="bi bi-arrow-up-right"></i> +1.40%</span>
            </div>
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon" style="background-color: #1e3a8a;"><i class="bi bi-arrow-repeat"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Pending Invoice</span>
                        <h3 class="stat-value"><?= $stats['pending_invoices'] ?></h3>
                    </div>
                </div>
                <span class="stat-badge red"><i class="bi bi-arrow-down-right"></i> -1.56%</span>
            </div>
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon"><i class="bi bi-exclamation-circle"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Overdue</span>
                        <h3 class="stat-value"><?= $stats['overdue_invoices'] ?></h3>
                    </div>
                </div>
                <span class="stat-badge green"><i class="bi bi-arrow-up-right"></i> +0.37%</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-row">
            <div class="filter-group">
                <div class="search-input">
                    <i class="bi bi-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search name, treatment, etc">
                </div>
                <div class="select-filter">
                    <i class="bi bi-calendar3 me-2 text-muted"></i> 1 - 20 July 2028 <i class="bi bi-chevron-down ms-2 text-muted"></i>
                </div>
                <div class="select-filter">
                    <i class="bi bi-funnel me-2 text-muted"></i> Status <i class="bi bi-chevron-down ms-2 text-muted"></i>
                </div>
            </div>
            <button class="btn btn-sm text-white" style="background-color: #1e293b; border-radius: 8px; padding: 0.5rem 1.25rem; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                <i class="bi bi-plus-lg me-1"></i> Add Invoice
            </button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Invoice ID <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Patient Name <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Treatment <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Date <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Amount <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Status <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $inv): ?>
                    <tr class="invoice-row">
                        <td class="fw-medium text-dark"><?= htmlspecialchars($inv['invoice_number']) ?></td>
                        <td><?= htmlspecialchars($inv['patient_name']) ?></td>
                        <td>
                            <div class="treatment-info">
                                <span class="treatment-name"><?= htmlspecialchars($inv['treatment_title']) ?></span>
                                <span class="treatment-dept"><i class="bi bi-building"></i> <?= htmlspecialchars($inv['department_name']) ?></span>
                            </div>
                        </td>
                        <td><?= date('j F Y', strtotime($inv['invoice_date'])) ?></td>
                        <td class="fw-medium">$<?= number_format($inv['amount'], 2) ?></td>
                        <td>
                            <span class="status-badge <?= $inv['status'] ?>"><?= $inv['status'] ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="action-btn btn-view" data-id="<?= $inv['invoice_id'] ?>" data-number="<?= htmlspecialchars($inv['invoice_number']) ?>" data-patient="<?= htmlspecialchars($inv['patient_name']) ?>" data-treatment="<?= htmlspecialchars($inv['treatment_title']) ?>" data-amount="<?= $inv['amount'] ?>" data-status="<?= $inv['status'] ?>" data-date="<?= $inv['invoice_date'] ?>" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button class="action-btn btn-edit" data-id="<?= $inv['invoice_id'] ?>" data-patient-id="<?= $inv['patient_id'] ?>" data-department-id="<?= $inv['department_id'] ?>" data-treatment="<?= htmlspecialchars($inv['treatment_title']) ?>" data-amount="<?= $inv['amount'] ?>" data-status="<?= $inv['status'] ?>" data-date="<?= $inv['invoice_date'] ?>" data-bs-toggle="modal" data-bs-target="#editInvoiceModal">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No invoices found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container">
            <div>
                Showing <select class="form-select form-select-sm d-inline-block w-auto mx-1 border-0 bg-light"><option>12</option></select> out of <?= count($invoices) ?>
            </div>
            <div class="d-flex gap-1">
                <a href="#" class="page-link"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="page-link"><i class="bi bi-chevron-right"></i></a>
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

    <!-- Add Invoice Modal -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form method="POST" action="admin/payments/add">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Add Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Patient</label>
                    <select class="form-select" name="patient_id" required>
                        <option value="">Select Patient...</option>
                        <?php foreach($patients as $pat): ?>
                            <option value="<?= $pat['patient_id'] ?>"><?= htmlspecialchars($pat['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Department</label>
                    <select class="form-select" name="department_id" required>
                        <option value="">Select Department...</option>
                        <?php foreach($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Treatment Title</label>
                    <input type="text" class="form-control" name="treatment_title" placeholder="e.g. Routine Check-Up" required>
                 </div>
                 <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Amount ($)</label>
                        <input type="number" step="0.01" class="form-control" name="amount" placeholder="100.00" required>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Date</label>
                        <input type="date" class="form-control" name="invoice_date" required>
                     </div>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="Paid">Paid</option>
                        <option value="Pending" selected>Pending</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                 </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #1e293b;">Save Invoice</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form method="POST" action="admin/payments/edit">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <input type="hidden" name="invoice_id" id="edit_invoice_id">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Edit Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Patient</label>
                    <select class="form-select" name="patient_id" id="edit_patient_id" required>
                        <?php foreach($patients as $pat): ?>
                            <option value="<?= $pat['patient_id'] ?>"><?= htmlspecialchars($pat['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Department</label>
                    <select class="form-select" name="department_id" id="edit_department_id" required>
                        <?php foreach($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Treatment Title</label>
                    <input type="text" class="form-control" name="treatment_title" id="edit_treatment_title" required>
                 </div>
                 <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Amount ($)</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="edit_amount" required>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Date</label>
                        <input type="date" class="form-control" name="invoice_date" id="edit_invoice_date" required>
                     </div>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Status</label>
                    <select class="form-select" name="status" id="edit_status" required>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                 </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #1e293b;">Update Invoice</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- View Invoice Modal -->
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-0 pb-0 pt-4 px-4">
            <h5 class="modal-title fw-bold">Invoice Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body px-4 py-4">
             <div class="d-flex justify-content-between align-items-center mb-4">
                 <div>
                     <h3 class="fw-bold mb-0" id="view_amount">$0.00</h3>
                     <span class="text-muted" id="view_status">Status</span>
                 </div>
                 <div class="text-end">
                     <div class="fw-bold" id="view_number">INV-000</div>
                     <div class="text-muted" id="view_date">Date</div>
                 </div>
             </div>
             
             <div class="mb-3 border-bottom pb-3">
                 <div class="text-muted" style="font-size:0.8rem; font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">Patient</div>
                 <div class="fw-medium" id="view_patient">Patient Name</div>
             </div>
             <div class="mb-3">
                 <div class="text-muted" style="font-size:0.8rem; font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">Treatment</div>
                 <div class="fw-medium" id="view_treatment">Treatment Name</div>
             </div>
          </div>
          <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-between">
              <form method="POST" action="admin/payments/delete" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                  <input type="hidden" name="invoice_id" id="view_delete_id">
                  <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
              </form>
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search Filter
        document.getElementById('tableSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.invoice-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Edit Modal Data Population
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit_invoice_id').value = this.getAttribute('data-id');
                document.getElementById('edit_patient_id').value = this.getAttribute('data-patient-id');
                document.getElementById('edit_department_id').value = this.getAttribute('data-department-id');
                document.getElementById('edit_treatment_title').value = this.getAttribute('data-treatment');
                document.getElementById('edit_amount').value = this.getAttribute('data-amount');
                document.getElementById('edit_status').value = this.getAttribute('data-status');
                document.getElementById('edit_invoice_date').value = this.getAttribute('data-date');
            });
        });

        // View Modal Data Population
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('view_amount').textContent = '$' + parseFloat(this.getAttribute('data-amount')).toFixed(2);
                document.getElementById('view_number').textContent = this.getAttribute('data-number');
                document.getElementById('view_patient').textContent = this.getAttribute('data-patient');
                document.getElementById('view_treatment').textContent = this.getAttribute('data-treatment');
                document.getElementById('view_status').textContent = this.getAttribute('data-status');
                document.getElementById('view_date').textContent = this.getAttribute('data-date');
                document.getElementById('view_delete_id').value = this.getAttribute('data-id');
            });
        });
    </script>
</body>
</html>
