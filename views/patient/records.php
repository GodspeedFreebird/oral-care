<?php require_once VIEWS_PATH . '/layouts/patient_sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Medical Records</h4>
</div>

<!-- Tabs -->
<ul class="nav custom-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link" href="patient/dashboard">Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="patient/appointments">Appointments</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="patient/records">Medical History</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="patient/notifications">Notification History</a>
    </li>
</ul>

<div class="row g-4">
    <div class="col-12">
        <style>
            .nav-tabs .nav-link { color: #6b7280; border: none; border-bottom: 2px solid transparent; padding: 1rem 1.5rem; font-size: 0.95rem; }
            .nav-tabs .nav-link:hover { border-color: transparent; color: #1976d2; }
            .nav-tabs .nav-link.active { color: #1976d2 !important; border: none; border-bottom: 2px solid #1976d2 !important; background: transparent; }
            .tooth-chart-container { display: flex; flex-direction: column; align-items: center; gap: 1rem; }
            .jaw-row { display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap; justify-content: center; }
            .tooth-box { width: 32px; height: 42px; border: 2px solid #cbd5e1; border-radius: 6px; display: flex; justify-content: center; align-items: center; font-weight: 700; font-size: 0.8rem; color: #475569; cursor: default; transition: all 0.2s; background-color: #ffffff; }
            .tooth-box:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .midline { height: 45px; width: 2px; background-color: #94a3b8; margin: 0 0.5rem; }
            .tooth-healthy { background-color: #ffffff; }
            .tooth-decayed { background-color: #fee2e2; border-color: #ef4444; color: #b91c1c; }
            .tooth-filled { background-color: #e0f2fe; border-color: #3b82f6; color: #1d4ed8; }
            .tooth-missing { background-color: #f1f5f9; border-color: #94a3b8; color: #94a3b8; text-decoration: line-through; }
            .legend-box { width: 16px; height: 16px; border-radius: 4px; border: 2px solid; }
        </style>
        
        <div class="info-card p-0 overflow-hidden shadow-sm">
            <div class="border-bottom bg-white pt-2 px-3">
                <ul class="nav nav-tabs border-bottom-0" id="recordTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="tooth-chart-tab" data-bs-toggle="tab" data-bs-target="#tooth-chart" type="button" role="tab"><i class="bi bi-heptagon-half me-1"></i> Tooth Chart</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="medical-history-tab" data-bs-toggle="tab" data-bs-target="#medical-history" type="button" role="tab"><i class="bi bi-file-medical me-1"></i> Medical History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="dental-history-tab" data-bs-toggle="tab" data-bs-target="#dental-history" type="button" role="tab"><i class="bi bi-journal-medical me-1"></i> Dental History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="medications-tab" data-bs-toggle="tab" data-bs-target="#medications" type="button" role="tab"><i class="bi bi-capsule me-1"></i> Medications</button>
                    </li>
                </ul>
            </div>
            
            <div class="tab-content p-4 bg-white" id="recordTabsContent">
                <div class="tab-pane fade show active" id="tooth-chart" role="tabpanel">
                    <div class="tooth-chart-container py-3">
                        <div class="text-center mb-1"><h6 class="fw-bold text-muted small text-uppercase">Maxillary (Upper Jaw)</h6></div>
                        
                        <div class="jaw-row">
                            <div class="tooth-box tooth-healthy" title="Tooth 18">18</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 17">17</div>
                            <div class="tooth-box tooth-filled" title="Tooth 16">16</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 15">15</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 14">14</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 13">13</div>
                            <div class="tooth-box tooth-decayed" title="Tooth 12">12</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 11">11</div>
                            <div class="midline"></div>
                            <div class="tooth-box tooth-healthy" title="Tooth 21">21</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 22">22</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 23">23</div>
                            <div class="tooth-box tooth-missing" title="Tooth 24">24</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 25">25</div>
                            <div class="tooth-box tooth-filled" title="Tooth 26">26</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 27">27</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 28">28</div>
                        </div>
                        
                        <div class="w-100 border-top my-3" style="max-width: 600px;"></div>
                        
                        <div class="jaw-row">
                            <div class="tooth-box tooth-missing" title="Tooth 48">48</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 47">47</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 46">46</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 45">45</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 44">44</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 43">43</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 42">42</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 41">41</div>
                            <div class="midline"></div>
                            <div class="tooth-box tooth-healthy" title="Tooth 31">31</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 32">32</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 33">33</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 34">34</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 35">35</div>
                            <div class="tooth-box tooth-decayed" title="Tooth 36">36</div>
                            <div class="tooth-box tooth-healthy" title="Tooth 37">37</div>
                            <div class="tooth-box tooth-missing" title="Tooth 38">38</div>
                        </div>
                        
                        <div class="text-center mt-1"><h6 class="fw-bold text-muted small text-uppercase">Mandibular (Lower Jaw)</h6></div>
                    </div>
                    
                    <div class="d-flex justify-content-center flex-wrap gap-4 mt-4 pt-4 border-top">
                        <div class="d-flex align-items-center gap-2"><div class="legend-box tooth-healthy" style="border-color: #cbd5e1;"></div><span class="small fw-medium text-muted">Healthy</span></div>
                        <div class="d-flex align-items-center gap-2"><div class="legend-box tooth-decayed"></div><span class="small fw-medium text-danger">Decayed</span></div>
                        <div class="d-flex align-items-center gap-2"><div class="legend-box tooth-filled"></div><span class="small fw-medium text-primary">Filled</span></div>
                        <div class="d-flex align-items-center gap-2"><div class="legend-box tooth-missing"></div><span class="small fw-medium text-muted">Missing</span></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="medical-history" role="tabpanel">
                    <?php if ($dental_record): ?>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-file-medical text-primary me-2"></i> General Medical History</h6>
                                <div class="p-3 bg-light rounded border shadow-sm" style="min-height: 100px;">
                                    <?= escape($dental_record['medical_history'] ?: 'No medical history recorded.') ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle text-danger me-2"></i> Drug Allergies</h6>
                                <div class="p-3 bg-light rounded border shadow-sm" style="min-height: 100px;">
                                    <?= escape($dental_record['drug_allergies'] ?: 'No known drug allergies.') ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No medical history on file.</p>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="dental-history" role="tabpanel">
                    <?php if ($dental_record): ?>
                        <h6 class="fw-bold mb-3"><i class="bi bi-journal-medical text-primary me-2"></i> Past Dental History</h6>
                        <div class="p-4 bg-light rounded border shadow-sm" style="min-height: 120px;">
                            <?= escape($dental_record['dental_history'] ?: 'No past dental history recorded.') ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No dental history on file.</p>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="medications" role="tabpanel">
                    <?php if ($dental_record): ?>
                        <h6 class="fw-bold mb-3"><i class="bi bi-capsule text-primary me-2"></i> Current Medications</h6>
                        <div class="p-4 bg-light rounded border shadow-sm" style="min-height: 120px;">
                            <?= escape($dental_record['current_medications'] ?: 'Patient is not currently taking any recorded medications.') ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No medications on file.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <h5 class="fw-bold mb-3 mt-2">Consultation Notes</h5>
        <?php if (empty($consultations)): ?>
            <div class="info-card text-center py-5">
                <i class="bi bi-clipboard-x fs-1 text-muted mb-3 d-block"></i>
                <h6 class="text-muted mb-0">No consultation notes available.</h6>
            </div>
        <?php else: ?>
            <div class="accordion" id="consultationAccordion">
                <?php foreach ($consultations as $index => $note): ?>
                    <div class="accordion-item border-0 mb-3 rounded overflow-hidden shadow-sm" style="background: white;">
                        <h2 class="accordion-header" id="heading<?= $note['note_id'] ?>">
                            <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?> bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $note['note_id'] ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $note['note_id'] ?>">
                                <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                    <div>
                                        <strong><?= date('M d, Y', strtotime($note['created_at'])) ?></strong> - Dr. <?= escape($note['practitioner_name']) ?>
                                    </div>
                                    <span class="badge bg-light text-dark border">
                                        <?= escape(substr($note['diagnosis'], 0, 30)) ?><?= strlen($note['diagnosis']) > 30 ? '...' : '' ?>
                                    </span>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse<?= $note['note_id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $note['note_id'] ?>" data-bs-parent="#consultationAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="info-label">Presenting Complaint</div>
                                        <p class="small mb-0"><?= nl2br(escape($note['presenting_complaint'])) ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-label">Clinical Findings</div>
                                        <p class="small mb-0"><?= nl2br(escape($note['clinical_findings'])) ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-label">Diagnosis</div>
                                        <p class="small mb-0"><?= nl2br(escape($note['diagnosis'])) ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-label">Treatment Performed</div>
                                        <p class="small mb-0"><?= nl2br(escape($note['treatment_performed'])) ?></p>
                                    </div>
                                    <?php if ($note['treatment_plan']): ?>
                                    <div class="col-12 mb-3">
                                        <div class="info-label">Treatment Plan</div>
                                        <p class="small mb-0"><?= nl2br(escape($note['treatment_plan'])) ?></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($note['follow_up_instructions']): ?>
                                    <div class="col-12">
                                        <div class="info-label text-primary"><i class="bi bi-info-circle me-1"></i> Follow-up Instructions</div>
                                        <p class="small mb-0 fw-bold"><?= nl2br(escape($note['follow_up_instructions'])) ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/patient_footer.php'; ?>
