<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div> <!-- Close header's default container -->

<style>
    :root {
        --bs-primary: #3b82f6;
        --bs-primary-rgb: 59, 130, 246;
        --bs-secondary: #64748b;
        --bs-body-bg: #f1f5f9;
    }
    body {
        background-color: var(--bs-body-bg);
        overflow-x: hidden;
    }
    .content-area {
        flex-grow: 1;
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .profile-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 20px 20px 0 0;
        padding: 3rem 2rem 5rem;
        color: white;
        position: relative;
    }
    .profile-avatar-container {
        position: relative;
        margin-top: -4rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        object-fit: cover;
        background-color: white;
    }
    .form-control, .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        background-color: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .form-label {
        font-weight: 500;
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    .btn-custom {
        border-radius: 10px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59,130,246,0.4);
        color: white;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        <?php require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="content-area">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">Profile Settings</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="doctor/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                            </ol>
                        </nav>
                    </div>

                    <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                        <div>Settings updated successfully!</div>
                    </div>
                    <?php endif; ?>

                    <div class="glass-card mb-5">
                        <div class="profile-header">
                            <h5 class="mb-1 fw-bold"><?= escape($doctor['full_name']) ?></h5>
                            <p class="mb-0 text-white-50"><i class="bi bi-geo-alt me-1"></i>OAUTHC Dental Centre</p>
                        </div>
                        
                        <div class="profile-avatar-container">
                            <?php if (!empty($doctor['profile_picture'])): ?>
                                <img src="<?= escape($doctor['profile_picture']) ?>" alt="Profile Picture" class="profile-avatar">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode(str_replace('Dr. ', '', $doctor['full_name'])) ?>&background=eff6ff&color=3b82f6&size=200" alt="Profile Picture" class="profile-avatar">
                            <?php endif; ?>
                        </div>

                        <form action="doctor/settings/update" method="POST" class="p-4 pt-0" enctype="multipart/form-data">
    <div class="row g-5">
        <!-- Personal Information -->
        <div class="col-lg-6">
            <h6 class="section-title"><i class="bi bi-person-badge text-primary me-2"></i>Personal Information</h6>
            
            <div class="mb-3">
                <label class="form-label">Profile Picture (Optional)</label>
                <div class="input-group">
                    <input type="file" name="profile_picture" class="form-control" accept="image/jpeg,image/png,image/webp">
                </div>
                <div class="form-text mt-1 text-muted" style="font-size: 0.8rem;">Upload a new image to change your avatar. Max size: 2MB.</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" name="full_name" class="form-control border-start-0 ps-0" value="<?= escape($doctor['full_name']) ?>" required>
                </div>
            </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="email" class="form-control border-start-0 ps-0" value="<?= escape($doctor['email']) ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                            <input type="text" name="phone" class="form-control border-start-0 ps-0" value="<?= escape($doctor['phone'] ?? '') ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Professional Bio</label>
                                        <textarea name="about" class="form-control" rows="4" placeholder="Tell your patients a bit about your experience..."><?= escape($doctor['about'] ?? '') ?></textarea>
                                    </div>
                                </div>

                                <!-- Professional Details & Security -->
                                <div class="col-lg-6">
                                    <h6 class="section-title"><i class="bi bi-briefcase text-primary me-2"></i>Professional Details</h6>
                                    
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Department</label>
                                            <select name="department_id" class="form-select">
                                                <?php foreach ($departments as $dept): ?>
                                                    <option value="<?= $dept['department_id'] ?>" <?= $dept['department_id'] == $doctor['department_id'] ? 'selected' : '' ?>><?= escape($dept['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Specialisation</label>
                                            <input type="text" name="specialisation" class="form-control" value="<?= escape($doctor['specialisation'] ?? '') ?>">
                                        </div>
                                    </div>
                                    
                                    <h6 class="section-title mt-4"><i class="bi bi-shield-lock text-primary me-2"></i>Security</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-at"></i></span>
                                            <input type="text" name="username" class="form-control border-start-0 ps-0" value="<?= escape($doctor['username']) ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                                            <input type="password" name="new_password" class="form-control border-start-0 ps-0" placeholder="Leave blank to keep current">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4" style="border-color: #e2e8f0;">
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="doctor/dashboard" class="btn btn-light btn-custom text-secondary border">Cancel</a>
                                <button type="submit" class="btn btn-primary-custom btn-custom">
                                    <i class="bi bi-check2-circle me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
        </main>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<div class="container" style="display: none;"> <!-- Reopen for footer -->
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
