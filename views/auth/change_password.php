<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock text-primary" style="font-size: 4rem;"></i>
                <h2 class="fw-bold mt-2">Change Password</h2>
                <p class="text-muted">For your security, you must change your auto-generated password before continuing.</p>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger border-0 rounded-3 text-center mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= escape($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form action="change-password" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= escape(AuthService::generateCSRF()) ?>">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Current Password</label>
                            <input type="password" name="current_password" class="form-control form-control-lg bg-light border-0" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">New Password</label>
                            <input type="password" name="new_password" class="form-control form-control-lg bg-light border-0" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg bg-light border-0" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill border-0 shadow-sm" style="background: linear-gradient(135deg, #0052cc 0%, #003d99 100%);">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
