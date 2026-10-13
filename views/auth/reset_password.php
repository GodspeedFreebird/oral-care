<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
<div class="row justify-content-center py-5">
    <div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-key text-primary display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold text-primary">New Password</h3>
                    <p class="text-muted small">Enter your new secure password</p>
                </div>

                <form method="POST" action="reset-password">
                    <input type="hidden" name="csrf_token" value="<?php echo escape($csrf_token ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
