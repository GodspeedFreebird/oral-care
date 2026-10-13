-- Alter existing Invoices table to support line items and new UI fields
ALTER TABLE invoices ADD COLUMN due_date DATE NULL AFTER invoice_date;
ALTER TABLE invoices ADD COLUMN subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER amount;
ALTER TABLE invoices ADD COLUMN tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER subtotal;
ALTER TABLE invoices ADD COLUMN payment_method VARCHAR(50) NULL AFTER status;
ALTER TABLE invoices ADD COLUMN notes TEXT NULL AFTER payment_method;

-- Rename 'amount' to 'total_amount' if we want, or keep 'amount' as the total. We'll just use 'amount' as the total.
-- Wait, let's keep 'amount' as the total, we'll just populate it.
-- Alter 'status' ENUM to include 'Cancelled' just in case.
ALTER TABLE invoices MODIFY COLUMN status ENUM('Paid', 'Pending', 'Overdue', 'Cancelled') NOT NULL DEFAULT 'Pending';

-- Create Invoice Items Table
CREATE TABLE IF NOT EXISTS invoice_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT(11) NOT NULL,
    treatment_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE
);

-- Create Treatment Photos Table (for before/after)
CREATE TABLE IF NOT EXISTS treatment_photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT(11) NOT NULL,
    note_id INT NULL, -- linked to consultation
    tooth_code VARCHAR(10) NULL,
    photo_type ENUM('Before', 'After', 'X-Ray', 'Other') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    description TEXT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
);

-- Add Vitals to Consultation Notes
ALTER TABLE consultation_notes ADD COLUMN blood_pressure VARCHAR(20) NULL;
ALTER TABLE consultation_notes ADD COLUMN heart_rate VARCHAR(20) NULL;
ALTER TABLE consultation_notes ADD COLUMN glucose VARCHAR(20) NULL;
ALTER TABLE consultation_notes ADD COLUMN weight VARCHAR(20) NULL;
ALTER TABLE consultation_notes ADD COLUMN temperature VARCHAR(20) NULL;
