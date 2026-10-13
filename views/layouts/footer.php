    </div> <!-- End Main Container -->

    <footer class="bg-light mt-5 pt-5 pb-4 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-primary">OAUTHC Dental Centre</h5>
                    <p class="text-muted small">Providing world-class oral health services and clinical excellence as part of the Obafemi Awolowo University Teaching Hospitals Complex.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold">Contact Us</h6>
                    <ul class="list-unstyled text-muted small">
                        <li><i class="bi bi-geo-alt-fill text-primary me-2"></i> Ile-Ife, Osun State, Nigeria</li>
                        <li><i class="bi bi-telephone-fill text-primary me-2"></i> +234 (0) 123 456 7890</li>
                        <li><i class="bi bi-envelope-fill text-primary me-2"></i> dental@oauthc.edu.ng</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold">Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li><a href="./#departments" class="text-decoration-none text-muted">Our Departments</a></li>
                        <li><a href="login" class="text-decoration-none text-muted">Patient Portal</a></li>
                        <li><a href="register" class="text-decoration-none text-muted">Book an Appointment</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-muted small mt-3 pt-3 border-top">
                &copy; <?php echo date('Y'); ?> OAUTHC Dental Centre. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Patient Record Shared Scripts -->
    <script>
    function showConsultation(index) {
        // Hide all
        document.querySelectorAll('.consult-content').forEach(el => el.classList.add('d-none'));
        
        // Show clicked
        const target = document.getElementById('consult-content-' + index);
        if(target) target.classList.remove('d-none');
        
        // Reset all buttons
        document.querySelectorAll('.consultation-btn').forEach(btn => {
            btn.classList.remove('btn-primary', 'shadow-sm', 'text-white', 'active');
            btn.classList.add('btn-light', 'text-muted', 'bg-transparent');
            
            const icon = btn.querySelector('.consultation-icon');
            if(icon) {
                icon.classList.remove('text-white');
                icon.classList.add('text-primary');
            }
        });
        
        // Set active button
        const activeBtn = document.querySelectorAll('.consultation-btn')[index];
        if(activeBtn) {
            activeBtn.classList.remove('btn-light', 'text-muted', 'bg-transparent');
            activeBtn.classList.add('btn-primary', 'shadow-sm', 'text-white', 'active');
            
            const activeIcon = activeBtn.querySelector('.consultation-icon');
            if(activeIcon) {
                activeIcon.classList.remove('text-primary');
                activeIcon.classList.add('text-white');
            }
        }
    }

    // Search functionality for history dates
    function initHistorySearch() {
        const searchInput = document.getElementById('historySearchInput');
        if (searchInput) {
            // Remove old listener if exists to prevent duplicates (by cloning)
            const newSearchInput = searchInput.cloneNode(true);
            searchInput.parentNode.replaceChild(newSearchInput, searchInput);
            
            newSearchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                document.querySelectorAll('.consultation-btn').forEach(btn => {
                    const text = btn.innerText.toLowerCase();
                    if (text.includes(term)) {
                        btn.classList.remove('d-none');
                    } else {
                        btn.classList.add('d-none');
                    }
                });
            });
        }
    }

    // Initialize on normal page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHistorySearch);
    } else {
        initHistorySearch();
    }
    </script>
    <?php require_once BASE_PATH . '/views/layouts/session_timeout_modal.php'; ?>
</body>
</html>
