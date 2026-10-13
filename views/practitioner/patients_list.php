<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        <!-- Sidebar -->
        <?php 
            if (isset($isAdminView) && $isAdminView) {
                $current_page = 'patients';
                require_once VIEWS_PATH . '/layouts/admin_sidebar.php';
            } else {
                $current_page = 'practitioner_patients';
                require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php';
            }
        ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            
            <!-- Top Header Area -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center bg-white p-3 shadow-sm rounded mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 text-dark">Patient</h4>
                </div>
                <div class="d-flex gap-2">
                    <form action="<?= isset($isAdminView) ? 'admin/patients' : 'doctor/patients' ?>" method="GET" class="d-flex position-relative me-3">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="q" class="form-control rounded-pill bg-light border-0 ps-5 pe-3" placeholder="Search patient..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="width: 250px;">
                    </form>
                    <!-- "Add New" can be restricted or available. For now, it just goes to a generic add page or is disabled for doctors -->
                    <button class="btn btn-primary rounded shadow-sm px-4 fw-bold"><i class="bi bi-plus me-1"></i> Add New</button>
                    <button class="btn btn-light rounded-circle shadow-sm ms-2" style="width: 40px; height: 40px;"><i class="bi bi-bell"></i></button>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-0">
                    <?php if (empty($patients)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-person-x text-muted opacity-50" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">No Patients Found</h4>
                            <p class="text-muted">You have no matching patients in your directory.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead style="background-color: #f8f9fc;">
                                    <tr>
                                        <th class="ps-4 py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem; border-top-left-radius: 12px;">
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Patient Name</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Patient ID</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Date Of Birth</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Gender</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Phone Number</th>
                                        <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.8rem;">Condition</th>
                                        <th class="pe-4 py-3 border-0 text-muted fw-bold text-center" style="font-size: 0.8rem; border-top-right-radius: 12px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($patients as $p): ?>
                                    <tr style="cursor: pointer;" onclick="window.location='<?= isset($isAdminView) ? 'admin/patients/' : 'doctor/patients/' ?><?= $p['patient_id'] ?>/view'">
                                        <td class="ps-4 py-3" onclick="event.stopPropagation();">
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded overflow-hidden me-3 bg-light text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                                                    <?php if (!empty($p['profile_picture'])): ?>
                                                        <img src="<?= htmlspecialchars($p['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                                                    <?php else: ?>
                                                        <?= substr($p['full_name'], 0, 1) ?>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($p['full_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="py-3 fw-bold text-dark" style="font-size: 0.85rem;">#<?= htmlspecialchars($p['patient_number']) ?></td>
                                        <td class="py-3 text-muted" style="font-size: 0.85rem;">
                                            <?= date('d M Y', strtotime($p['date_of_birth'])) ?>
                                        </td>
                                        <td class="py-3 text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars(ucfirst($p['gender'])) ?></td>
                                        <td class="py-3 text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($p['phone']) ?></td>
                                        <td class="py-3">
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Check Up</span>
                                        </td>
                                        <td class="pe-4 py-3 text-center" onclick="event.stopPropagation();">
                                            <a href="<?= isset($isAdminView) ? 'admin/patients/' : 'doctor/patients/' ?><?= $p['patient_id'] ?>/view" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded"><i class="bi bi-pencil"></i></a>
                                            <button class="btn btn-sm btn-light text-danger shadow-sm rounded"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <span class="text-muted small">Showing 1 to <?= count($patients) ?> of <?= count($patients) ?> entries</span>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><a class="page-link border-0" href="#">Previous</a></li>
                                    <li class="page-item active"><a class="page-link border-0 rounded bg-primary text-white" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link border-0 text-dark" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link border-0 text-dark" href="#">3</a></li>
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
