-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 19, 2026 at 01:13 AM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `target_user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` enum('submitted','shortlisted','rejected','hired') DEFAULT 'submitted',
  `applied_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `seeker_id`, `recruiter_id`, `cover_letter`, `resume_path`, `status`, `applied_at`) VALUES
(1, 20, 2, NULL, 'my cover letter. weuehdiuwbuwp9egd wed ehdowhdowdewf', '../uploads/resumes/1779128679_Data Com_Section-M_Final_ Marks_Spring 2025-26.pdf', 'submitted', '2026-05-19 02:24:18'),
(2, 1, 2, NULL, 'yo bro iam prachi . iam very happy today', '../uploads/resumes/1779128679_Data Com_Section-M_Final_ Marks_Spring 2025-26.pdf', '', '2026-05-19 02:26:00'),
(3, 2, 2, NULL, 'rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr', '../uploads/resumes/1779128679_Data Com_Section-M_Final_ Marks_Spring 2025-26.pdf', '', '2026-05-19 03:12:47'),
(4, 1, 1, NULL, 'I am very interested in this position.', '../uploads/resumes/sample.pdf', 'rejected', '2026-05-19 03:27:54'),
(5, 2, 1, NULL, 'I have relevant experience for this role.', '../uploads/resumes/sample.pdf', 'shortlisted', '2026-05-19 03:27:54'),
(6, 3, 1, NULL, 'Looking forward to this opportunity.', '../uploads/resumes/sample.pdf', 'rejected', '2026-05-19 03:27:54'),
(7, 4, 1, NULL, 'I believe I am a perfect fit.', '../uploads/resumes/sample.pdf', '', '2026-05-19 03:27:54'),
(8, 5, 1, NULL, 'Applying for this exciting role.', '../uploads/resumes/sample.pdf', 'submitted', '2026-05-19 03:27:54'),
(9, 10, 2, NULL, 'Registered Nurse hello', '../uploads/resumes/1779128679_Data Com_Section-M_Final_ Marks_Spring 2025-26.pdf', '', '2026-05-19 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Software & IT', 'Software development, IT support, systems', '2026-05-18 19:32:29'),
(2, 'Marketing', 'Digital marketing, branding, advertising', '2026-05-18 19:32:29'),
(3, 'Finance', 'Accounting, banking, investments', '2026-05-18 19:32:29'),
(4, 'Engineering', 'Civil, mechanical, electrical', '2026-05-18 19:32:29'),
(5, 'Healthcare', 'Doctors, nurses, medical staff', '2026-05-18 19:32:29'),
(6, 'Education', 'Teachers, trainers, tutors', '2026-05-18 19:32:29'),
(7, 'Sales', 'Business development, retail, B2B', '2026-05-18 19:32:29');

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
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `submitter_id`, `subject_id`, `description`, `status`, `admin_note`, `created_at`) VALUES
(1, 2, 6, 'very rude, very pressure, aaaaa', 'open', NULL, '2026-05-19 05:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `employer_profiles`
--

CREATE TABLE `employer_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `industry` varchar(150) DEFAULT NULL,
  `company_size` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employer_profiles`
--

INSERT INTO `employer_profiles` (`id`, `user_id`, `company_name`, `industry`, `company_size`, `description`, `website`, `address`, `logo_path`) VALUES
(1, 2, 'TechCorp Bangladesh', 'Information Technology', '50-200', 'Leading software development company in Bangladesh', 'www.techcorpbd.com', 'Gulshan, Dhaka', NULL),
(2, 3, 'Digital Marketing BD', 'Marketing & Advertising', '10-50', 'Full-service digital marketing agency', 'www.digitalbd.com', 'Banani, Dhaka', NULL),
(3, 4, 'Finance Solutions Ltd', 'Financial Services', '50-200', 'Providing financial consulting and accounting services', 'www.financesolutionsbd.com', 'Motijheel, Dhaka', NULL),
(4, 5, 'BuildRight Engineering', 'Construction & Engineering', '200-500', 'Leading construction and engineering firm', 'www.buildrightbd.com', 'Uttara, Dhaka', NULL),
(5, 6, 'MediCare Hospital', 'Healthcare', '200-500', 'Modern multi-specialty hospital', 'www.medicarebd.com', 'Dhanmondi, Dhaka', NULL),
(6, 7, 'Bright Future Academy', 'Education', '50-200', 'Premier educational institution', 'www.brightfuture.edu', 'Mirpur, Dhaka', NULL),
(7, 8, 'SalesPro BD', 'Sales & Distribution', '50-200', 'Leading B2B sales organization', 'www.salesprobd.com', 'Tejgaon, Dhaka', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `employer_id` int(11) DEFAULT NULL,
  `recruiter_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `salary_min` decimal(10,2) DEFAULT 0.00,
  `salary_max` decimal(10,2) DEFAULT 0.00,
  `location` varchar(150) DEFAULT NULL,
  `job_type` enum('full-time','part-time','remote','contract') NOT NULL DEFAULT 'full-time',
  `experience_level` enum('entry','mid','senior') NOT NULL DEFAULT 'entry',
  `deadline` date DEFAULT NULL,
  `status` enum('active','closed','draft','removed') DEFAULT 'active',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `employer_id`, `recruiter_id`, `category_id`, `title`, `description`, `requirements`, `benefits`, `salary_min`, `salary_max`, `location`, `job_type`, `experience_level`, `deadline`, `status`, `is_featured`, `created_at`) VALUES
(1, 2, NULL, 1, 'Senior PHP Developer', 'We are looking for an experienced PHP developer to join our team and build scalable web applications.', '5+ years PHP experience, MySQL, JavaScript, MVC frameworks, REST APIs', 'Health insurance, flexible hours, remote Fridays, annual bonus', 80000.00, 120000.00, 'Dhaka, Bangladesh', 'full-time', 'senior', '2025-12-31', 'active', 1, '2026-05-19 01:39:13'),
(2, 2, NULL, 1, 'Junior Frontend Developer', 'Join our startup as a junior frontend developer working with React and modern CSS.', '1+ year HTML/CSS/JavaScript, React basics, responsive design, Git', 'Mentorship program, learning budget, free lunch, casual dress code', 30000.00, 45000.00, 'Chittagong, Bangladesh', 'full-time', 'entry', '2025-11-30', 'active', 1, '2026-05-19 01:39:13'),
(3, 2, NULL, 1, 'Remote Python Backend Engineer', 'Build microservices and APIs for our cloud-based SaaS platform.', '3+ years Python, Django/Flask, PostgreSQL, Docker, AWS', 'Fully remote, flexible schedule, equity options, home office stipend', 90000.00, 130000.00, 'Remote', 'remote', 'mid', '2025-10-15', 'active', 0, '2026-05-19 01:39:13'),
(4, 3, NULL, 2, 'Digital Marketing Specialist', 'Manage and optimize digital marketing campaigns across multiple channels.', '2+ years digital marketing, Google Ads, Facebook Ads, SEO, analytics', 'Performance bonus, gym membership, phone allowance, team outings', 40000.00, 60000.00, 'Dhaka, Bangladesh', 'full-time', 'mid', '2025-11-15', 'active', 0, '2026-05-19 01:39:13'),
(5, 3, NULL, 2, 'Content Writer (Part-Time)', 'Create engaging blog posts, social media content, and email newsletters.', 'Excellent English writing skills, SEO knowledge, WordPress experience', 'Flexible hours, work from home, writing tools provided', 15000.00, 25000.00, 'Remote', 'part-time', 'entry', '2025-09-30', 'active', 0, '2026-05-19 01:39:13'),
(6, 4, NULL, 3, 'Financial Analyst', 'Analyze financial data, create reports, and provide insights for business decisions.', 'BBA/MBA in Finance, 2+ years experience, Excel advanced, QuickBooks', 'Health insurance, provident fund, yearly increment, transport allowance', 50000.00, 75000.00, 'Dhaka, Bangladesh', 'full-time', 'mid', '2025-12-15', 'active', 0, '2026-05-19 01:39:13'),
(7, 4, NULL, 3, 'Accounts Officer', 'Handle day-to-day accounting operations, invoicing, and bank reconciliation.', 'Bachelor degree in Accounting, 1+ year experience, Tally/QuickBooks', 'Two festival bonuses, lunch facility, medical allowance', 25000.00, 35000.00, 'Khulna, Bangladesh', 'full-time', 'entry', '2025-10-31', 'active', 0, '2026-05-19 01:39:13'),
(8, 5, NULL, 4, 'Civil Engineer - Site Supervisor', 'Supervise construction site activities and ensure quality standards.', 'BSc in Civil Engineering, 3+ years site experience, AutoCAD', 'Accommodation provided, site allowance, yearly bonus, transport', 60000.00, 80000.00, 'Sylhet, Bangladesh', 'full-time', 'mid', '2025-11-30', 'active', 0, '2026-05-19 01:39:13'),
(9, 5, NULL, 4, 'Mechanical Engineer', 'Design and develop mechanical systems for industrial equipment.', 'BSc Mechanical Engineering, SolidWorks/CATIA, 4+ years experience', 'Health coverage, overtime pay, training abroad opportunities', 70000.00, 100000.00, 'Dhaka, Bangladesh', 'full-time', 'senior', '2025-12-20', 'active', 0, '2026-05-19 01:39:13'),
(10, 6, NULL, 5, 'Registered Nurse', 'Provide quality patient care in a modern hospital environment.', 'BSc Nursing, valid registration, 2+ years clinical experience', 'Shift allowance, health insurance, accommodation, overtime pay', 35000.00, 50000.00, 'Dhaka, Bangladesh', 'full-time', 'mid', '2025-10-15', 'active', 1, '2026-05-19 01:39:13'),
(11, 6, NULL, 5, 'Medical Officer (Contract)', 'Provide medical consultations and treatment to patients on contract basis.', 'MBBS degree, registered with BMDC, 1+ year experience', 'Flexible schedule, per patient bonus, accommodation', 50000.00, 70000.00, 'Rajshahi, Bangladesh', 'contract', 'entry', '2025-11-30', 'active', 0, '2026-05-19 01:39:13'),
(12, 7, NULL, 6, 'English Teacher', 'Teach English to high school students using modern teaching methods.', 'MA in English, teaching certification, 2+ years teaching experience', 'Summer vacation, pension plan, accommodation allowance', 30000.00, 45000.00, 'Dhaka, Bangladesh', 'full-time', 'mid', '2025-12-31', 'active', 0, '2026-05-19 01:39:13'),
(13, 7, NULL, 6, 'Online Tutor (Part-Time)', 'Provide online tutoring sessions for university students in Computer Science.', 'BSc in CSE, strong communication, online teaching tools familiarity', 'Work from anywhere, flexible hours, performance incentives', 15000.00, 30000.00, 'Remote', 'part-time', 'entry', '2025-09-30', 'active', 0, '2026-05-19 01:39:13'),
(14, 8, NULL, 7, 'Sales Executive', 'Generate leads, meet clients, and close sales deals for our software products.', '1+ year B2B sales experience, excellent communication, target-oriented', 'High commission, travel allowance, mobile bill, yearly tour', 35000.00, 60000.00, 'Dhaka, Bangladesh', 'full-time', 'entry', '2025-11-15', 'active', 0, '2026-05-19 01:39:13'),
(15, 8, NULL, 7, 'Business Development Manager', 'Develop business strategies and manage key client relationships.', '5+ years B2B sales, MBA preferred, strong network in IT industry', 'Company car, fuel allowance, profit sharing, health coverage', 80000.00, 120000.00, 'Dhaka, Bangladesh', 'full-time', 'senior', '2025-12-31', 'active', 1, '2026-05-19 01:39:13'),
(16, 2, NULL, 1, 'Mobile App Developer (Flutter)', 'Build cross-platform mobile applications using Flutter and Dart.', '2+ years Flutter, Dart, Firebase, published apps on stores', 'Remote option, device allowance, conference budget, stock options', 60000.00, 90000.00, 'Remote', 'remote', 'mid', '2025-11-30', 'active', 0, '2026-05-19 01:39:13'),
(17, NULL, 3, 1, 'DevOps Engineer', 'Manage cloud infrastructure and CI/CD pipelines for our growing platform.', '3+ years DevOps, AWS/Azure, Docker, Kubernetes, Jenkins', 'Fully remote, flexible hours, certification paid, home internet allowance', 100000.00, 140000.00, 'Remote', 'remote', 'senior', '2025-10-31', 'active', 0, '2026-05-19 01:39:13'),
(18, 3, NULL, 2, 'Social Media Manager', 'Create and manage social media strategy across Instagram, Facebook, and LinkedIn.', '2+ years social media management, content creation, analytics tools', 'Creative freedom, flexible hours, phone allowance, team events', 30000.00, 50000.00, 'Dhaka, Bangladesh', 'full-time', 'mid', '2025-11-15', 'active', 0, '2026-05-19 01:39:13'),
(19, 5, NULL, 4, 'Electrical Engineer - Contract', 'Design and maintain electrical systems for industrial plant on contract basis.', 'BSc Electrical Engineering, 4+ years experience, PLC programming', 'Contract completion bonus, accommodation, overtime pay', 50000.00, 70000.00, 'Gazipur, Bangladesh', 'contract', 'mid', '2025-12-15', 'active', 0, '2026-05-19 01:39:13'),
(20, 6, NULL, 5, 'Pharmacist', 'Manage pharmacy operations and provide medication counseling to patients.', 'BPharm degree, registered pharmacist, 1+ year experience', 'Health insurance, annual increment, festival bonus', 30000.00, 45000.00, 'Dhaka, Bangladesh', 'full-time', 'entry', '2025-10-30', 'active', 0, '2026-05-19 01:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `job_alerts`
--

CREATE TABLE `job_alerts` (
  `id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `keyword` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_alerts`
--

INSERT INTO `job_alerts` (`id`, `seeker_id`, `keyword`, `category_id`, `location`, `job_type`, `created_at`) VALUES
(1, 2, 'PHP', 4, 'Dhaka, Dhaka, Bangladesh', '', '2026-05-19 04:32:23');

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
  `sent_at` datetime NOT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `application_id`, `body`, `sent_at`, `is_read`) VALUES
(1, 2, 9, NULL, 'welcome', '2026-05-19 04:49:49', 0),
(2, 2, 9, NULL, 'thanks', '2026-05-19 04:54:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 2, '🔔 Test: New job matching your alert: Senior PHP Developer', '../Controller/job_details_controller.php?id=1', 0, '2026-05-19 04:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `platform_policies`
--

CREATE TABLE `platform_policies` (
  `policy_key` varchar(100) NOT NULL,
  `policy_value` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_policies`
--

INSERT INTO `platform_policies` (`policy_key`, `policy_value`, `updated_at`) VALUES
('max_active_applications_per_seeker', '20', '2026-05-18 19:32:30'),
('max_jobs_per_employer', '10', '2026-05-18 19:32:30'),
('resume_visibility_default', 'private', '2026-05-18 19:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_clients`
--

CREATE TABLE `recruiter_clients` (
  `id` int(11) NOT NULL,
  `recruiter_id` int(11) NOT NULL,
  `employer_id` int(11) DEFAULT NULL,
  `company_name_override` varchar(200) DEFAULT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_outreach`
--

CREATE TABLE `recruiter_outreach` (
  `id` int(11) NOT NULL,
  `recruiter_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('sent','read','responded') NOT NULL DEFAULT 'sent',
  `sent_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recruiter_outreach`
--

INSERT INTO `recruiter_outreach` (`id`, `recruiter_id`, `seeker_id`, `job_id`, `message`, `status`, `sent_at`) VALUES
(1, 9, 1, 1, 'Hi! We noticed your profile and think you would be a great fit for the Senior PHP Developer position at TechCorp Bangladesh. Would you be interested in discussing this opportunity?', 'sent', '2026-05-18 10:30:00'),
(2, 9, 1, 17, 'Hello! We have an exciting DevOps Engineer role that matches your skills. The position offers 100% remote work with excellent benefits. Interested?', 'sent', '2026-05-17 14:15:00'),
(3, 9, 2, 2, 'Hi! I came across your profile and wanted to reach out about a Junior Frontend Developer position. This is a great opportunity to grow your career!', 'responded', '2026-05-16 09:00:00'),
(4, 9, 2, 16, 'Your skills look perfect for a Mobile App Developer role we are hiring for. Flutter experience is exactly what our client needs. Let me know if you want to learn more!', 'responded', '2026-05-15 11:45:00'),
(5, 9, 1, 6, 'We have a Financial Analyst position at Finance Solutions Ltd that aligns with your background. The role offers great growth potential and benefits.', 'sent', '2026-05-14 16:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_profiles`
--

CREATE TABLE `recruiter_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_name` varchar(200) DEFAULT NULL,
  `specialization` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recruiter_profiles`
--

INSERT INTO `recruiter_profiles` (`id`, `user_id`, `agency_name`, `specialization`, `description`, `website`) VALUES
(1, 9, 'TalentHub Recruiters', 'IT & Technology', 'Specialized recruitment agency for tech talent', 'www.talenthub.com');

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`id`, `user_id`, `job_id`, `saved_at`) VALUES
(2, 2, 2, '2026-05-19 04:22:57');

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
  `years_experience` int(11) DEFAULT 0,
  `education_level` varchar(100) DEFAULT NULL,
  `current_salary` decimal(10,2) DEFAULT 0.00,
  `expected_salary` decimal(10,2) DEFAULT 0.00,
  `preferred_location` varchar(150) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seeker_profiles`
--

INSERT INTO `seeker_profiles` (`id`, `user_id`, `headline`, `summary`, `skills`, `years_experience`, `education_level`, `current_salary`, `expected_salary`, `preferred_location`, `resume_path`) VALUES
(1, 2, 'software devoloper', '0bg', 'c#, java, c++, ooad', 1, 'Bachelor\\\'s Degree', 1.00, 200000.00, 'Narsingdi', '../uploads/resumes/1779128679_Data Com_Section-M_Final_ Marks_Spring 2025-26.pdf'),
(2, 1, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('seeker','employer','recruiter','admin') NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `profile_pic`, `is_active`, `is_verified`, `created_at`) VALUES
(1, 'Protiti Sahajee Prachi', 'prachi120123@gmail.com', '$2y$10$JuKLqdUOap9QOYUZ2V4ve.q0KVEHTcRI1H7rUnWxpwGQX0MfYTkwm', '01330497737', 'seeker', NULL, 1, 1, '2026-05-18 14:34:45'),
(2, 'prachi', 'tt@email.com', '$2y$10$/qjRTXuz16tr3NnnkIvUfOxt4dtaN9i8yG50Yr5GBVmfn6EC446j6', '01557154854', 'seeker', '../uploads/profile_pics/1779128726_airport-security-scanner-icon-conveyor-belt-with-passenger-luggage-baggage-carousel-scan-isolated-on-white-package-x-ray-baggage-security-logistic-and-delivery-cartoon-flat-illustration-vector (1).jpg', 1, 1, '2026-05-18 14:55:49'),
(3, 'Admin', 'admin@gmail.com', '$2y$12$4Yiap1uq2bHBkc6cM2AiROXwGQRHJZ3w8.KHnoufGsCmt2zvddVbS', '01700000000', 'admin', '', 1, 1, '0000-00-00 00:00:00'),
(4, 'TechCorp Bangladesh', 'techcorp@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000001', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(5, 'Digital Marketing BD', 'digitalbd@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000002', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(6, 'Finance Solutions Ltd', 'financebd@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000003', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(7, 'BuildRight Engineering', 'buildright@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000004', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(8, 'MediCare Hospital', 'medicare@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000005', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(9, 'Bright Future Academy', 'brightfuture@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000006', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(10, 'SalesPro BD', 'salespro@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000007', 'employer', NULL, 1, 1, '2026-05-19 01:37:41'),
(11, 'TalentHub Recruiters', 'talenthub@example.com', '$2y$10$dummyhash1234567890123456789012345678901234567890', '01710000008', 'recruiter', NULL, 1, 1, '2026-05-19 01:37:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `target_user_id` (`target_user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `seeker_id` (`seeker_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitter_id` (`submitter_id`);

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
  ADD KEY `employer_id` (`employer_id`),
  ADD KEY `recruiter_id` (`recruiter_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seeker_id` (`seeker_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `platform_policies`
--
ALTER TABLE `platform_policies`
  ADD PRIMARY KEY (`policy_key`);

--
-- Indexes for table `recruiter_clients`
--
ALTER TABLE `recruiter_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recruiter_id` (`recruiter_id`);

--
-- Indexes for table `recruiter_outreach`
--
ALTER TABLE `recruiter_outreach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recruiter_id` (`recruiter_id`),
  ADD KEY `seeker_id` (`seeker_id`);

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
  ADD UNIQUE KEY `unique_save` (`user_id`,`job_id`),
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
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `job_alerts`
--
ALTER TABLE `job_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recruiter_clients`
--
ALTER TABLE `recruiter_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruiter_outreach`
--
ALTER TABLE `recruiter_outreach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `recruiter_profiles`
--
ALTER TABLE `recruiter_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seeker_profiles`
--
ALTER TABLE `seeker_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD CONSTRAINT `admin_actions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_actions_ibfk_2` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`submitter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  ADD CONSTRAINT `employer_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`recruiter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jobs_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD CONSTRAINT `job_alerts_ibfk_1` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recruiter_clients`
--
ALTER TABLE `recruiter_clients`
  ADD CONSTRAINT `recruiter_clients_ibfk_1` FOREIGN KEY (`recruiter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recruiter_outreach`
--
ALTER TABLE `recruiter_outreach`
  ADD CONSTRAINT `recruiter_outreach_ibfk_1` FOREIGN KEY (`recruiter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recruiter_outreach_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
