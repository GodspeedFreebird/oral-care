<!-- Hero Section -->
<section class="py-5 text-center">
    <div class="row py-lg-5">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1 class="display-4 fw-bold text-primary mb-3">OAUTHC Dental Centre</h1>
            <p class="lead text-muted mb-4">
                Delivering comprehensive, specialized oral healthcare and advancing dental medicine within the Obafemi Awolowo University Teaching Hospitals Complex.
            </p>
            <p>
                <a href="register" class="btn btn-primary btn-lg my-2 px-4 shadow-sm">Book an Appointment</a>
                <a href="#departments" class="btn btn-outline-secondary btn-lg my-2 px-4 shadow-sm">Learn About Our Departments</a>
            </p>
        </div>
    </div>
</section>

<!-- Departments Section -->
<section id="departments" class="py-5 border-top border-bottom">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Our Departments</h2>
        <p class="text-muted">Specialized clinical divisions providing expert care.</p>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
        <?php
        require_once BASE_PATH . '/config/database.php';
        $db = Database::getInstance()->getConnection();
        $departments = $db->query("SELECT * FROM departments ORDER BY name")->fetchAll();
        foreach ($departments as $dept):
            $img = !empty($dept['image_url']) ? $dept['image_url'] : 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?w=600&q=80';
        ?>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm bg-light overflow-hidden">
                <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($dept['name']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center p-4">
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($dept['name']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($dept['description'] ?? 'Specialized clinical care.') ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Why Book Online Section -->
<section class="py-5 mb-4">
    <div class="text-center mb-5 mt-3">
        <h2 class="fw-bold text-primary">Why Book Online?</h2>
        <p class="text-muted">Streamline your visit with our digital portal.</p>
    </div>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="p-4 border rounded-3 h-100">
                <i class="bi bi-clock-history text-primary fs-1 mb-3"></i>
                <h5 class="fw-bold mt-2">Save Time</h5>
                <p class="text-muted small mb-0">Avoid the physical trip to the hospital just to schedule a consultation.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-4 border rounded-3 h-100">
                <i class="bi bi-envelope-check text-primary fs-1 mb-3"></i>
                <h5 class="fw-bold mt-2">Stay Notified</h5>
                <p class="text-muted small mb-0">Receive automated email reminders so you never miss an appointment.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-4 border rounded-3 h-100">
                <i class="bi bi-file-earmark-medical text-primary fs-1 mb-3"></i>
                <h5 class="fw-bold mt-2">Digital Records</h5>
                <p class="text-muted small mb-0">Keep a persistent, secure digital record of your visits and prescriptions.</p>
            </div>
        </div>
    </div>
</section>

