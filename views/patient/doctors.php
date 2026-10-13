<?php require_once VIEWS_PATH . '/layouts/patient_sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Our Doctors</h4>
</div>

<div class="row g-4">
    <?php if (empty($doctors)): ?>
        <div class="col-12">
            <p class="text-muted">No doctors available at the moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($doctors as $doc): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 info-card p-3">
                    <div class="d-flex align-items-center mb-3">
                        <?php if (!empty($doc['profile_picture'])): ?>
                            <img src="<?= escape($doc['profile_picture']) ?>" class="profile-avatar me-3" style="width:60px; height:60px;">
                        <?php else: ?>
                            <div class="profile-avatar me-3" style="width:60px; height:60px;"><?= substr($doc['full_name'], 0, 1) ?></div>
                        <?php endif; ?>
                        <div>
                            <h6 class="fw-bold mb-1">Dr. <?= escape($doc['full_name']) ?></h6>
                            <div class="text-primary small fw-bold"><?= escape($doc['department_name'] ?? 'General Dentistry') ?></div>
                        </div>
                    </div>
                    <div class="text-muted small mb-3">
                        <i class="bi bi-envelope me-1"></i> <?= escape($doc['email']) ?><br>
                        <?php if ($doc['phone']): ?>
                            <i class="bi bi-telephone me-1"></i> <?= escape($doc['phone']) ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($doc['specialisation']): ?>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border"><?= escape($doc['specialisation']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="mt-auto">
                        <a href="patient/book-appointment?practitioner_id=<?= $doc['practitioner_id'] ?>" class="btn btn-outline-primary btn-sm w-100">
                            Book Appointment
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . '/layouts/patient_footer.php'; ?>
