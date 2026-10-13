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
    <title>Departments - OAUTHC Dental</title>
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
            background-color: #f9fafb; /* gray-50 */
            color: #111827; /* gray-900 */
        }
        
        /* Sidebar Styles (Reused) */
        .sidebar {
            width: 250px;
            background-color: #f3f4f6; /* gray-100 */
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
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
            color: #6b7280; /* gray-500 */
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #a5f3fc; /* cyan-200 */
            color: #155e75; /* cyan-900 */
        }
        .sidebar-link i { font-size: 1.1rem; }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Top Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .icon-btn:hover { background-color: #f3f4f6; }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background-color: #f3f4f6;
            padding: 0.5rem 1rem 0.5rem 0.5rem;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .user-profile:hover { border-color: #e5e7eb; }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Departments Grid */
        .dept-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        .dept-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #f3f4f6;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .dept-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        .dept-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #f3f4f6;
        }
        .dept-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .dept-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .dept-desc {
            font-size: 0.85rem;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            flex: 1;
        }
        
        .dept-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        
        /* Stacked Avatars */
        .stacked-avatars {
            display: flex;
            align-items: center;
        }
        .stacked-avatars img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            margin-right: -10px;
            object-fit: cover;
            background-color: #f3f4f6;
        }
        .stacked-avatars img:last-of-type {
            margin-right: 0;
        }
        .others-text {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-left: 0.5rem;
            white-space: nowrap;
        }

        .btn-detail {
            background-color: #1f2937; /* gray-800 */
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            border: none;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        .btn-detail:hover {
            background-color: #111827; /* gray-900 */
            color: #ffffff;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'departments'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">Departments</h1>
            
            <div class="header-actions">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeptModal" style="border-radius: 8px; font-weight: 500; padding: 0.5rem 1rem;">
                    <i class="bi bi-plus-lg me-1"></i> Add Department
                </button>
                <a href="admin/settings" class="icon-btn" title="Settings"><i class="bi bi-gear"></i></a>
                <div class="dropdown d-inline-block">
                    <a href="#" class="icon-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
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
                    <div data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=0D8ABC&color=fff" alt="User" class="user-avatar" style="width:32px;height:32px;border-radius:50%;">
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

        <!-- Departments Grid -->
        <div class="dept-grid">
            <?php foreach($departments as $dept): ?>
            <div class="dept-card">
                <!-- If no image provided, fallback to random gradient -->
                <img src="<?= escape($dept['image_url'] ?? 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=600&auto=format&fit=crop') ?>" alt="<?= escape($dept['name']) ?>" class="dept-img">
                
                <div class="dept-content">
                    <h3 class="dept-name"><?= escape($dept['name']) ?></h3>
                    <p class="dept-desc"><?= escape($dept['description'] ?: 'Specialized department providing expert care and services to our patients.') ?></p>
                    
                    <div class="dept-footer">
                        <div class="stacked-avatars">
                            <?php 
                            // Take up to 4 doctors for avatars
                            $displayDoctors = array_slice($dept['doctors'], 0, 4);
                            $remaining = max(0, $dept['doctor_count'] - 4);
                            
                            foreach($displayDoctors as $docName): 
                            ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode(str_replace('Dr. ', '', $docName)) ?>&background=random&size=100" alt="<?= escape($docName) ?>" title="<?= escape($docName) ?>">
                            <?php endforeach; ?>
                            
                            <?php if ($remaining > 0): ?>
                                <span class="others-text">+ <?= $remaining ?> others</span>
                            <?php elseif ($dept['doctor_count'] === 0): ?>
                                <span class="others-text" style="margin-left: 0;">No doctors</span>
                            <?php endif; ?>
                        </div>
                        <a href="admin/department/details?id=<?= $dept['department_id'] ?>" class="btn-detail">See Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between pt-5 mt-4 pb-2" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
            <div>Copyright &copy; <?= date('Y') ?> OAUTHC Dental</div>
            <div class="d-flex gap-4">
                <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Term and conditions</a>
                <a href="#" class="text-muted text-decoration-none">Contact</a>
            </div>
            <div class="d-flex gap-2">
                <i class="bi bi-facebook"></i>
                <i class="bi bi-twitter-x"></i>
                <i class="bi bi-instagram"></i>
                <i class="bi bi-youtube"></i>
                <i class="bi bi-linkedin"></i>
            </div>
        </div>

    </main>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDeptModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="admin/department/add">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Department Name</label>
                        <input type="text" class="form-control bg-light border-0" name="name" required placeholder="e.g. Neurology">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Image URL</label>
                        <input type="url" class="form-control bg-light border-0" name="image_url" required placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Description</label>
                        <textarea class="form-control bg-light border-0" name="description" rows="4" required placeholder="Description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Department</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
