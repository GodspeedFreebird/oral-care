<?php 
$page_title = 'Patients';
$current_page = 'reception_search';
ob_start();
?>
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            color: #000;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .search-form {
            display: flex;
            align-items: center;
            background-color: white;
            border: 1px solid #eef0f4;
            border-radius: 10px;
            padding: 5px 15px;
            width: 250px;
        }
        .search-form input {
            border: none;
            outline: none;
            width: 100%;
            background: transparent;
            font-size: 0.9rem;
            color: var(--text-dark);
        }
        .search-form button {
            background: transparent;
            border: none;
            color: var(--primary-teal);
            cursor: pointer;
        }
        .header-profile-pic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #eef0f4;
        }
        
        /* Table Styles */
        .patients-table-wrapper {
            background: transparent;
        }
        .patients-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .patients-table th {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0 20px 10px 20px;
            border: none;
            text-align: left;
        }
        .patients-table td {
            background-color: #fbfdff;
            padding: 15px 20px;
            vertical-align: middle;
            font-size: 0.9rem;
            color: var(--text-dark);
            border: none;
            transition: 0.2s;
        }
        .patients-table tr td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .patients-table tr td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        
        /* Hover & Active States */
        .patients-table tbody tr {
            cursor: pointer;
        }
        .patients-table tbody tr:hover td {
            background-color: #e0f2f1; /* Light teal hover */
            color: #000;
        }
        
        /* Simulating the selected row for demonstration (using CSS focus/active or just styling first row if we want) */
        /* Let's just make the hover state identical to the selected state in reference: */
        .patients-table tbody tr:active td {
            background-color: var(--primary-teal) !important;
            color: white !important;
        }
        .patients-table tbody tr:active td .text-muted {
            color: rgba(255,255,255,0.8) !important;
        }
        
        /* Patient Cell */
        .patient-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .patient-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #eee;
        }
        
    </style>
<?php 
$extra_css = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/reception_new_top.php'; 
?>

    <!-- 2. Main Content -->
    <div class="main-content" style="background-color: white; margin-right: 15px;">
        
        <div class="page-header">
            <h1 class="page-title">Patients</h1>
            <div class="header-right">
                <form action="reception/patients/search" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'User') ?>&background=1976d2&color=fff" alt="Profile" class="header-profile-pic">
            </div>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" style="border-radius: 10px;">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="border-radius: 10px;">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="patients-table-wrapper">
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>Id no.</th>
                        <th>Patients Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Diagnosis</th>
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px; background: transparent;">
                                <div class="text-muted">No patients found. <br><br> <a href="reception/patients/register" class="btn btn-outline-primary btn-sm">Add New Patient</a></div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($results as $patient): 
                            $avatar = $patient['profile_picture'] ?: "https://ui-avatars.com/api/?name=" . urlencode($patient['name']) . "&background=random";
                            $diagnosis = $patient['diagnosis'] ?: 'Check up'; // Default fallback matching the design
                            $doctor = $patient['doctor_dept'] ?: 'Unassigned';
                            $status = $patient['latest_status'] ? ucfirst($patient['latest_status']) : 'Hospital';
                            
                            // For formatting the ID number to look like "11001" or similar
                            $id_display = preg_replace('/[^0-9]/', '', $patient['id']);
                            if(empty($id_display)) $id_display = $patient['id']; // Fallback
                        ?>
                            <!-- Navigate to new Patient Info page -->
                            <tr onclick="window.location.href='reception/patient/info?id=<?= $patient['patient_id'] ?>';">
                                <td style="color: var(--text-muted);"><?= htmlspecialchars($id_display) ?></td>
                                <td>
                                    <div class="patient-cell">
                                        <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="patient-avatar">
                                        <span><?= htmlspecialchars($patient['name']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($patient['age'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($patient['gender'] ?: 'Unknown') ?></td>
                                <td><?= htmlspecialchars($diagnosis) ?></td>
                                <td><?= htmlspecialchars($doctor) ?></td>
                                <td><?= htmlspecialchars($status) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

<?php require_once VIEWS_PATH . '/layouts/reception_new_bottom.php'; ?>
