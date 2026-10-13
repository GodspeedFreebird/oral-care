<?php 
$page_title = 'Patient Info';
ob_start();
?>
    <style>
        /* OVERRIDE SHARED MAIN-CONTENT STYLE FOR THIS PAGE */
        .main-content {
            overflow: hidden !important;
            padding: 0 !important;
        }

        .info-layout {
            display: flex;
            height: 100%;
        }

        /* LEFT COLUMN */
        .left-col {
            width: 35%;
            padding: 30px;
            border-right: 1px solid #edf1f5;
            overflow-y: auto;
            height: 100%;
        }
        /* Custom subtle scrollbar for the left column to act as a separator */
        .left-col::-webkit-scrollbar {
            width: 6px;
        }
        .left-col::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 10px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: #000;
        }
        .profile-card {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }
        .profile-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #eef0f4;
        }
        .profile-card h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #202b36;
        }
        .icon-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #edf1f5;
            color: var(--primary-teal);
            margin-left: 10px;
            cursor: pointer;
        }

        .info-section {
            margin-bottom: 40px;
        }
        .info-section h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #202b36;
        }
        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .info-list li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        .info-label {
            color: var(--primary-teal);
            font-weight: 500;
            width: 40%;
        }
        .info-val {
            color: #4a5568;
            width: 60%;
            text-align: right;
            font-weight: 500;
        }

        /* RIGHT COLUMN */
        .right-col {
            width: 65%;
            padding: 30px;
            overflow-y: auto;
            height: 100%;
        }
        .header-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 20px;
            margin-bottom: 30px;
        }
        .search-form {
            display: flex;
            align-items: center;
            background-color: white;
            border: 1px solid #edf1f5;
            border-radius: 10px;
            padding: 5px 15px;
            width: 300px;
        }
        .search-form input {
            border: none;
            outline: none;
            width: 100%;
            background: transparent;
            font-size: 0.9rem;
        }
        .header-profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .section-header h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: #202b36;
        }
        
        .toggle-tabs {
            background-color: white;
            border: 1px solid #edf1f5;
            border-radius: 10px;
            display: flex;
            overflow: hidden;
        }
        .toggle-tab {
            padding: 8px 15px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            border-right: 1px solid #edf1f5;
        }
        .toggle-tab:last-child { border: none; }
        .toggle-tab.active {
            background-color: var(--primary-teal);
            color: white;
        }

        .diagnosis-cards {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }
        .diag-card {
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .diag-card .diag-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
        }
        .diag-text h6 { margin: 0; font-size: 0.85rem; font-weight: 600; }
        .diag-text p { margin: 0; font-size: 0.75rem; opacity: 0.8; }
        
        .c-red { background-color: #fce4ec; color: #d81b60; }
        .c-blue { background-color: #e3f2fd; color: #1976d2; }
        .c-orange { background-color: #fff3e0; color: #f57c00; }
        .c-teal { background-color: #e0f2f1; color: #00897b; }

        .chart-section {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
        }
        .bar-chart-container {
            flex: 2;
            height: 200px;
            display: flex;
            align-items: flex-end;
            gap: 15px;
            margin-left: 35px; /* Gives room for the Y-axis labels so they don't hit the scrollbar */
            padding-left: 30px;
            position: relative;
            border-bottom: 1px solid #edf1f5;
            border-left: 1px solid #edf1f5;
        }
        .y-axis {
            position: absolute;
            left: -5px;
            top: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--text-muted);
            transform: translateX(-100%);
        }
        .bar-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
        }
        .bar {
            width: 100%;
            max-width: 40px;
            border-radius: 3px 3px 0 0;
            transition: 0.3s;
        }
        .bar-label {
            margin-top: 10px;
            font-size: 0.7rem;
            color: var(--text-muted);
            position: absolute;
            bottom: -25px;
        }
        
        .kpi-cards {
            flex: 1;
            display: flex;
            gap: 15px;
        }
        .kpi-card {
            flex: 1;
            border: 1px solid var(--primary-teal);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .kpi-header {
            display: flex;
            justify-content: space-between;
            color: #d81b60; /* Heart color for aesthetic */
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .kpi-val {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        .kpi-unit {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .ecg-container {
            width: 100%;
            height: 120px;
            background-color: #fff9f9;
            border: 1px solid #ffebee;
            background-image: linear-gradient(rgba(224, 224, 224, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(224, 224, 224, 0.5) 1px, transparent 1px);
            background-size: 15px 15px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }
        .ecg-line {
            width: 100%;
            height: 100%;
        }
    </style>
<?php 
$extra_css = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/reception_new_top.php'; 
?>

    <div class="main-content" style="background-color: white; margin-right: 15px; padding: 30px;">
        
        <div class="info-layout">
            
            <!-- LEFT COLUMN -->
            <div class="left-col">
                <h1 class="page-title">Patients info</h1>
                
                <div class="profile-card">
                    <img src="<?= htmlspecialchars($patient['profile_picture'] ?: 'https://ui-avatars.com/api/?name='.urlencode($patient['full_name']).'&background=random') ?>" alt="Patient">
                    <div>
                        <h4><?= htmlspecialchars($patient['full_name']) ?></h4>
                    </div>
                    <div class="icon-btn" title="Message Patient"><i class="bi bi-envelope"></i></div>
                </div>

                <div class="info-section">
                    <h5>General information</h5>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Date of birth</span>
                            <span class="info-val"><?= date('j F Y', strtotime($patient['date_of_birth'])) ?></span>
                        </li>
                        <li>
                            <span class="info-label">Status</span>
                            <span class="info-val">Active</span>
                        </li>
                        <li>
                            <span class="info-label">Sex</span>
                            <span class="info-val"><?= htmlspecialchars($patient['gender'] ?: 'N/A') ?></span>
                        </li>
                        <li>
                            <span class="info-label">Nationality</span>
                            <span class="info-val">Local</span>
                        </li>
                        <li>
                            <span class="info-label">Preferred language</span>
                            <span class="info-val">English</span>
                        </li>
                    </ul>
                </div>

                <div class="info-section">
                    <h5>Contact information</h5>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Address</span>
                            <span class="info-val"><?= htmlspecialchars($patient['address'] ?: 'Not provided') ?></span>
                        </li>
                        <li>
                            <span class="info-label">Phone</span>
                            <span class="info-val"><?= htmlspecialchars($patient['phone'] ?: 'Not provided') ?></span>
                        </li>
                        <li>
                            <span class="info-label">Email</span>
                            <span class="info-val"><?= htmlspecialchars($patient['email'] ?: 'Not provided') ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Add a quick Book Appointment button at the bottom of the left column -->
                <div class="mt-4 text-center">
                    <form action="reception/appointments/book" method="POST">
                        <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">
                        <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: var(--primary-teal); border-radius: 10px; width: 100%;">
                            <i class="bi bi-calendar-plus me-2"></i> Book Appointment
                        </button>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="right-col">
                
                <div class="header-right">
                    <form action="reception/patients/search" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Search other patients...">
                        <button type="submit"><i class="bi bi-search"></i></button>
                    </form>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'User') ?>&background=1976d2&color=fff" alt="Profile" class="header-profile-pic">
                </div>

                <div class="section-header">
                    <h5>Treatment History</h5>
                    <div class="toggle-tabs">
                        <div class="toggle-tab">Personal info</div>
                        <div class="toggle-tab active">Treatments</div>
                        <div class="toggle-tab">Prescription</div>
                    </div>
                </div>

                <div class="diagnosis-cards">
                    <?php 
                        // Mock dental diagnosis history
                        $mock_diagnoses = [
                            ['title' => 'Cavity Filling', 'date' => '12 Jan 2023', 'class' => 'c-red', 'icon' => 'bi-bandaid'],
                            ['title' => 'Root Canal', 'date' => '12 May 2023', 'class' => 'c-blue', 'icon' => 'bi-shield-check'],
                            ['title' => 'Teeth Whitening', 'date' => '12 Feb 2024', 'class' => 'c-orange', 'icon' => 'bi-stars'],
                            ['title' => 'Routine Check', 'date' => '12 May 2024', 'class' => 'c-teal', 'icon' => 'bi-clipboard-check']
                        ];
                        
                        foreach ($mock_diagnoses as $diag):
                    ?>
                    <div class="diag-card <?= $diag['class'] ?>">
                        <div class="diag-icon"><i class="bi <?= $diag['icon'] ?>"></i></div>
                        <div class="diag-text">
                            <h6><?= $diag['title'] ?></h6>
                            <p><?= $diag['date'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="section-header">
                    <h5>Gum Health Index</h5>
                    <a href="#" style="font-size: 0.8rem; color: #828d99; text-decoration: none;">View more &raquo;</a>
                </div>

                <div class="chart-section">
                    <div class="bar-chart-container">
                        <div class="y-axis">
                            <span>100%</span>
                            <span>75%</span>
                            <span>50%</span>
                            <span>25%</span>
                            <span>0%</span>
                        </div>
                        
                        <!-- Mock Bars -->
                        <div class="bar-wrapper">
                            <div class="bar" style="height: 30%; background-color: #d1d5db;"></div>
                            <span class="bar-label">Jan</span>
                        </div>
                        <div class="bar-wrapper">
                            <div class="bar" style="height: 45%; background-color: #fbcfe8;"></div>
                            <span class="bar-label">Feb</span>
                        </div>
                        <div class="bar-wrapper">
                            <div class="bar" style="height: 90%; background-color: #c7d2fe;"></div>
                            <span class="bar-label">Mar</span>
                        </div>
                        <div class="bar-wrapper">
                            <div class="bar" style="height: 60%; background-color: #fbcfe8;"></div>
                            <span class="bar-label">Apr</span>
                        </div>
                        <div class="bar-wrapper">
                            <div class="bar" style="height: 40%; background-color: #c7d2fe;"></div>
                            <span class="bar-label">May</span>
                        </div>
                        <div class="bar-wrapper">
                            <div class="bar" style="height: 25%; background-color: #f3e8ff;"></div>
                            <span class="bar-label">Jun</span>
                        </div>
                    </div>

                    <div class="kpi-cards">
                        <div class="kpi-card">
                            <div class="kpi-header">
                                <span>PLA</span>
                                <i class="bi bi-heart"></i>
                            </div>
                            <p class="kpi-val">12</p>
                            <p class="kpi-unit">Index</p>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-header">
                                <span>BOP</span>
                                <i class="bi bi-heart"></i>
                            </div>
                            <p class="kpi-val">8</p>
                            <p class="kpi-unit">%</p>
                        </div>
                    </div>
                </div>

                <div class="section-header">
                    <h5>Pain Level Tracker</h5>
                    <a href="#" style="font-size: 0.8rem; color: #828d99; text-decoration: none;">View more &raquo;</a>
                </div>
                
                <!-- Mock ECG look-alike adapted for Dental Pain Tracker aesthetics -->
                <div class="ecg-container">
                    <svg class="ecg-line" viewBox="0 0 1000 100" preserveAspectRatio="none">
                        <!-- Simulated peak/valley line similar to ECG for visual continuity -->
                        <polyline points="0,50 50,50 60,30 70,80 80,40 90,50 150,50 160,30 170,80 180,40 190,50 250,50 260,30 270,80 280,40 290,50 350,50 360,30 370,80 380,40 390,50 450,50 460,30 470,80 480,40 490,50 550,50 560,30 570,80 580,40 590,50 650,50 660,30 670,80 680,40 690,50 750,50 760,30 770,80 780,40 790,50 850,50 860,30 870,80 880,40 890,50 950,50 960,30 970,80 980,40 990,50 1000,50" 
                                  fill="none" stroke="#2c3e50" stroke-width="1.5"/>
                    </svg>
                </div>

            </div>
        </div>

    </div>

<?php require_once VIEWS_PATH . '/layouts/reception_new_bottom.php'; ?>
