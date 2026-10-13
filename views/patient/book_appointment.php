<?php 
if (isset($_SESSION['role']) && $_SESSION['role'] === 'receptionist') {
    require_once VIEWS_PATH . '/layouts/reception_new_top.php';
    echo '<div class="main-content" style="background-color: white; margin-right: 15px;">';
} else {
    require_once VIEWS_PATH . '/layouts/patient_sidebar.php'; 
}
?>

<style>
    /* Styling to match reference image */
    .department-btn { border-color: #dee2e6; color: #495057; }
    .department-btn.active { background-color: #0b1c3c; color: white; border-color: #0b1c3c; }
    .doc-card { cursor: pointer; transition: 0.2s; border: 1px solid #f8f9fa; }
    .doc-card:hover { border-color: #dee2e6; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .doc-card.active { background-color: #f1f3f5; border-color: #e9ecef; }
    .doc-avatar { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background: #e9ecef; display:flex; align-items:center; justify-content:center; }
    .slot-btn { font-size: 0.85rem; padding: 0.5rem; transition: 0.2s; border-color: #e9ecef; }
    .slot-btn:hover { background-color: #f8f9fa; }
    .slot-btn.active { background-color: #0b1c3c !important; color: white !important; border-color: #0b1c3c !important; }
    #doctorDetailsPanel { transition: opacity 0.3s ease; }
    .btn-dark-blue { background-color: #0b1c3c; color: white; }
    .btn-dark-blue:hover { background-color: #1a305e; color: white; }
    
    /* Scrollbar hiding for tabs */
    #departmentList::-webkit-scrollbar { display: none; }
    #departmentList { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="mb-4">
    <h3 class="fw-bold mb-3">Book Appointment</h3>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?php echo escape($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Departments Horizontal Scroll -->
    <div class="d-flex overflow-auto pb-2 mb-4 gap-2 border-bottom" id="departmentList">
        <?php foreach ($departments as $dept): ?>
            <button class="btn btn-sm btn-outline-secondary rounded-pill flex-shrink-0 department-btn px-4 py-2 fw-medium" data-id="<?php echo $dept['department_id']; ?>">
                <?php echo escape($dept['name']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="row g-4" style="min-height: 60vh;">
        <!-- Column 1: Doctors List -->
        <div class="col-md-4 d-flex flex-column">
            <h6 class="fw-bold text-muted mb-3">Choose Doctor</h6>
            <div id="doctorList" class="d-flex flex-column gap-3 pe-2 overflow-auto" style="max-height: 60vh;">
                <div class="text-muted small p-3 bg-white rounded-3 border">Select a department above to load available practitioners.</div>
            </div>
        </div>

        <!-- Column 2: Doctor Details -->
        <div class="col-md-4">
            <div id="doctorDetailsPanel" class="d-none bg-white p-4 rounded-4 shadow-sm h-100 border border-light position-relative">
                <div class="d-flex align-items-center mb-4">
                    <img id="detailAvatar" src="" class="doc-avatar me-3" style="width: 60px; height: 60px;">
                    <div>
                        <h5 class="fw-bold mb-0" id="detailName"></h5>
                        <div class="text-muted small">Specialist | <span id="detailSpec"></span></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="small fw-bold text-muted mb-2">Education</h6>
                    <div class="small">DMD, Obafemi Awolowo University</div>
                    <div class="small">Fellow, West African College of Surgeons</div>
                </div>

                <div class="mb-4">
                    <h6 class="small fw-bold text-muted mb-2">Available Schedule</h6>
                    <div class="small"><i class="bi bi-calendar-check text-success me-2"></i> Monday - Friday</div>
                    <div class="small"><i class="bi bi-clock text-primary me-2"></i> 09:00 AM - 04:00 PM</div>
                </div>
                
                <div class="mb-4">
                    <h6 class="small fw-bold text-muted mb-2">Specialty Procedures</h6>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <span class="badge bg-light text-dark border fw-normal">Consultation</span>
                        <span class="badge bg-light text-dark border fw-normal">Diagnosis</span>
                        <span class="badge bg-light text-dark border fw-normal">Treatment Planning</span>
                    </div>
                </div>
                
                <!-- Fake reviews to match UI -->
                <div>
                    <h6 class="small fw-bold text-muted mb-3">Reviews</h6>
                    <div class="d-flex align-items-start mb-3 border-bottom pb-2">
                        <i class="bi bi-person-circle fs-4 text-secondary me-2"></i>
                        <div>
                            <div class="small fw-bold">Anonymous Patient</div>
                            <div class="text-warning small"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">Very professional and gentle approach. Highly recommended.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 3: Scheduling -->
        <div class="col-md-4 d-flex flex-column gap-3">
            <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Calendar</h6>
                </div>
                <!-- HTML5 Date Picker for simplicity, restricted to weekdays and future dates -->
                <input type="date" id="appointmentDate" class="form-control bg-light border-0 py-2" disabled min="<?php echo date('Y-m-d'); ?>">
                <div class="form-text small mt-2">Select a date (Mon-Fri) to see available hours.</div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm border border-light flex-grow-1 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Visit Hours</h6>
                </div>
                <div id="slotsList" class="row g-2 mb-auto">
                    <div class="col-12 text-muted small">Select a doctor and date first.</div>
                </div>
                
                <form id="bookingForm" method="POST" action="patient/book-appointment" class="mt-4 d-none">
                    <input type="hidden" name="csrf_token" value="<?php echo escape(AuthService::generateCSRF()); ?>">
                    <?php if (isset($patient)): ?>
                    <input type="hidden" name="patient_id" value="<?php echo escape($patient['patient_id']); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="practitioner_id" id="formPractitionerId">
                    <input type="hidden" name="appointment_date" id="formDate">
                    <input type="hidden" name="appointment_time" id="formTime">
                    <button type="submit" class="btn btn-dark-blue w-100 rounded-pill py-2 fw-bold" id="finalBookBtn" disabled>Book an appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deptBtns = document.querySelectorAll('.department-btn');
    const doctorList = document.getElementById('doctorList');
    const doctorDetailsPanel = document.getElementById('doctorDetailsPanel');
    const appointmentDate = document.getElementById('appointmentDate');
    const slotsList = document.getElementById('slotsList');
    const bookingForm = document.getElementById('bookingForm');
    const finalBookBtn = document.getElementById('finalBookBtn');
    
    let selectedDoctorId = null;
    let selectedDate = null;
    let selectedTime = null;

    // 1. Department Click
    deptBtns.forEach(btn => {
        btn.addEventListener('click', async function() {
            deptBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const deptId = this.dataset.id;
            
            // Reset downstream UI
            selectedDoctorId = null;
            doctorDetailsPanel.classList.add('d-none');
            appointmentDate.disabled = true;
            appointmentDate.value = '';
            slotsList.innerHTML = '<div class="col-12 text-muted small">Select a doctor and date first.</div>';
            bookingForm.classList.add('d-none');
            
            doctorList.innerHTML = '<div class="text-center p-3"><div class="spinner-border text-primary spinner-border-sm"></div></div>';
            
            try {
                const response = await fetch(`api/practitioners?department_id=${deptId}`);
                const doctors = await response.json();
                
                doctorList.innerHTML = '';
                if (doctors.length === 0) {
                    doctorList.innerHTML = '<div class="text-muted small p-3 bg-white rounded-3 border">No doctors available in this department.</div>';
                    return;
                }
                
                doctors.forEach(doc => {
                    const avatarContent = doc.profile_picture !== 'dummy.jpg' 
                        ? `<img src="${doc.profile_picture}" class="w-100 h-100 rounded-circle" style="object-fit:cover;">`
                        : doc.full_name.charAt(0);
                        
                    const card = document.createElement('div');
                    card.className = 'doc-card bg-white p-3 rounded-4';
                    card.dataset.id = doc.practitioner_id;
                    card.innerHTML = `
                        <div class="d-flex align-items-center mb-3">
                            <div class="doc-avatar me-3 fs-5 fw-bold text-secondary text-uppercase">${avatarContent}</div>
                            <div>
                                <h6 class="fw-bold mb-0">${doc.full_name}</h6>
                                <div class="text-primary small">${doc.department_name}</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-dark-blue rounded-pill w-100 select-doc-btn">Select for booking</button>
                    `;
                    
                    card.addEventListener('click', () => selectDoctor(card, doc));
                    doctorList.appendChild(card);
                });
            } catch (e) {
                doctorList.innerHTML = '<div class="text-danger small">Error loading doctors.</div>';
            }
        });
    });
    
    // 2. Doctor Click
    function selectDoctor(cardElement, doc) {
        document.querySelectorAll('.doc-card').forEach(c => c.classList.remove('active'));
        cardElement.classList.add('active');
        
        selectedDoctorId = doc.practitioner_id;
        document.getElementById('formPractitionerId').value = doc.practitioner_id;
        
        // Populate Details
        document.getElementById('detailName').textContent = doc.full_name;
        document.getElementById('detailSpec').textContent = doc.department_name;
        
        const avatarEl = document.getElementById('detailAvatar');
        if (doc.profile_picture !== 'dummy.jpg') {
            avatarEl.src = doc.profile_picture;
            avatarEl.classList.remove('d-none');
        } else {
            // Using a generic avatar icon if no real picture exists
            avatarEl.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(doc.full_name) + '&background=f1f3f5&color=0b1c3c';
        }
        
        doctorDetailsPanel.classList.remove('d-none');
        
        // Enable Calendar
        appointmentDate.disabled = false;
        if(appointmentDate.value) {
            fetchSlots();
        }
    }
    
    // 3. Date Change
    appointmentDate.addEventListener('change', function() {
        const d = new Date(this.value);
        // Basic check for weekends
        if(d.getDay() === 0 || d.getDay() === 6) {
            alert('Please select a weekday (Monday - Friday).');
            this.value = '';
            slotsList.innerHTML = '<div class="col-12 text-muted small">Select a valid weekday.</div>';
            return;
        }
        
        selectedDate = this.value;
        document.getElementById('formDate').value = this.value;
        fetchSlots();
    });
    
    // 4. Fetch Slots
    async function fetchSlots() {
        if (!selectedDoctorId || !selectedDate) return;
        
        slotsList.innerHTML = '<div class="col-12 text-center p-3"><div class="spinner-border text-primary spinner-border-sm"></div></div>';
        bookingForm.classList.add('d-none');
        finalBookBtn.disabled = true;
        
        try {
            const response = await fetch(`api/slots?practitioner_id=${selectedDoctorId}&date=${selectedDate}`);
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            
            const textResponse = await response.text();
            let slots;
            try {
                slots = JSON.parse(textResponse);
            } catch (err) {
                console.error("Raw response:", textResponse);
                throw new Error("Invalid server response format");
            }
            
            slotsList.innerHTML = '';
            
            if (slots.length === 0) {
                slotsList.innerHTML = '<div class="col-12 text-danger small p-2 bg-light rounded text-center">No available slots on this date.</div>';
                return;
            }
            
            slots.forEach(time => {
                // Format time H:i:s to h:i A
                const timeParts = time.split(':');
                const d = new Date();
                d.setHours(timeParts[0], timeParts[1], 0);
                const formattedTime = d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4';
                
                const btn = document.createElement('button');
                btn.className = 'btn btn-outline-secondary w-100 rounded-3 slot-btn fw-medium bg-white';
                btn.textContent = formattedTime;
                
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    selectedTime = time;
                    document.getElementById('formTime').value = time;
                    
                    bookingForm.classList.remove('d-none');
                    finalBookBtn.disabled = false;
                });
                
                col.appendChild(btn);
                slotsList.appendChild(col);
            });
            
        } catch (e) {
            slotsList.innerHTML = `<div class="col-12 text-danger small p-2 bg-light rounded border border-danger">
                <strong>Error loading time slots:</strong> ${e.message}
            </div>`;
        }
    }
});
</script>

<?php 
if (isset($_SESSION['role']) && $_SESSION['role'] === 'receptionist') {
    echo '</div>'; // Close main-content
    require_once VIEWS_PATH . '/layouts/reception_new_bottom.php';
} else {
    require_once VIEWS_PATH . '/layouts/patient_footer.php'; 
}
?>
