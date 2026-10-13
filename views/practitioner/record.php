<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        <!-- Sidebar -->
        <?php 
            if (isset($isAdminView) && $isAdminView) {
                $current_page = 'patients';
                require_once VIEWS_PATH . '/layouts/admin_sidebar.php';
            } else {
                $current_page = 'practitioner_patients';
                require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php';
            }
        ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 p-0 min-vh-100" style="background-color: #f4f6fb;">
            <?php require_once 'record_partial.php'; ?>
        </main>
    </div>
</div>

<div class="container" style="display: none;">
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
