-- Insert the 5 Dental Departments
INSERT INTO departments (name, description) VALUES
('Child Dental Health', 'Department handling pediatric dentistry.'),
('Oral and Maxillofacial Surgery', 'Surgical treatment of diseases, injuries and defects.'),
('Oral Medicine and Oral Pathology', 'Diagnosis and non-surgical management of oral diseases.'),
('Restorative Dentistry', 'Diagnosis, management, and treatment of teeth and supporting structures.'),
('Preventive Dentistry', 'Preventing oral disease and promoting oral health.');

-- Insert the initial Admin User
-- Password Hash below is a placeholder for 'password123' (hashed using bcrypt)
INSERT INTO users (username, email, password_hash, role, is_active) VALUES
('admin', 'admin@oauthc.edu.ng', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Insert the corresponding Admin Profile for the initial Admin User
INSERT INTO admins (user_id, full_name, access_level) VALUES
(LAST_INSERT_ID(), 'System Administrator', 'super');
