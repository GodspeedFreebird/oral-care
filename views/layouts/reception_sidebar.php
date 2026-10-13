<!-- views/layouts/reception_sidebar.php -->
<style>
    .rec-sidebar-link.active, .rec-sidebar-link:hover {
        background-color: #ccfbf1 !important;
        color: #0f766e !important;
        font-weight: 600 !important;
    }
    .rec-sidebar-link i {
        font-size: 1.1rem;
    }
</style>
<div class="col-md-2 col-lg-1 d-md-block sidebar collapse shadow-sm px-0 bg-white" style="min-height: calc(100vh - 56px); border-top-right-radius: 20px; border-bottom-right-radius: 20px; min-width: 90px; border-right: 1px solid #eaeaea;">
    <div class="text-center py-4 px-2">
        <img src="assets/images/logo_oauthc.png" alt="OAUTHC Dental" class="img-fluid" style="max-height: 45px; object-fit: contain;">
    </div>
    
    <ul class="nav flex-column mb-auto text-center w-100 px-2 mt-3">
        <li class="nav-item mb-2">
            <a class="nav-link rec-sidebar-link <?= $current_page === 'reception_dashboard' ? 'active' : 'text-secondary' ?>" href="reception/dashboard" style="border-radius: 12px; padding: 10px 0;">
                <i class="bi bi-grid d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Overview</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rec-sidebar-link <?= $current_page === 'reception_search' ? 'active' : 'text-secondary' ?>" href="reception/patients/search" style="border-radius: 12px; padding: 10px 0;">
                <i class="bi bi-search d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Find / Book</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rec-sidebar-link <?= $current_page === 'reception_register' ? 'active' : 'text-secondary' ?>" href="reception/patients/register" style="border-radius: 12px; padding: 10px 0;">
                <i class="bi bi-person-plus d-block mb-1"></i> 
                <span style="font-size: 0.75rem;">Register</span>
            </a>
        </li>
    </ul>
</div>
