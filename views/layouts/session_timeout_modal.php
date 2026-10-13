<?php
// Only display for logged-in users
if (!isset($_SESSION['user_id'])) return;
?>
<!-- Session Timeout Modal -->
<div class="modal fade" id="sessionTimeoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true" style="z-index: 9999;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="sessionTimeoutModalLabel"><i class="bi bi-exclamation-triangle"></i> Session Expiring Soon</h5>
      </div>
      <div class="modal-body text-dark">
        Your session will expire in <strong id="sessionCountdown">2:00</strong> minutes due to inactivity. Would you like to extend your session?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="window.location.href='logout'">Logout Now</button>
        <button type="button" class="btn btn-primary" onclick="extendSession()">Extend Session</button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
    // 30 minutes total, show modal at 28 minutes
    const TIMEOUT_DURATION = 30 * 60 * 1000; 
    const WARNING_DURATION = 28 * 60 * 1000;
    
    let warningTimer;
    let logoutTimer;
    let countdownInterval;
    
    function resetTimers() {
        clearTimeout(warningTimer);
        clearTimeout(logoutTimer);
        clearInterval(countdownInterval);
        
        warningTimer = setTimeout(showWarningModal, WARNING_DURATION);
        logoutTimer = setTimeout(() => window.location.href = 'logout', TIMEOUT_DURATION);
    }
    
    function showWarningModal() {
        if (typeof bootstrap !== 'undefined') {
            const modalEl = document.getElementById('sessionTimeoutModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                startCountdown(120);
                return;
            }
        }
        // Fallback
        if (confirm('Your session will expire in 2 minutes. Click OK to extend.')) {
            extendSession();
        } else {
            window.location.href = 'logout';
        }
    }
    
    function startCountdown(seconds) {
        const el = document.getElementById('sessionCountdown');
        countdownInterval = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                return;
            }
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            if (el) el.innerText = m + ':' + (s < 10 ? '0' : '') + s;
        }, 1000);
    }
    
    window.extendSession = function() {
        fetch('api/extend_session')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (typeof bootstrap !== 'undefined') {
                        const modalEl = document.getElementById('sessionTimeoutModal');
                        if (modalEl) {
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                    }
                    resetTimers();
                } else {
                    window.location.href = 'logout';
                }
            })
            .catch(() => window.location.href = 'logout');
    };
    
    // Start timers on load
    resetTimers();
})();
</script>
