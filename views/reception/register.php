<?php 
$page_title = 'Register Patient';
ob_start();
?>
<style>
    .card { border-radius: 15px; }
    .card-header { border-top-left-radius: 15px !important; border-top-right-radius: 15px !important; }
    .form-control, .form-select { border-radius: 10px; padding: 10px 15px; }
    .btn-primary { background-color: var(--primary-teal); border: none; border-radius: 10px; padding: 12px; }
    .btn-primary:hover { background-color: #004d55; }
</style>
<?php 
$extra_css = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/reception_new_top.php'; 
?>

<div class="main-content" style="background-color: #f4f6f8; margin-right: 15px; padding: 30px;">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
        <h1 class="h3 fw-bold" style="color: var(--primary-teal);">Register Walk-in Patient</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
                    <h4 class="fw-bold mb-0" style="color: var(--primary-teal);">New Patient Registration</h4>
                    <p class="text-muted small mt-1">Fill out the form below to create a new patient account.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger shadow-sm border-0" style="border-radius: 10px;">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="reception/patients/register" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2" style="font-size: 0.85rem;">Personal Information</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="full_name" class="form-label fw-semibold text-muted small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" id="full_name" name="full_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label fw-semibold text-muted small">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control bg-light border-0" id="date_of_birth" name="date_of_birth" required>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label fw-semibold text-muted small">Gender <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0" id="gender" name="gender" required>
                                    <option value="">Select Gender...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2" style="font-size: 0.85rem;">Contact Information</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-muted small">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control bg-light border-0" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-muted small">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control bg-light border-0" id="email" name="email" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold text-muted small">Residential Address <span class="text-danger">*</span></label>
                                <textarea class="form-control bg-light border-0" id="address" name="address" rows="2" required></textarea>
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2" style="font-size: 0.85rem;">Account Setup</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold text-muted small">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" id="username" name="username" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-muted small">Temporary Password <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" id="password" name="password" placeholder="e.g. Password123!" required>
                                <div class="form-text small mt-2">Provide this to the patient and ask them to change it.</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                                <i class="bi bi-person-plus-fill me-2"></i> Register Patient & Continue to Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/reception_new_bottom.php'; ?>
