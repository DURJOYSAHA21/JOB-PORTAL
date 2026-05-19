-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 07:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_portal_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `recruiter_id` int(11) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `status` enum('submitted','reviewed','shortlisted','interview','rejected','withdrawn') NOT NULL DEFAULT 'submitted',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `seeker_id`, `recruiter_id`, `cover_letter`, `resume_path`, `status`, `applied_at`) VALUES
(14, 2, 1, NULL, 'I am very interested in this position. I have 5 years of experience in this field and believe I would be a great fit for your team.', 'resume_applicant_1.pdf', 'submitted', '2026-05-10 03:30:00'),
(15, 2, 2, NULL, 'With my background and skills, I am confident I can contribute significantly to your organization.', 'resume_applicant_2.pdf', 'reviewed', '2026-05-12 08:15:00'),
(16, 2, 3, NULL, 'I have been following your company for years and would love to be part of your team.', 'resume_applicant_3.pdf', 'shortlisted', '2026-05-08 05:00:00'),
(17, 2, 4, NULL, 'This role excites me because it matches my career goals. I have the skills to excel.', 'resume_applicant_4.pdf', 'interview', '2026-05-05 10:45:00'),
(18, 2, 5, NULL, 'I am eager to bring my expertise to your team. Thank you for considering my application.', 'resume_applicant_5.pdf', 'rejected', '2026-05-01 04:00:00'),
(19, 2, 6, NULL, 'Please find my application for this position. I believe my qualifications make me an ideal candidate.', 'resume_applicant_6.pdf', 'reviewed', '2026-05-18 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Information Technology', 'Software development, IT support, network administration, cybersecurity, and related tech roles'),
(2, 'Healthcare', 'Medical, nursing, pharmacy, healthcare administration, and allied health positions'),
(3, 'Finance & Accounting', 'Banking, investment, accounting, auditing, financial analysis, and insurance roles');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `submitter_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','resolved') NOT NULL DEFAULT 'open',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `submitter_id`, `subject_id`, `description`, `status`, `admin_note`, `created_at`) VALUES
(1, 25, 32, 'Fygfkjzs ugvfhjaihzfjn zjovjosjdoug', 'open', NULL, '2026-05-18 13:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `employer_profiles`
--

CREATE TABLE `employer_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `company_size` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employer_profiles`
--

INSERT INTO `employer_profiles` (`id`, `user_id`, `company_name`, `industry`, `company_size`, `description`, `website`, `address`, `logo_path`) VALUES
(1, 8, 'Durjoy', 'fd', '201-500', 'dedew', '', '', 'Array'),
(2, 9, 'ihi', '', '', '', '', '', 'Array'),
(3, 10, 'nkl', '', '', '', '', '', 'Array'),
(4, 13, 'ihi', '', '', '', '', '', 'Array'),
(5, 14, 'hijo', '', '', '', '', '', 'Array'),
(6, 15, 'iog', '', '', '', '', '', 'Array'),
(8, 16, 'sf', '', '', '', '', '', 'Array'),
(10, 18, 'dbugi', '', '', '', '', '', ''),
(11, 19, 'dkni', '', '', '', '', '', ''),
(12, 20, 'abu', '', '', '', '', '', ''),
(13, 21, 'nig', '', '', '', '', '', ''),
(14, 22, 'hiho', '', '', '', '', '', ''),
(16, 24, 'ihi', '', '', '', '', '', ''),
(17, 25, 'DJO', 'BGIS', '501-1000', '', 'https://www.w3schools.com/js/DEFAULT.asp', '', 'logo_25_6a0c02dee28ca.jpg'),
(18, 35, 'hiho', '', '', '', '', '', ''),
(23, 36, 'ihi', '', '', '', '', '', ''),
(24, 37, 'nibvw', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `recruiter_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `job_type` enum('full-time','part-time','remote','contract') NOT NULL,
  `experience_level` enum('entry','mid','senior') NOT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('active','closed','draft') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `employer_id`, `recruiter_id`, `category_id`, `title`, `description`, `requirements`, `benefits`, `salary_min`, `salary_max`, `location`, `job_type`, `experience_level`, `deadline`, `status`, `is_featured`, `created_at`) VALUES
(2, 25, NULL, 3, 'Senif', 'buiowr', '', '', 89203.00, 90038.00, 'Tangail', 'part-time', 'entry', '2026-05-26', 'active', 0, '2026-05-18 09:42:34'),
(3, 25, 1, 1, 'Senior React Developer', 'Looking for an experienced React developer to lead frontend development for our client platform. You will architect and build complex user interfaces.', '5+ years React, TypeScript, Redux, REST APIs, GraphQL', 'Health insurance, 401k, Remote work, Annual bonus', 90000.00, 130000.00, 'Remote', 'full-time', 'senior', '2026-07-01', 'active', 0, '2026-05-02 04:00:00'),
(4, 25, 1, 1, 'DevOps Engineer', 'Seeking DevOps engineer for cloud infrastructure management and CI/CD pipeline optimization across multiple client environments.', 'AWS, Docker, Kubernetes, Terraform, Jenkins, Linux', 'Flexible hours, Stock options, Health coverage, Conference budget', 85000.00, 120000.00, 'San Francisco, CA', 'full-time', 'mid', '2026-06-15', 'active', 0, '2026-05-05 05:00:00'),
(5, 25, 1, 1, 'Junior QA Tester', 'Entry-level QA tester for manual and automated testing of web applications. Training provided.', 'Basic programming knowledge, Attention to detail, ISTQB certification a plus', 'Paid training, Health insurance, Casual dress code', 45000.00, 60000.00, 'Austin, TX', 'full-time', 'entry', '2026-06-30', 'active', 0, '2026-05-10 08:00:00'),
(9, 25, 1, 1, 'Senior React Developer', 'Looking for an experienced React developer to lead frontend development for our client platform.', '5+ years React, TypeScript, Redux, REST APIs, GraphQL', 'Health insurance, 401k, Remote work, Annual bonus', 90000.00, 130000.00, 'Remote', 'full-time', 'senior', '2026-07-01', 'active', 0, '2026-05-02 04:00:00'),
(10, 25, 1, 1, 'DevOps Engineer', 'Seeking DevOps engineer for cloud infrastructure management and CI/CD pipeline optimization.', 'AWS, Docker, Kubernetes, Terraform, Jenkins, Linux', 'Flexible hours, Stock options, Health coverage', 85000.00, 120000.00, 'San Francisco, CA', 'full-time', 'mid', '2026-06-15', 'active', 0, '2026-05-05 05:00:00'),
(11, 25, 1, 1, 'Junior QA Tester', 'Entry-level QA tester for manual and automated testing of web applications.', 'Basic programming knowledge, Attention to detail', 'Paid training, Health insurance, Casual dress code', 45000.00, 60000.00, 'Austin, TX', 'full-time', 'entry', '2026-06-30', 'active', 0, '2026-05-10 08:00:00'),
(12, 25, 2, 3, 'Financial Analyst', 'Financial analyst needed for budgeting, forecasting, and financial modeling.', '3+ years financial analysis, CFA preferred, Advanced Excel, SAP', 'Bonus structure, Professional development, Health benefits', 65000.00, 85000.00, 'New York, NY', 'full-time', 'mid', '2026-06-20', 'active', 0, '2026-05-12 03:00:00'),
(13, 25, 2, 3, 'Senior Accountant', 'Senior accountant to manage client accounts, prepare tax filings, and oversee audits.', 'CPA required, 5+ years public accounting, QuickBooks, GAAP expertise', '401k matching, Profit sharing, Health & dental', 75000.00, 95000.00, 'Chicago, IL', 'full-time', 'senior', '2026-07-10', 'active', 0, '2026-05-14 08:00:00'),
(14, 25, 2, 3, 'Payroll Specialist', 'Payroll specialist to process multi-state payroll for 500+ employees using ADP.', '2+ years payroll experience, ADP Workforce Now, Multi-state tax knowledge', 'Health insurance, 401k, Paid time off', 50000.00, 65000.00, 'Remote', 'full-time', 'mid', '2026-06-15', 'active', 0, '2026-05-16 05:00:00'),
(15, 25, 3, 2, 'Registered Nurse', 'RN needed for telehealth patient care coordination and virtual consultations.', 'Valid RN license, BSN preferred, 2+ years clinical experience', 'Signing bonus, Flexible scheduling, Health insurance', 70000.00, 90000.00, 'Chicago, IL', 'full-time', 'entry', '2026-07-15', 'active', 0, '2026-05-16 02:00:00'),
(16, 25, 3, 2, 'Medical Lab Technician', 'Lab technician needed for clinical laboratory testing and analysis.', 'ASCP certification, 1+ year lab experience, Attention to detail', 'Health insurance, Paid time off, Shift differential', 50000.00, 65000.00, 'Boston, MA', 'full-time', 'entry', '2026-06-25', 'active', 0, '2026-05-18 04:00:00'),
(17, 25, 3, 2, 'Physical Therapist', 'Physical therapist needed for outpatient rehabilitation clinic.', 'DPT degree, State license, 2+ years experience', 'CE allowance, Health benefits, Flexible schedule', 75000.00, 95000.00, 'Seattle, WA', 'full-time', 'mid', '2026-07-01', 'active', 0, '2026-05-20 03:30:00'),
(18, 37, NULL, 3, 'Software', 'ihdowofbwjewkr', '', '', 89203.00, 90038.00, 'Tangail', 'part-time', 'mid', '2026-05-26', 'active', 0, '2026-05-18 22:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `job_alerts`
--

CREATE TABLE `job_alerts` (
  `id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `keyword` varchar(150) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `job_type` enum('full-time','part-time','remote','contract') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `body` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `application_id`, `body`, `sent_at`, `is_read`) VALUES
(1, 25, 31, 19, 'Hello Mr. Frank you are shortlisted for the job congo bro congo', '2026-05-18 12:11:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_clients`
--

CREATE TABLE `recruiter_clients` (
  `id` int(11) NOT NULL,
  `recruiter_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `company_name_override` varchar(150) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter_clients`
--

INSERT INTO `recruiter_clients` (`id`, `recruiter_id`, `employer_id`, `company_name_override`, `added_at`) VALUES
(7, 1, 17, NULL, '2026-05-01 04:00:00'),
(8, 2, 17, NULL, '2026-05-10 08:30:00'),
(9, 3, 17, NULL, '2026-05-15 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_outreach`
--

CREATE TABLE `recruiter_outreach` (
  `id` int(11) NOT NULL,
  `recruiter_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('sent','read','responded') NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_profiles`
--

CREATE TABLE `recruiter_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_name` varchar(150) NOT NULL,
  `specialization` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter_profiles`
--

INSERT INTO `recruiter_profiles` (`id`, `user_id`, `agency_name`, `specialization`, `description`, `website`) VALUES
(1, 32, 'TalentFind Solutions', 'Information Technology, Engineering', 'Specialized tech recruitment agency with 10+ years of experience placing top engineering talent.', 'https://talentfind.example.com'),
(2, 33, 'HirePro Recruiters', 'Finance & Accounting, Legal', 'Full-service recruitment firm specializing in professional services placements for Fortune 500 companies.', 'https://hirepro.example.com'),
(3, 34, 'Elite Staffing Group', 'Healthcare, Education & Teaching', 'Boutique staffing agency focusing on healthcare and education sectors nationwide.', 'https://elitestaffing.example.com');

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seeker_profiles`
--

CREATE TABLE `seeker_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `headline` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `years_experience` int(11) NOT NULL,
  `education_level` varchar(100) NOT NULL,
  `current_salary` decimal(10,2) DEFAULT NULL,
  `expected_salary` decimal(10,2) DEFAULT NULL,
  `preferred_location` varchar(150) NOT NULL,
  `resume_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seeker_profiles`
--

INSERT INTO `seeker_profiles` (`id`, `user_id`, `headline`, `summary`, `skills`, `years_experience`, `education_level`, `current_salary`, `expected_salary`, `preferred_location`, `resume_path`) VALUES
(1, 26, 'Senior Software Developer', 'Experienced developer with 5+ years in web development', 'PHP,JavaScript,MySQL,React', 5, 'Bachelors', 75000.00, 90000.00, 'New York', 'resume_applicant_1.pdf'),
(2, 27, 'Full Stack Engineer', 'Full stack developer specializing in modern frameworks', 'Node.js,React,MongoDB,Python', 3, 'Masters', 65000.00, 80000.00, 'Remote', 'resume_applicant_2.pdf'),
(3, 28, 'DevOps Engineer', 'Cloud infrastructure and CI/CD specialist', 'AWS,Docker,Kubernetes,Jenkins', 4, 'Bachelors', 80000.00, 95000.00, 'San Francisco', 'resume_applicant_3.pdf'),
(4, 29, 'Frontend Developer', 'Creative frontend developer focused on user experience', 'React,Vue.js,CSS,Sass,TypeScript', 2, 'Bachelors', 55000.00, 70000.00, 'Austin', 'resume_applicant_4.pdf'),
(5, 30, 'Backend Developer', 'Backend systems architect with database expertise', 'Java,Spring,PostgreSQL,Redis', 6, 'Masters', 85000.00, 100000.00, 'Chicago', 'resume_applicant_5.pdf'),
(6, 31, 'Junior Developer', 'Recent graduate eager to learn and grow', 'HTML,CSS,JavaScript,Python', 1, 'Bachelors', 40000.00, 55000.00, 'Boston', 'resume_applicant_6.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('seeker','employer','recruiter','admin') NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `profile_pic`, `is_active`, `is_verified`, `created_at`) VALUES
(1, 'Durjoy', 'durjoy.saha1115@gmail.com', '$2y$10$fLsMgaGvtgr4PI/d6yaydu4crx7GhzZGxjE/l4r4W8R0B2PPcPlOu', '0172665148', 'employer', NULL, 1, 0, '2026-05-15 07:27:34'),
(3, 'Prachi', 'durjoy.saha115@gmail.com', '$2y$10$MUcuHKkBQGszPG0jx3wZZeqyk7vyHVk/GLEXlZgbpaoSXrvHC/YVW', '0172665148', 'employer', NULL, 1, 0, '2026-05-17 21:59:45'),
(5, 'gdui', 'durjoy.saha5@gmail.com', '$2y$10$pxTX29KpJbwSlf24oQWLZ.c0iMMnz./fu9VVjFX77np.4ORsbOnh.', '01726651482', 'employer', NULL, 1, 0, '2026-05-17 22:07:26'),
(7, 'gdui', 'durjoy.sah@gmail.com', '$2y$10$zl6jMaUzRBduJiDlJDG4DepEBnxJyNZaXqbPwe79wX/4zZDZyxbcu', '01726651482', 'employer', NULL, 1, 0, '2026-05-17 22:10:33'),
(8, 'gdui', 'durjoy.saha@gmail.com', '$2y$10$3mhYNx9dV.YYUf34ZYDt0ONR07rlD13TgejdV7kZmC5Uov/pNvTPu', '01726651482', 'employer', NULL, 1, 0, '2026-05-17 23:26:44'),
(9, 'Qwif', 'a@gmail.com', '$2y$10$sENShG6j3W8DTLansdWr0eoc3uQT.IziBRuJX2nPYwfNo.j6PVDGK', '0172665148', 'employer', NULL, 1, 0, '2026-05-18 06:26:09'),
(10, 'Durjoy', 'ss@gmail.com', '$2y$10$Tmh.ptiFRCRenlu3nNjBLOkmUG2KrMr8u9E6CY.x0Rn.8kGktjWzi', '0172665148', 'employer', NULL, 1, 0, '2026-05-18 06:35:35'),
(11, 'Durjoy', 'sjo@gmail.com', '$2y$10$JTnvkknbwp7uN0ORPPX4weFYnPE5nCKZxTwLFGG.EtF/XZfdrKGB.', '0172665148', 'employer', NULL, 1, 0, '2026-05-18 06:37:20'),
(12, 'Durjoy', 'sjojd@gmail.com', '$2y$10$d0vs8XVNcPqizP5JoltJxeGpHNf9q6YMZwWTp3.rT6lDY0FACZFwW', '0172665148', 'employer', NULL, 1, 0, '2026-05-18 06:37:45'),
(13, 'Durjoy', 'sjoqind@gmail.com', '$2y$10$5mGVHTHuzPEckRG7WhzeKuYX.u7bMCqbK4DV6lg5Bh3YR86mo5EjG', '0172665148', 'employer', NULL, 1, 0, '2026-05-18 06:39:16'),
(14, 'Durjoy', 'sjoqnd@gmail.com', '$2y$10$wYLtAtzalDeMh/Odg/UfoO4oB7BRRm8APurkTNqHoKk0j62aWkmYW', '0172665148', 'employer', NULL, 1, 0, '2026-05-18 06:43:37'),
(15, 'Prachi', 'so@gmail.com', '$2y$10$JTOpHXB0CoXw2Y0d3CpSFeTvQTjqNJmxFRQeHFCJSVwyiN/pKoDZa', '01726651482', 'employer', NULL, 1, 0, '2026-05-18 07:08:42'),
(16, 'Prachi', 'swo@gmail.com', '$2y$10$o7ghG5t838zOxtIA5Tx1qeOIA8nuT3OeYDFeP0DjrtjoIOrRzqcwa', '01726651482', 'employer', NULL, 1, 0, '2026-05-18 07:10:20'),
(17, 'Prachi', 'sfjio@gmail.com', '$2y$10$b204A0AXppNYw2p/jOFs9uqYYtKvMxZ0BMftOhTc2J3ik4WEkegsC', '01726651482', 'employer', NULL, 1, 0, '2026-05-18 07:20:06'),
(18, 'Prachi', 'sfjiddo@gmail.com', '$2y$10$Qg3cvSKV52LSBeAoyEA6.uJS8dvp3zWVrJYDx0Blkb4gSqnDZgBFu', '01726651482', 'employer', NULL, 1, 0, '2026-05-18 07:23:37'),
(19, 'Prachi', 'sfjiddos@gmail.com', '$2y$10$Hevq65xfJJt3bNW8SoutNeers.YicQLVFsAjqz1qiPCagBp95eg9K', '01726651482', 'employer', NULL, 1, 0, '2026-05-18 07:24:31'),
(20, 'Prachi', 'sfjiddosf@gmail.com', '$2y$10$bcCRlVgSulai.agUTRIHzeDm7kowylSfXe0BEPA8cbbFm5m0Hvz0C', '01726651482', 'employer', NULL, 1, 0, '2026-05-18 07:25:22'),
(21, 'Prachi', 'sfjiddoasf@gmail.com', '$2y$10$DaZOabS42nHm4.JgxABOCufCKWDx8IfD268KwMXdbaWDFYnjfHJu6', '01726651482', 'employer', NULL, 1, 1, '2026-05-18 07:27:16'),
(22, 'Prachi', 'sfjiddoassf@gmail.com', '$2y$10$XmbgLo1Rp7/u5v9nPDq/Ie/iItaMVA1jFcjfe4Ho1E6zFss4CsYem', '01726651482', 'employer', NULL, 1, 1, '2026-05-18 07:35:20'),
(24, 'Prachi', 'sfjisf@gmail.com', '$2y$10$hVoS.ILVCXUJrOiDlPtLR.j4hRmvxlVWgs2ObXFd/O.PuIZOdrWiG', '01726651482', 'employer', NULL, 1, 1, '2026-05-18 07:39:21'),
(25, 'Prachi', 'sfjis@gmail.com', '$2y$10$zBc7OrQKZyhEdLdZAxjcF.sTZ.Bq0xnhaYAn4aoZic20x9tB0hEYC', '01726651482', 'employer', NULL, 1, 1, '2026-05-18 07:41:52'),
(26, 'Alice Johnson', 'alice.johnson@email.com', '$2y$10$dummyhash', '555-0101', 'seeker', NULL, 1, 1, '2026-05-18 10:59:34'),
(27, 'Bob Williams', 'bob.williams@email.com', '$2y$10$dummyhash', '555-0102', 'seeker', NULL, 1, 1, '2026-05-18 10:59:34'),
(28, 'Carol Martinez', 'carol.martinez@email.com', '$2y$10$dummyhash', '555-0103', 'seeker', NULL, 1, 1, '2026-05-18 10:59:34'),
(29, 'David Brown', 'david.brown@email.com', '$2y$10$dummyhash', '555-0104', 'seeker', NULL, 1, 1, '2026-05-18 10:59:34'),
(30, 'Emma Davis', 'emma.davis@email.com', '$2y$10$dummyhash', '555-0105', 'seeker', NULL, 1, 1, '2026-05-18 10:59:34'),
(31, 'Frank Wilson', 'frank.wilson@email.com', '$2y$10$dummyhash', '555-0106', 'seeker', NULL, 1, 1, '2026-05-18 10:59:34'),
(32, 'Sarah Connor', 'sarah.connor@talentfind.com', '$2y$10$dummyhash', '555-0201', 'recruiter', NULL, 1, 1, '2026-05-18 13:05:06'),
(33, 'Mike Rodriguez', 'mike.rodriguez@hirepro.com', '$2y$10$dummyhash', '555-0202', 'recruiter', NULL, 1, 1, '2026-05-18 13:05:06'),
(34, 'Lisa Chen', 'lisa.chen@elitestaffing.com', '$2y$10$dummyhash', '555-0203', 'recruiter', NULL, 1, 1, '2026-05-18 13:05:06'),
(35, 'Jhonson', 'js@gmail.com', '$2y$10$u.GZRX7LntA1Xo3978zDu.i4jQ2ZImXHT67ZClGFGVThwgE5v2676', '01726651486', 'employer', NULL, 1, 0, '2026-05-18 16:15:06'),
(36, 'John Employer', 'jhon@gmail.com', '$2y$10$7hXayCu9YQhoHzJs97S5keAo9LyqFFJn1mLTRqIn4ulUBLUU2Uuai', '0172665148', 'employer', NULL, 1, 1, '2026-05-18 21:25:41'),
(37, 'John Employer', 'jhonf@gmail.com', '$2y$10$9ZRPyCfc3ln04ELw85aplO7lOcisvj06aELuNYHqyfKbca5AUZGj.', '0172665148', 'employer', NULL, 1, 1, '2026-05-18 21:53:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_id` (`job_id`,`seeker_id`),
  ADD KEY `seeker_id` (`seeker_id`),
  ADD KEY `recruiter_id` (`recruiter_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitter_id` (`submitter_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recruiter_id` (`recruiter_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `jobs_ibfk_1` (`employer_id`);

--
-- Indexes for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seeker_id` (`seeker_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `recruiter_clients`
--
ALTER TABLE `recruiter_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recruiter_id` (`recruiter_id`),
  ADD KEY `employer_id` (`employer_id`);

--
-- Indexes for table `recruiter_outreach`
--
ALTER TABLE `recruiter_outreach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recruiter_id` (`recruiter_id`),
  ADD KEY `seeker_id` (`seeker_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `recruiter_profiles`
--
ALTER TABLE `recruiter_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`job_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `seeker_profiles`
--
ALTER TABLE `seeker_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `job_alerts`
--
ALTER TABLE `job_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recruiter_clients`
--
ALTER TABLE `recruiter_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `recruiter_outreach`
--
ALTER TABLE `recruiter_outreach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruiter_profiles`
--
ALTER TABLE `recruiter_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seeker_profiles`
--
ALTER TABLE `seeker_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `seeker_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`recruiter_id`) REFERENCES `recruiter_profiles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`submitter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  ADD CONSTRAINT `employer_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employer_profiles` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`recruiter_id`) REFERENCES `recruiter_profiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jobs_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD CONSTRAINT `job_alerts_ibfk_1` FOREIGN KEY (`seeker_id`) REFERENCES `seeker_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_alerts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recruiter_clients`
--
ALTER TABLE `recruiter_clients`
  ADD CONSTRAINT `recruiter_clients_ibfk_1` FOREIGN KEY (`recruiter_id`) REFERENCES `recruiter_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recruiter_clients_ibfk_2` FOREIGN KEY (`employer_id`) REFERENCES `employer_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recruiter_outreach`
--
ALTER TABLE `recruiter_outreach`
  ADD CONSTRAINT `recruiter_outreach_ibfk_1` FOREIGN KEY (`recruiter_id`) REFERENCES `recruiter_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recruiter_outreach_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `seeker_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recruiter_outreach_ibfk_3` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recruiter_profiles`
--
ALTER TABLE `recruiter_profiles`
  ADD CONSTRAINT `recruiter_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD CONSTRAINT `saved_jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seeker_profiles`
--
ALTER TABLE `seeker_profiles`
  ADD CONSTRAINT `seeker_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
