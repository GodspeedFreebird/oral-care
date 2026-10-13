<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        <!-- Sidebar -->
        <?php 
            $current_page = 'practitioner_invoices';
            require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            
            <!-- Top Header Area -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center bg-white p-3 shadow-sm rounded mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 text-dark">Invoices</h4>
                </div>
                <div class="d-flex gap-2">
                    <form action="doctor/invoices" method="GET" class="d-flex position-relative me-3">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="q" class="form-control rounded-pill bg-light border-0 ps-5 pe-3" placeholder="Search invoice..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="width: 250px;">
                    </form>
                    <button class="btn btn-primary rounded shadow-sm px-4 fw-bold"><i class="bi bi-plus me-1"></i> Add New</button>
                    <button class="btn btn-light rounded-circle shadow-sm ms-2" style="width: 40px; height: 40px;"><i class="bi bi-bell"></i></button>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-0">
                    <?php if (empty($invoices)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-receipt text-muted opacity-50" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">No Invoices Found</h4>
                            <p class="text-muted">You have no matching invoices in the system.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead style="background-color: #f8f9fc;">
                                    <tr>
                                        <th class="ps-4 py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem; border-top-left-radius: 12px;">
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Invoice ID</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Patient Name</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Date Issued</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Amount</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Status</th>
                                        <th class="pe-4 py-3 border-0 text-muted fw-bold text-center" style="font-size: 0.8rem; border-top-right-radius: 12px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $inv): ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td class="py-3 fw-bold text-dark" style="font-size: 0.85rem;">#<?= htmlspecialchars($inv['invoice_number'] ?? 'INV-' . $inv['invoice_id']) ?></td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded overflow-hidden me-3 bg-light text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                                                    <?= substr($inv['patient_name'], 0, 1) ?>
                                                </div>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($inv['patient_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted" style="font-size: 0.85rem;">
                                            <?= date('d M Y', strtotime($inv['issue_date'] ?? $inv['created_at'])) ?>
                                        </td>
                                        <td class="py-3 text-dark fw-bold" style="font-size: 0.85rem;">$<?= number_format($inv['total_amount'], 2) ?></td>
                                        <td class="py-3">
                                            <?php if ($inv['status'] === 'paid'): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Paid</span>
                                            <?php elseif ($inv['status'] === 'partial'): ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Partial</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Unpaid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 py-3 text-center">
                                            <button class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded" data-bs-toggle="modal" data-bs-target="#invoiceModal<?= $inv['invoice_id'] ?>"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-light text-success shadow-sm rounded"><i class="bi bi-download"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Invoice Detail Modals -->
                        <?php foreach ($invoices as $inv): ?>
                        <div class="modal fade" id="invoiceModal<?= $inv['invoice_id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Invoice #<?= htmlspecialchars($inv['invoice_number'] ?? 'INV-' . $inv['invoice_id']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row mb-4">
                                            <div class="col-sm-6">
                                                <h6 class="fw-bold text-muted small text-uppercase">Billed To</h6>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($inv['patient_name']) ?></div>
                                                <div class="text-muted small">Date: <?= date('d M Y', strtotime($inv['issue_date'] ?? $inv['created_at'])) ?></div>
                                            </div>
                                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                                <h6 class="fw-bold text-muted small text-uppercase">Status</h6>
                                                <div class="fs-5 fw-bold text-<?= $inv['status'] === 'paid' ? 'success' : 'danger' ?> text-capitalize"><?= htmlspecialchars($inv['status']) ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table border">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="py-2">Description</th>
                                                        <th class="py-2 text-end">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        // Mock items if none fetched
                                                        $items = $inv['items'] ?? [
                                                            ['description' => 'Consultation Fee', 'amount' => 50.00],
                                                            ['description' => 'Treatment', 'amount' => $inv['total_amount'] - 50.00]
                                                        ];
                                                        foreach($items as $item):
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($item['description']) ?></td>
                                                        <td class="text-end">$<?= number_format($item['amount'], 2) ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <tr>
                                                        <td class="text-end fw-bold">Total</td>
                                                        <td class="text-end fw-bold text-primary fs-5">$<?= number_format($inv['total_amount'], 2) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary fw-bold px-4 rounded shadow-sm"><i class="bi bi-credit-card me-1"></i> Process Payment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <span class="text-muted small">Showing 1 to <?= count($invoices) ?> of <?= count($invoices) ?> entries</span>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><a class="page-link border-0" href="#">Previous</a></li>
                                    <li class="page-item active"><a class="page-link border-0 rounded bg-primary text-white" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link border-0 text-dark" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>
</div>

<div class="container" style="display: none;">
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
