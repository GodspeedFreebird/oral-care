<style>
    .custom-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 15px 20px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .custom-tabs .nav-link.active {
        color: var(--bs-primary) !important;
        border-bottom-color: var(--bs-primary) !important;
        background: transparent;
    }
    .custom-tabs .nav-link:hover:not(.active) {
        border-bottom-color: #e9ecef;
        color: #495057;
    }
</style>

<div class="p-0 min-vh-100 bg-white" style="width: 100%; overflow-x: hidden;">
            <!-- Profile Header -->
            <div class="position-relative" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); height: 140px;">
                <a href="<?= isset($isAdminView) ? 'admin/patients' : 'doctor/patients' ?>" class="text-decoration-none text-white position-absolute top-0 start-0 m-3 fw-bold"><i class="bi bi-arrow-left me-1"></i> Back</a>
                
                <div class="d-flex align-items-center position-absolute" style="bottom: -40px; left: 40px; z-index: 10;">
                    <div class="rounded-circle overflow-hidden border border-4 border-white bg-light text-primary d-flex align-items-center justify-content-center fw-bold fs-1 shadow-sm" style="width: 120px; height: 120px;">
                        <?php if (!empty($patient['profile_picture'])): ?>
                            <img src="<?= htmlspecialchars($patient['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                            <?= substr($patient['full_name'], 0, 1) ?>
                        <?php endif; ?>
                    </div>
                    <div class="ms-4 mb-5 text-white">
                        <h4 class="fw-bold mb-0"><?= htmlspecialchars($patient['full_name']) ?></h4>
                        <div class="small opacity-75"><?= htmlspecialchars($patient['phone']) ?></div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="position-absolute top-0 end-0 m-4 d-flex gap-2">
                    <?php if(!isset($isAdminView)): ?>
                    <a href="doctor/patients/<?= $patient['patient_id'] ?>/consult" class="btn btn-light fw-bold px-4 rounded shadow-sm text-primary">
                        <i class="bi bi-pencil-square me-1"></i> Start Consult
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Tabs -->
            <div class="bg-white px-4 pt-5 pb-0 shadow-sm mb-4" style="border-bottom: 1px solid #e9ecef;">
                <ul class="nav custom-tabs ms-4" id="patientTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Patient Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Appointments History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="treatments-tab" data-bs-toggle="tab" data-bs-target="#treatments" type="button" role="tab">Treatments</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">Invoices</button>
                    </li>
                </ul>
            </div>

            <div class="px-4 tab-content" id="patientTabsContent">
                
                <!-- 1. Patient Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                        <div class="card-body p-5">
                            
                            <!-- Patient Data Section -->
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-dark rounded-pill me-3" style="width: 4px; height: 24px;"></div>
                                <h5 class="fw-bold mb-0 text-dark">Patient Data</h5>
                            </div>
                            
                            <div class="row g-5 mb-5">
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Patient name</div>
                                    <div class="text-dark fw-medium"><?= htmlspecialchars($patient['full_name']) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Contact number</div>
                                    <div class="text-dark fw-medium"><?= htmlspecialchars($patient['phone']) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Date of birth</div>
                                    <div class="text-dark fw-medium"><?= date('d M Y', strtotime($patient['date_of_birth'])) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Gender</div>
                                    <div class="text-dark fw-medium text-capitalize"><?= htmlspecialchars($patient['gender']) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Email ID</div>
                                    <div class="text-dark fw-medium"><?= htmlspecialchars($patient['email'] ?? 'emilyrichdsn14@gmial.com') ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Occupation</div>
                                    <div class="text-dark fw-medium"><?= htmlspecialchars($patient['occupation'] ?? 'Finance Manager') ?></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="text-muted small mb-1">Address</div>
                                    <div class="text-dark fw-medium"><?= htmlspecialchars($patient['address']) ?></div>
                                </div>
                            </div>
                            
                            <hr class="border-light opacity-100 mb-5">
                            
                            <!-- Medical Data Section -->
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-dark rounded-pill me-3" style="width: 4px; height: 24px;"></div>
                                <h5 class="fw-bold mb-0 text-dark me-3">Medical Data</h5>
                                <span class="text-muted small">Last update <?= date('d M Y') ?></span>
                            </div>
                            
                            <div class="row g-5">
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Blood Pressure</div>
                                    <div class="text-dark fw-medium">132/82 mmHg</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Heart Rate</div>
                                    <div class="text-dark fw-medium">108 mg/dl</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Weight</div>
                                    <div class="text-dark fw-medium">80 lbs</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Blood Group</div>
                                    <div class="text-dark fw-medium text-capitalize"><?= htmlspecialchars($patient['blood_group'] ?? 'Unknown') ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small mb-1">Allergies</div>
                                    <div class="text-dark fw-medium"><?= htmlspecialchars($clinicalRecord['allergies'] ?? 'None Reported') ?></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- 2. Appointments History Tab -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="row g-0">
                            <!-- Sidebar Dates -->
                            <div class="col-md-3 bg-light border-end" style="min-height: 500px;">
                                <div class="p-3">
                                    <div class="position-relative mb-3">
                                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                        <input type="text" id="historySearchInput" class="form-control rounded-pill bg-white border-0 shadow-sm ps-5" placeholder="Search">
                                    </div>
                                    
                                    <div class="d-flex flex-column gap-2">
                                        <?php if(empty($consultations)): ?>
                                            <div class="text-center p-3 text-muted">No history</div>
                                        <?php else: ?>
                                            <?php foreach($consultations as $index => $c): ?>
                                                <button type="button" class="btn consultation-btn <?= $index === 0 ? 'btn-primary shadow-sm text-white active' : 'btn-light text-muted bg-transparent' ?> border-0 text-start px-3 py-2 fw-medium rounded-3" style="font-size: 0.9rem;" onclick="showConsultation(<?= $index ?>)">
                                                    <i class="bi bi-calendar-event <?= $index === 0 ? 'text-white' : 'text-primary' ?> me-2 consultation-icon"></i> <?= date('d F Y', strtotime($c['created_at'])) ?>
                                                </button>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Main Content (Clinical Notes & Diagnose) -->
                            <div class="col-md-9 bg-white">
                                <?php if(!empty($consultations)): ?>
                                    <div class="p-4 p-md-5">
                                        <?php foreach($consultations as $index => $latest): ?>
                                        <div id="consult-content-<?= $index ?>" class="consult-content <?= $index === 0 ? '' : 'd-none' ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Clinical Note</h5>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#editConsultModal<?= $latest['note_id'] ?>">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit Note
                                                </button>
                                            </div>
                                            <div class="border rounded-3 p-4 mb-4 shadow-sm bg-white text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                                                <?php 
                                                    $noteText = trim($latest['presenting_complaint'] ?? '') . "\n\n" . trim($latest['clinical_findings'] ?? '');
                                                    echo !empty(trim($noteText)) ? nl2br(htmlspecialchars(trim($noteText))) : 'No clinical notes documented.';
                                                ?>
                                            </div>
                                            
                                            <h5 class="fw-bold text-dark mb-3 mt-5" style="font-size: 1.1rem;">Diagnose</h5>
                                            <div class="border rounded-4 p-4 shadow-sm bg-white">
                                                <?php if(!empty($latest['tooth_entries'])): ?>
                                                    <?php foreach($latest['tooth_entries'] as $idx => $t): ?>
                                                        <?php if($idx > 0) echo '<hr class="my-4 border-light opacity-50">'; ?>
                                                        <div class="tooth-diagnosis-block">
                                                            <h6 class="fw-bold text-dark mb-3" style="font-size: 1rem;">Tooth #<?= htmlspecialchars($t['tooth_code']) ?></h6>
                                                            
                                                            <div class="row g-4 mb-3">
                                                                <div class="col-md-4">
                                                                    <div class="text-muted small mb-1">Condition type</div>
                                                                    <div class="text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($t['condition_code'] ?? 'N/A') ?></div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-muted small mb-1">Percussion test</div>
                                                                    <div class="text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($t['percussion_test'] ?? 'Negative') ?></div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-muted small mb-1">Recommended treatment</div>
                                                                    <div class="text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($t['recommended_treatment'] ?? 'N/A') ?></div>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php if(!empty($t['notes'])): ?>
                                                            <div class="mt-2">
                                                                <div class="text-muted small mb-1">Clinical notes</div>
                                                                <div class="text-dark" style="font-size: 0.9rem;"><?= nl2br(htmlspecialchars($t['notes'])) ?></div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <!-- Fallback if no specific teeth were diagnosed -->
                                                    <div class="row g-4">
                                                        <div class="col-md-12">
                                                            <div class="text-muted small mb-1">Primary Diagnosis</div>
                                                            <div class="text-dark fw-medium"><?= htmlspecialchars($latest['diagnosis'] ?? 'N/A') ?></div>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <div class="text-muted small mb-1">Treatment Performed / Plan</div>
                                                            <div class="text-dark fw-medium">
                                                                <?= nl2br(htmlspecialchars($latest['treatment_performed'] ?? '')) ?>
                                                                <br>
                                                                <?= nl2br(htmlspecialchars($latest['treatment_plan'] ?? '')) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="p-5 text-center text-muted h-100 d-flex flex-column justify-content-center">
                                        <i class="bi bi-folder2-open fs-1 opacity-25"></i>
                                        <p class="mt-3">No medical records to display.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Treatments Tab -->
                <div class="tab-pane fade" id="treatments" role="tabpanel">
                    <div class="row g-4 mb-4">
                        
                        <!-- Left Panel: Dental Overview -->
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                                <div class="card-body p-4 p-xl-5 text-center d-flex flex-column">
                                    <h5 class="fw-bold text-dark text-start mb-4">Dental Overview</h5>
                                    
                                    <div class="position-relative mx-auto mt-auto mb-auto" style="max-width: 280px;">
                                        <!-- Odontogram Base Image (Static representation) -->
                                        <img src="assets/images/odontogram.png" alt="Odontogram" class="img-fluid opacity-75" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/FDI_World_Dental_Federation_notation.svg/1024px-FDI_World_Dental_Federation_notation.svg.png'">
                                        
                                        <!-- Static overlays to match mockup WOW factor -->
                                        <div class="position-absolute rounded-circle bg-secondary" style="width:12px; height:12px; top:15%; left:18%; border:2px solid white;"></div>
                                        <div class="position-absolute rounded-circle bg-danger" style="width:12px; height:12px; top:35%; left:12%; border:2px solid white;"></div>
                                        <div class="position-absolute rounded-circle bg-success" style="width:12px; height:12px; top:20%; right:15%; border:2px solid white;"></div>
                                        <div class="position-absolute rounded-circle bg-success" style="width:12px; height:12px; top:45%; right:10%; border:2px solid white;"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-center flex-wrap gap-3 mt-5 small">
                                        <span class="text-muted d-flex align-items-center"><span class="d-inline-block rounded-circle bg-secondary me-2" style="width:10px;height:10px;"></span>Diagnosed</span>
                                        <span class="text-muted d-flex align-items-center"><span class="d-inline-block rounded-circle bg-danger me-2" style="width:10px;height:10px;"></span>In Treatment</span>
                                        <span class="text-muted d-flex align-items-center"><span class="d-inline-block rounded-circle bg-success me-2" style="width:10px;height:10px;"></span>Treated</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Treatments & History -->
                        <div class="col-md-7">
                            
                            <!-- Current Treatment (Dynamic from Database) -->
                            <h5 class="fw-bold text-dark mb-3">Current Treatment</h5>
                            <?php
                            $procedures = [];
                            foreach ($milestones as $ms) {
                                $procName = $ms['procedure_name'] ?: 'General Procedure';
                                if (!isset($procedures[$procName])) {
                                    $procedures[$procName] = [];
                                }
                                $procedures[$procName][] = $ms;
                            }
                            
                            if (empty($procedures)): ?>
                                <div class="card border-0 shadow-sm rounded-4 mb-5 p-5 text-center bg-light">
                                    <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i> No active multi-step treatments.</p>
                                </div>
                            <?php else: 
                                // Get the most recent procedure (last key)
                                $procNames = array_keys($procedures);
                                $currentProcedureName = end($procNames);
                                $currentProcedureSteps = $procedures[$currentProcedureName];
                                
                                $totalSteps = count($currentProcedureSteps);
                                $completedSteps = 0;
                                foreach ($currentProcedureSteps as $step) {
                                    if ($step['status'] === 'Completed') $completedSteps++;
                                }
                                $progressPercent = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
                            ?>
                            <div class="card border border-primary shadow-sm rounded-4 mb-5 position-relative overflow-hidden" style="background-color: #f8fbff; border-color: rgba(13, 110, 253, 0.2) !important;">
                                <!-- Top Right pagination indicator -->
                                <div class="position-absolute top-0 end-0 p-3 text-muted small">Step <?= $completedSteps ?> of <?= $totalSteps ?></div>
                                
                                <div class="card-body p-4 p-xl-5">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <h5 class="fw-bold mb-0 text-dark me-3"><?= htmlspecialchars($currentProcedureName) ?></h5>
                                            <?php if ($completedSteps === $totalSteps): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">In Progress</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="text-muted small mb-2 mt-4"><?= $progressPercent ?>% Completed</div>
                                    <div class="progress mb-4" style="height: 6px;">
                                        <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: <?= $progressPercent ?>%"></div>
                                    </div>
                                    
                                    <div class="position-relative ms-2 mt-4">
                                        <!-- Vertical Line -->
                                        <div class="position-absolute top-0 bottom-0 border-start border-2 border-primary border-opacity-25" style="left: 9px;"></div>
                                        
                                        <?php foreach ($currentProcedureSteps as $idx => $step): 
                                            $isLast = ($idx === $totalSteps - 1);
                                        ?>
                                        <!-- Step -->
                                        <div class="position-relative ps-4 <?= !$isLast ? 'mb-4' : '' ?>">
                                            <?php if ($step['status'] === 'Completed'): ?>
                                                <div class="position-absolute bg-success rounded-circle shadow-sm" style="width:20px;height:20px;left:0;top:0; z-index: 2;">
                                                    <i class="bi bi-check text-white d-flex justify-content-center align-items-center h-100" style="font-size: 14px;"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="position-absolute bg-white border border-2 border-secondary rounded-circle" style="width:20px;height:20px;left:0;top:0; z-index: 2;">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex justify-content-between align-items-start ms-2">
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($step['step_name']) ?></div>
                                                    
                                                    <?php if ($step['status'] === 'Completed'): ?>
                                                        <div class="text-muted" style="font-size: 0.85rem;"><?= !empty($step['completed_date']) ? date('d M Y', strtotime($step['completed_date'])) : 'Done' ?></div>
                                                    <?php elseif ($step['status'] === 'In Progress'): ?>
                                                        <div class="text-primary fw-bold" style="font-size: 0.85rem;">In Progress</div>
                                                    <?php else: ?>
                                                        <div class="text-primary fw-bold" style="font-size: 0.85rem;"><?= !empty($step['scheduled_date']) ? date('d M Y', strtotime($step['scheduled_date'])) : 'Schedule' ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if ($step['status'] === 'Completed'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Completed</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Treatment History -->
                            <h5 class="fw-bold text-dark mb-3">Treatment History</h5>
                            
                            <?php if(empty($consultations)): ?>
                                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                                    <p class="text-muted mb-0">No treatments recorded yet.</p>
                                </div>
                            <?php else: ?>
                                <div class="accordion" id="treatmentsAccordion">
                                    <?php foreach ($consultations as $index => $c): 
                                        $c_status = 'Completed'; 
                                        $c_status_class = 'bg-success text-success';
                                        $total_m = 0;
                                        $comp_m = 0;
                                        foreach ($milestones as $m) {
                                            if ($m['note_id'] == $c['note_id']) {
                                                $total_m++;
                                                if ($m['status'] == 'Completed') $comp_m++;
                                            }
                                        }
                                        if ($total_m > 0) {
                                            if ($comp_m == 0) {
                                                $c_status = 'Scheduled';
                                                $c_status_class = 'bg-secondary text-secondary';
                                            } elseif ($comp_m < $total_m) {
                                                $c_status = 'In Progress (' . round(($comp_m / $total_m) * 100) . '%)';
                                                $c_status_class = 'bg-primary text-primary';
                                            }
                                        }
                                    ?>
                                    <div class="accordion-item border border-light mb-3 bg-white rounded-4 shadow-sm overflow-visible" style="border-radius: 1rem !important;">
                                        <h2 class="accordion-header" id="headingTrt<?= $index ?>">
                                            <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?> bg-white fw-bold text-dark border-0 shadow-none rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrt<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapseTrt<?= $index ?>">
                                                <div class="w-100 pe-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <span style="font-size: 1rem;"><?= htmlspecialchars($c['diagnosis']) ?></span>
                                                            <span class="badge <?= $c_status_class ?> bg-opacity-10 ms-3 rounded-pill"><?= $c_status ?></span>
                                                        </div>
                                                        <span class="text-muted fw-normal" style="font-size: 0.8rem;"><i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($c['created_at'])) ?></span>
                                                    </div>
                                                    <div class="text-muted small fw-normal"><i class="bi bi-tooth me-1"></i> <?= !empty($c['tooth_entries']) ? htmlspecialchars($c['tooth_entries'][0]['tooth_code']) : 'General' ?></div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseTrt<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="headingTrt<?= $index ?>" data-bs-parent="#treatmentsAccordion">
                                            <div class="accordion-body bg-white border-top">
                                                
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="fw-bold small text-dark mb-0 me-3">Procedure details</h6>
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#editConsultModal<?= $c['note_id'] ?>">
                                                            <i class="bi bi-pencil-square me-1"></i> Edit Note
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Before & After Dropdown Toggle -->
                                                    <?php if (!empty($c['photo_before']) || !empty($c['photo_after'])): ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold dropdown-toggle" type="button" id="photoDrop<?= $index ?>" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                            Before & After
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end p-3 shadow border-0 rounded-4" aria-labelledby="photoDrop<?= $index ?>" style="width: 320px;">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($c['diagnosis']) ?></h6>
                                                                <button type="button" class="btn-close" style="font-size: 10px;"></button>
                                                            </div>
                                                            <div class="text-muted small mb-3"><i class="bi bi-tooth me-1"></i> <?= !empty($c['tooth_entries']) ? htmlspecialchars($c['tooth_entries'][0]['tooth_code']) : 'General' ?></div>
                                                            
                                                            <div class="row g-2 mb-3">
                                                                <?php if (!empty($c['photo_before'])): ?>
                                                                <div class="col-6 position-relative">
                                                                    <div class="bg-light rounded text-center d-flex align-items-center justify-content-center overflow-hidden" style="height: 100px;">
                                                                        <img src="<?= htmlspecialchars($c['photo_before']) ?>" class="img-fluid object-fit-cover w-100 h-100" alt="Before">
                                                                    </div>
                                                                    <span class="position-absolute bottom-0 start-0 ms-3 mb-1 badge bg-white text-dark shadow-sm">Before</span>
                                                                </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if (!empty($c['photo_after'])): ?>
                                                                <div class="col-6 position-relative">
                                                                    <div class="bg-light rounded text-center d-flex align-items-center justify-content-center overflow-hidden" style="height: 100px;">
                                                                        <img src="<?= htmlspecialchars($c['photo_after']) ?>" class="img-fluid object-fit-cover w-100 h-100" alt="After">
                                                                    </div>
                                                                    <span class="position-absolute bottom-0 start-0 ms-3 mb-1 badge bg-white text-dark shadow-sm">After</span>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="text-dark small fw-medium">Result</div>
                                                            <div class="text-muted small">Procedure completed successfully, function restored.</div>
                                                        </div>
                                                    </div>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border px-2 py-1"><i class="bi bi-images me-1"></i> No photos attached</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <p class="text-muted mb-0 small" style="line-height: 1.6;"><?= nl2br(htmlspecialchars($c['treatment_performed'] ?? $c['treatment_plan'] ?? 'No additional details.')) ?></p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            
    </div>

<?php if(!empty($consultations)): ?>
<?php foreach ($consultations as $c): ?>
<!-- Edit Consult Modal -->
<div class="modal fade" id="editConsultModal<?= $c['note_id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Edit Consultation Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="doctor/patients/<?= $patientId ?>/edit-consult" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="note_id" value="<?= $c['note_id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Presenting Complaint</label>
                        <textarea class="form-control" name="presenting_complaint" rows="2" required><?= htmlspecialchars($c['presenting_complaint'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Clinical Findings</label>
                        <textarea class="form-control" name="clinical_findings" rows="2"><?= htmlspecialchars($c['clinical_findings'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Diagnosis</label>
                        <textarea class="form-control" name="diagnosis" rows="2" required><?= htmlspecialchars($c['diagnosis'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Procedure Details (Treatment Performed)</label>
                        <textarea class="form-control" name="treatment_performed" rows="3"><?= htmlspecialchars($c['treatment_performed'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Future Treatment Plan</label>
                        <textarea class="form-control" name="treatment_plan" rows="2"><?= htmlspecialchars($c['treatment_plan'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Follow Up Instructions</label>
                        <textarea class="form-control" name="rx_instruction" rows="2"><?= htmlspecialchars($c['follow_up_instructions'] ?? '') ?></textarea>
                    </div>

                    <!-- Milestone Updates -->
                    <?php 
                        $noteMilestones = array_filter($milestones, function($m) use ($c) {
                            return $m['note_id'] == $c['note_id'];
                        });
                    ?>
                    <?php if (!empty($noteMilestones)): ?>
                    <div class="mb-4 p-3 bg-light rounded-4 border border-light shadow-sm">
                        <h6 class="fw-bold text-dark mb-3 small">Update Treatment Milestones</h6>
                        <?php foreach($noteMilestones as $m): ?>
                            <input type="hidden" name="update_ms_id[]" value="<?= $m['milestone_id'] ?>">
                            <div class="row g-2 mb-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label text-muted small mb-1">Step</label>
                                    <input type="text" class="form-control form-control-sm border-0 shadow-sm" value="<?= htmlspecialchars($m['step_name']) ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small mb-1">Status</label>
                                    <select class="form-select form-select-sm border-0 shadow-sm" name="update_ms_status[]">
                                        <option value="Schedule" <?= $m['status'] == 'Schedule' ? 'selected' : '' ?>>Schedule</option>
                                        <option value="In Progress" <?= $m['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Completed" <?= $m['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small mb-1">Date</label>
                                    <input type="date" class="form-control form-control-sm border-0 shadow-sm" name="update_ms_date[]" value="<?= $m['status'] == 'Completed' ? ($m['completed_date'] ?? date('Y-m-d')) : ($m['scheduled_date'] ?? '') ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Replace 'Before' Photo</label>
                            <input type="file" class="form-control form-control-sm" name="photo_before" accept="image/*">
                            <?php if(!empty($c['photo_before'])): ?>
                                <div class="form-text small">Current: <a href="<?= htmlspecialchars($c['photo_before']) ?>" target="_blank">View Photo</a></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Replace 'After' Photo</label>
                            <input type="file" class="form-control form-control-sm" name="photo_after" accept="image/*">
                            <?php if(!empty($c['photo_after'])): ?>
                                <div class="form-text small">Current: <a href="<?= htmlspecialchars($c['photo_after']) ?>" target="_blank">View Photo</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

