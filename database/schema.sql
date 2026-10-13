-- ====================================================
-- OAUTHC Dental Centre Complete Database Schema
-- ====================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `access_level` enum('super','support') NOT NULL DEFAULT 'support',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `practitioner_id` int(11) NOT NULL,
  `booked_by_user_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled','no_show') NOT NULL DEFAULT 'pending',
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `appointment_type` enum('New Patient','Follow-Up') DEFAULT 'New Patient',
  PRIMARY KEY (`appointment_id`),
  UNIQUE KEY `uq_slot` (`practitioner_id`,`appointment_date`,`appointment_time`),
  KEY `booked_by_user_id` (`booked_by_user_id`),
  KEY `idx_appt_date` (`appointment_date`),
  KEY `idx_appt_patient` (`patient_id`),
  KEY `idx_appt_practitioner` (`practitioner_id`),
  CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`practitioner_id`) REFERENCES `practitioners` (`practitioner_id`),
  CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`booked_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_affected` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `idx_audit_user` (`user_id`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `consultation_notes`;
CREATE TABLE `consultation_notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `practitioner_id` int(11) NOT NULL,
  `presenting_complaint` text NOT NULL,
  `clinical_findings` text DEFAULT NULL,
  `diagnosis` text NOT NULL,
  `treatment_performed` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `follow_up_instructions` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `blood_pressure` varchar(20) DEFAULT NULL,
  `heart_rate` varchar(20) DEFAULT NULL,
  `glucose` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `temperature` varchar(20) DEFAULT NULL,
  `photo_before` varchar(255) DEFAULT NULL,
  `photo_after` varchar(255) DEFAULT NULL,
  `last_cleaning_date` date DEFAULT NULL,
  `gum_health` varchar(100) DEFAULT NULL,
  `plaque_index` varchar(50) DEFAULT NULL,
  `brushing_freq` varchar(50) DEFAULT NULL,
  `flossing_freq` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`note_id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `practitioner_id` (`practitioner_id`),
  KEY `idx_notes_record` (`record_id`),
  CONSTRAINT `consultation_notes_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `dental_records` (`record_id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_notes_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`),
  CONSTRAINT `consultation_notes_ibfk_3` FOREIGN KEY (`practitioner_id`) REFERENCES `practitioners` (`practitioner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `dental_records`;
CREATE TABLE `dental_records` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `medical_history` text DEFAULT NULL,
  `drug_allergies` text DEFAULT NULL,
  `current_medications` text DEFAULT NULL,
  `dental_history` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `patient_id` (`patient_id`),
  CONSTRAINT `dental_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `department_treatments`;
CREATE TABLE `department_treatments` (
  `treatment_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`treatment_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `department_treatments_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `availability` enum('Available','Low','Out of Stock') NOT NULL,
  `qty_in_stock` int(11) NOT NULL DEFAULT 0,
  `qty_in_reorder` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `treatment_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `treatment_title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Paid','Pending','Overdue','Cancelled') NOT NULL DEFAULT 'Pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`invoice_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `patient_id` (`patient_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`message_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) NOT NULL,
  `type` enum('booking_confirmation','reminder_24h','cancellation','system_alert') NOT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `scheduled_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `appointment_id` (`appointment_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`reset_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `patient_number` varchar(30) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`patient_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `patient_number` (`patient_number`),
  KEY `idx_patient_number` (`patient_number`),
  CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `practitioner_availability`;
CREATE TABLE `practitioner_availability` (
  `availability_id` int(11) NOT NULL AUTO_INCREMENT,
  `practitioner_id` int(11) NOT NULL,
  `day_of_week` enum('mon','tue','wed','thu','fri') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `slot_length_mins` int(11) NOT NULL DEFAULT 30,
  PRIMARY KEY (`availability_id`),
  KEY `practitioner_id` (`practitioner_id`),
  CONSTRAINT `practitioner_availability_ibfk_1` FOREIGN KEY (`practitioner_id`) REFERENCES `practitioners` (`practitioner_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `practitioner_experience`;
CREATE TABLE `practitioner_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practitioner_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `employment_type` varchar(50) DEFAULT 'Full-Time',
  `duration_text` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `practitioner_id` (`practitioner_id`),
  CONSTRAINT `practitioner_experience_ibfk_1` FOREIGN KEY (`practitioner_id`) REFERENCES `practitioners` (`practitioner_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `practitioner_feedback`;
CREATE TABLE `practitioner_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practitioner_id` int(11) NOT NULL,
  `patient_name` varchar(150) NOT NULL,
  `feedback_date` date NOT NULL,
  `feedback_text` text NOT NULL,
  `patient_avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `practitioner_id` (`practitioner_id`),
  CONSTRAINT `practitioner_feedback_ibfk_1` FOREIGN KEY (`practitioner_id`) REFERENCES `practitioners` (`practitioner_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `practitioner_tasks`;
CREATE TABLE `practitioner_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practitioner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `task_type` enum('meeting','task') NOT NULL,
  `task_date` date NOT NULL,
  `task_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `practitioner_id` (`practitioner_id`),
  CONSTRAINT `practitioner_tasks_ibfk_1` FOREIGN KEY (`practitioner_id`) REFERENCES `practitioners` (`practitioner_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `practitioners`;
CREATE TABLE `practitioners` (
  `practitioner_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `specialisation` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  `licence_no` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `about` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`practitioner_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `licence_no` (`licence_no`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `practitioners_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `practitioners_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `prescriptions`;
CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL AUTO_INCREMENT,
  `note_id` int(11) NOT NULL,
  `drug_name` varchar(150) NOT NULL,
  `dosage` varchar(50) NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`prescription_id`),
  KEY `note_id` (`note_id`),
  CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `consultation_notes` (`note_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `receptionists`;
CREATE TABLE `receptionists` (
  `receptionist_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `shift` enum('morning','afternoon','full_day') NOT NULL DEFAULT 'full_day',
  PRIMARY KEY (`receptionist_id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `receptionists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tooth_chart_entries`;
CREATE TABLE `tooth_chart_entries` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `note_id` int(11) DEFAULT NULL,
  `tooth_code` varchar(2) NOT NULL,
  `condition_code` text DEFAULT NULL,
  `recommended_treatment` text DEFAULT NULL,
  `percussion_test` varchar(50) DEFAULT NULL,
  `surface` varchar(10) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`entry_id`),
  KEY `note_id` (`note_id`),
  KEY `idx_tooth_record` (`record_id`),
  CONSTRAINT `tooth_chart_entries_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `dental_records` (`record_id`) ON DELETE CASCADE,
  CONSTRAINT `tooth_chart_entries_ibfk_2` FOREIGN KEY (`note_id`) REFERENCES `consultation_notes` (`note_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `treatment_milestones`;
CREATE TABLE `treatment_milestones` (
  `milestone_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `procedure_name` varchar(150) NOT NULL,
  `step_name` varchar(150) NOT NULL,
  `status` enum('Completed','In Progress','Schedule') NOT NULL DEFAULT 'Schedule',
  `scheduled_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `tooth_code` varchar(2) DEFAULT NULL,
  `step_order` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`milestone_id`),
  KEY `record_id` (`record_id`),
  KEY `note_id` (`note_id`),
  CONSTRAINT `treatment_milestones_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `dental_records` (`record_id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_milestones_ibfk_2` FOREIGN KEY (`note_id`) REFERENCES `consultation_notes` (`note_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `treatment_photos`;
CREATE TABLE `treatment_photos` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `note_id` int(11) DEFAULT NULL,
  `tooth_code` varchar(10) DEFAULT NULL,
  `photo_type` enum('Before','After','X-Ray','Other') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`photo_id`),
  KEY `patient_id` (`patient_id`),
  CONSTRAINT `treatment_photos_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','practitioner','receptionist','patient') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `force_password_change` tinyint(1) DEFAULT 0,
  `requires_password_change` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
