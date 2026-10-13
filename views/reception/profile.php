<?php 
$page_title = 'My Profile';
require_once BASE_PATH . '/views/layouts/reception_new_top.php'; 
?>

<div class="main-content" style="background-color: #f8fafc;">
    
    <div class="header-bar mb-4">
        <h1 class="header-title" style="font-size: 2rem; font-weight: 700; color: #1f2937;">Receptionist Profile</h1>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header text-center" style="background-color: #1976d2; color: white; border-top-left-radius: 15px; border-top-right-radius: 15px; padding: 30px;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background-color: white; color: #1976d2; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 15px auto; font-weight: bold;">
                        <?= strtoupper(substr($profile['full_name'] ?? $profile['username'], 0, 1)) ?>
                    </div>
                    <h4 class="mb-0 fw-bold"><?= htmlspecialchars($profile['full_name'] ?? $profile['username']) ?></h4>
                    <p class="mb-0 text-white-50">Member since <?= date('M Y', strtotime($profile['created_at'])) ?></p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="reception/profile/update" method="POST">
                        
                        <h5 class="fw-bold mb-4" style="color: #1976d2; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Personal Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Full Name</label>
                            <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" required style="border-radius: 8px;">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($profile['username'] ?? '') ?>" required style="border-radius: 8px;">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Email Address</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required style="border-radius: 8px;">
                        </div>
                        
                        <h5 class="fw-bold mb-4" style="color: #1976d2; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Work Details</h5>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Assigned Shift</label>
                            <select class="form-select" name="shift" style="border-radius: 8px;">
                                <option value="morning" <?= ($profile['shift'] === 'morning') ? 'selected' : '' ?>>Morning</option>
                                <option value="afternoon" <?= ($profile['shift'] === 'afternoon') ? 'selected' : '' ?>>Afternoon</option>
                                <option value="full_day" <?= ($profile['shift'] === 'full_day') ? 'selected' : '' ?>>Full Day</option>
                            </select>
                        </div>
                        
                        <h5 class="fw-bold mb-4" style="color: #1976d2; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Security</h5>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">New Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password" style="border-radius: 8px;">
                            <div class="form-text">Only fill this out if you wish to change your password.</div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg" style="background-color: #1976d2; border: none; border-radius: 10px; font-weight: bold;">Save Changes</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php require_once BASE_PATH . '/views/layouts/reception_new_bottom.php'; ?>
