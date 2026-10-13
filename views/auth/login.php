<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
<div class="row justify-content-center py-5">
    <div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle text-primary display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold text-primary">Login</h3>
                    <p class="text-muted small">Access your OAUTHC Dental portal</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger small py-2"><?php echo escape($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="login">
                    <input type="hidden" name="csrf_token" value="<?php echo escape($csrf_token); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Email or Username</label>
                        <input type="text" name="identifier" class="form-control" required autofocus>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-bold text-secondary">Password</label>
                            <a href="forgot-password" class="small text-decoration-none">Forgot password?</a>
                        </div>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted small">Don't have an account? </span>
                    <a href="register" class="text-decoration-none small fw-bold">Register as Patient</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
