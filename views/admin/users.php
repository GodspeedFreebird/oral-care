<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f8f9fa;">
    <div class="row g-0">
        <?php require_once VIEWS_PATH . '/layouts/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-dark">User Management</h1>
                <button class="btn btn-primary rounded-pill fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-person-plus-fill me-1"></i> Add New User
                </button>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <?= escape($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <?= escape($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Username</th>
                                    <th class="py-3">Email</th>
                                    <th class="py-3">Role</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Joined Date</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-4 py-3 fw-bold text-dark"><?= htmlspecialchars($u['username']) ?></td>
                                    <td class="py-3 text-muted"><?= htmlspecialchars($u['email']) ?></td>
                                    <td class="py-3">
                                        <?php
                                        $badgeClass = 'bg-secondary';
                                        if ($u['role'] === 'admin') $badgeClass = 'bg-danger';
                                        if ($u['role'] === 'practitioner') $badgeClass = 'bg-primary';
                                        if ($u['role'] === 'receptionist') $badgeClass = 'bg-info text-dark';
                                        if ($u['role'] === 'patient') $badgeClass = 'bg-success';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> text-uppercase"><?= htmlspecialchars($u['role']) ?></span>
                                    </td>
                                    <td class="py-3">
                                        <?php if ($u['is_active']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="bi bi-x-circle-fill me-1"></i> Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 text-muted"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                                    <td class="pe-4 py-3 text-end">
                                        <a href="admin/users/<?= $u['user_id'] ?>/edit" class="btn btn-sm btn-light text-primary border rounded-pill px-3 fw-bold">Edit</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold">Provision New Staff Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <p class="text-muted small mb-4">A random password will be auto-generated and shown to you. The user must change it upon their first login.</p>
        
        <form action="admin/users" method="POST">
            <input type="hidden" name="csrf_token" value="<?= escape(AuthService::generateCSRF()) ?>">
            <div class="mb-3">
                <label class="form-label fw-bold small">Role</label>
                <select name="role" id="roleSelect" class="form-select bg-light border-0" required onchange="toggleRoleFields()">
                    <option value="">Select a role...</option>
                    <option value="admin">System Administrator</option>
                    <option value="practitioner">Practitioner / Doctor</option>
                    <option value="receptionist">Receptionist</option>
                </select>
                <div class="form-text">Note: Patients must self-register via the public portal.</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold small">Username</label>
                <input type="text" name="username" class="form-control bg-light border-0" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold small">Email Address</label>
                <input type="email" name="email" class="form-control bg-light border-0" required>
            </div>
            
            <!-- Dynamic Fields Container -->
            <div id="dynamicFields" style="display: none;" class="p-3 bg-light rounded-3 mb-4 border">
                <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Additional Profile Details</h6>
                
                <div class="mb-3" id="fullNameField">
                    <label class="form-label fw-bold small">Full Name</label>
                    <input type="text" name="full_name" id="full_name" class="form-control bg-white">
                </div>
                
                <!-- Practitioner Fields -->
                <div id="practitionerFields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Specialisation</label>
                        <input type="text" name="specialisation" id="specialisation" class="form-control bg-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Licence Number</label>
                        <input type="text" name="licence_no" id="licence_no" class="form-control bg-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Department</label>
                        <select name="department_id" id="department_id" class="form-select bg-white">
                            <?php foreach ($departments as $d): ?>
                                <option value="<?= $d['department_id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Receptionist Fields -->
                <div id="receptionistFields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Shift</label>
                        <select name="shift" id="shift" class="form-select bg-white">
                            <option value="morning">Morning</option>
                            <option value="afternoon">Afternoon</option>
                            <option value="full_day">Full Day</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm">Create Account</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('roleSelect').value;
    const container = document.getElementById('dynamicFields');
    const pracFields = document.getElementById('practitionerFields');
    const recFields = document.getElementById('receptionistFields');
    
    // Reset all
    container.style.display = 'none';
    pracFields.style.display = 'none';
    recFields.style.display = 'none';
    
    // Remove required attrs
    document.getElementById('full_name').required = false;
    document.getElementById('specialisation').required = false;
    document.getElementById('licence_no').required = false;
    
    if (role === 'practitioner') {
        container.style.display = 'block';
        pracFields.style.display = 'block';
        document.getElementById('full_name').required = true;
        document.getElementById('specialisation').required = true;
        document.getElementById('licence_no').required = true;
    } else if (role === 'receptionist') {
        container.style.display = 'block';
        recFields.style.display = 'block';
        document.getElementById('full_name').required = true;
    }
}
</script>

<div class="container" style="display: none;">
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
