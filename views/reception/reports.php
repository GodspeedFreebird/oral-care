<?php 
$page_title = 'Reports';
$extra_css = '
<style>
    .reports-container {
        display: flex;
        gap: 30px;
        height: 100%;
    }
    
    .reports-left {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .reports-right {
        width: 380px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .header-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #cbd5e1;
        background-image: url("https://ui-avatars.com/api/?name=Admin&background=random");
        background-size: cover;
    }

    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 15px;
        flex: 1;
        overflow-y: auto;
    }
    
    .reports-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    
    .reports-table th {
        color: #6b7280;
        font-weight: 600;
        text-align: left;
        padding: 0 20px 10px 20px;
        font-size: 0.9rem;
    }
    
    .reports-table td {
        background-color: #f8fafc;
        padding: 15px 20px;
        font-size: 0.95rem;
        color: #334155;
    }
    
    .reports-table tr td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: #0f766e;
    }
    
    .reports-table tr td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    
    .user-row-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .user-mini-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #64748b;
    }
    
    .download-btn {
        background: none;
        border: none;
        color: #1976d2;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 5px;
        border-radius: 5px;
        transition: 0.2s;
    }
    
    .download-btn:hover {
        background-color: #e0f2fe;
    }
    
    /* Right Side Styles */
    .toggle-group {
        display: flex;
        border: 1px solid #1976d2;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 5px;
        align-self: flex-end;
    }
    
    .toggle-btn {
        padding: 6px 15px;
        background: white;
        border: none;
        color: #64748b;
        font-size: 0.9rem;
        cursor: pointer;
        transition: 0.2s;
    }
    
    .toggle-btn.active {
        background-color: #1976d2;
        color: white;
    }
    
    .chart-card {
        background: #f8fafc;
        border-radius: 15px;
        padding: 20px;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #475569;
    }
    
    .chart-legend {
        display: flex;
        gap: 15px;
        font-size: 0.8rem;
        color: #64748b;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .stat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .stat-card {
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 110px;
    }
    
    .stat-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .stat-card-percent {
        font-size: 0.8rem;
        align-self: flex-end;
        font-weight: 600;
    }
    
    .stat-card-number {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
        margin: 5px 0;
    }
    
    .stat-card-subtitle {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    /* Colors */
    .card-hospital { background-color: #ffedd5; color: #ea580c; }
    .card-emergency { background-color: #fce7f3; color: #be123c; }
    .card-consult { background-color: #ccfbf1; color: #0f766e; }
    .card-admission { background-color: #dbeafe; color: #1d4ed8; }

    /* Hidden template for PDF generation */
    #pdf-template {
        display: none;
        padding: 40px;
        background: white;
        font-family: Arial, sans-serif;
    }
    
    #pdf-content-wrapper {
        color: #333;
    }
    
    .pdf-header {
        text-align: center;
        border-bottom: 2px solid #1976d2;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .pdf-header h1 {
        color: #1976d2;
        margin: 0 0 10px 0;
    }
</style>
';

require_once BASE_PATH . '/views/layouts/reception_new_top.php'; 
?>

<div class="main-content">
    
    <div class="header-bar">
        <h1 class="header-title">Reports</h1>
        
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="search-box" style="margin: 0; width: 250px; background: white; border: 1px solid #e2e8f0;">
                <i class="bi bi-search" style="color: #94a3b8;"></i>
                <input type="text" placeholder="Search..." id="reportSearch">
            </div>
            
            <button class="btn btn-sm btn-outline-primary fw-bold" onclick="exportGlobalPDF()" style="border-radius: 8px;">
                <i class="bi bi-download me-1"></i> Export All
            </button>
            <div class="user-profile">
                <div class="user-avatar"></div>
            </div>
        </div>
    </div>
    
    <div class="reports-container" id="global-pdf-target">
        
        <!-- Left Side: Table -->
        <div class="reports-left">
            <div class="table-container">
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>Id No.</th>
                            <th>Users Name</th>
                            <th>Role</th>
                            <th>Date Joined</th>
                            <th class="text-center" data-html2canvas-ignore="true">File</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <?php foreach($users as $user): 
                            $id_display = $user['id_no'] ?: 'USR-' . str_pad($user['user_id'], 4, '0', STR_PAD_LEFT);
                            $date_display = date('M d, Y', strtotime($user['created_at']));
                            $role_display = ucfirst($user['role']);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($id_display) ?></td>
                            <td>
                                <div class="user-row-info">
                                    <div class="user-mini-avatar">
                                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                    </div>
                                    <?= htmlspecialchars($user['full_name']) ?>
                                </div>
                            </td>
                            <td><?= $role_display ?></td>
                            <td><?= $date_display ?></td>
                            <td class="text-center" data-html2canvas-ignore="true">
                                <button class="download-btn" onclick="exportUserPDF('<?= htmlspecialchars($user['full_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($id_display, ENT_QUOTES) ?>', '<?= htmlspecialchars($role_display, ENT_QUOTES) ?>', '<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>', '<?= $date_display ?>')" title="Download Report">
                                    <i class="bi bi-download"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Right Side: Charts & Stats -->
        <div class="reports-right">
            
            <div style="display: flex; justify-content: flex-end;">
                <div class="toggle-group">
                    <button class="toggle-btn">Day</button>
                    <button class="toggle-btn active">Week</button>
                    <button class="toggle-btn">Month</button>
                </div>
            </div>
            
            <!-- Hits Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Hits</div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #0f766e;"></div> Men 60%
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #f472b6;"></div> Women 50%
                        </div>
                    </div>
                </div>
                <div style="position: relative; height: 180px; width: 100%;">
                    <canvas id="hitsChart"></canvas>
                </div>
            </div>
            
            <!-- 4 Stat Cards -->
            <div class="stat-grid">
                
                <div class="stat-card card-hospital">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div class="stat-card-title">Total Users</div>
                        <div class="stat-card-percent">100%</div>
                    </div>
                    <div>
                        <div class="stat-card-number"><?= number_format($stats['total_users']) ?></div>
                        <div class="stat-card-subtitle">Users</div>
                    </div>
                </div>
                
                <div class="stat-card card-emergency">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div class="stat-card-title">Patients</div>
                        <div class="stat-card-percent">
                            <?= $stats['total_users'] > 0 ? round(($stats['total_patients'] / $stats['total_users']) * 100) : 0 ?>%
                        </div>
                    </div>
                    <div>
                        <div class="stat-card-number"><?= number_format($stats['total_patients']) ?></div>
                        <div class="stat-card-subtitle">Registered</div>
                    </div>
                </div>
                
                <div class="stat-card card-consult">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div class="stat-card-title">Doctors</div>
                        <div class="stat-card-percent">
                            <?= $stats['total_users'] > 0 ? round(($stats['total_doctors'] / $stats['total_users']) * 100) : 0 ?>%
                        </div>
                    </div>
                    <div>
                        <div class="stat-card-number"><?= number_format($stats['total_doctors']) ?></div>
                        <div class="stat-card-subtitle">Practitioners</div>
                    </div>
                </div>
                
                <div class="stat-card card-admission">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div class="stat-card-title">Staff</div>
                        <div class="stat-card-percent">
                            <?= $stats['total_users'] > 0 ? round(($stats['total_staff'] / $stats['total_users']) * 100) : 0 ?>%
                        </div>
                    </div>
                    <div>
                        <div class="stat-card-number"><?= number_format($stats['total_staff']) ?></div>
                        <div class="stat-card-subtitle">Admins & Reception</div>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>
</div>

<!-- Hidden PDF Template -->
<div id="pdf-template">
    <div id="pdf-content-wrapper">
        <div class="pdf-header">
            <h1>OAuthC Dental - User Report</h1>
            <p style="color: #666;">Generated on <?= date('F j, Y, g:i a') ?></p>
        </div>
        <div style="margin-top: 30px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 15px; border: 1px solid #ddd; width: 30%; font-weight: bold; background: #f9f9f9;">System ID:</td>
                    <td style="padding: 15px; border: 1px solid #ddd;" id="pdf-id"></td>
                </tr>
                <tr>
                    <td style="padding: 15px; border: 1px solid #ddd; font-weight: bold; background: #f9f9f9;">Full Name:</td>
                    <td style="padding: 15px; border: 1px solid #ddd;" id="pdf-name"></td>
                </tr>
                <tr>
                    <td style="padding: 15px; border: 1px solid #ddd; font-weight: bold; background: #f9f9f9;">Role:</td>
                    <td style="padding: 15px; border: 1px solid #ddd;" id="pdf-role"></td>
                </tr>
                <tr>
                    <td style="padding: 15px; border: 1px solid #ddd; font-weight: bold; background: #f9f9f9;">Email Address:</td>
                    <td style="padding: 15px; border: 1px solid #ddd;" id="pdf-email"></td>
                </tr>
                <tr>
                    <td style="padding: 15px; border: 1px solid #ddd; font-weight: bold; background: #f9f9f9;">Date Joined:</td>
                    <td style="padding: 15px; border: 1px solid #ddd;" id="pdf-joined"></td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 50px; text-align: center; color: #999; font-size: 0.9em;">
            <p>This is an officially generated system report.</p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Search functionality
    document.getElementById('reportSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#userTableBody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });

    // Chart.js Implementation
    const ctx = document.getElementById('hitsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [
                {
                    label: 'Men',
                    data: [23, 15, 35, 26, 42, 38, 60],
                    borderColor: '#0f766e',
                    backgroundColor: '#0f766e',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3
                },
                {
                    label: 'Women',
                    data: [10, 20, 30, 25, 45, 40, 50],
                    borderColor: '#f472b6',
                    backgroundColor: '#f472b6',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 70,
                    grid: { borderDash: [5, 5] },
                    ticks: { stepSize: 10, color: '#94a3b8', font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 10 } }
                }
            }
        }
    });

    // PDF Generation Functions
    function exportUserPDF(name, id, role, email, joined) {
        // Populate template
        document.getElementById('pdf-name').innerText = name;
        document.getElementById('pdf-id').innerText = id;
        document.getElementById('pdf-role').innerText = role;
        document.getElementById('pdf-email').innerText = email;
        document.getElementById('pdf-joined').innerText = joined;
        
        // Show template temporarily
        const template = document.getElementById('pdf-template');
        template.style.display = 'block';
        
        const opt = {
            margin:       1,
            filename:     `${name.replace(/\s+/g, '_')}_Report.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        
        // Generate PDF
        html2pdf().set(opt).from(document.getElementById('pdf-content-wrapper')).save().then(() => {
            template.style.display = 'none'; // Hide template again
        });
    }

    function exportGlobalPDF() {
        const element = document.getElementById('global-pdf-target');
        
        const opt = {
            margin:       0.5,
            filename:     'Global_System_Report.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        
        // The data-html2canvas-ignore="true" attribute on the File columns will hide the buttons from the PDF automatically!
        html2pdf().set(opt).from(element).save();
    }
</script>

<?php require_once BASE_PATH . '/views/layouts/reception_new_bottom.php'; ?>
