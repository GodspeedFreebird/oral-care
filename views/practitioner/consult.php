
<script>
function toggleOdontogramMode() {
    const isChild = document.getElementById("odontogram_child").checked;
    const hitboxes = document.querySelectorAll(".tooth-hitbox");
    const manualSelect = document.getElementById("manualToothSelect");
    
    // Update Manual Select Options
    if (isChild) {
        manualSelect.innerHTML = `
            <option value="">+ Select Tooth Manually</option>
            <optgroup label="Upper Right">
                <option value="55">Tooth 55</option><option value="54">Tooth 54</option><option value="53">Tooth 53</option><option value="52">Tooth 52</option><option value="51">Tooth 51</option>
            </optgroup>
            <optgroup label="Upper Left">
                <option value="61">Tooth 61</option><option value="62">Tooth 62</option><option value="63">Tooth 63</option><option value="64">Tooth 64</option><option value="65">Tooth 65</option>
            </optgroup>
            <optgroup label="Lower Left">
                <option value="71">Tooth 71</option><option value="72">Tooth 72</option><option value="73">Tooth 73</option><option value="74">Tooth 74</option><option value="75">Tooth 75</option>
            </optgroup>
            <optgroup label="Lower Right">
                <option value="81">Tooth 81</option><option value="82">Tooth 82</option><option value="83">Tooth 83</option><option value="84">Tooth 84</option><option value="85">Tooth 85</option>
            </optgroup>
        `;
    } else {
        manualSelect.innerHTML = `
            <option value="">+ Select Tooth Manually</option>
            <optgroup label="Upper Right">
                <option value="18">Tooth 18</option><option value="17">Tooth 17</option><option value="16">Tooth 16</option><option value="15">Tooth 15</option>
                <option value="14">Tooth 14</option><option value="13">Tooth 13</option><option value="12">Tooth 12</option><option value="11">Tooth 11</option>
            </optgroup>
            <optgroup label="Upper Left">
                <option value="21">Tooth 21</option><option value="22">Tooth 22</option><option value="23">Tooth 23</option><option value="24">Tooth 24</option>
                <option value="25">Tooth 25</option><option value="26">Tooth 26</option><option value="27">Tooth 27</option><option value="28">Tooth 28</option>
            </optgroup>
            <optgroup label="Lower Left">
                <option value="31">Tooth 31</option><option value="32">Tooth 32</option><option value="33">Tooth 33</option><option value="34">Tooth 34</option>
                <option value="35">Tooth 35</option><option value="36">Tooth 36</option><option value="37">Tooth 37</option><option value="38">Tooth 38</option>
            </optgroup>
            <optgroup label="Lower Right">
                <option value="41">Tooth 41</option><option value="42">Tooth 42</option><option value="43">Tooth 43</option><option value="44">Tooth 44</option>
                <option value="45">Tooth 45</option><option value="46">Tooth 46</option><option value="47">Tooth 47</option><option value="48">Tooth 48</option>
            </optgroup>
        `;
    }

    
    const mapping = {
        "11": "51", "12": "52", "13": "53", "14": "54", "15": "55",
        "21": "61", "22": "62", "23": "63", "24": "64", "25": "65",
        "31": "71", "32": "72", "33": "73", "34": "74", "35": "75",
        "41": "81", "42": "82", "43": "83", "44": "84", "45": "85"
    };
    
    const hiddenInChild = ["16", "17", "18", "26", "27", "28", "36", "37", "38", "46", "47", "48"];
    
    hitboxes.forEach(box => {
        let currentTooth = box.getAttribute("data-tooth");
        let originalTooth = box.getAttribute("data-original-tooth") || currentTooth;
        
        if (!box.hasAttribute("data-original-tooth")) {
            box.setAttribute("data-original-tooth", originalTooth);
        }
        
        if (isChild) {
            if (hiddenInChild.includes(originalTooth)) {
                box.style.display = "none";
            } else {
                box.style.display = "block";
                let newTooth = mapping[originalTooth];
                box.setAttribute("data-tooth", newTooth);
                box.setAttribute("title", "Tooth " + newTooth);
                box.setAttribute("onclick", "openToothDiagnosisModal(" + newTooth + ")");
            }
        } else {
            box.style.display = "block";
            box.setAttribute("data-tooth", originalTooth);
            box.setAttribute("title", "Tooth " + originalTooth);
            box.setAttribute("onclick", "openToothDiagnosisModal(" + originalTooth + ")");
        }
    });
}
</script>

<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>
</div>

<style>

    
    .tooth-chart-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin: 20px 0;
    }
    .tooth-row {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .tooth {
        width: 32px;
        height: 48px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        background-color: white;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
    }
    .tooth:hover {
        border-color: var(--bs-primary);
        transform: translateY(-2px);
    }
    .tooth .num {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        font-weight: bold;
        color: #6c757d;
    }
    .tooth.lower .num {
        top: auto;
        bottom: -20px;
    }
    
    /* Tooth conditions */
    .tooth.cond-decay { background-color: #ffcccc; border-color: #dc3545; }
    .tooth.cond-filled { background-color: #d1e7dd; border-color: #198754; }
    .tooth.cond-missing { background-color: #e9ecef; border-color: #6c757d; opacity: 0.6; }
    
</style>

<div class="container-fluid px-0" style="background-color: #f4f6fb;">
    <div class="row g-0">
        
<script>
function toggleOdontogramMode() {
    const isChild = document.getElementById("odontogram_child").checked;
    const hitboxes = document.querySelectorAll(".tooth-hitbox");
    const manualSelect = document.getElementById("manualToothSelect");
    
    // Update Manual Select Options
    if (isChild) {
        manualSelect.innerHTML = `
            <option value="">+ Select Tooth Manually</option>
            <optgroup label="Upper Right">
                <option value="55">Tooth 55</option><option value="54">Tooth 54</option><option value="53">Tooth 53</option><option value="52">Tooth 52</option><option value="51">Tooth 51</option>
            </optgroup>
            <optgroup label="Upper Left">
                <option value="61">Tooth 61</option><option value="62">Tooth 62</option><option value="63">Tooth 63</option><option value="64">Tooth 64</option><option value="65">Tooth 65</option>
            </optgroup>
            <optgroup label="Lower Left">
                <option value="71">Tooth 71</option><option value="72">Tooth 72</option><option value="73">Tooth 73</option><option value="74">Tooth 74</option><option value="75">Tooth 75</option>
            </optgroup>
            <optgroup label="Lower Right">
                <option value="81">Tooth 81</option><option value="82">Tooth 82</option><option value="83">Tooth 83</option><option value="84">Tooth 84</option><option value="85">Tooth 85</option>
            </optgroup>
        `;
    } else {
        manualSelect.innerHTML = `
            <option value="">+ Select Tooth Manually</option>
            <optgroup label="Upper Right">
                <option value="18">Tooth 18</option><option value="17">Tooth 17</option><option value="16">Tooth 16</option><option value="15">Tooth 15</option>
                <option value="14">Tooth 14</option><option value="13">Tooth 13</option><option value="12">Tooth 12</option><option value="11">Tooth 11</option>
            </optgroup>
            <optgroup label="Upper Left">
                <option value="21">Tooth 21</option><option value="22">Tooth 22</option><option value="23">Tooth 23</option><option value="24">Tooth 24</option>
                <option value="25">Tooth 25</option><option value="26">Tooth 26</option><option value="27">Tooth 27</option><option value="28">Tooth 28</option>
            </optgroup>
            <optgroup label="Lower Left">
                <option value="31">Tooth 31</option><option value="32">Tooth 32</option><option value="33">Tooth 33</option><option value="34">Tooth 34</option>
                <option value="35">Tooth 35</option><option value="36">Tooth 36</option><option value="37">Tooth 37</option><option value="38">Tooth 38</option>
            </optgroup>
            <optgroup label="Lower Right">
                <option value="41">Tooth 41</option><option value="42">Tooth 42</option><option value="43">Tooth 43</option><option value="44">Tooth 44</option>
                <option value="45">Tooth 45</option><option value="46">Tooth 46</option><option value="47">Tooth 47</option><option value="48">Tooth 48</option>
            </optgroup>
        `;
    }

    
    const mapping = {
        "11": "51", "12": "52", "13": "53", "14": "54", "15": "55",
        "21": "61", "22": "62", "23": "63", "24": "64", "25": "65",
        "31": "71", "32": "72", "33": "73", "34": "74", "35": "75",
        "41": "81", "42": "82", "43": "83", "44": "84", "45": "85"
    };
    
    const hiddenInChild = ["16", "17", "18", "26", "27", "28", "36", "37", "38", "46", "47", "48"];
    
    hitboxes.forEach(box => {
        let currentTooth = box.getAttribute("data-tooth");
        let originalTooth = box.getAttribute("data-original-tooth") || currentTooth;
        
        if (!box.hasAttribute("data-original-tooth")) {
            box.setAttribute("data-original-tooth", originalTooth);
        }
        
        if (isChild) {
            if (hiddenInChild.includes(originalTooth)) {
                box.style.display = "none";
            } else {
                box.style.display = "block";
                let newTooth = mapping[originalTooth];
                box.setAttribute("data-tooth", newTooth);
                box.setAttribute("title", "Tooth " + newTooth);
                box.setAttribute("onclick", "openToothDiagnosisModal(" + newTooth + ")");
            }
        } else {
            box.style.display = "block";
            box.setAttribute("data-tooth", originalTooth);
            box.setAttribute("title", "Tooth " + originalTooth);
            box.setAttribute("onclick", "openToothDiagnosisModal(" + originalTooth + ")");
        }
    });
}
</script>

<?php require_once VIEWS_PATH . '/layouts/practitioner_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100 flex-grow-1">
            
            <a href="doctor/patients/<?= $patientId ?>/view" class="text-decoration-none text-muted mb-3 d-inline-block fw-bold"><i class="bi bi-arrow-left me-1"></i> Back to Record</a>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-dark">Consultation</h4>
                <div class="d-flex align-items-center">
                    <div class="bg-white text-primary px-3 py-1 rounded-pill fw-bold me-3 border border-primary shadow-sm d-flex align-items-center">
                        <i class="bi bi-stopwatch me-2" style="font-size: 1.1rem;"></i>
                        <span id="consultTimer" style="font-family: monospace; font-size: 1.1rem; letter-spacing: 1px;">00:00:00</span>
                    </div>
                    <div class="text-end text-muted small fw-bold">
                        <?= date('d M Y, h:i A') ?>
                    </div>
                </div>
            </div>

            <form action="doctor/patients/<?= $patientId ?>/consult" method="POST" id="consultForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointmentId ?? '') ?>">
                
                <div class="row g-4">
                    <!-- Left Column: Patient Overview -->
                    <div class="col-lg-3 col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top: 20px;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 text-dark">Patient Overview</h6>
                                
                                <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light">
                                    <div class="rounded overflow-hidden me-3 flex-shrink-0 bg-light text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;">
                                        <?php if (!empty($patient['profile_picture'])): ?>
                                            <img src="<?= htmlspecialchars($patient['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                                        <?php else: ?>
                                            <?= substr($patient['full_name'], 0, 1) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= ucwords(htmlspecialchars($patient['full_name'])) ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Age: <?= (new DateTime())->diff(new DateTime($patient['date_of_birth']))->y ?></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="text-muted fw-bold small mb-2"><i class="bi bi-person-lines-fill text-primary me-1"></i> Patient Details</div>
                                    <div class="bg-light p-3 rounded text-dark small">
                                        <div class="mb-2">
                                            <span class="text-muted">Gender:</span> 
                                            <span class="fw-bold ms-1"><?= !empty($patient['gender']) ? htmlspecialchars(ucfirst($patient['gender'])) : 'N/A' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Phone:</span> 
                                            <span class="fw-bold ms-1"><?= !empty($patient['phone']) ? htmlspecialchars($patient['phone']) : 'N/A' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Email:</span> 
                                            <span class="fw-bold ms-1 text-break"><?= !empty($patient['email']) ? htmlspecialchars($patient['email']) : 'N/A' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Address:</span> 
                                            <span class="fw-bold ms-1 text-break"><?= !empty($patient['address']) ? htmlspecialchars($patient['address']) : 'N/A' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Emergency:</span> 
                                            <span class="fw-bold ms-1 text-danger"><?= !empty($patient['emergency_contact']) ? htmlspecialchars($patient['emergency_contact']) : 'N/A' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Marital Status:</span> 
                                            <span class="fw-bold ms-1"><?= !empty($patient['marital_status']) ? htmlspecialchars(ucfirst($patient['marital_status'])) : 'N/A' ?></span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Registered On:</span> 
                                            <span class="fw-bold ms-1"><?= !empty($patient['created_at']) ? date('M d, Y', strtotime($patient['created_at'])) : 'N/A' ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="text-muted fw-bold small mb-2"><i class="bi bi-file-medical text-primary me-1"></i> Medical Profile</div>
                                    <div class="bg-light p-3 rounded text-dark small">
                                        <div class="mb-2">
                                            <span class="text-muted">Blood Group:</span> 
                                            <span class="fw-bold ms-1 text-danger"><?= !empty($patient['blood_group']) ? htmlspecialchars($patient['blood_group']) : 'Unknown' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Medical History:</span><br>
                                            <span class="fw-bold"><?= !empty($record['medical_history']) ? nl2br(htmlspecialchars($record['medical_history'])) : 'None recorded' ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted">Drug Allergies:</span><br>
                                            <span class="fw-bold text-danger"><?= !empty($record['drug_allergies']) ? nl2br(htmlspecialchars($record['drug_allergies'])) : 'No known allergies' ?></span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Current Meds:</span><br>
                                            <span class="fw-bold"><?= !empty($record['current_medications']) ? nl2br(htmlspecialchars($record['current_medications'])) : 'None' ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="text-muted fw-bold small mb-2"><i class="bi bi-chat-text text-primary me-1"></i> Today's Complaint</div>
                                    <div class="text-dark small bg-light p-2 rounded text-muted" id="ov_complaint" style="font-style: italic; min-height: 40px;"><?= !empty($appointment['reason']) && $appointment['reason'] !== 'Walk-in/Ad-hoc Consult' ? nl2br(htmlspecialchars($appointment['reason'])) : 'Not entered...' ?></div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="text-muted fw-bold small mb-2"><i class="bi bi-tooth text-primary me-1"></i> Selected Teeth</div>
                                    <div class="d-flex flex-wrap gap-2" id="ov_selected_teeth">
                                        <span class="text-muted small">None</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="text-muted fw-bold small mb-2"><i class="bi bi-card-checklist text-primary me-1"></i> Treatments</div>
                                    <div class="d-flex flex-column gap-2" id="ov_treatments">
                                        <span class="text-muted small">None selected</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Main Wizard -->
                    <div class="col-lg-9 col-md-8">
                        
                        <!-- Horizontal Stepper -->
                        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center position-relative px-md-4">
                                    <div class="position-absolute bg-light" style="height: 3px; top: 35%; left: 10%; right: 10%; z-index: 0;"></div>
                                    
                                    <div class="text-center position-relative z-1 wizard-step active" onclick="showStep(1)" style="cursor:pointer; width: 80px;">
                                        <div class="step-num rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 step-circle shadow-sm" style="width:36px; height:36px;"><i class="bi bi-file-medical"></i></div>
                                        <div class="small fw-bold step-text" style="font-size: 0.75rem;">Intake & Vitals</div>
                                    </div>
                                    <div class="text-center position-relative z-1 wizard-step" onclick="showStep(2)" style="cursor:pointer; width: 80px;">
                                        <div class="step-num rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 step-circle bg-white border border-light text-muted" style="width:36px; height:36px;"><i class="bi bi-journal-medical"></i></div>
                                        <div class="small fw-bold step-text text-muted" style="font-size: 0.75rem;">Diagnosis</div>
                                    </div>
                                    <div class="text-center position-relative z-1 wizard-step" onclick="showStep(3)" style="cursor:pointer; width: 80px;">
                                        <div class="step-num rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 step-circle bg-white border border-light text-muted" style="width:36px; height:36px;"><i class="bi bi-clipboard2-pulse"></i></div>
                                        <div class="small fw-bold step-text text-muted" style="font-size: 0.75rem;">Treatment Plan</div>
                                    </div>
                                    <div class="text-center position-relative z-1 wizard-step" onclick="showStep(4)" style="cursor:pointer; width: 80px;">
                                        <div class="step-num rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 step-circle bg-white border border-light text-muted" style="width:36px; height:36px;"><i class="bi bi-capsule"></i></div>
                                        <div class="small fw-bold step-text text-muted" style="font-size: 0.75rem;">Prescription</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wizard Content Cards -->
                        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                            <div class="card-body p-4">
                                
                                <!-- Step 1: Intake & Vitals -->
                                <div id="step1" class="wizard-content">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="fw-bold text-dark mb-0">Intake & Vitals</h5>
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" onclick="showStep(2)">Continue to Diagnosis <i class="bi bi-chevron-right ms-1"></i></button>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">Presenting Complaint</label>
                                        <textarea class="form-control bg-light border-0" id="input_complaint" name="presenting_complaint" rows="3" placeholder="Chief complaint..."><?= !empty($appointment['reason']) && $appointment['reason'] !== 'Walk-in/Ad-hoc Consult' ? htmlspecialchars($appointment['reason']) : '' ?></textarea>
                                    </div>
                                    
                                    <h6 class="fw-bold mb-3 mt-4 text-dark" style="font-size: 0.9rem;">Vitals</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">Blood Pressure (mmHg)</label>
                                            <input type="text" class="form-control bg-light border-0" id="input_bp" name="blood_pressure" placeholder="120/80">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">Heart Rate (bpm)</label>
                                            <input type="number" class="form-control bg-light border-0" id="input_hr" name="heart_rate" placeholder="72">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">Blood Sugar / Glucose (mg/dL)</label>
                                            <input type="text" class="form-control bg-light border-0" id="input_gluc" name="glucose" placeholder="95">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">Weight (kg)</label>
                                            <input type="text" class="form-control bg-light border-0" id="input_wt" name="weight" placeholder="70">
                                        </div>
                                    </div>
                                    
                                    <h6 class="fw-bold mb-3 mt-4 text-dark" style="font-size: 0.9rem;">Dental Hygiene & Metrics</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">Last Cleaning Date</label>
                                            <input type="date" class="form-control bg-light border-0" name="last_cleaning_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">Gum Health (Perio)</label>
                                            <select class="form-select bg-light border-0" name="gum_health">
                                                <option value="">Select...</option>
                                                <option value="Healthy">Healthy</option>
                                                <option value="Gingivitis - Mild">Gingivitis - Mild</option>
                                                <option value="Gingivitis - Severe">Gingivitis - Severe</option>
                                                <option value="Periodontitis">Periodontitis</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Plaque Index</label>
                                            <select class="form-select bg-light border-0" name="plaque_index">
                                                <option value="">Select...</option>
                                                <option value="0% (Excellent)">0% (Excellent)</option>
                                                <option value="10-30% (Good)">10-30% (Good)</option>
                                                <option value="30-50% (Fair)">30-50% (Fair)</option>
                                                <option value=">50% (Poor)">>50% (Poor)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Brushing Frequency</label>
                                            <select class="form-select bg-light border-0" name="brushing_freq">
                                                <option value="">Select...</option>
                                                <option value="2x Daily">2x Daily</option>
                                                <option value="1x Daily">1x Daily</option>
                                                <option value="Occasionally">Occasionally</option>
                                                <option value="Rarely">Rarely</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Flossing</label>
                                            <select class="form-select bg-light border-0" name="flossing_freq">
                                                <option value="">Select...</option>
                                                <option value="Daily">Daily</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Rarely">Rarely</option>
                                                <option value="Never">Never</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Diagnosis & Odontogram -->
                                <div id="step2" class="wizard-content d-none">
                                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                        <h5 class="fw-bold text-dark mb-0">Diagnosis</h5>
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" onclick="showStep(3)">Continue to Treatment <i class="bi bi-chevron-right ms-1"></i></button>
                                    </div>

                                    <div class="row">
                                        <!-- Left Side: Tooth Chart -->
                                        <div class="col-md-5 border-end pe-4">
                                            
<div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h6 class="fw-bold small text-muted mb-0">Select & Mark Teeth</h6>
    <div class="btn-group" role="group">
        <input type="radio" class="btn-check" name="odontogram_type" id="odontogram_adult" value="adult" checked autocomplete="off" onchange="toggleOdontogramMode()">
        <label class="btn btn-outline-primary btn-sm" for="odontogram_adult" style="font-size:0.75rem;">Adult</label>
      
        <input type="radio" class="btn-check" name="odontogram_type" id="odontogram_child" value="child" autocomplete="off" onchange="toggleOdontogramMode()">
        <label class="btn btn-outline-primary btn-sm" for="odontogram_child" style="font-size:0.75rem;">Child</label>
    </div>
</div>
<div class="odontogram-wrapper mx-auto position-relative" style="width: 280px; height: 350px; background: url('assets/images/odontogram.png') no-repeat center center; background-size: contain; margin-top: -30px;">
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="18" onclick="openToothDiagnosisModal(18)" style="width: 12px; height: 12px; left: calc(25.5% - 6px); top: calc(46% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 18"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="17" onclick="openToothDiagnosisModal(17)" style="width: 12px; height: 12px; left: calc(26.5% - 6px); top: calc(38% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 17"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="16" onclick="openToothDiagnosisModal(16)" style="width: 12px; height: 12px; left: calc(28.5% - 6px); top: calc(31% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 16"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="15" onclick="openToothDiagnosisModal(15)" style="width: 12px; height: 12px; left: calc(32% - 6px); top: calc(25% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 15"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="14" onclick="openToothDiagnosisModal(14)" style="width: 12px; height: 12px; left: calc(36.5% - 6px); top: calc(21% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 14"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="13" onclick="openToothDiagnosisModal(13)" style="width: 12px; height: 12px; left: calc(41.5% - 6px); top: calc(18.5% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 13"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="12" onclick="openToothDiagnosisModal(12)" style="width: 12px; height: 12px; left: calc(45.5% - 6px); top: calc(17.5% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 12"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="11" onclick="openToothDiagnosisModal(11)" style="width: 12px; height: 12px; left: calc(48.5% - 6px); top: calc(17.5% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 11"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="21" onclick="openToothDiagnosisModal(21)" style="width: 12px; height: 12px; left: calc(51.5% - 6px); top: calc(17.5% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 21"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="22" onclick="openToothDiagnosisModal(22)" style="width: 12px; height: 12px; left: calc(54.5% - 6px); top: calc(17.5% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 22"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="23" onclick="openToothDiagnosisModal(23)" style="width: 12px; height: 12px; left: calc(58.5% - 6px); top: calc(18.5% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 23"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="24" onclick="openToothDiagnosisModal(24)" style="width: 12px; height: 12px; left: calc(63.5% - 6px); top: calc(21% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 24"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="25" onclick="openToothDiagnosisModal(25)" style="width: 12px; height: 12px; left: calc(68% - 6px); top: calc(25% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 25"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="26" onclick="openToothDiagnosisModal(26)" style="width: 12px; height: 12px; left: calc(71.5% - 6px); top: calc(31% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 26"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="27" onclick="openToothDiagnosisModal(27)" style="width: 12px; height: 12px; left: calc(73.5% - 6px); top: calc(38% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 27"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="28" onclick="openToothDiagnosisModal(28)" style="width: 12px; height: 12px; left: calc(74.5% - 6px); top: calc(46% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 28"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="48" onclick="openToothDiagnosisModal(48)" style="width: 12px; height: 12px; left: calc(26.5% - 6px); top: calc(52% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 48"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="47" onclick="openToothDiagnosisModal(47)" style="width: 12px; height: 12px; left: calc(27.5% - 6px); top: calc(60% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 47"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="46" onclick="openToothDiagnosisModal(46)" style="width: 12px; height: 12px; left: calc(29.5% - 6px); top: calc(67% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 46"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="45" onclick="openToothDiagnosisModal(45)" style="width: 12px; height: 12px; left: calc(33% - 6px); top: calc(72% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 45"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="44" onclick="openToothDiagnosisModal(44)" style="width: 12px; height: 12px; left: calc(37% - 6px); top: calc(75% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 44"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="43" onclick="openToothDiagnosisModal(43)" style="width: 12px; height: 12px; left: calc(41.5% - 6px); top: calc(77% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 43"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="42" onclick="openToothDiagnosisModal(42)" style="width: 12px; height: 12px; left: calc(45.5% - 6px); top: calc(78% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 42"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="41" onclick="openToothDiagnosisModal(41)" style="width: 12px; height: 12px; left: calc(48.5% - 6px); top: calc(78% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 41"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="31" onclick="openToothDiagnosisModal(31)" style="width: 12px; height: 12px; left: calc(51.5% - 6px); top: calc(78% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 31"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="32" onclick="openToothDiagnosisModal(32)" style="width: 12px; height: 12px; left: calc(54.5% - 6px); top: calc(78% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 32"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="33" onclick="openToothDiagnosisModal(33)" style="width: 12px; height: 12px; left: calc(58.5% - 6px); top: calc(77% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 33"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="34" onclick="openToothDiagnosisModal(34)" style="width: 12px; height: 12px; left: calc(63% - 6px); top: calc(75% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 34"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="35" onclick="openToothDiagnosisModal(35)" style="width: 12px; height: 12px; left: calc(67% - 6px); top: calc(72% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 35"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="36" onclick="openToothDiagnosisModal(36)" style="width: 12px; height: 12px; left: calc(70.5% - 6px); top: calc(67% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 36"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="37" onclick="openToothDiagnosisModal(37)" style="width: 12px; height: 12px; left: calc(72.5% - 6px); top: calc(60% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 37"></div>
    <div class="tooth-hitbox tooth position-absolute rounded-circle cursor-pointer" data-tooth="38" onclick="openToothDiagnosisModal(38)" style="width: 12px; height: 12px; left: calc(73.5% - 6px); top: calc(52% - 6px); opacity: 1; border: 1.5px solid rgba(13, 110, 253, 0.4); background-color: rgba(13, 110, 253, 0.05); z-index: 10; pointer-events: auto; transition: all 0.2s;" title="Tooth 38"></div>
</div>

<div class="mt-2 text-center px-4">
    <select id="manualToothSelect" class="form-select border-light shadow-sm bg-white fw-bold text-primary" onchange="if(this.value) openToothDiagnosisModal(this.value); this.value='';">
        <option value="">+ Select Tooth Manually</option>
        <optgroup label="Upper Right">
            <option value="18">Tooth 18</option><option value="17">Tooth 17</option><option value="16">Tooth 16</option><option value="15">Tooth 15</option>
            <option value="14">Tooth 14</option><option value="13">Tooth 13</option><option value="12">Tooth 12</option><option value="11">Tooth 11</option>
        </optgroup>
        <optgroup label="Upper Left">
            <option value="21">Tooth 21</option><option value="22">Tooth 22</option><option value="23">Tooth 23</option><option value="24">Tooth 24</option>
            <option value="25">Tooth 25</option><option value="26">Tooth 26</option><option value="27">Tooth 27</option><option value="28">Tooth 28</option>
        </optgroup>
        <optgroup label="Lower Left">
            <option value="31">Tooth 31</option><option value="32">Tooth 32</option><option value="33">Tooth 33</option><option value="34">Tooth 34</option>
            <option value="35">Tooth 35</option><option value="36">Tooth 36</option><option value="37">Tooth 37</option><option value="38">Tooth 38</option>
        </optgroup>
        <optgroup label="Lower Right">
            <option value="41">Tooth 41</option><option value="42">Tooth 42</option><option value="43">Tooth 43</option><option value="44">Tooth 44</option>
            <option value="45">Tooth 45</option><option value="46">Tooth 46</option><option value="47">Tooth 47</option><option value="48">Tooth 48</option>
        </optgroup>
    </select>
</div>

<style>
.tooth-hitbox:hover { background-color: rgba(13, 110, 253, 0.2) !important; }
.tooth-hitbox.has-condition { background-color: rgba(220, 53, 69, 0.5) !important; border: 2px solid #dc3545 !important; }
</style>

                                            <div class="d-flex flex-wrap gap-2 justify-content-center mt-4 pt-3 border-top">
                                                <div class="d-flex align-items-center"><div class="rounded-circle me-1" style="width:10px; height:10px; background-color: #ffcccc; border: 1px solid #dc3545;"></div><span style="font-size: 0.7rem;" class="fw-bold">Decay</span></div>
                                                <div class="d-flex align-items-center"><div class="rounded-circle me-1" style="width:10px; height:10px; background-color: #d1e7dd; border: 1px solid #198754;"></div><span style="font-size: 0.7rem;" class="fw-bold">Filled</span></div>
                                                <div class="d-flex align-items-center"><div class="rounded-circle me-1" style="width:10px; height:10px; background-color: #e9ecef; border: 1px solid #6c757d;"></div><span style="font-size: 0.7rem;" class="fw-bold">Missing</span></div>
                                                <div class="d-flex align-items-center"><div class="rounded-circle me-1" style="width:10px; height:10px; background-color: #cff4fc; border: 1px solid #0dcaf0;"></div><span style="font-size: 0.7rem;" class="fw-bold">Other</span></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Right Side: Diagnosed List -->
                                        <div class="col-md-7 ps-4">
                                            <div id="diagnosed-teeth-list" class="d-flex flex-column gap-3">
                                                <div class="text-center text-muted mt-5 pt-5" id="empty-diagnosis-state">
                                                    <i class="bi bi-teeth display-1 opacity-25"></i>
                                                    <p class="mt-3">Click on a tooth in the Odontogram<br>to add a diagnosis.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Global Diagnosis -->
                                    <div class="mt-5 pt-3 border-top">
                                        <h6 class="fw-bold mb-3 text-dark">Overall Diagnosis</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">Primary Diagnosis <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control bg-light border-0" name="diagnosis" placeholder="e.g. Dental Caries">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">Clinical Findings (O/E)</label>
                                            <textarea class="form-control bg-light border-0" name="clinical_findings" rows="3" placeholder="Observation notes..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Step 3: Treatment Plan -->
                                <div id="step3" class="wizard-content d-none">
                                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                        <h5 class="fw-bold text-dark mb-0">Treatment Plan</h5>
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" onclick="showStep(4)">Continue to Prescription <i class="bi bi-chevron-right ms-1"></i></button>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">Treatment Performed Today</label>
                                        <textarea class="form-control bg-light border-0" name="treatment_performed" rows="3" placeholder="What did you do today?"></textarea>
                                    </div>
                                    <div class="mb-4 bg-light rounded p-3 border border-light">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <label class="form-label fw-bold small text-dark mb-0">Multi-Step Treatment Plan (Milestones)</label>
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-3 fw-bold bg-white" onclick="addMilestoneRow()"><i class="bi bi-plus"></i> Add Step</button>
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" class="form-control form-control-sm border-0 shadow-sm px-3 py-2" name="procedure_name" placeholder="Overall Procedure Name (e.g. Root Canal) - Leave blank if single visit">
                                        </div>
                                        <div id="milestoneList">
                                            <!-- Dynamic rows will go here via JS -->
                                        </div>
                                        <div class="text-muted small mt-2 fst-italic"><i class="bi bi-info-circle me-1"></i> These steps will automatically populate the patient's "Current Treatment" timeline.</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-3 mt-4 text-dark" style="font-size: 0.9rem;">Clinical Photos</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">Before Treatment Photo (Optional)</label>
                                                <input class="form-control form-control-sm bg-light border-0" type="file" name="photo_before" accept="image/*">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">After Treatment Photo (Optional)</label>
                                                <input class="form-control form-control-sm bg-light border-0" type="file" name="photo_after" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Prescription -->
                                <div id="step4" class="wizard-content d-none">
                                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                        <h5 class="fw-bold text-dark mb-0">Prescription</h5>
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">Complete & Save Consult</button>
                                    </div>
                                    
                                    <div id="prescriptionList">
                                        <!-- Row 0 -->
                                        <div class="d-flex flex-wrap flex-xl-nowrap gap-2 align-items-end mb-3 rx-row" data-idx="0">
                                            <div style="flex: 1; min-width: 160px;">
                                                <label class="form-label text-muted small mb-1">Medicine</label>
                                                <input type="text" class="form-control form-control-sm bg-light border-0 shadow-none px-2" name="rx_medicine[0]" placeholder="Amoxicillin 500mg">
                                            </div>
                                            <div style="width: 100px;">
                                                <label class="form-label text-muted small mb-1">Duration</label>
                                                <select class="form-select form-select-sm bg-light border-0 shadow-none px-2" name="rx_duration[0]">
                                                    <option value="Daily">Daily</option>
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Monthly">Monthly</option>
                                                </select>
                                            </div>
                                            <div style="width: 120px;">
                                                <label class="form-label text-muted small mb-1">Intake</label>
                                                <select class="form-select form-select-sm bg-light border-0 shadow-none px-2" name="rx_intake[0]">
                                                    <option value="After food">After food</option>
                                                    <option value="Before food">Before food</option>
                                                </select>
                                            </div>
                                            <div class="d-flex gap-2 align-items-center mb-1">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="rx_morning[0]" value="1" checked>
                                                    <label class="form-check-label small" style="white-space: nowrap;">Morning</label>
                                                </div>
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="rx_noon[0]" value="1">
                                                    <label class="form-check-label small" style="white-space: nowrap;">Noon</label>
                                                </div>
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="rx_night[0]" value="1" checked>
                                                    <label class="form-check-label small" style="white-space: nowrap;">Night</label>
                                                </div>
                                            </div>
                                            <div class="mb-1 ms-1">
                                                <button type="button" class="btn btn-sm text-danger border-0 bg-transparent p-0 remove-rx"><i class="bi bi-trash text-danger bg-danger bg-opacity-10 p-1 rounded"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4 mt-2">
                                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0" id="addMedicineBtn"><i class="bi bi-plus-circle me-1"></i> Add Medicine</button>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <textarea class="form-control bg-light border-0 shadow-none" name="rx_instruction" rows="3" placeholder="General prescription instructions..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
            
        </main>
    </div>
</div>

<!-- Tooth Diagnosis Modal -->
<div class="modal fade" id="toothDiagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold mb-0" id="toothModalTitle">Tooth Diagnosis</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="activeToothNum">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Condition type</label>
                    <div class="position-relative custom-multi-select" id="conditionSelect">
                        <div class="form-control bg-light border-0 d-flex flex-wrap gap-1 align-items-center" style="min-height: 38px; cursor: pointer;">
                            <div class="tags d-flex flex-wrap gap-1"></div>
                            <input type="text" class="border-0 bg-transparent flex-grow-1 shadow-none" style="outline: none; min-width: 50px;" placeholder="Search...">
                        </div>
                        <div class="dropdown-menu w-100 shadow-sm border-0 mt-1" style="max-height: 200px; overflow-y: auto;">
                            <a class="dropdown-item small" href="#" data-val="Deep caries">Deep caries</a>
                            <a class="dropdown-item small" href="#" data-val="Enamel Fracture">Enamel Fracture</a>
                            <a class="dropdown-item small" href="#" data-val="Irreversible pulpitis">Irreversible pulpitis</a>
                            <a class="dropdown-item small" href="#" data-val="Pulpal necrosis">Pulpal necrosis</a>
                            <a class="dropdown-item small" href="#" data-val="Gingivitis">Gingivitis</a>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Recommended treatment</label>
                    <div class="position-relative custom-multi-select" id="treatmentSelect">
                        <div class="form-control bg-light border-0 d-flex flex-wrap gap-1 align-items-center" style="min-height: 38px; cursor: pointer;">
                            <div class="tags d-flex flex-wrap gap-1"></div>
                            <input type="text" class="border-0 bg-transparent flex-grow-1 shadow-none" style="outline: none; min-width: 50px;" placeholder="Search...">
                        </div>
                        <div class="dropdown-menu w-100 shadow-sm border-0 mt-1" style="max-height: 200px; overflow-y: auto;">
                            <a class="dropdown-item small" href="#" data-val="Root Canal">Root Canal</a>
                            <a class="dropdown-item small" href="#" data-val="Crown">Crown</a>
                            <a class="dropdown-item small" href="#" data-val="Extraction">Extraction</a>
                            <a class="dropdown-item small" href="#" data-val="Filling">Filling</a>
                            <a class="dropdown-item small" href="#" data-val="Scaling">Scaling</a>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Percussion test</label>
                    <select class="form-select bg-light border-0 shadow-none" id="percussionSelect">
                        <option value="Negative">Negative</option>
                        <option value="Positive - mild">Positive - mild</option>
                        <option value="Positive - severe">Positive - severe</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Clinical Note</label>
                    <textarea class="form-control bg-light border-0" id="toothNote" rows="3" placeholder="Observation details..."></textarea>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="saveToothDiagnosis()">Save</button>
                    <button type="button" class="btn btn-light text-danger rounded-circle" onclick="deleteToothDiagnosis()"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ----------------------------------------------------
    // Dynamic Left Panel Updates
    // ----------------------------------------------------
    const bindInput = (inputId, outputId, suffix = '') => {
        const input = document.getElementById(inputId);
        const output = document.getElementById(outputId);
        if(input && output) {
            input.addEventListener('input', () => {
                output.innerText = input.value ? input.value + suffix : '--' + suffix;
            });
        }
    };
    bindInput('input_bp', 'ov_bp', ' mmHg');
    bindInput('input_hr', 'ov_hr', ' bpm');
    bindInput('input_gluc', 'ov_gluc', ' mg/dL');
    bindInput('input_wt', 'ov_wt', ' kg');
    
    const complaintInput = document.getElementById('input_complaint');
    if(complaintInput) {
        complaintInput.addEventListener('input', () => {
            document.getElementById('ov_complaint').innerText = complaintInput.value || 'Not entered...';
        });
    }

    // ----------------------------------------------------
    // Horizontal Stepper Logic
    // ----------------------------------------------------
    function showStep(stepNum) {
        document.querySelectorAll('.wizard-content').forEach(el => el.classList.add('d-none'));
        document.getElementById('step' + stepNum).classList.remove('d-none');
        
        document.querySelectorAll('.wizard-step').forEach((el, index) => {
            const circle = el.querySelector('.step-circle');
            const text = el.querySelector('.step-text');
            
            if(index + 1 === stepNum) {
                circle.classList.add('bg-primary', 'text-white', 'shadow-sm');
                circle.classList.remove('bg-white', 'border', 'border-light', 'text-muted');
                text.classList.add('text-primary');
                text.classList.remove('text-muted');
            } else if (index + 1 < stepNum) {
                circle.classList.add('bg-primary', 'text-white', 'shadow-sm');
                circle.classList.remove('bg-white', 'border', 'border-light', 'text-muted');
                text.classList.add('text-primary');
                text.classList.remove('text-muted');
            } else {
                circle.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                circle.classList.add('bg-white', 'border', 'border-light', 'text-muted');
                text.classList.remove('text-primary');
                text.classList.add('text-muted');
            }
        });
    }

    // ----------------------------------------------------
    // Prescription Logic
    // ----------------------------------------------------
    let rxIndex = 1;
    document.getElementById('addMedicineBtn').addEventListener('click', function() {
        const list = document.getElementById('prescriptionList');
        const row = document.createElement('div');
        row.className = 'd-flex flex-wrap flex-xl-nowrap gap-2 align-items-end mb-3 rx-row';
        row.dataset.idx = rxIndex;
        row.innerHTML = `
            <div style="flex: 1; min-width: 160px;">
                <input type="text" class="form-control form-control-sm bg-light border-0 shadow-none px-2" name="rx_medicine[${rxIndex}]" placeholder="Medicine">
            </div>
            <div style="width: 100px;">
                <select class="form-select form-select-sm bg-light border-0 shadow-none px-2" name="rx_duration[${rxIndex}]">
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </div>
            <div style="width: 120px;">
                <select class="form-select form-select-sm bg-light border-0 shadow-none px-2" name="rx_intake[${rxIndex}]">
                    <option value="After food">After food</option>
                    <option value="Before food">Before food</option>
                </select>
            </div>
            <div class="d-flex gap-2 align-items-center mb-1">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="rx_morning[${rxIndex}]" value="1">
                    <label class="form-check-label small" style="white-space: nowrap;">Morning</label>
                </div>
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="rx_noon[${rxIndex}]" value="1">
                    <label class="form-check-label small" style="white-space: nowrap;">Noon</label>
                </div>
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="rx_night[${rxIndex}]" value="1">
                    <label class="form-check-label small" style="white-space: nowrap;">Night</label>
                </div>
            </div>
            <div class="mb-1 ms-1">
                <button type="button" class="btn btn-sm text-danger border-0 bg-transparent p-0 remove-rx"><i class="bi bi-trash text-danger bg-danger bg-opacity-10 p-1 rounded"></i></button>
            </div>
        `;
        list.appendChild(row);
        rxIndex++;
    });

    document.getElementById('prescriptionList').addEventListener('click', function(e) {
        if (e.target.closest('.remove-rx')) {
            e.target.closest('.rx-row').remove();
        }
    });

    // ----------------------------------------------------
    // Custom Multi-Select Logic
    // ----------------------------------------------------
    function setupMultiSelect(containerId) {
        const container = document.getElementById(containerId);
        const input = container.querySelector('input[type="text"]');
        const dropdown = container.querySelector('.dropdown-menu');
        const tagsContainer = container.querySelector('.tags');
        const items = dropdown.querySelectorAll('.dropdown-item');
        
        input.addEventListener('focus', () => {
            dropdown.classList.add('show');
        });
        
        document.addEventListener('click', (e) => {
            if(!container.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        input.addEventListener('input', () => {
            const val = input.value.toLowerCase();
            items.forEach(item => {
                if(item.innerText.toLowerCase().includes(val)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            dropdown.classList.add('show');
        });
        
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                addTag(tagsContainer, item.dataset.val);
                input.value = '';
                items.forEach(i => i.style.display = 'block');
                dropdown.classList.remove('show');
            });
        });
    }

    function addTag(container, text) {
        // Avoid duplicate
        const existing = Array.from(container.querySelectorAll('.tag-text')).map(el => el.innerText);
        if(existing.includes(text)) return;
        
        const tag = document.createElement('span');
        tag.className = 'badge bg-light text-primary border border-primary d-flex align-items-center gap-1 p-2';
        tag.innerHTML = `<span class="tag-text">${text}</span> <i class="bi bi-x-circle ms-1" style="cursor:pointer;" onclick="this.parentElement.remove()"></i>`;
        container.appendChild(tag);
    }
    
    setupMultiSelect('conditionSelect');
    setupMultiSelect('treatmentSelect');

    // ----------------------------------------------------
    // Tooth Diagnosis Data Logic
    // ----------------------------------------------------
    let toothData = {};
    let toothModal = null;
    
    function getToothName(num) {
        // Simplified naming logic
        if(num >= 11 && num <= 18) return `Tooth ${num} (Upper Right)`;
        if(num >= 21 && num <= 28) return `Tooth ${num} (Upper Left)`;
        if(num >= 31 && num <= 38) return `Tooth ${num} (Lower Left)`;
        if(num >= 41 && num <= 48) return `Tooth ${num} (Lower Right)`;
        return `Tooth ${num}`;
    }

    function openToothDiagnosisModal(toothNum) {
        if (!toothModal) {
            toothModal = new bootstrap.Modal(document.getElementById('toothDiagnosisModal'));
        }
        document.getElementById('activeToothNum').value = toothNum;
        document.getElementById('toothModalTitle').innerText = getToothName(toothNum);
        
        // Reset fields
        document.querySelector('#conditionSelect .tags').innerHTML = '';
        document.querySelector('#treatmentSelect .tags').innerHTML = '';
        document.getElementById('percussionSelect').value = 'Negative';
        document.getElementById('toothNote').value = '';
        
        // Load existing if any
        if(toothData[toothNum]) {
            toothData[toothNum].conditions.forEach(c => addTag(document.querySelector('#conditionSelect .tags'), c));
            toothData[toothNum].treatments.forEach(t => addTag(document.querySelector('#treatmentSelect .tags'), t));
            document.getElementById('percussionSelect').value = toothData[toothNum].percussion;
            document.getElementById('toothNote').value = toothData[toothNum].note;
        }
        
        toothModal.show();
    }

    function saveToothDiagnosis() {
        const toothNum = document.getElementById('activeToothNum').value;
        const conditions = Array.from(document.querySelectorAll('#conditionSelect .tag-text')).map(el => el.innerText);
        const treatments = Array.from(document.querySelectorAll('#treatmentSelect .tag-text')).map(el => el.innerText);
        const percussion = document.getElementById('percussionSelect').value;
        const note = document.getElementById('toothNote').value;
        
        if(conditions.length === 0) {
            alert('Please select at least one condition');
            return;
        }
        
        toothData[toothNum] = {
            conditions, treatments, percussion, note
        };
        
        // Color Odontogram Tooth Map Hitbox
        const tEl = document.querySelector(`.tooth[data-tooth="${toothNum}"]`);
        if(tEl) {
            tEl.classList.add('has-condition');
        }
        
        renderDiagnosedList();
        updateLeftPanelOverview();
        
        toothModal.hide();
    }

    function deleteToothDiagnosis() {
        const toothNum = document.getElementById('activeToothNum').value;
        delete toothData[toothNum];
        
        const tEl = document.querySelector(`.tooth[data-tooth="${toothNum}"]`);
        if(tEl) {
            tEl.classList.remove('cond-decay', 'cond-filled', 'cond-missing');
        }
        
        renderDiagnosedList();
        updateLeftPanelOverview();
        toothModal.hide();
    }

    function renderDiagnosedList() {
        const listContainer = document.getElementById('diagnosed-teeth-list');
        const emptyState = document.getElementById('empty-diagnosis-state');
        
        // Remove existing items
        listContainer.querySelectorAll('.diagnosis-card').forEach(el => el.remove());
        
        const keys = Object.keys(toothData);
        if(keys.length === 0) {
            if(emptyState) emptyState.style.display = 'block';
            return;
        }
        
        if(emptyState) emptyState.style.display = 'none';
        
        // Hidden inputs container to send data to backend
        let hiddenInputsHtml = '';

        keys.forEach(num => {
            const d = toothData[num];
            
            // Generate hidden inputs for form submission
            const condStr = JSON.stringify(d.conditions).replace(/"/g, '&quot;');
            const treatStr = JSON.stringify(d.treatments).replace(/"/g, '&quot;');
            hiddenInputsHtml += `
                <input type="hidden" name="tooth_diagnoses[${num}][conditions]" value="${condStr}">
                <input type="hidden" name="tooth_diagnoses[${num}][treatments]" value="${treatStr}">
                <input type="hidden" name="tooth_diagnoses[${num}][percussion]" value="${d.percussion}">
                <input type="hidden" name="tooth_diagnoses[${num}][note]" value="${d.note}">
            `;
            
            const card = document.createElement('div');
            card.className = 'diagnosis-card border border-light rounded-4 p-3 bg-white shadow-sm position-relative';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0 text-dark">${getToothName(num)}</h6>
                    <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-1 px-2" onclick="openToothDiagnosisModal(${num})"><i class="bi bi-pencil"></i></button>
                </div>
                <div class="row g-2 mt-1">
                    <div class="col-6">
                        <div class="small text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Condition Type</div>
                        <div class="fw-bold small">${d.conditions.join(', ')}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Percussion Test</div>
                        <div class="fw-bold small">${d.percussion}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Recommended Treatment</div>
                        <div class="fw-bold small text-primary">${d.treatments.join(', ') || 'None'}</div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="small text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Clinical Note</div>
                        <div class="small bg-light p-2 rounded text-muted fst-italic">${d.note || 'No notes added'}</div>
                    </div>
                </div>
            `;
            listContainer.appendChild(card);
        });
        
        // Append hidden inputs
        const hiddenDiv = document.createElement('div');
        hiddenDiv.className = 'diagnosis-card d-none';
        hiddenDiv.innerHTML = hiddenInputsHtml;
        listContainer.appendChild(hiddenDiv);
    }
    
    function updateLeftPanelOverview() {
        const teethContainer = document.getElementById('ov_selected_teeth');
        const treatmentsContainer = document.getElementById('ov_treatments');
        
        const keys = Object.keys(toothData);
        if(keys.length === 0) {
            teethContainer.innerHTML = '<span class="text-muted small">None</span>';
            treatmentsContainer.innerHTML = '<span class="text-muted small">None selected</span>';
            return;
        }
        
        // Update teeth badges
        teethContainer.innerHTML = keys.map(k => `<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill"><i class="bi bi-tooth me-1"></i>${k}</span>`).join('');
        
        // Update treatments
        let allTreatments = new Set();
        keys.forEach(k => {
            toothData[k].treatments.forEach(t => allTreatments.add(t));
        });
        
        if(allTreatments.size === 0) {
            treatmentsContainer.innerHTML = '<span class="text-muted small">No treatments advised</span>';
        } else {
            treatmentsContainer.innerHTML = Array.from(allTreatments).map(t => `<div class="d-flex align-items-center small text-dark"><i class="bi bi-check-circle-fill text-success me-2" style="font-size:0.8rem;"></i>${t}</div>`).join('');
        }
    }
    
    // ----------------------------------------------------
    // Milestone Builder Logic
    // ----------------------------------------------------
    let milestoneCount = 0;
    function addMilestoneRow() {
        const container = document.getElementById('milestoneList');
        const html = `
            <div class="d-flex flex-wrap flex-xl-nowrap gap-2 align-items-end mb-2 ms-row" data-idx="${milestoneCount}">
                <div style="flex: 1; min-width: 160px;">
                    <label class="form-label text-muted small mb-1">Step Name</label>
                    <input type="text" class="form-control form-control-sm border-0 px-2 shadow-sm bg-white" name="ms_step_name[${milestoneCount}]" placeholder="e.g. Pulp Removal">
                </div>
                <div style="width: 130px;">
                    <label class="form-label text-muted small mb-1">Status</label>
                    <select class="form-select form-select-sm border-0 px-2 shadow-sm bg-white" name="ms_status[${milestoneCount}]">
                        <option value="Schedule">Schedule</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div style="width: 140px;">
                    <label class="form-label text-muted small mb-1">Date</label>
                    <input type="date" class="form-control form-control-sm border-0 px-2 shadow-sm bg-white" name="ms_date[${milestoneCount}]">
                </div>
                <div class="mb-1 ms-1">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded p-1" onclick="this.closest('.ms-row').remove()"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        milestoneCount++;
    }
    // ----------------------------------------------------
    // Consultation Timer Logic
    // ----------------------------------------------------
    let timerSeconds = 0;
    const timerDisplay = document.getElementById('consultTimer');
    setInterval(() => {
        timerSeconds++;
        const hrs = Math.floor(timerSeconds / 3600);
        const mins = Math.floor((timerSeconds % 3600) / 60);
        const secs = timerSeconds % 60;
        
        timerDisplay.textContent = 
            String(hrs).padStart(2, '0') + ':' +
            String(mins).padStart(2, '0') + ':' + 
            String(secs).padStart(2, '0');
    }, 1000);

</script>


<div class="container" style="display: none;">

<script>
function toggleOdontogramMode() {
    const isChild = document.getElementById("odontogram_child").checked;
    const hitboxes = document.querySelectorAll(".tooth-hitbox");
    const manualSelect = document.getElementById("manualToothSelect");
    
    // Update Manual Select Options
    if (isChild) {
        manualSelect.innerHTML = `
            <option value="">+ Select Tooth Manually</option>
            <optgroup label="Upper Right">
                <option value="55">Tooth 55</option><option value="54">Tooth 54</option><option value="53">Tooth 53</option><option value="52">Tooth 52</option><option value="51">Tooth 51</option>
            </optgroup>
            <optgroup label="Upper Left">
                <option value="61">Tooth 61</option><option value="62">Tooth 62</option><option value="63">Tooth 63</option><option value="64">Tooth 64</option><option value="65">Tooth 65</option>
            </optgroup>
            <optgroup label="Lower Left">
                <option value="71">Tooth 71</option><option value="72">Tooth 72</option><option value="73">Tooth 73</option><option value="74">Tooth 74</option><option value="75">Tooth 75</option>
            </optgroup>
            <optgroup label="Lower Right">
                <option value="81">Tooth 81</option><option value="82">Tooth 82</option><option value="83">Tooth 83</option><option value="84">Tooth 84</option><option value="85">Tooth 85</option>
            </optgroup>
        `;
    } else {
        manualSelect.innerHTML = `
            <option value="">+ Select Tooth Manually</option>
            <optgroup label="Upper Right">
                <option value="18">Tooth 18</option><option value="17">Tooth 17</option><option value="16">Tooth 16</option><option value="15">Tooth 15</option>
                <option value="14">Tooth 14</option><option value="13">Tooth 13</option><option value="12">Tooth 12</option><option value="11">Tooth 11</option>
            </optgroup>
            <optgroup label="Upper Left">
                <option value="21">Tooth 21</option><option value="22">Tooth 22</option><option value="23">Tooth 23</option><option value="24">Tooth 24</option>
                <option value="25">Tooth 25</option><option value="26">Tooth 26</option><option value="27">Tooth 27</option><option value="28">Tooth 28</option>
            </optgroup>
            <optgroup label="Lower Left">
                <option value="31">Tooth 31</option><option value="32">Tooth 32</option><option value="33">Tooth 33</option><option value="34">Tooth 34</option>
                <option value="35">Tooth 35</option><option value="36">Tooth 36</option><option value="37">Tooth 37</option><option value="38">Tooth 38</option>
            </optgroup>
            <optgroup label="Lower Right">
                <option value="41">Tooth 41</option><option value="42">Tooth 42</option><option value="43">Tooth 43</option><option value="44">Tooth 44</option>
                <option value="45">Tooth 45</option><option value="46">Tooth 46</option><option value="47">Tooth 47</option><option value="48">Tooth 48</option>
            </optgroup>
        `;
    }

    
    const mapping = {
        "11": "51", "12": "52", "13": "53", "14": "54", "15": "55",
        "21": "61", "22": "62", "23": "63", "24": "64", "25": "65",
        "31": "71", "32": "72", "33": "73", "34": "74", "35": "75",
        "41": "81", "42": "82", "43": "83", "44": "84", "45": "85"
    };
    
    const hiddenInChild = ["16", "17", "18", "26", "27", "28", "36", "37", "38", "46", "47", "48"];
    
    hitboxes.forEach(box => {
        let currentTooth = box.getAttribute("data-tooth");
        let originalTooth = box.getAttribute("data-original-tooth") || currentTooth;
        
        if (!box.hasAttribute("data-original-tooth")) {
            box.setAttribute("data-original-tooth", originalTooth);
        }
        
        if (isChild) {
            if (hiddenInChild.includes(originalTooth)) {
                box.style.display = "none";
            } else {
                box.style.display = "block";
                let newTooth = mapping[originalTooth];
                box.setAttribute("data-tooth", newTooth);
                box.setAttribute("title", "Tooth " + newTooth);
                box.setAttribute("onclick", "openToothDiagnosisModal(" + newTooth + ")");
            }
        } else {
            box.style.display = "block";
            box.setAttribute("data-tooth", originalTooth);
            box.setAttribute("title", "Tooth " + originalTooth);
            box.setAttribute("onclick", "openToothDiagnosisModal(" + originalTooth + ")");
        }
    });
}
</script>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
