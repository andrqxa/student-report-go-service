-- Minimal seed data for Problem 7

INSERT INTO roles (id, name)
VALUES
    (1, 'Admin'),
    (2, 'Teacher'),
    (3, 'Student')
    ON CONFLICT (id) DO NOTHING;

-- Optional reporter (e.g., admin who created the student)
INSERT INTO users (id, name, email, role_id, is_active)
VALUES (100, 'Admin Reporter', 'admin@school-admin.com', 1, TRUE)
    ON CONFLICT (id) DO NOTHING;

-- Student record expected by queries (role_id = 3 is hardcoded in list query)
INSERT INTO users (id, name, email, role_id, is_active, reporter_id, password, is_email_verified)
VALUES (1, 'Test Student', 'student@test.local', 3, TRUE, 100, 'dev', TRUE)
    ON CONFLICT (id) DO NOTHING;

INSERT INTO user_profiles (
    user_id, phone, gender, dob, class_name, section_name, roll,
    father_name, father_phone, current_address, permanent_address, admission_dt
) VALUES (
             1, '+1000000000', 'male', '2005-01-15', 'Grade 10', 'A', 101,
             'Robert Doe', '+1000000001', 'Test Address 1', 'Test Address 2', '2024-01-01'
         )
    ON CONFLICT (user_id) DO NOTHING;
