<!-- views/layouts/practitioner_sidebar.php -->
<style>
    .doc-sidebar {
        background-color: #FFFFFF !important;
        border-top-right-radius: 20px !important;
        border-bottom-right-radius: 20px !important;
        min-height: calc(100vh - 56px);
        box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        border-right: 1px solid #e5e7eb !important;
    }
    .doc-sidebar-link {
        color: #6b7280 !important;
        border-radius: 12px;
        padding: 10px 0;
        transition: all 0.3s ease;
    }
    .doc-sidebar-link.active, .doc-sidebar-link:hover {
        background-color: #ccfbf1 !important;
        color: #0f766e !important;
        font-weight: 600 !important;
    }
    .doc-sidebar-link i {
        font-size: 1.1rem;
    }
    .brand-icon-bg {
        background-color: #f3f4f6 !important;
        color: #1976d2 !important;
    }
</style>
<div class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow-sm px-0 doc-sidebar">
    <div class="text-center py-4 px-3">
        <img src="assets/images/logo_oauthc.png" alt="OAUTHC Dental" class="img-fluid" style="max-height: 45px; object-fit: contain;">
    </div>
    
    <ul class="nav flex-column mb-auto text-center w-100 px-2 mt-3">
                <li class="nav-item mb-2">
            <a class="nav-link doc-sidebar-link <?= $current_page === 'practitioner_dashboard' ? 'active' : '' ?>" href="doctor/dashboard">
                <i class="bi bi-grid d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Overview</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link doc-sidebar-link <?= $current_page === 'practitioner_patients' ? 'active' : '' ?>" href="doctor/patients">
                <i class="bi bi-people d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Patients</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link doc-sidebar-link <?= $current_page === 'practitioner_schedule' ? 'active' : '' ?>" href="doctor/schedule">
                <i class="bi bi-calendar4-event d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Schedule</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link doc-sidebar-link <?= $current_page === 'reports' ? 'active' : '' ?>" href="reports">
                <i class="bi bi-graph-up-arrow d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Reports</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link doc-sidebar-link <?= $current_page === 'practitioner_settings' ? 'active' : '' ?>" href="doctor/settings">
                <i class="bi bi-gear d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Settings</span>
            </a>
        </li>
    </ul>
</div>

