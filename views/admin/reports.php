<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .sidebar, .navbar, .btn, .no-print { display: none !important; }
        main { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; page-break-inside: avoid; }
        .bg-light, .bg-white { background-color: transparent !important; }
        h1 { margin-bottom: 30px !important; }
    }
</style>

<div class="container-fluid px-0 bg-light">
    <div class="row g-0">
        <?php require_once VIEWS_PATH . '/layouts/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-dark">System Analytics & Reports</h1>
                <button onclick="window.print()" class="btn btn-outline-primary fw-bold rounded-pill px-4 shadow-sm no-print">
                    <i class="bi bi-printer-fill me-1"></i> Print Report
                </button>
            </div>

            <div class="row g-4 mb-4">
                <!-- Appointment Trends -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">Appointment Volume (Last 7 Days)</h6>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="appointmentChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Non-attendance Rate -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">Attendance Rate</h6>
                        </div>
                        <div class="card-body p-4 d-flex justify-content-center align-items-center">
                            <div style="width: 250px; height: 250px;">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Patient Registrations -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">Patient Registration Trend (6 Months)</h6>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="registrationChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Practitioner Workload -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">Practitioner Workload (Total Appointments)</h6>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="workloadChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Appointment Chart (Bar)
    const apptData = <?= json_encode($apptTrend) ?>;
    new Chart(document.getElementById('appointmentChart'), {
        type: 'bar',
        data: {
            labels: apptData.map(d => d.date),
            datasets: [{
                label: 'Appointments',
                data: apptData.map(d => d.count),
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 2. Attendance Chart (Doughnut)
    const attData = <?= json_encode($statusCounts) ?>;
    const attLabels = attData.map(d => d.status.toUpperCase());
    const attCounts = attData.map(d => d.count);
    // Custom colors for statuses (scheduled, completed, no_show, cancelled)
    const attColors = attLabels.map(l => {
        if(l === 'COMPLETED') return '#198754';
        if(l === 'NO_SHOW') return '#dc3545';
        if(l === 'CANCELLED') return '#6c757d';
        return '#0d6efd'; // SCHEDULED
    });
    
    new Chart(document.getElementById('attendanceChart'), {
        type: 'doughnut',
        data: {
            labels: attLabels,
            datasets: [{
                data: attCounts,
                backgroundColor: attColors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 3. Patient Registration Chart (Line)
    const regData = <?= json_encode($patientTrend) ?>;
    new Chart(document.getElementById('registrationChart'), {
        type: 'line',
        data: {
            labels: regData.map(d => d.month),
            datasets: [{
                label: 'New Patients',
                data: regData.map(d => d.count),
                borderColor: '#20c997',
                backgroundColor: 'rgba(32, 201, 151, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 4. Workload Chart (Bar - Horizontal)
    const workData = <?= json_encode($workload) ?>;
    new Chart(document.getElementById('workloadChart'), {
        type: 'bar',
        data: {
            labels: workData.map(d => d.full_name),
            datasets: [{
                label: 'Total Appointments',
                data: workData.map(d => d.count),
                backgroundColor: 'rgba(111, 66, 193, 0.7)',
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>

<div class="container" style="display: none;">
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
