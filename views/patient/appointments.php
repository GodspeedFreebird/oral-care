<?php require_once VIEWS_PATH . '/layouts/patient_sidebar.php'; ?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <h3 class="fw-bold mb-0">My Appointments</h3>
    <a href="patient/book-appointment" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-2"></i>New Booking</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?php echo escape($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?php echo escape($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-start">Date & Time</th>
                        <th class="py-3 fw-medium border-0">Doctor</th>
                        <th class="py-3 fw-medium border-0">Department</th>
                        <th class="py-3 fw-medium border-0">Status</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end rounded-top-end">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($appointments)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">You have no booking history.</td></tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold"><?php echo date('M d, Y', strtotime($appt['appointment_date'])); ?></div>
                                    <div class="small text-muted"><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></div>
                                </td>
                                <td class="py-3 fw-medium">Dr. <?php echo escape($appt['practitioner_name']); ?></td>
                                <td class="py-3 text-muted"><?php echo escape($appt['department_name']); ?></td>
                                <td class="py-3">
                                    <?php 
                                        $badge = 'bg-secondary';
                                        if ($appt['status'] === 'pending') $badge = 'bg-warning text-dark';
                                        if ($appt['status'] === 'confirmed') $badge = 'bg-primary text-white';
                                        if ($appt['status'] === 'completed') $badge = 'bg-success text-white';
                                        if ($appt['status'] === 'cancelled') $badge = 'bg-danger text-white';
                                    ?>
                                    <span class="badge <?php echo $badge; ?> rounded-pill fw-normal px-3 py-2 text-capitalize"><?php echo escape($appt['status']); ?></span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <?php if (in_array($appt['status'], ['pending', 'confirmed'])): ?>
                                        <form method="POST" action="patient/cancel-appointment" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo escape(AuthService::generateCSRF()); ?>">
                                            <input type="hidden" name="appointment_id" value="<?php echo escape($appt['appointment_id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill">Cancel</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light px-3 rounded-pill disabled text-muted border">Completed/Cancelled</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/patient_footer.php'; ?>
