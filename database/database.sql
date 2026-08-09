-- Database তৈরি করো
CREATE DATABASE student_council;
USE student_council;

-- Students table
CREATE TABLE students (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE,   -- শুধু Student এর জন্য
    department VARCHAR(100),         -- শুধু Student এর জন্য
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- bcrypt hash
    role ENUM('student','counselor','admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Queries table
CREATE TABLE queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,   -- যে Student query করেছে
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending','in_progress','answered') DEFAULT 'pending',
    assigned_counselor_user_id INT NULL, -- কোন Counselor assign হয়েছে
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_user_id) REFERENCES students(user_id),
    FOREIGN KEY (assigned_counselor_user_id) REFERENCES students(user_id)
);

-- Responses table
CREATE TABLE responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_id INT NOT NULL,
    responder_user_id INT NOT NULL, -- Counselor/Admin যিনি উত্তর দিয়েছেন
    response_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (query_id) REFERENCES queries(id),
    FOREIGN KEY (responder_user_id) REFERENCES students(user_id)
);
