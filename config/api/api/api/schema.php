CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS admins;

-- Administrative Credentials Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Advanced Student Records Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    department VARCHAR(50) NOT NULL,
    semester INT NOT NULL CHECK (semester BETWEEN 1 AND 8),
    cgpa DECIMAL(3,2) NOT NULL CHECK (cgpa BETWEEN 0.00 AND 10.00),
    attendance_pct DECIMAL(5,2) DEFAULT 85.00 CHECK (attendance_pct BETWEEN 0.00 AND 100.00),
    academic_status ENUM('Good Standing', 'Academic Probation', 'Dean\'s List') DEFAULT 'Good Standing',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default Admin
INSERT INTO admins (username, password_hash, full_name) 
VALUES ('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'System Administrator');

-- Cohort Test Data
INSERT INTO students (roll_number, full_name, email, department, semester, cgpa, attendance_pct, academic_status) VALUES
('NU/CSE/2024/01', 'Sam William', 'samwilliam3@gmail.com', 'CSE', 5, 7.14, 91.50, 'Good Standing'),
('NU/CSE/2024/02', 'Vikramaditya Roy', 'v.roy@nagalanduniv.ac.in', 'CSE', 5, 8.85, 96.00, 'Dean\'s List'),
('NU/ECE/2024/08', 'Ananya Sengupta', 'ananya.s@nagalanduniv.ac.in', 'ECE', 5, 6.40, 72.00, 'Academic Probation'),
('NU/CSE/2024/15', 'Temsula Jamir', 'temsula.j@nagalanduniv.ac.in', 'CSE', 5, 8.92, 94.20, 'Dean\'s List'),
('NU/IT/2024/12', 'Rohan Phukan', 'rohan.p@nagalanduniv.ac.in', 'IT', 5, 7.60, 84.50, 'Good Standing');