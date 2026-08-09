-- Student account
INSERT INTO students (student_id, department, name, email, password, role, created_at)
VALUES ('S001', 'CSE', 'Rahim', 'rahim@student.com', 
        '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 
        'student', NOW());

-- Counselor account
INSERT INTO students (name, email, password, role, created_at)
VALUES ('Karim', 'karim@counselor.com', 
        '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 
        'counselor', NOW());

-- Admin account
INSERT INTO students (name, email, password, role, created_at)
VALUES ('System Admin', 'admin@portal.com', 
        '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 
        'admin', NOW());
