<?php require_once VIEWS_PATH . '/layouts/patient_sidebar.php'; ?>

<!-- Patient Header Card -->
<div class="patient-header-card d-flex align-items-center">
    <div class="profile-avatar me-4 fs-3 fw-bold text-secondary text-uppercase overflow-hidden">
        <?php if (!empty($patient['profile_picture'])): ?>
            <img src="<?php echo escape($patient['profile_picture']); ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
        <?php else: ?>
            <?php echo substr($patient['full_name'], 0, 1); ?>
        <?php endif; ?>
    </div>
    <div class="flex-grow-1">
        <div class="d-flex align-items-center mb-2">
            <h4 class="mb-0 fw-bold me-3"><?php echo escape($patient['full_name']); ?></h4>
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small">Active</span>
        </div>
        <div class="text-muted small d-flex gap-4">
            <span><i class="bi bi-envelope me-1"></i> <?php echo escape($patient['email']); ?></span>
            <span><i class="bi bi-telephone me-1"></i> <?php echo escape($patient['phone']); ?></span>
            <span><i class="bi bi-geo-alt me-1"></i> <?php echo escape($patient['address']); ?></span>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav custom-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link" href="patient/dashboard">Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="patient/appointments">Appointments</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="patient/records">Medical History</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="patient/notifications">Notification History</a>
    </li>
</ul>

<!-- Content Cards -->
<div class="info-card">
    <h5 class="fw-bold mb-4">Update Profile Information</h5>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success small py-2"><i class="bi bi-check-circle me-2"></i><?php echo escape($success); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger small py-2"><i class="bi bi-exclamation-circle me-2"></i><?php echo escape($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="patient/profile" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape($csrf_token); ?>">
        
        <div class="text-muted small mb-3">Basic Information</div>
        <div class="row g-3 mb-4 pb-4 border-bottom">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Patient ID Number</label>
                <input type="text" class="form-control bg-light text-muted" value="<?php echo escape($patient['patient_number']); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Full Name</label>
                <input type="text" name="full_name" class="form-control bg-light" value="<?php echo escape($patient['full_name']); ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control bg-light" value="<?php echo escape($patient['date_of_birth']); ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Gender</label>
                <select name="gender" class="form-select bg-light" required>
                    <option value="male" <?php echo ($patient['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($patient['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Phone Number</label>
                <input type="tel" name="phone" class="form-control bg-light" value="<?php echo escape($patient['phone']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Address</label>
                <input type="text" name="address" class="form-control bg-light" value="<?php echo escape($patient['address']); ?>" required>
            </div>
        </div>

        <div class="text-muted small mb-3">Emergency Contact</div>
        <div class="row g-3 mb-4 pb-4 border-bottom">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Contact Name</label>
                <input type="text" name="emergency_contact_name" class="form-control bg-light" value="<?php echo escape($patient['emergency_contact_name'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Contact Phone</label>
                <input type="tel" name="emergency_contact_phone" class="form-control bg-light" value="<?php echo escape($patient['emergency_contact_phone'] ?? ''); ?>">
            </div>
        </div>

        <div class="text-muted small mb-3">Profile Picture</div>
        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <label class="form-label small fw-bold text-secondary">Upload New Picture</label>
                <input type="file" name="profile_picture" class="form-control bg-light" accept="image/*">
                <div class="form-text small">Upload a new image to replace your current avatar.</div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="patient/dashboard" class="btn btn-light border">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
        </div>
    </form>
</div>

<?php require_once VIEWS_PATH . '/layouts/patient_footer.php'; ?>
