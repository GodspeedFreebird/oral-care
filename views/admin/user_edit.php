<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f8f9fa;">
    <div class="row g-0">
        <?php require_once VIEWS_PATH . '/layouts/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            <a href="admin/users" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Back to Users</a>
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-dark">Edit User Account: <?= htmlspecialchars($user['username']) ?></h1>
                <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?> px-3 py-2 text-uppercase"><?= htmlspecialchars($user['role']) ?></span>
            </div>

            <div class="row">
                <div class="col-md-8 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            
                            <form action="admin/users/<?= $user['user_id'] ?>/edit" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= escape(AuthService::generateCSRF()) ?>">
                                
                                <h6 class="fw-bold mb-4 text-primary border-bottom pb-2">Core Account Settings</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Username (Cannot be changed)</label>
                                    <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label text-muted small">Email (Cannot be changed)</label>
                                    <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                </div>
                                
                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActive" <?= $user['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold <?= $user['is_active'] ? 'text-success' : 'text-danger' ?>" for="isActive">Account is Active</label>
                                    <div class="form-text">Deactivating an account prevents the user from logging in.</div>
                                </div>

                                <?php if ($user['role'] === 'practitioner' || $user['role'] === 'receptionist'): ?>
                                    <h6 class="fw-bold mb-4 mt-5 text-primary border-bottom pb-2">Profile Details</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Full Name</label>
                                        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($details['full_name'] ?? '') ?>" required>
                                    </div>
                                    
                                    <?php if ($user['role'] === 'practitioner'): ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Specialisation</label>
                                            <input type="text" name="specialisation" class="form-control" value="<?= htmlspecialchars($details['specialisation'] ?? '') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Licence Number (Cannot be changed)</label>
                                            <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars($details['licence_no'] ?? '') ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Department</label>
                                            <select name="department_id" class="form-select" required>
                                                <?php foreach ($departments as $d): ?>
                                                    <option value="<?= $d['department_id'] ?>" <?= ($details['department_id'] == $d['department_id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($d['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($user['role'] === 'receptionist'): ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Shift</label>
                                            <select name="shift" class="form-select" required>
                                                <option value="morning" <?= ($details['shift'] === 'morning') ? 'selected' : '' ?>>Morning</option>
                                                <option value="afternoon" <?= ($details['shift'] === 'afternoon') ? 'selected' : '' ?>>Afternoon</option>
                                                <option value="full_day" <?= ($details['shift'] === 'full_day') ? 'selected' : '' ?>>Full Day</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                    
                                <?php endif; ?>

                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-4 rounded-pill shadow-sm">Save Changes</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="container" style="display: none;">
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
