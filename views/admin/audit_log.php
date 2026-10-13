<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f8f9fa;">
    <div class="row g-0">
        <?php require_once VIEWS_PATH . '/layouts/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-dark">System Audit Logs</h1>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 bg-white rounded-4">
                    <form action="admin/audit-log" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Filter by Action</label>
                            <select name="action" class="form-select bg-light border-0">
                                <option value="">All Actions</option>
                                <?php foreach ($actions as $act): ?>
                                    <option value="<?= htmlspecialchars($act) ?>" <?= ($actionFilter === $act) ? 'selected' : '' ?>><?= htmlspecialchars($act) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Filter by Date</label>
                            <input type="date" name="date" class="form-control bg-light border-0" value="<?= htmlspecialchars($dateFilter) ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">Apply Filters</button>
                            <a href="admin/audit-log" class="btn btn-light ms-2 fw-bold px-4 rounded-pill">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Timestamp</th>
                                    <th class="py-3">User</th>
                                    <th class="py-3">Action</th>
                                    <th class="py-3">Details</th>
                                    <th class="pe-4 py-3">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logs)): ?>
                                    <tr><td colspan="5" class="text-center py-5 text-muted">No audit logs found matching criteria.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted small"><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                                        <td class="py-3 fw-bold text-dark">
                                            <?= htmlspecialchars($log['username'] ?? 'System') ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1 border" style="font-size: 0.6rem;"><?= htmlspecialchars($log['role'] ?? '') ?></span>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-info bg-opacity-10 text-dark border border-info border-opacity-25"><?= htmlspecialchars($log['action']) ?></span>
                                        </td>
                                        <td class="py-3 text-muted"><?= htmlspecialchars($log['details']) ?></td>
                                        <td class="pe-4 py-3 text-muted small"><?= htmlspecialchars($log['ip_address']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<div class="container" style="display: none;">
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
