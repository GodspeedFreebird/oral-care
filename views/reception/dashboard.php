<?php 
$page_title = 'Receptionist Dashboard';
$current_page = 'reception_dashboard';
ob_start();
?>
    <style>
        .btn-add-patient {
            background-color: var(--primary-teal);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        /* Welcome Banner */
        .welcome-banner {
            background-color: var(--primary-teal);
            border-radius: 15px;
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .welcome-banner h2 {
            font-weight: 600;
            margin-bottom: 5px;
            margin-top: 0;
            line-height: 1.2;
            position: relative;
            z-index: 2;
        }
        .welcome-banner p {
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 2;
        }
        .welcome-img {
            position: absolute;
            right: 20px;
            bottom: -10px;
            height: 180px;
            z-index: 1;
        }

        /* Section Titles */
        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .section-title h5 {
            font-weight: 600;
            margin: 0;
            color: #202b36;
        }
        .view-more {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-decoration: none;
        }

        /* Our Doctors */
        .doctors-scroll {
            display: flex;
            overflow-x: auto;
            padding-bottom: 15px;
            gap: 15px;
        }
        .doctors-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .doctors-scroll::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 10px;
        }
        .doctor-card {
            background-color: var(--bg-light);
            border-radius: 15px;
            min-width: 130px;
            padding: 15px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: 0.3s;
            flex-shrink: 0;
        }
        .doctor-card:hover {
            background-color: #e0f2f1;
            transform: translateY(-3px);
        }
        .doc-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            background-color: white;
            border: 2px solid white;
        }
        .doc-name {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }
        .doc-spec {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Today Reports Stats */
        .stats-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-box {
            flex: 1;
            background-color: white;
            border: 1px solid #edf1f5;
            border-radius: 15px;
            padding: 20px 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            height: 45px;
            width: 45px;
            border-radius: 12px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }
        .icon-blue { background: #e3f2fd; color: #1976d2; }
        .icon-purple { background: #f3e5f5; color: #7b1fa2; }
        .icon-teal { background: #e0f2f1; color: #00897b; }
        .icon-orange { background: #fff3e0; color: #f57c00; }
        
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 5px;
            font-weight: 500;
        }
        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
        }

        /* My Appointments Table */
        .appointments-table th {
            color: var(--text-muted);
            font-weight: 500;
            border-bottom: 1px solid #edf1f5;
            padding-bottom: 10px;
        }
        .appointments-table td {
            vertical-align: middle;
            padding: 15px 10px;
            color: var(--text-dark);
            font-weight: 500;
        }
        .status-badge {
            background-color: #e0f2f1;
            color: #00897b;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* 3. Right Panel */
        .right-panel {
            width: 400px;
            background-color: var(--bg-light);
            padding: 30px 20px;
            overflow-y: auto;
            display: block;
            flex-shrink: 0;
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid white;
        }
        .profile-info h6 {
            margin: 0;
            font-weight: 600;
        }
        .profile-info p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .profile-actions {
            margin-left: auto;
            display: flex;
            gap: 10px;
        }
        .profile-actions i {
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-dark);
        }

        /* Calendar Strip */
        .calendar-strip {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .cal-day {
            text-align: center;
            padding: 10px 5px;
            border-radius: 12px;
            min-width: 38px;
        }
        .cal-day.active {
            background-color: var(--primary-teal);
            color: white;
        }
        .cal-day .day-name {
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--text-muted);
        }
        .cal-day.active .day-name { color: rgba(255,255,255,0.8); }
        .cal-day .day-num {
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* Upcoming Appointment Cards */
        .upcoming-cards {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            overflow-x: auto;
            padding-bottom: 10px;
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .upcoming-cards::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
        .upcoming-card {
            background-color: white;
            border: 1px solid #edf1f5;
            border-radius: 15px;
            padding: 15px;
            min-width: 120px;
            text-align: center;
            border-top: 3px solid #1976d2; /* Default blue border top */
            flex-shrink: 0;
        }
        .upcoming-card:nth-child(2) { border-top-color: #d81b60; } /* Pink */
        .upcoming-card:nth-child(3) { border-top-color: #00897b; } /* Teal */
        
        .up-icon {
            font-size: 1.5rem;
            color: #1976d2;
            margin-bottom: 10px;
        }
        .upcoming-card:nth-child(2) .up-icon { color: #d81b60; }
        .upcoming-card:nth-child(3) .up-icon { color: #00897b; }

        .up-name {
            font-size: 0.8rem;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .up-type {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 10px;
        }
        .up-time {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-dark);
            border-top: 1px solid #edf1f5;
            padding-top: 8px;
        }

        /* Requests List */
        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .toggle-btn {
            background: white;
            border: 1px solid #00897b;
            color: #00897b;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .request-card {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
        }
        .request-total {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
            text-align: right;
        }
        .req-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .req-item:last-child { margin-bottom: 0; }
        .req-avatar {
            font-size: 1.5rem;
            color: #828d99;
            margin-right: 15px;
        }
        .req-info {
            flex-grow: 1;
        }
        .req-name {
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0;
        }
        .req-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin: 0;
        }
        .req-actions {
            display: flex;
            gap: 8px;
        }
        .btn-check {
            color: #00897b;
            background: #e0f2f1;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8rem;
            cursor: pointer;
        }
        .btn-cross {
            color: #d81b60;
            background: #fce4ec;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8rem;
            cursor: pointer;
        }
    </style>
    </style>
<?php 
$extra_css = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/reception_new_top.php'; 
?>

    <!-- 2. Main Content -->
    <div class="main-content">
        
        <div class="top-header">
            <div class="search-box">
                <i class="bi bi-search text-muted"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div>
                <a href="reception/patients/register" class="btn-add-patient text-decoration-none d-inline-block me-2">
                    <i class="bi bi-plus"></i> Add patients
                </a>
                <a href="#" class="btn-add-patient text-decoration-none d-inline-block" style="background-color: #f3e5f5; color: #7b1fa2;" data-bs-toggle="modal" data-bs-target="#bookAppointmentModal">
                    <i class="bi bi-calendar-plus"></i> Book Appointment
                </a>
            </div>
        </div>

        <div class="welcome-banner">
            <h2>Good morning, <?= htmlspecialchars($_SESSION['username'] ?? 'Mia Dolan') ?></h2>
            <p>Have a good day at work</p>
            <img src="assets/images/receptionist_banner.jpg" class="welcome-img" alt="Welcome Illustration" style="mix-blend-mode: multiply;">
        </div>

        <div class="section-title">
            <h5>Our Doctors</h5>
            <a href="#" class="view-more">View more <i class="bi bi-chevron-double-right"></i></a>
        </div>
        <div class="doctors-scroll">
            <?php foreach ($practitioners as $doc): ?>
                <div class="doctor-card">
                    <img src="<?= htmlspecialchars($doc['profile_picture'] ?? 'assets/images/default_avatar.png') ?>" alt="Doctor" class="doc-avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($doc['full_name']) ?>&background=random'">
                    <p class="doc-name">Dr. <?= htmlspecialchars($doc['full_name']) ?></p>
                    <p class="doc-spec"><?= htmlspecialchars($doc['department_name'] ?? 'Specialist') ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="section-title mt-4">
            <h5>Today reports</h5>
            <a href="#" class="view-more">View more <i class="bi bi-chevron-double-right"></i></a>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon icon-teal"><i class="bi bi-person-fill"></i></div>
                <div class="stat-label">Total patients</div>
                <div class="stat-value"><?= number_format($stats['total_patients'] ?? 0) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-icon icon-purple"><i class="bi bi-telephone-fill"></i></div>
                <div class="stat-label">Phone calls</div>
                <div class="stat-value">12</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-check-fill"></i></div>
                <div class="stat-label">Appointments</div>
                <div class="stat-value"><?= number_format($stats['today_appointments'] ?? 0) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-icon icon-blue"><i class="bi bi-hospital-fill"></i></div>
                <div class="stat-label">Available bed</div>
                <div class="stat-value">15</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon icon-orange"><i class="bi bi-envelope-fill"></i></div>
                <div class="stat-label">Unread mails</div>
                <div class="stat-value">2</div>
            </div>
        </div>

        <div class="section-title">
            <h5>My appointment</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless appointments-table">
                <thead>
                    <tr>
                        <th>Id. no</th>
                        <th>Patients Name</th>
                        <th>Doctor</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 0;
                    foreach ($upcomingAppointments as $appt): 
                        if ($count >= 4) break;
                        $count++;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($appt['patient_number']) ?></td>
                            <td><?= htmlspecialchars($appt['patient_name']) ?></td>
                            <td><?= htmlspecialchars($appt['department_name']) ?></td>
                            <td><?= date('h:i A', strtotime($appt['appointment_time'])) ?></td>
                            <td><span class="status-badge"><?= ucfirst(htmlspecialchars($appt['status'] ?: 'Pending')) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($upcomingAppointments)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No upcoming appointments</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- 3. Right Panel -->
    <div class="right-panel">
        
        <div class="profile-header">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'User') ?>&background=1976d2&color=fff" alt="Profile" class="profile-pic">
            <div class="profile-info">
                <h6><?= htmlspecialchars($_SESSION['username'] ?? 'Mia Dolan') ?></h6>
                <p>Receptionist</p>
            </div>
            <div class="profile-actions align-items-center">
                <a href="messages" class="text-dark me-2" title="Messages"><i class="bi bi-chat-left-text"></i></a>
                
                <div class="dropdown">
                    <a href="#" class="text-dark position-relative" title="Notifications" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <?php if (!empty($pendingRequests)): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.55rem; padding: 0.25rem 0.45rem;">
                                <?= count($pendingRequests) ?>
                                <span class="visually-hidden">unread requests</span>
                            </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="width: 320px; z-index: 1050;">
                        <li class="px-2 py-1 mb-2 border-bottom">
                            <h6 class="fw-bold mb-0">Pending Appointments</h6>
                        </li>
                        <?php if (empty($pendingRequests)): ?>
                            <li class="px-2 py-3 text-center text-muted small">No new requests</li>
                        <?php else: ?>
                            <?php foreach(array_slice($pendingRequests, 0, 5) as $req): ?>
                                <li class="mb-2">
                                    <div class="dropdown-item rounded px-2 py-2 d-flex flex-column bg-light" style="white-space: normal;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold small"><?= htmlspecialchars($req['patient_name']) ?></span>
                                            <span class="text-muted" style="font-size: 0.7rem;"><?= date('M d, g:i A', strtotime($req['appointment_date'].' '.$req['appointment_time'])) ?></span>
                                        </div>
                                        <div class="d-flex justify-content-end gap-1 mt-2">
                                            <a href="reception/patient/info?id=<?= $req['patient_id'] ?>" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">View Profile</a>
                                            <form action="reception/appointments/update-status" method="POST" class="m-0">
                                                <input type="hidden" name="appointment_id" value="<?= $req['appointment_id'] ?>">
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-sm btn-success py-0 px-2" style="font-size: 0.75rem;">Accept</button>
                                            </form>
                                            <form action="reception/appointments/update-status" method="POST" class="m-0">
                                                <input type="hidden" name="appointment_id" value="<?= $req['appointment_id'] ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <h6 class="mb-3" style="font-size:0.85rem; font-weight:600;">Schedule calender</h6>
        <div class="calendar-strip">
            <?php 
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $todayIdx = date('N') - 1; // 0 (Mon) to 6 (Sun)
            $todayDate = date('d');
            
            for ($i = 0; $i < 7; $i++) {
                $diff = $i - $todayIdx;
                $dateNum = date('d', strtotime("$diff days"));
                $isActive = ($i == $todayIdx) ? 'active' : '';
                echo "<div class='cal-day $isActive'>
                        <div class='day-name'>{$days[$i]}</div>
                        <div class='day-num'>{$dateNum}</div>
                      </div>";
            }
            ?>
        </div>

        <h6 class="mb-3" style="font-size:0.85rem; font-weight:600;">Upcoming appointment</h6>
        <div class="upcoming-cards">
            <?php 
            $count = 0;
            foreach ($upcomingAppointments as $appt): 
                if ($count >= 3) break;
                $count++;
            ?>
                <div class="upcoming-card">
                    <i class="bi bi-person-circle up-icon"></i>
                    <p class="up-name"><?= htmlspecialchars($appt['patient_name']) ?></p>
                    <p class="up-type">Patient</p>
                    <p class="up-time">
                        <span class="text-muted fw-normal" style="font-size:0.6rem;">Next</span><br>
                        <?= date('h:i a', strtotime($appt['appointment_time'])) ?>
                    </p>
                </div>
            <?php endforeach; ?>
            <?php if(empty($upcomingAppointments)): ?>
                <div class="text-muted small">No upcoming appointments</div>
            <?php endif; ?>
        </div>

        <div class="request-header">
            <h6 class="mb-0" style="font-size:0.85rem; font-weight:600;">Appointment request</h6>
            <button class="toggle-btn">Online | Offline</button>
        </div>
        
        <div class="request-card">
            <div class="request-total"><span class="badge bg-teal" style="background:#1976d2;">Total : <?= count($pendingRequests) ?></span></div>
            
            <?php 
            $reqCount = 0;
            foreach ($pendingRequests as $req): 
                if ($reqCount >= 4) break;
                $reqCount++;
            ?>
                <div class="req-item">
                    <i class="bi bi-person-circle req-avatar"></i>
                    <div class="req-info">
                        <p class="req-name"><?= htmlspecialchars($req['patient_name']) ?></p>
                        <p class="req-time"><?= date('h:i a d, M Y', strtotime($req['appointment_date'] . ' ' . $req['appointment_time'])) ?></p>
                    </div>
                    <div class="req-actions">
                        <a href="reception/patient/info?id=<?= $req['patient_id'] ?>" class="btn-check text-primary bg-light text-decoration-none" style="border: 1px solid #dee2e6;" title="View Profile"><i class="bi bi-person"></i></a>
                        <form action="reception/appointments/update-status" method="POST" style="margin:0;">
                            <input type="hidden" name="appointment_id" value="<?= $req['appointment_id'] ?>">
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn-check"><i class="bi bi-check2"></i></button>
                        </form>
                        <form action="reception/appointments/update-status" method="POST" style="margin:0;">
                            <input type="hidden" name="appointment_id" value="<?= $req['appointment_id'] ?>">
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn-cross"><i class="bi bi-x"></i></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if(empty($pendingRequests)): ?>
                <p class="text-muted small text-center mb-0">No pending requests</p>
            <?php endif; ?>
        </div>

    </div>
    
    <!-- Book Appointment Modal -->
    <div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="reception/appointments/book" method="POST">
              <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold" id="bookAppointmentModalLabel"><i class="bi bi-calendar-plus text-primary me-2"></i> Book Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                    <label for="patientSelect" class="form-label text-muted fw-bold small">Select Patient</label>
                    <select class="form-select" id="patientSelect" name="patient_id" required>
                        <option value="" disabled selected>Choose a patient...</option>
                        <?php foreach($allPatients ?? [] as $pat): ?>
                            <option value="<?= $pat['patient_id'] ?>"><?= htmlspecialchars($pat['full_name']) ?> (<?= htmlspecialchars($pat['patient_number']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> You will be redirected to the booking page to select a department, doctor, and time slot.</p>
              </div>
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4" style="background-color: var(--primary-teal); border: none;">Continue to Booking</button>
              </div>
          </form>
        </div>
      </div>
    </div>

<?php require_once VIEWS_PATH . '/layouts/reception_new_bottom.php'; ?>
