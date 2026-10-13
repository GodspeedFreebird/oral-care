<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
<div class="row justify-content-center py-5">
    <div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock text-primary display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold text-primary">Reset Password</h3>
                    <p class="text-muted small">Enter your email to receive a reset link</p>
                </div>

                <form method="POST" action="forgot-password">
                    <input type="hidden" name="csrf_token" value="<?php echo escape($csrf_token); ?>">
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="login" class="text-decoration-none small fw-bold">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
