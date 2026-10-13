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
        <a class="nav-link active" href="patient/notifications">Notification History</a>
    </li>
</ul>

<!-- Notifications List -->
<div class="info-card p-0 overflow-hidden">
    <div class="p-4 border-bottom bg-light">
        <h5 class="mb-0 fw-bold">Recent Notifications</h5>
        <p class="text-muted small mb-0 mt-1">A history of automated alerts, booking confirmations, and reminders sent to you.</p>
    </div>
    
    <div class="list-group list-group-flush">
        <?php if (empty($notifications)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-3"></i>
                You don't have any notifications yet.
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="list-group-item p-4 d-flex align-items-start gap-3">
                    <?php 
                        $iconClass = 'bi-bell';
                        $iconBg = 'bg-primary';
                        if ($notif['type'] == 'booking_confirmation') {
                            $iconClass = 'bi-calendar-check';
                            $iconBg = 'bg-success';
                        } elseif ($notif['type'] == 'cancellation') {
                            $iconClass = 'bi-calendar-x';
                            $iconBg = 'bg-danger';
                        } elseif ($notif['type'] == 'reminder_24h') {
                            $iconClass = 'bi-alarm';
                            $iconBg = 'bg-warning text-dark';
                        }
                    ?>
                    <div class="rounded-circle <?= $iconBg ?> text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="bi <?= $iconClass ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold text-dark text-capitalize">
                                <?= str_replace('_', ' ', htmlspecialchars($notif['type'])) ?>
                            </h6>
                            <small class="text-muted">
                                <?= date('M j, Y g:i A', strtotime($notif['scheduled_at'])) ?>
                            </small>
                        </div>
                        <div class="mb-2 text-secondary notification-body">
                            <?= $notif['message'] ?>
                        </div>
                        <div class="d-flex gap-3 small">
                            <span class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i> 
                                Appt: <?= date('M j, Y', strtotime($notif['appointment_date'])) ?> at <?= date('g:i A', strtotime($notif['appointment_time'])) ?>
                            </span>
                            <span class="text-muted">
                                <i class="bi bi-envelope-check me-1"></i> 
                                Sent to: <?= htmlspecialchars($notif['recipient_email']) ?>
                            </span>
                            <span class="badge <?= $notif['status'] == 'sent' ? 'bg-success' : ($notif['status'] == 'failed' ? 'bg-danger' : 'bg-secondary') ?> bg-opacity-10 <?= $notif['status'] == 'sent' ? 'text-success' : ($notif['status'] == 'failed' ? 'text-danger' : 'text-secondary') ?> border px-2 py-0 align-self-center">
                                <?= ucfirst(htmlspecialchars($notif['status'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

    </div>
    <!-- End main-content -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
