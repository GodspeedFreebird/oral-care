<?php 
$page_title = 'Calendar Overview';
$extra_css = '
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: bold;
        color: #1976d2;
    }
    .fc-button-primary {
        background-color: #1976d2 !important;
        border-color: #1976d2 !important;
    }
    .fc-button-primary:hover {
        background-color: #00464d !important;
        border-color: #00464d !important;
    }
    .fc-button-primary:not(:disabled).fc-button-active, 
    .fc-button-primary:not(:disabled):active {
        background-color: #00363c !important;
        border-color: #00363c !important;
    }
    .calendar-container {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        height: 100%;
    }
</style>
';

// Prepare events data for JS
$events = [];
foreach ($appointments as $appt) {
    $start_dt = $appt['appointment_date'] . 'T' . $appt['appointment_time'];
    
    $color = '#3788d8'; // blue default
    if ($appt['status'] === 'confirmed') $color = '#10b981'; // green
    if ($appt['status'] === 'completed') $color = '#64748b'; // gray
    if ($appt['status'] === 'cancelled' || $appt['status'] === 'no_show') $color = '#ef4444'; // red
    if ($appt['status'] === 'pending') $color = '#f59e0b'; // yellow

    $events[] = [
        'id' => $appt['appointment_id'],
        'title' => $appt['patient_name'] . ' (' . ucfirst($appt['status']) . ')',
        'start' => $start_dt,
        'backgroundColor' => $color,
        'borderColor' => $color,
        'extendedProps' => [
            'patient' => $appt['patient_name'],
            'doctor' => $appt['doctor_name'],
            'type' => $appt['appointment_type'],
            'reason' => $appt['reason'] ?: 'None specified',
            'status' => $appt['status']
        ]
    ];
}

require_once BASE_PATH . '/views/layouts/reception_new_top.php'; 
?>

<div class="main-content">
    
    <div class="header-bar mb-4">
        <h1 class="header-title" style="font-size: 2rem; font-weight: 700; color: #1f2937;">Master Calendar</h1>
        <div class="user-profile" style="display: flex; align-items: center; gap: 10px;">
            <div class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; background-color: #cbd5e1; background-image: url('https://ui-avatars.com/api/?name=Admin&background=random'); background-size: cover;"></div>
        </div>
    </div>

    <div class="calendar-container">
        <div id="calendar"></div>
    </div>
    
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1976d2; color: white;">
                <h5 class="modal-title" id="eventModalTitle">Appointment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr><th style="width:35%; color: #64748b;">Patient:</th><td id="mPatient" class="fw-bold"></td></tr>
                        <tr><th style="color: #64748b;">Doctor:</th><td id="mDoctor"></td></tr>
                        <tr><th style="color: #64748b;">Time:</th><td id="mTime"></td></tr>
                        <tr><th style="color: #64748b;">Type:</th><td id="mType"></td></tr>
                        <tr><th style="color: #64748b;">Reason:</th><td id="mReason"></td></tr>
                    </tbody>
                </table>
                
                <hr>
                
                <!-- Status Update Form -->
                <form action="reception/appointments/update-status" method="POST">
                    <input type="hidden" name="appointment_id" id="mApptId">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Update Status</label>
                        <select class="form-select" name="status" id="mStatus">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="no_show">No Show</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #1976d2; border: none;">Save Changes</button>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventsData = <?= json_encode($events) ?>;
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: eventsData,
        eventClick: function(info) {
            // Get event data
            var props = info.event.extendedProps;
            var start = info.event.start;
            
            // Format time nicely
            var options = { weekday: 'short', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            var timeStr = start.toLocaleDateString('en-US', options);
            
            // Populate modal
            document.getElementById('mPatient').textContent = props.patient || 'Unknown';
            document.getElementById('mDoctor').textContent = props.doctor || 'Unassigned';
            document.getElementById('mTime').textContent = timeStr;
            document.getElementById('mType').textContent = props.type || 'N/A';
            document.getElementById('mReason').textContent = props.reason;
            
            document.getElementById('mApptId').value = info.event.id;
            document.getElementById('mStatus').value = props.status;
            
            // Show modal (Using Bootstrap 5 API via window.bootstrap if available, else fallback jQuery if loaded)
            if (typeof bootstrap !== 'undefined') {
                var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            }
        },
        height: 'auto'
    });
    
    calendar.render();
});
</script>

<?php require_once BASE_PATH . '/views/layouts/reception_new_bottom.php'; ?>
