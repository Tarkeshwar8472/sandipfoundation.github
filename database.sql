CREATE DATABASE IF NOT EXISTS sandip_foundation;
USE sandip_foundation;

CREATE TABLE IF NOT EXISTS admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(150) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    program_name VARCHAR(150) NOT NULL,
    branch_name VARCHAR(150) NOT NULL,
    city_name VARCHAR(120) NOT NULL,
    state_name VARCHAR(120) NOT NULL,
    father_name VARCHAR(150) NOT NULL,
    mother_name VARCHAR(150) NOT NULL,
    email_address VARCHAR(160) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    category_name VARCHAR(30) DEFAULT '',
    last_qualification VARCHAR(150) DEFAULT '',
    tenth_marks VARCHAR(20) NOT NULL,
    twelfth_marks VARCHAR(20) DEFAULT '',
    address_text TEXT NOT NULL,
    hostel_required VARCHAR(10) DEFAULT '',
    parent_mobile VARCHAR(20) DEFAULT '',
    whatsapp_number VARCHAR(20) DEFAULT '',
    session_year VARCHAR(20) DEFAULT '2026-27',
    created_at DATETIME NOT NULL
);
