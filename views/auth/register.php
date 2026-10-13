<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
<div class="row justify-content-center py-5">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus text-primary display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold text-primary">Patient Registration</h3>
                    <p class="text-muted small">Create your secure digital record</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger small py-2">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo escape($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo escape($csrf_token); ?>">
                    
                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Personal Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-secondary">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo escape($_POST['full_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="<?php echo escape($_POST['date_of_birth'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo escape($_POST['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo escape($_POST['address'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-secondary">Profile Picture (Optional)</label>
                            <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Account Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo escape($_POST['email'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo escape($_POST['username'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            <div class="form-text small">Minimum 8 characters.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="8">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted small">Already registered? </span>
                    <a href="login" class="text-decoration-none small fw-bold">Login Here</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
