-- Run this file on your existing job_portal database.
-- It will not delete your current users, jobs, applications, categories, or profile data.

CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('seeker', 'employer', 'recruiter', 'admin') NOT NULL,
    profile_pic VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP PROCEDURE IF EXISTS add_column_if_missing;
DELIMITER $$
CREATE PROCEDURE add_column_if_missing(
    IN p_table_name VARCHAR(64),
    IN p_column_name VARCHAR(64),
    IN p_column_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table_name
          AND COLUMN_NAME = p_column_name
    ) THEN
        SET @sql_text = CONCAT('ALTER TABLE `', p_table_name, '` ADD COLUMN `', p_column_name, '` ', p_column_definition);
        PREPARE stmt FROM @sql_text;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

CALL add_column_if_missing('users', 'name', 'VARCHAR(100) NULL');
CALL add_column_if_missing('users', 'email', 'VARCHAR(150) NULL');
CALL add_column_if_missing('users', 'password_hash', 'VARCHAR(255) NULL');
CALL add_column_if_missing('users', 'phone', 'VARCHAR(20) NULL');
CALL add_column_if_missing('users', 'role', 'ENUM(''seeker'', ''employer'', ''recruiter'', ''admin'') NULL');
CALL add_column_if_missing('users', 'profile_pic', 'VARCHAR(255) NULL');
CALL add_column_if_missing('users', 'is_active', 'TINYINT(1) DEFAULT 1');
CALL add_column_if_missing('users', 'is_verified', 'TINYINT(1) DEFAULT 0');
CALL add_column_if_missing('users', 'created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

ALTER TABLE users MODIFY role ENUM('seeker', 'employee', 'employer', 'recruiter', 'admin') NULL;
UPDATE users SET role = 'seeker' WHERE role IS NULL OR role = '';
UPDATE users SET role = 'employer' WHERE role = 'employee';
ALTER TABLE users MODIFY role ENUM('seeker', 'employer', 'recruiter', 'admin') NOT NULL;
UPDATE users SET is_active = 1 WHERE is_active IS NULL;
UPDATE users SET is_verified = 1 WHERE role IN ('admin', 'seeker');

CREATE TABLE IF NOT EXISTS seeker_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    headline VARCHAR(255),
    summary TEXT,
    skills TEXT,
    years_experience INT DEFAULT 0,
    education_level VARCHAR(100),
    current_salary DECIMAL(10,2) DEFAULT 0,
    expected_salary DECIMAL(10,2) DEFAULT 0,
    preferred_location VARCHAR(255),
    resume_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CALL add_column_if_missing('seeker_profiles', 'user_id', 'INT NULL');
CALL add_column_if_missing('seeker_profiles', 'headline', 'VARCHAR(255) NULL');
CALL add_column_if_missing('seeker_profiles', 'summary', 'TEXT NULL');
CALL add_column_if_missing('seeker_profiles', 'skills', 'TEXT NULL');
CALL add_column_if_missing('seeker_profiles', 'years_experience', 'INT DEFAULT 0');
CALL add_column_if_missing('seeker_profiles', 'education_level', 'VARCHAR(100) NULL');
CALL add_column_if_missing('seeker_profiles', 'current_salary', 'DECIMAL(10,2) DEFAULT 0');
CALL add_column_if_missing('seeker_profiles', 'expected_salary', 'DECIMAL(10,2) DEFAULT 0');
CALL add_column_if_missing('seeker_profiles', 'preferred_location', 'VARCHAR(255) NULL');
CALL add_column_if_missing('seeker_profiles', 'resume_path', 'VARCHAR(255) NULL');

CREATE TABLE IF NOT EXISTS employer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    company_name VARCHAR(150),
    industry VARCHAR(100),
    company_size VARCHAR(50),
    description TEXT,
    website VARCHAR(255),
    address VARCHAR(255),
    logo_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CALL add_column_if_missing('employer_profiles', 'user_id', 'INT NULL');
CALL add_column_if_missing('employer_profiles', 'company_name', 'VARCHAR(150) NULL');
CALL add_column_if_missing('employer_profiles', 'industry', 'VARCHAR(100) NULL');
CALL add_column_if_missing('employer_profiles', 'company_size', 'VARCHAR(50) NULL');
CALL add_column_if_missing('employer_profiles', 'description', 'TEXT NULL');
CALL add_column_if_missing('employer_profiles', 'website', 'VARCHAR(255) NULL');
CALL add_column_if_missing('employer_profiles', 'address', 'VARCHAR(255) NULL');
CALL add_column_if_missing('employer_profiles', 'logo_path', 'VARCHAR(255) NULL');

CREATE TABLE IF NOT EXISTS recruiter_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    agency_name VARCHAR(150),
    specialization VARCHAR(150),
    description TEXT,
    website VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CALL add_column_if_missing('recruiter_profiles', 'user_id', 'INT NULL');
CALL add_column_if_missing('recruiter_profiles', 'agency_name', 'VARCHAR(150) NULL');
CALL add_column_if_missing('recruiter_profiles', 'specialization', 'VARCHAR(150) NULL');
CALL add_column_if_missing('recruiter_profiles', 'description', 'TEXT NULL');
CALL add_column_if_missing('recruiter_profiles', 'website', 'VARCHAR(255) NULL');

CREATE TABLE IF NOT EXISTS admin_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL,
    target_user_id INT NOT NULL,
    action_type VARCHAR(50) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (target_user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CALL add_column_if_missing('categories', 'name', 'VARCHAR(100) NULL');
CALL add_column_if_missing('categories', 'description', 'VARCHAR(255) NULL');
CALL add_column_if_missing('categories', 'created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    category_id INT NULL,
    employer_id INT NOT NULL,
    recruiter_id INT NULL,
    job_type VARCHAR(50) DEFAULT 'Full-time',
    location VARCHAR(150),
    status ENUM('active', 'closed', 'draft', 'removed') DEFAULT 'active',
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE SET NULL
);
CALL add_column_if_missing('jobs', 'title', 'VARCHAR(150) NULL');
CALL add_column_if_missing('jobs', 'description', 'TEXT NULL');
CALL add_column_if_missing('jobs', 'category_id', 'INT NULL');
CALL add_column_if_missing('jobs', 'employer_id', 'INT NULL');
CALL add_column_if_missing('jobs', 'recruiter_id', 'INT NULL');
CALL add_column_if_missing('jobs', 'job_type', 'VARCHAR(50) DEFAULT ''Full-time''');
CALL add_column_if_missing('jobs', 'location', 'VARCHAR(150) NULL');
CALL add_column_if_missing('jobs', 'status', 'ENUM(''active'', ''closed'', ''draft'', ''removed'') DEFAULT ''active''');
CALL add_column_if_missing('jobs', 'is_featured', 'TINYINT(1) DEFAULT 0');
CALL add_column_if_missing('jobs', 'created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
UPDATE jobs SET status = 'active' WHERE status IS NULL OR status = '' OR status NOT IN ('active', 'closed', 'draft', 'removed');
ALTER TABLE jobs MODIFY status ENUM('active', 'closed', 'draft', 'removed') DEFAULT 'active';
UPDATE jobs SET status = 'active' WHERE status IS NULL OR status = '';

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    recruiter_id INT NULL,
    status ENUM('submitted', 'shortlisted', 'rejected', 'hired') DEFAULT 'submitted',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE SET NULL
);
CALL add_column_if_missing('applications', 'job_id', 'INT NULL');
CALL add_column_if_missing('applications', 'seeker_id', 'INT NULL');
CALL add_column_if_missing('applications', 'recruiter_id', 'INT NULL');
CALL add_column_if_missing('applications', 'status', 'ENUM(''submitted'', ''shortlisted'', ''rejected'', ''hired'') DEFAULT ''submitted''');
CALL add_column_if_missing('applications', 'applied_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
UPDATE applications SET status = 'submitted' WHERE status IS NULL OR status = '' OR status NOT IN ('submitted', 'shortlisted', 'rejected', 'hired');
ALTER TABLE applications MODIFY status ENUM('submitted', 'shortlisted', 'rejected', 'hired') DEFAULT 'submitted';
UPDATE applications SET status = 'submitted' WHERE status IS NULL OR status = '';

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submitter_id INT NULL,
    subject_id INT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'resolved') DEFAULT 'open',
    admin_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME NULL,
    FOREIGN KEY (submitter_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (subject_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS platform_policies (
    policy_key VARCHAR(100) PRIMARY KEY,
    policy_value VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (name, email, password_hash, phone, role, profile_pic, is_active, is_verified)
SELECT
    'Admin',
    'admin@gmail.com',
    '$2y$12$4Yiap1uq2bHBkc6cM2AiROXwGQRHJZ3w8.KHnoufGsCmt2zvddVbS',
    '01700000000',
    'admin',
    '',
    1,
    1
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@gmail.com');

INSERT INTO seeker_profiles (user_id)
SELECT u.id FROM users u
WHERE u.role = 'seeker'
  AND NOT EXISTS (SELECT 1 FROM seeker_profiles sp WHERE sp.user_id = u.id);

INSERT INTO employer_profiles (user_id)
SELECT u.id FROM users u
WHERE u.role = 'employer'
  AND NOT EXISTS (SELECT 1 FROM employer_profiles ep WHERE ep.user_id = u.id);

INSERT INTO recruiter_profiles (user_id)
SELECT u.id FROM users u
WHERE u.role = 'recruiter'
  AND NOT EXISTS (SELECT 1 FROM recruiter_profiles rp WHERE rp.user_id = u.id);

INSERT INTO platform_policies (policy_key, policy_value) VALUES
('max_jobs_per_employer', '10'),
('max_active_applications_per_seeker', '20'),
('resume_visibility_default', 'private')
ON DUPLICATE KEY UPDATE policy_value = VALUES(policy_value);

DROP PROCEDURE IF EXISTS add_column_if_missing;
