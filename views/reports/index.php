<?php
$role = $_SESSION['role'] ?? '';
$reportType = $_GET['type'] ?? 'appointment_summary';
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

$reportTitles = [
    'appointment_summary' => 'Appointment Summary Report',
    'patient_registration' => 'Patient Registration Report',
    'workload' => 'Practitioner Workload Report',
    'non_attendance' => 'Appointment Non-Attendance Report',
    'diagnoses' => 'Common Diagnoses Report'
];

$base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if (substr($base_url, -1) !== '/') $base_url .= '/';

// Layout Header Logic
if ($role === 'receptionist') {
    $page_title = "Reports - OAUTHC Dental";
    require_once BASE_PATH . '/views/layouts/reception_new_top.php';
} elseif ($role === 'practitioner') {
    require_once BASE_PATH . '/views/layouts/header.php';
    echo '</div><!-- Close header container -->';
    echo '<style>.doc-sidebar { background-color: #FFFFFF !important; border-top-right-radius: 20px; border-bottom-right-radius: 20px; min-height: calc(100vh - 56px); box-shadow: 4px 0 15px rgba(0,0,0,0.05); border-right: 1px solid #e5e7eb; } .doc-sidebar-link { color: #6b7280; border-radius: 12px; padding: 10px 0; transition: all 0.3s ease; } .doc-sidebar-link.active, .doc-sidebar-link:hover { background-color: #ccfbf1; color: #0f766e; font-weight: 600; }</style>';
    echo '<div class="container-fluid"><div class="row">';
    $current_page = 'reports';
    require_once BASE_PATH . '/views/layouts/practitioner_sidebar.php';
} elseif ($role === 'admin') {
    $current_page = 'reports';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reports - OAUTHC Dental</title>
        <base href="<?= htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8') ?>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #1f2937; overflow-x: hidden; }
            .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background-color: #ffffff; border-right: 1px solid #e5e7eb; z-index: 1000; display: flex; flex-direction: column; }
            .sidebar-brand { padding: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 1.25rem; color: #111827; text-decoration: none; }
            .sidebar-brand i { color: #14b8a6; }
            .sidebar-menu { list-style: none; padding: 0 1rem; margin: 0; flex: 1; overflow-y: auto; }
            .sidebar-item { margin-bottom: 0.25rem; }
            .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 0.75rem 1rem; color: #6b7280; text-decoration: none; border-radius: 12px; font-weight: 500; transition: all 0.2s; }
            .sidebar-link:hover { color: #111827; background-color: #f3f4f6; }
            .sidebar-link.active { background-color: #ccfbf1; color: #0f766e; font-weight: 600; }
            .sidebar-link i { font-size: 1.25rem; }
            .unlock-card { margin: 1.5rem; padding: 1.5rem; background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%); border-radius: 16px; color: white; }
            .unlock-card h6 { font-weight: 700; margin-bottom: 0.5rem; }
        </style>
    </head>
    <body>
    <?php
    require_once BASE_PATH . '/views/layouts/admin_sidebar.php';
}
?>

<style>
    .main-content { padding: 2rem; min-height: 100vh; transition: margin-left 0.3s ease; }
    <?php if ($role === 'admin'): ?>
        .main-content { margin-left: 260px; }
    <?php elseif ($role === 'receptionist'): ?>
        .main-content { margin-left: 80px; }
    <?php elseif ($role === 'practitioner'): ?>
        .main-content { margin-left: 0px; padding: 1rem; }
    <?php endif; ?>
    
    .report-card { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); padding: 2rem; }
    
    @media print {
        body { background: white; }
        .sidebar, .doc-sidebar, .reception-sidebar, .navbar { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        .no-print { display: none !important; }
        .report-card { box-shadow: none; padding: 0; border: none; }
        .table { border: 1px solid #dee2e6; }
        th { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
    }
</style>

<?php if ($role === 'practitioner'): ?>
    <div class="col-md-9 col-lg-10 main-content">
<?php else: ?>
    <div class="main-content">
<?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h4 class="fw-bold mb-0">Reports & Analytics</h4>
        <div>
            <a href="reports?type=<?= $reportType ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&export=csv" class="btn btn-outline-primary me-2">
                <i class="bi bi-filetype-csv me-1"></i> Export CSV
            </a>
            <button onclick="window.print()" class="btn btn-outline-danger">
                <i class="bi bi-filetype-pdf me-1"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4 no-print">
        <div class="card-body p-4">
            <form action="reports" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Report Type</label>
                    <select name="type" class="form-select">
                        <?php foreach($reportTitles as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $reportType === $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Generate</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Data Section -->
    <div class="report-card">
        <div class="text-center mb-4 d-none d-print-block">
            <h3>OAUTHC Dental Centre</h3>
            <p class="text-muted mb-0">Generated on <?= date('Y-m-d H:i:s') ?> by <?= htmlspecialchars($_SESSION['username'] ?? '') ?></p>
        </div>
        
        <h5 class="fw-bold mb-4"><?= htmlspecialchars($reportTitles[$reportType] ?? 'Report') ?></h5>
        <p class="text-muted small mb-3">Period: <?= htmlspecialchars($startDate) ?> to <?= htmlspecialchars($endDate) ?></p>

        <?php if (empty($data)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                <p class="text-muted">No data found for the selected period.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <?php foreach ($headers as $header): ?>
                                <th><?= htmlspecialchars($header) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($role === 'practitioner'): ?>
    </div><!-- End row -->
    </div><!-- End container-fluid -->
<?php endif; ?>

<?php
// Layout Footer Logic
if ($role === 'admin') {
    echo '</body></html>';
} elseif ($role === 'practitioner') {
    require_once BASE_PATH . '/views/layouts/footer.php';
}
// Receptionist top layout handles its own body/html tags but doesn't have a matching footer, so we just end.
?>
