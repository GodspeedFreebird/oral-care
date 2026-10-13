<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div> <!-- Close header's default container -->

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Stats row */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .stat-box {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 1.25rem;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .stat-box .title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        white-space: nowrap;
    }
    .stat-box .value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
        line-height: 1;
    }
    .trend-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.4rem;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
    }
    .trend-badge.up { background-color: #d1fae5; color: #059669; }
    .trend-badge.down { background-color: #fee2e2; color: #dc2626; }
    .trend-badge.neutral { background-color: #f3f4f6; color: #6b7280; }
    
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        <!-- Sidebar -->
        <?php 
            if (isset($isAdminView) && $isAdminView) {
                $current_page = 'doctors';
                require_once VIEWS_PATH . '/layouts/admin_sidebar.php';
            } else {
                require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php';
            }
        ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <!-- Top Header Area -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center bg-white p-3 shadow-sm rounded mb-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle overflow-hidden me-3 d-flex align-items-center justify-content-center bg-light border" style="width: 50px; height: 50px; color: var(--bs-primary); font-size: 1.5rem; font-weight: bold;">
                        <?php if (!empty($doctor['profile_picture'] ?? $_SESSION['practitioner_pic'])): ?>
                            <img src="<?= htmlspecialchars($doctor['profile_picture'] ?? $_SESSION['practitioner_pic']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                            <?= substr($doctor['full_name'] ?? $_SESSION['practitioner_name'], 0, 1) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Morning, Dr. <?= htmlspecialchars($doctor['full_name'] ?? $_SESSION['practitioner_name']) ?></h4>
                        <span class="text-muted small"><?= date('l, d F Y') ?></span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px;"><i class="bi bi-person"></i></button>
                    <button class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px;"><i class="bi bi-bell"></i></button>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column (Stats & Chart) -->
                <div class="col-lg-8">
                    
                    <!-- Stats Row -->
                    <div class="stats-grid mb-4">
                        <!-- Patients Today -->
                        <div class="stat-box">
                            <div class="title" title="Patients Today"><i class="bi bi-people text-muted flex-shrink-0"></i> <span class="text-truncate">Patients Today</span></div>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <h3 class="value"><?= str_pad($consultationsToday, 2, '0', STR_PAD_LEFT) ?></h3>
                                <div class="trend-badge up"><i class="bi bi-graph-up-arrow"></i> +12%</div>
                            </div>
                        </div>
                        
                        <!-- In Queue -->
                        <div class="stat-box">
                            <div class="title" title="In Queue"><i class="bi bi-hourglass-split text-muted flex-shrink-0"></i> <span class="text-truncate">In Queue</span></div>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <h3 class="value"><?= str_pad(count($upcomingAppointments), 2, '0', STR_PAD_LEFT) ?></h3>
                                <div class="trend-badge up"><i class="bi bi-graph-up-arrow"></i> +10%</div>
                            </div>
                        </div>
                        
                        <!-- Follow-ups -->
                        <div class="stat-box">
                            <div class="title" title="Follow-ups"><i class="bi bi-person-check text-muted flex-shrink-0"></i> <span class="text-truncate">Follow-ups</span></div>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <h3 class="value">04</h3>
                                <div class="trend-badge down"><i class="bi bi-graph-down-arrow"></i> -05%</div>
                            </div>
                        </div>

                        <!-- Ongoing Treats -->
                        <div class="stat-box">
                            <div class="title" title="Ongoing Treats"><i class="bi bi-heart-pulse text-muted flex-shrink-0"></i> <span class="text-truncate">Ongoing Treats</span></div>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <h3 class="value">15</h3>
                                <div class="trend-badge up"><i class="bi bi-graph-up-arrow"></i> +03%</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Row -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0 text-dark">Patient Visit</h6>
                                <select class="form-select form-select-sm w-auto border rounded-pill text-muted fw-bold" style="font-size: 0.75rem;">
                                    <option>Monthly</option>
                                    <option>Weekly</option>
                                </select>
                            </div>
                            <div style="height: 250px; width: 100%;">
                                <canvas id="appointmentsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Widgets -->
                    <div class="row g-4 mb-4">
                        <!-- Treatment Overview -->
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-4 text-dark">Treatment Overview</h6>
                                    <div class="d-flex align-items-center">
                                        <div style="height: 140px; width: 140px; position: relative;" class="flex-shrink-0">
                                            <canvas id="treatmentChart"></canvas>
                                            <div class="position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none;">
                                                <h4 class="fw-bold mb-0 text-dark">16</h4>
                                                <span class="text-muted" style="font-size: 0.7rem;">Total</span>
                                            </div>
                                        </div>
                                        <div class="ms-4 flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="rounded-circle me-2 flex-shrink-0 bg-primary" style="width: 8px; height: 8px;"></span>
                                                <span class="text-muted" style="font-size: 0.75rem;">Root Canal</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="rounded-circle me-2 flex-shrink-0 bg-warning" style="width: 8px; height: 8px;"></span>
                                                <span class="text-muted" style="font-size: 0.75rem;">Extraction</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="rounded-circle me-2 flex-shrink-0 bg-danger" style="width: 8px; height: 8px;"></span>
                                                <span class="text-muted" style="font-size: 0.75rem;">Crown</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="rounded-circle me-2 flex-shrink-0 bg-secondary" style="width: 8px; height: 8px;"></span>
                                                <span class="text-muted" style="font-size: 0.75rem;">Composite</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="rounded-circle me-2 flex-shrink-0 bg-info" style="width: 8px; height: 8px;"></span>
                                                <span class="text-muted" style="font-size: 0.75rem;">Fluoride</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Daily Brief -->
                        <div class="col-md-7">
                            <div class="d-flex flex-column h-100 gap-3">
                                <!-- Next Patient Mini Card -->
                                <?php if ($nextPatient): ?>
                                <div class="card border-0 shadow-sm bg-white" style="border-radius: 12px;">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle overflow-hidden me-3 bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">
                                                <?php if (!empty($nextPatient['profile_picture'])): ?>
                                                    <img src="<?= htmlspecialchars($nextPatient['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                                                <?php else: ?>
                                                    <?= substr($nextPatient['patient_name'], 0, 1) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark text-truncate mb-0" style="font-size: 0.85rem;">Next Patient</div>
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.95rem;"><?= htmlspecialchars($nextPatient['patient_name']) ?></div>
                                                <div class="text-muted" style="font-size: 0.75rem;">Follow-Up • Session 4 of 5</div>
                                            </div>
                                        </div>
                                        <div class="fw-bold text-dark fs-5">
                                            <?= date('H:i', strtotime($nextPatient['appointment_time'])) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Schedule) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 text-dark">Todays Appointment</h5>
                            
                            <!-- Interactive Mini Calendar strip -->
                            <div class="d-flex justify-content-between text-center mb-4 pb-2 border-bottom px-1" id="calendarStrip">
                                <?php for ($i = -3; $i <= 3; $i++): 
                                    $d = strtotime("$i day"); 
                                    $isToday = ($i == 0);
                                ?>
                                    <div class="calendar-day cursor-pointer <?= $isToday ? 'active bg-primary text-white shadow-sm' : 'text-muted' ?>" 
                                         style="border-radius: 12px; padding: 10px 5px; cursor: pointer; min-width: 38px;"
                                         data-date="<?= date('Y-m-d', $d) ?>"
                                         onclick="fetchSchedule(this, '<?= date('Y-m-d', $d) ?>')">
                                        <div class="fw-bold mb-1" style="font-size: 0.65rem;"><?= date('D', $d) ?></div>
                                        <div class="fw-bold <?= $isToday ? 'text-white' : 'text-dark' ?>" style="font-size: 0.9rem;"><?= date('d', $d) ?></div>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <div class="fw-bold text-muted mb-3" style="font-size: 0.8rem;" id="scheduleDateLabel">Today, <?= date('d M') ?></div>

                            <div class="schedule-list position-relative" id="scheduleList" style="max-height: 600px; overflow-y: auto;">
                                <!-- Vertical Timeline Line -->
                                <div class="position-absolute h-100 border-start" style="left: 60px; top: 0; z-index: 1;"></div>
                                
                                <?php if (empty($upcomingAppointments)): ?>
                                    <div class="text-center py-5">
                                        <i class="bi bi-calendar-check text-muted opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 fw-bold">No appointments on this date.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($upcomingAppointments as $appt): ?>
                                        <div class="d-flex align-items-center mb-4 position-relative" style="z-index: 2;">
                                            <div class="rounded-circle overflow-hidden flex-shrink-0 bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow border border-2 border-white" style="width: 45px; height: 45px; z-index: 3;">
                                                <?php if (!empty($appt['profile_picture'])): ?>
                                                    <img src="<?= htmlspecialchars($appt['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                                                <?php else: ?>
                                                    <?= substr($appt['patient_name'], 0, 1) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ms-3 flex-grow-1" style="min-width: 0;">
                                                <h6 class="mb-1 fw-bold text-dark text-truncate" style="font-size: 0.9rem;"><?= htmlspecialchars($appt['patient_name']) ?></h6>
                                                <div class="text-muted text-truncate d-flex align-items-center" style="font-size: 0.75rem;">
                                                    <span class="rounded-circle bg-warning me-2" style="width: 6px; height: 6px;"></span> 
                                                    Consultation
                                                </div>
                                            </div>
                                            <div class="text-end ms-2 text-muted fw-bold" style="font-size: 0.75rem;">
                                                <?= date('H:i', strtotime($appt['appointment_time'])) ?> - <?= date('H:i', strtotime($appt['appointment_time'] . ' +30 minutes')) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Area Chart for Patient Visit
    const ctxBar = document.getElementById('appointmentsChart').getContext('2d');
    let gradient = ctxBar.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(32, 201, 151, 0.4)'); // Teal with opacity
    gradient.addColorStop(1, 'rgba(32, 201, 151, 0.0)');

    const dataLabels = [];
    // Mocking the specific curve points from the user's image
    const dataValues = [0, 0, 0, 2.0, 0, 0, 1.0, 0, 0, 0, 0, 0, 1.0, 0];
    
    // Generate dates: 14 days ending around Sep 10, 2026 to match image
    let startDate = new Date('2026-08-28');
    for (let i = 0; i < 14; i++) {
        let d = new Date(startDate);
        d.setDate(d.getDate() + i);
        let dateString = d.toLocaleDateString('en-GB', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
        // Make sure format looks like "Fri, 28 Aug 2026"
        dataLabels.push(dateString.replace(/,/g, ''));
    }

    new Chart(ctxBar, {
        type: 'line',
        data: {
            labels: dataLabels,
            datasets: [{
                label: 'Patient Visit',
                data: dataValues,
                borderColor: '#20c997', 
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#20c997',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 10,
                    callbacks: {
                        label: function(context) { return context.parsed.y + ' Patients'; }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 2.0,
                    grid: { color: '#f0f2f5' },
                    ticks: { color: '#6c757d', padding: 10, font: {size: 11}, stepSize: 0.5 }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        color: '#6c757d', 
                        padding: 10, 
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Doughnut Chart (Treatment Overview)
    const ctxDoughnut = document.getElementById('treatmentChart').getContext('2d');
    
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Root Canal', 'Extraction', 'Crown', 'Composite', 'Fluoride'],
            datasets: [{
                data: [4, 3, 3, 4, 2],
                backgroundColor: ['#0056b3', '#ffc107', '#dc3545', '#6c757d', '#0dcaf0'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%', // Makes the doughnut thin
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

function fetchSchedule(element, dateStr) {
    // Update active UI state on calendar strip
    document.querySelectorAll('.calendar-day').forEach(el => {
        el.classList.remove('active', 'bg-primary', 'text-white', 'shadow-sm');
        el.classList.add('text-muted');
        el.querySelector('div:nth-child(2)').classList.remove('text-white');
        el.querySelector('div:nth-child(2)').classList.add('text-dark');
    });
    
    element.classList.add('active', 'bg-primary', 'text-white', 'shadow-sm');
    element.classList.remove('text-muted');
    element.querySelector('div:nth-child(2)').classList.add('text-white');
    element.querySelector('div:nth-child(2)').classList.remove('text-dark');
    
    // Update label
    const dateObj = new Date(dateStr);
    const options = { day: '2-digit', month: 'short' };
    document.getElementById('scheduleDateLabel').innerText = (dateStr === new Date().toISOString().split('T')[0] ? 'Today, ' : '') + dateObj.toLocaleDateString('en-GB', options);

    // Make AJAX request
    const practitionerId = <?= json_encode($doctor['practitioner_id'] ?? $this->practitionerId) ?>;

    const scheduleList = document.getElementById('scheduleList');
    scheduleList.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>`;

    fetch(`doctor/appointments/by_date?date=${dateStr}&practitioner_id=${practitionerId}`)
        .then(res => res.json())
        .then(appointments => {
            let html = '<div class="position-absolute h-100 border-start" style="left: 60px; top: 0; z-index: 1;"></div>';
            
            if (appointments.length === 0) {
                html += `
                    <div class="text-center py-5 position-relative" style="z-index: 2;">
                        <i class="bi bi-calendar-check text-muted opacity-25" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 fw-bold">No appointments on this date.</p>
                    </div>
                `;
                scheduleList.innerHTML = html;
                return;
            }

            appointments.forEach(appt => {
                const avatar = appt.profile_picture ? 
                    `<img src="${appt.profile_picture}" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">` : 
                    appt.patient_name.charAt(0);
                
                const timeDate = new Date('1970-01-01T' + appt.appointment_time);
                const startTime = timeDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                timeDate.setMinutes(timeDate.getMinutes() + 30);
                const endTime = timeDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

                html += `
                    <div class="d-flex align-items-center mb-4 position-relative" style="z-index: 2;">
                        <div class="rounded-circle overflow-hidden flex-shrink-0 bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow border border-2 border-white" style="width: 45px; height: 45px; z-index: 3;">
                            ${avatar}
                        </div>
                        <div class="ms-3 flex-grow-1" style="min-width: 0;">
                            <h6 class="mb-1 fw-bold text-dark text-truncate" style="font-size: 0.9rem;">${appt.patient_name}</h6>
                            <div class="text-muted text-truncate d-flex align-items-center" style="font-size: 0.75rem;">
                                <span class="rounded-circle bg-warning me-2" style="width: 6px; height: 6px;"></span> 
                                Consultation
                            </div>
                        </div>
                        <div class="text-end ms-2 text-muted fw-bold" style="font-size: 0.75rem;">
                            ${startTime} - ${endTime}
                        </div>
                    </div>
                `;
            });
            scheduleList.innerHTML = html;
        })
        .catch(err => {
            console.error('Error fetching schedule:', err);
            scheduleList.innerHTML = `<div class="text-danger p-3 text-center">Failed to load schedule.</div>`;
        });
}
</script>

<div class="container" style="display: none;"> <!-- Reopen for footer -->
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
