-- 1. users — central authentication table (all roles)
CREATE TABLE users (
    user_id         INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50)  NOT NULL UNIQUE,
    email           VARCHAR(100) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('admin','practitioner','receptionist','patient') NOT NULL,
    is_active       TINYINT(1)   NOT NULL DEFAULT 1,
    last_login      DATETIME     NULL,
    created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. password_resets — supports FR1.5
CREATE TABLE password_resets (
    reset_id     INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    token_hash   VARCHAR(255) NOT NULL,
    expires_at   DATETIME NOT NULL,
    used         TINYINT(1) NOT NULL DEFAULT 0,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. departments — the 4 clinical departments + preventive dentistry
CREATE TABLE departments (
    department_id   INT AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(100) NOT NULL,
    description      TEXT NULL
) ENGINE=InnoDB;

-- 4. patients
CREATE TABLE patients (
    patient_id       INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL UNIQUE,
    patient_number   VARCHAR(20) NOT NULL UNIQUE,   -- e.g. OAUTHC/DEN/2026/00001
    full_name        VARCHAR(150) NOT NULL,
    date_of_birth    DATE NOT NULL,
    gender           ENUM('male','female') NOT NULL,
    phone            VARCHAR(20) NOT NULL,
    address           TEXT NOT NULL,
    blood_group      VARCHAR(5) NULL,
    emergency_contact_name  VARCHAR(150) NULL,
    emergency_contact_phone VARCHAR(20) NULL,
    profile_picture  VARCHAR(255) NULL,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. practitioners
CREATE TABLE practitioners (
    practitioner_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL UNIQUE,
    full_name        VARCHAR(150) NOT NULL,
    specialisation   VARCHAR(100) NOT NULL,
    department_id    INT NOT NULL,
    licence_no       VARCHAR(50) NOT NULL UNIQUE,
    phone            VARCHAR(20) NULL,
    hire_date        DATE NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
) ENGINE=InnoDB;

-- 6. receptionists
CREATE TABLE receptionists (
    receptionist_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL UNIQUE,
    full_name        VARCHAR(150) NOT NULL,
    shift            ENUM('morning','afternoon','full_day') NOT NULL DEFAULT 'full_day',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. admins
CREATE TABLE admins (
    admin_id     INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL UNIQUE,
    full_name    VARCHAR(150) NOT NULL,
    access_level ENUM('super','support') NOT NULL DEFAULT 'support',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. practitioner_availability — needed for slot generation/booking logic
CREATE TABLE practitioner_availability (
    availability_id  INT AUTO_INCREMENT PRIMARY KEY,
    practitioner_id  INT NOT NULL,
    day_of_week      ENUM('mon','tue','wed','thu','fri') NOT NULL,
    start_time       TIME NOT NULL,
    end_time         TIME NOT NULL,
    slot_length_mins INT NOT NULL DEFAULT 30,
    FOREIGN KEY (practitioner_id) REFERENCES practitioners(practitioner_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. appointments
CREATE TABLE appointments (
    appointment_id   INT AUTO_INCREMENT PRIMARY KEY,
    patient_id       INT NOT NULL,
    practitioner_id  INT NOT NULL,
    booked_by_user_id INT NOT NULL,          -- patient self-booked OR receptionist
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status           ENUM('pending','confirmed','completed','cancelled','no_show') NOT NULL DEFAULT 'pending',
    reason           VARCHAR(255) NULL,
    notes            TEXT NULL,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (practitioner_id) REFERENCES practitioners(practitioner_id),
    FOREIGN KEY (booked_by_user_id) REFERENCES users(user_id),
    UNIQUE KEY uq_slot (practitioner_id, appointment_date, appointment_time)  -- prevents double-booking
) ENGINE=InnoDB;

-- 10. dental_records — one persistent record per patient
CREATE TABLE dental_records (
    record_id           INT AUTO_INCREMENT PRIMARY KEY,
    patient_id           INT NOT NULL UNIQUE,
    medical_history       TEXT NULL,
    drug_allergies         TEXT NULL,
    current_medications    TEXT NULL,
    dental_history          TEXT NULL,
    created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 11. consultation_notes — per-appointment clinical documentation
CREATE TABLE consultation_notes (
    note_id               INT AUTO_INCREMENT PRIMARY KEY,
    record_id             INT NOT NULL,
    appointment_id        INT NOT NULL,
    practitioner_id       INT NOT NULL,
    presenting_complaint  TEXT NOT NULL,
    clinical_findings     TEXT NULL,
    diagnosis             TEXT NOT NULL,
    treatment_performed   TEXT NULL,
    treatment_plan        TEXT NULL,
    follow_up_instructions TEXT NULL,
    photo_before          VARCHAR(255) NULL,
    photo_after           VARCHAR(255) NULL,
    created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES dental_records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id),
    FOREIGN KEY (practitioner_id) REFERENCES practitioners(practitioner_id)
) ENGINE=InnoDB;

-- 12. tooth_chart_entries — FDI two-digit notation (11–48 permanent, 51–85 primary)
CREATE TABLE tooth_chart_entries (
    entry_id       INT AUTO_INCREMENT PRIMARY KEY,
    record_id      INT NOT NULL,
    note_id        INT NULL,
    tooth_code     VARCHAR(2) NOT NULL,
    condition_code VARCHAR(20) NOT NULL,
    surface        VARCHAR(10) NULL,
    notes          VARCHAR(255) NULL,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES dental_records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (note_id) REFERENCES consultation_notes(note_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 13. prescriptions
CREATE TABLE prescriptions (
    prescription_id  INT AUTO_INCREMENT PRIMARY KEY,
    note_id          INT NOT NULL,
    drug_name        VARCHAR(150) NOT NULL,
    dosage           VARCHAR(50) NOT NULL,
    frequency        VARCHAR(50) NOT NULL,
    duration         VARCHAR(50) NOT NULL,
    special_instructions TEXT NULL,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (note_id) REFERENCES consultation_notes(note_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 14. treatment_milestones
CREATE TABLE treatment_milestones (
    milestone_id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    note_id INT NOT NULL,
    procedure_name VARCHAR(150) NOT NULL,
    step_name VARCHAR(150) NOT NULL,
    status ENUM('Completed', 'In Progress', 'Schedule') NOT NULL DEFAULT 'Schedule',
    scheduled_date DATE NULL,
    completed_date DATE NULL,
    tooth_code VARCHAR(2) NULL,
    step_order INT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES dental_records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (note_id) REFERENCES consultation_notes(note_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 15. notifications
CREATE TABLE notifications (
    notification_id  INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id    INT NOT NULL,
    type              ENUM('booking_confirmation','reminder_24h','cancellation','system_alert') NOT NULL,
    recipient_email   VARCHAR(100) NOT NULL,
    message           TEXT NOT NULL,
    status            ENUM('pending','sent','failed') NOT NULL DEFAULT 'pending',
    scheduled_at      DATETIME NOT NULL,
    sent_at           DATETIME NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 15. audit_log
CREATE TABLE audit_log (
    log_id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NULL,
    action           VARCHAR(100) NOT NULL,
    table_affected   VARCHAR(50) NULL,
    record_id        INT NULL,
    details           TEXT NULL,
    ip_address        VARCHAR(45) NULL,
    created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Indexes
CREATE INDEX idx_appt_date ON appointments(appointment_date);
CREATE INDEX idx_appt_patient ON appointments(patient_id);
CREATE INDEX idx_appt_practitioner ON appointments(practitioner_id);
CREATE INDEX idx_patient_number ON patients(patient_number);
CREATE INDEX idx_notes_record ON consultation_notes(record_id);
CREATE INDEX idx_tooth_record ON tooth_chart_entries(record_id);
CREATE INDEX idx_audit_user ON audit_log(user_id);
