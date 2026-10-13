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
        <a class="nav-link active" href="patient/dashboard">Overview</a>
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
<div class="row">
    <div class="col-12">
        <div class="info-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Personal Information</h5>
                <a href="patient/profile" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            </div>
            
            <div class="text-muted small mb-3">Basic Information</div>
            <div class="row mb-4 pb-4 border-bottom">
                <div class="col-md-4">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo escape($patient['full_name']); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value"><?php echo date('M d, Y', strtotime($patient['date_of_birth'])); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="info-label">Gender</div>
                    <div class="info-value text-capitalize"><?php echo escape($patient['gender']); ?></div>
                </div>
            </div>

            <div class="text-muted small mb-3">Account & Emergency</div>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-label">Patient Number</div>
                    <div class="info-value"><?php echo escape($patient['patient_number']); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="info-label">Emergency Contact</div>
                    <div class="info-value"><?php echo escape($patient['emergency_contact_name'] ?? 'Not provided'); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="info-label">Emergency Phone</div>
                    <div class="info-value"><?php echo escape($patient['emergency_contact_phone'] ?? 'Not provided'); ?></div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h5 class="fw-bold mb-4">Upcoming Appointments</h5>
            <?php if ($nextAppointment): ?>
                <div class="text-muted small mb-2">Next Scheduled</div>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="info-label">Doctor</div>
                        <div class="info-value fw-bold">Dr. <?php echo escape($nextAppointment['practitioner_name']); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Specialty</div>
                        <div class="info-value"><?php echo escape($nextAppointment['department_name']); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Date & Time</div>
                        <div class="info-value">
                            <?php echo date('M d, Y', strtotime($nextAppointment['appointment_date'])); ?> at 
                            <?php echo date('h:i A', strtotime($nextAppointment['appointment_time'])); ?>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <span class="badge bg-primary text-white">Confirmed</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="py-3 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p>No upcoming appointments found.</p>
                    <a href="patient/book-appointment" class="btn btn-primary mt-2">Book an Appointment</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/patient_footer.php'; ?>
