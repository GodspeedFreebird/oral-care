<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($department['name']) ?> Details - OAUTHC Dental</title>
    <?php
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') $base_url .= '/';
    ?>
    <base href="<?= htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff; /* white background for this page based on reference */
            color: #111827;
        }
        
        /* Sidebar Styles (Reused) */
        .sidebar {
            width: 250px;
            background-color: #f3f4f6;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
            z-index: 100;
        }
        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            padding-left: 0.5rem;
        }
        .sidebar-brand i { color: #0f172a; font-size: 1.5rem; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex: 1; }
        .sidebar-item { margin-bottom: 0.25rem; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #a5f3fc;
            color: #155e75;
        }
        .sidebar-link i { font-size: 1.1rem; }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding: 2rem 3rem;
            max-width: 1200px;
        }

        /* Top Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .back-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: #111827;
        }
        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        .page-subtitle {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Hero Image */
        .hero-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        /* Title & Team Row */
        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
        }
        .dept-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .dept-title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background-color: #f3f4f6;
            border-radius: 50%;
            color: #9ca3af;
            font-size: 1rem;
            cursor: pointer;
            margin-left: 0.5rem;
        }

        /* Stacked Avatars */
        .team-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .team-label {
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .stacked-avatars {
            display: flex;
            align-items: center;
        }
        .stacked-avatars img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            margin-right: -10px;
            object-fit: cover;
            background-color: #f3f4f6;
        }
        .stacked-avatars img:last-of-type { margin-right: 0; }
        .others-text {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-left: 0.75rem;
        }

        /* Section Headings */
        .section-heading {
            font-size: 1.25rem;
            font-weight: 600;
            color: #d1d5db; /* Light gray based on reference */
            margin-bottom: 1rem;
            margin-top: 2rem;
        }
        .section-content {
            font-size: 0.95rem;
            color: #4b5563;
            line-height: 1.6;
        }

        /* Treatments List */
        .treatments-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .treatment-item {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .treatment-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            width: 10px;
            height: 10px;
            background-color: #0284c7; /* light blue dot */
            border-radius: 50%;
        }
        .treatment-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
        }
        .treatment-desc {
            font-size: 0.85rem;
            color: #6b7280;
        }
        .btn-remove-treatment {
            background: none;
            border: none;
            color: #ef4444;
            font-size: 0.7rem;
            padding: 0;
            cursor: pointer;
            visibility: hidden;
        }
        .treatment-item:hover .btn-remove-treatment {
            visibility: visible;
        }

        /* Team Grid */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .team-card {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .team-card-img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: 1rem;
            background-color: #a5f3fc; /* cyan background for avatar */
        }
        .team-card-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        .team-card-role {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }
        .team-socials {
            display: flex;
            gap: 0.5rem;
            color: #9ca3af;
        }
        .team-socials i { cursor: pointer; }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'departments'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <a href="admin/departments" class="back-link">
                <div class="back-btn"><i class="bi bi-arrow-left"></i></div>
                <div>
                    <div class="page-subtitle">Back to Department List</div>
                    <h1 class="page-title">Department Details</h1>
                </div>
            </a>
            
            <div class="header-actions">
                <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editDeptModal"><i class="bi bi-pencil me-1"></i> Edit Dept</button>
                <a href="admin/settings" class="icon-btn" title="Settings" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; color: #4b5563; text-decoration: none;"><i class="bi bi-gear"></i></a>
                <div class="dropdown d-inline-block">
                    <a href="#" class="icon-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; color: #4b5563; text-decoration: none;">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" style="width: 250px;">
                        <li class="px-3 py-2 border-bottom fw-bold">Notifications</li>
                        <li class="px-3 py-3 text-center text-muted small">No new notifications</li>
                    </ul>
                </div>
                <div class="user-profile dropdown d-inline-block">
                    <div data-bs-toggle="dropdown" aria-expanded="false" style="display: flex; align-items: center; gap: 0.75rem; background-color: #f3f4f6; padding: 0.5rem 1rem 0.5rem 0.5rem; border-radius: 30px; cursor: pointer;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=0D8ABC&color=fff" alt="User" class="user-avatar" style="width: 32px; height: 32px; border-radius: 50%;">
                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;"><?= escape($_SESSION['username'] ?? 'Administrator') ?> <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem;"></i></span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                        <li><a class="dropdown-item" href="admin/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Hero Image -->
        <img src="<?= escape($department['image_url'] ?? 'https://images.unsplash.com/photo-1584982751601-97dcc096659c?q=80&w=1200&auto=format&fit=crop') ?>" alt="<?= escape($department['name']) ?>" class="hero-img">

        <!-- Title Row -->
        <div class="title-row">
            <h2 class="dept-title"><?= escape($department['name']) ?> <div class="dept-title-icon"><i class="bi bi-chevron-down"></i></div></h2>
            
            <div class="team-box">
                <span class="team-label">Team:</span>
                <div class="stacked-avatars">
                    <?php 
                    $docCount = count($doctors);
                    $displayDoctors = array_slice($doctors, 0, 4);
                    $remaining = max(0, $docCount - 4);
                    
                    foreach($displayDoctors as $doc): 
                    ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(str_replace('Dr. ', '', $doc['full_name'])) ?>&background=random&size=100" alt="<?= escape($doc['full_name']) ?>" title="<?= escape($doc['full_name']) ?>">
                    <?php endforeach; ?>
                    
                    <?php if ($remaining > 0): ?>
                        <span class="others-text">+ <?= $remaining ?> others</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <h3 class="section-heading">About</h3>
        <div class="section-content">
            <?= escape($department['description']) ?>
        </div>

        <!-- Treatments Section -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
            <h3 class="section-heading mt-0 mb-0">Our Treatments</h3>
            <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#addTreatmentModal"><i class="bi bi-plus-circle me-1"></i> Add Treatment</button>
        </div>
        
        <ul class="treatments-list">
            <?php if (empty($treatments)): ?>
                <li class="text-muted small">No treatments added yet.</li>
            <?php else: ?>
                <?php foreach ($treatments as $t): ?>
                <li class="treatment-item">
                    <div class="treatment-title">
                        <?= escape($t['title']) ?>
                        <form method="POST" action="admin/department/treatment/remove" class="d-inline ms-2 m-0 p-0" onsubmit="return confirm('Remove this treatment?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="department_id" value="<?= $department['department_id'] ?>">
                            <input type="hidden" name="treatment_id" value="<?= $t['treatment_id'] ?>">
                            <button type="submit" class="btn-remove-treatment"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                    <div class="treatment-desc"><?= escape($t['description']) ?></div>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Our Team Section -->
        <h3 class="section-heading">Our Team</h3>
        <?php if (empty($doctors)): ?>
            <div class="text-muted small">No doctors assigned to this department.</div>
        <?php else: ?>
            <div class="team-grid">
                <?php foreach ($doctors as $doc): ?>
                <div class="team-card">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(str_replace('Dr. ', '', $doc['full_name'])) ?>&background=random&size=120" alt="<?= escape($doc['full_name']) ?>" class="team-card-img">
                    <div class="team-card-name"><?= escape($doc['full_name']) ?></div>
                    <div class="team-card-role"><?= escape($doc['specialisation'] ?: 'Doctor') ?></div>
                    <div class="team-socials">
                        <i class="bi bi-linkedin"></i>
                        <i class="bi bi-twitter-x"></i>
                        <i class="bi bi-instagram"></i>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between pt-5 mt-4 pb-2" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
            <div>Copyright &copy; <?= date('Y') ?> OAUTHC Dental</div>
            <div class="d-flex gap-4">
                <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Term and conditions</a>
                <a href="#" class="text-muted text-decoration-none">Contact</a>
            </div>
        </div>

    </main>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDeptModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="admin/department/edit">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="department_id" value="<?= $department['department_id'] ?>">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Department Name</label>
                        <input type="text" class="form-control bg-light border-0" name="name" value="<?= escape($department['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Image URL</label>
                        <input type="url" class="form-control bg-light border-0" name="image_url" value="<?= escape($department['image_url']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Description</label>
                        <textarea class="form-control bg-light border-0" name="description" rows="5" required><?= escape($department['description']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Treatment Modal -->
    <div class="modal fade" id="addTreatmentModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="admin/department/treatment/add">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="department_id" value="<?= $department['department_id'] ?>">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Treatment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Treatment Title</label>
                        <input type="text" class="form-control bg-light border-0" name="title" required placeholder="e.g. Routine Check-Ups">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Description</label>
                        <textarea class="form-control bg-light border-0" name="description" rows="3" required placeholder="Description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Treatment</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
