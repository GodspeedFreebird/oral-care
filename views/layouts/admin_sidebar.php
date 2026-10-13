<?php
$current_page = $current_page ?? 'dashboard';
?>
<aside class="sidebar">
    <a href="admin/dashboard" class="sidebar-brand">
        <i class="bi bi-hexagon-fill"></i> OAUTHC Dental
    </a>
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="admin/dashboard" class="sidebar-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </li>
        <li class="sidebar-item">
            <a href="admin/appointments" class="sidebar-link <?= $current_page === 'appointments' ? 'active' : '' ?>">
                <i class="bi bi-check-square"></i> Appointments
            </a>
        </li>
        <li class="sidebar-item">
            <a href="admin/patients" class="sidebar-link <?= $current_page === 'patients' ? 'active' : '' ?>">
                <i class="bi bi-person-lines-fill"></i> Patients
            </a>
        </li>
        <li class="sidebar-item">
            <a href="admin/doctors" class="sidebar-link <?= $current_page === 'doctors' ? 'active' : '' ?>">
                <i class="bi bi-file-medical"></i> Doctors
            </a>
        </li>
        <li class="sidebar-item">
            <a href="admin/departments" class="sidebar-link <?= $current_page === 'departments' ? 'active' : '' ?>">
                <i class="bi bi-building"></i> Departments
            </a>
        </li>
        <li class="sidebar-item">
            <a href="admin/schedule" class="sidebar-link <?= $current_page === 'schedule' ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i> Doctors' Schedule
            </a>
        </li>
        <li class="sidebar-item">
            <a href="reports" class="sidebar-link <?= $current_page === 'reports' ? 'active' : '' ?>">
                <i class="bi bi-graph-up-arrow"></i> Reports & Analytics
            </a>
        </li>
        <li class="sidebar-item">
            <a href="messages" class="sidebar-link <?= $current_page === 'messages' ? 'active' : '' ?> d-flex justify-content-between align-items-center">
                <span><i class="bi bi-chat-dots me-2"></i> Messages</span>
                <span class="badge bg-danger rounded-circle" style="font-size: 0.65rem; padding: 0.3rem 0.45rem;">7</span>
            </a>
        </li>
        <li class="sidebar-item mt-4">
            <a href="auth/logout" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>

    <div class="unlock-card" style="background: #111827;">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-white rounded p-1 me-2 text-dark" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-unlock-fill fs-5"></i>
            </div>
        </div>
        <h6 style="line-height: 1.4; margin-bottom: 0.5rem; font-weight: 700; color: white;">Unlock New Features & Maximize Your Hospital Management Efficiency</h6>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="#" class="text-white-50 text-decoration-none" style="font-size: 0.75rem;">What's New?</a>
            <button class="btn btn-light border-0 fw-bold px-3 py-1 text-dark" style="font-size: 0.75rem; border-radius: 12px;">Upgrade</button>
        </div>
    </div>
</aside>

<?php require_once BASE_PATH . '/views/layouts/session_timeout_modal.php'; ?>
