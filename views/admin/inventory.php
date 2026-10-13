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
    <title>Inventory - OAUTHC Dental</title>
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
            background-color: #ffffff;
            color: #111827;
        }

        /* Sidebar Styles */
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
        .sidebar-link.active {
            background-color: #a7f3d0;
            color: #065f46;
        }
        .sidebar-link i { font-size: 1.1rem; }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding: 1.5rem 2rem;
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

        /* Filters Row */
        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .left-filters {
            display: flex;
            gap: 1rem;
        }
        .right-filters {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .filter-btn-group {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #4b5563;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .search-input {
            position: relative;
            width: 250px;
        }
        .search-input input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 8px;
            border: none;
            background-color: #f3f4f6;
            font-size: 0.85rem;
        }
        .search-input input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #e5e7eb;
        }
        .search-input i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .btn-settings {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
            border-radius: 8px;
            color: #4b5563;
            border: none;
        }

        /* Table Styles */
        .table-responsive {
            background-color: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 500;
            color: #9ca3af;
            font-size: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: #ffffff;
            white-space: nowrap;
        }
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.85rem;
            color: #111827;
        }
        .form-check-input {
            margin-top: 0;
        }
        
        /* Item Cell */
        .item-thumbnail {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #f9fafb;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        .item-name {
            font-weight: 500;
            color: #111827;
        }

        /* Badges */
        .availability-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            background-color: #f9fafb;
        }
        .availability-badge::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 2px;
        }
        .availability-badge.Available {
            color: #047857;
            background-color: #d1fae5;
        }
        .availability-badge.Available::before { background-color: #059669; }
        
        .availability-badge.Low {
            color: #b91c1c;
            background-color: #fee2e2;
        }
        .availability-badge.Low::before { background-color: #dc2626; }
        
        .availability-badge.Out.of.Stock {
            color: #4b5563;
            background-color: #f3f4f6;
        }
        .availability-badge.Out.of.Stock::before { background-color: #9ca3af; }

        /* Actions */
        .btn-action-view {
            background: none;
            border: none;
            color: #6b7280;
            padding: 0.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .btn-action-view:hover { background-color: #f3f4f6; color: #111827; }
        
        .btn-reorder {
            background-color: #f3f4f6;
            border: none;
            color: #4b5563;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            transition: all 0.2s;
        }
        .btn-reorder:hover {
            background-color: #e5e7eb;
            color: #111827;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            font-size: 0.85rem;
            color: #6b7280;
        }
        .page-link {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #4b5563;
            text-decoration: none;
            background-color: #f9fafb;
            border: 1px solid transparent;
        }
        .page-link.active {
            background-color: #1e293b;
            color: white;
        }
        .page-link:hover:not(.active) {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php $current_page = 'inventory'; include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">Inventory</h1>
            
            <div class="header-actions">
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

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-3" style="font-size:0.9rem;">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-3" style="font-size:0.9rem;">
                <i class="bi bi-exclamation-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Filters -->
        <div class="filter-row">
            <div class="left-filters">
                <div class="filter-btn-group">
                    <i class="bi bi-funnel"></i> All Category <i class="bi bi-chevron-down"></i>
                </div>
                <div class="filter-btn-group">
                    <i class="bi bi-funnel"></i> All Status <i class="bi bi-chevron-down"></i>
                </div>
            </div>
            <div class="right-filters">
                <div class="search-input">
                    <i class="bi bi-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search item, etc">
                </div>
                <button class="btn-settings"><i class="bi bi-sliders"></i></button>
                <button class="btn btn-sm text-white" style="background-color: #1e293b; border-radius: 8px; padding: 0.5rem 1.25rem; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Item
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input class="form-check-input" type="checkbox"></th>
                        <th style="width: 60px;">Image</th>
                        <th>Item <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Category <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Availability <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Qty In Stock <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Qty In Reorder <i class="bi bi-chevron-expand ms-1"></i></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory_items as $item): ?>
                    <tr class="inventory-row">
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Thumbnail" class="item-thumbnail">
                        </td>
                        <td class="item-name"><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['category']) ?></td>
                        <td>
                            <?php 
                                $badgeClass = str_replace(' ', '.', $item['availability']);
                            ?>
                            <span class="availability-badge <?= $badgeClass ?>"><?= htmlspecialchars($item['availability']) ?></span>
                        </td>
                        <td class="fw-medium"><?= (int)$item['qty_in_stock'] ?></td>
                        <td class="text-muted"><?= (int)$item['qty_in_reorder'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn-action-view"><i class="bi bi-eye"></i></button>
                                <button class="btn-reorder" data-id="<?= $item['inventory_id'] ?>" data-name="<?= htmlspecialchars($item['item_name']) ?>" data-bs-toggle="modal" data-bs-target="#reorderModal">Reorder</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($inventory_items)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No inventory items found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container">
            <div>
                Showing <select class="form-select form-select-sm d-inline-block w-auto mx-1 border-0 bg-light" style="border-radius: 8px;"><option>12</option></select> out of 128
            </div>
            <div class="d-flex gap-1 align-items-center">
                <a href="#" class="page-link"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <span class="px-1">...</span>
                <a href="#" class="page-link">10</a>
                <a href="#" class="page-link"><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
        
        <div class="d-flex justify-content-between pt-5 mt-4 pb-2" style="font-size: 0.75rem; color: #9ca3af; font-weight: 500;">
            <div>Copyright &copy; <?= date('Y') ?> Peterdraw</div>
            <div class="d-flex gap-4 align-items-center">
                <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Term and conditions</a>
                <a href="#" class="text-muted text-decoration-none">Contact</a>
                <div class="d-flex gap-2 ms-4">
                    <i class="bi bi-facebook"></i>
                    <i class="bi bi-twitter-x"></i>
                    <i class="bi bi-instagram"></i>
                    <i class="bi bi-youtube"></i>
                    <i class="bi bi-linkedin"></i>
                </div>
            </div>
        </div>

    </main>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form method="POST" action="admin/inventory/add">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Add Inventory Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Item Name</label>
                    <input type="text" class="form-control" name="item_name" placeholder="e.g. Surgical Masks" required>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Category</label>
                    <input type="text" class="form-control" name="category" placeholder="e.g. PPE" required>
                 </div>
                 <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Qty In Stock</label>
                        <input type="number" class="form-control" name="qty_in_stock" placeholder="0" required>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Qty In Reorder</label>
                        <input type="number" class="form-control" name="qty_in_reorder" placeholder="0" required>
                     </div>
                 </div>
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Availability</label>
                    <select class="form-select" name="availability" required>
                        <option value="Available">Available</option>
                        <option value="Low">Low</option>
                        <option value="Out of Stock">Out of Stock</option>
                    </select>
                 </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #1e293b;">Add Item</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Reorder Modal -->
    <div class="modal fade" id="reorderModal" tabindex="-1">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form method="POST" action="admin/inventory/reorder">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <input type="hidden" name="inventory_id" id="reorder_inventory_id">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="modal-title fw-bold" id="reorder_title">Reorder Item</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                 <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:0.85rem;">Quantity to order</label>
                    <input type="number" class="form-control" name="reorder_qty" placeholder="100" min="1" required>
                 </div>
              </div>
              <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex flex-nowrap">
                <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white flex-grow-1" style="background-color: #1e293b;">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search Filter
        document.getElementById('tableSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.inventory-row').forEach(row => {
                const text = row.querySelector('.item-name').innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Reorder Modal Data Population
        document.querySelectorAll('.btn-reorder').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('reorder_inventory_id').value = this.getAttribute('data-id');
                document.getElementById('reorder_title').textContent = 'Reorder ' + this.getAttribute('data-name');
            });
        });
    </script>
</body>
</html>
