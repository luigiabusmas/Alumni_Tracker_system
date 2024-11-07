-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 02:40 PM
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
-- Database: `alumnitrackerdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni_profile_table`
--

CREATE TABLE `alumni_profile_table` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Time of data entry',
  `username` varchar(100) NOT NULL COMMENT 'User’s unique identifier, typically email',
  `email` varchar(100) NOT NULL COMMENT 'Primary email address',
  `alumni_id` varchar(50) NOT NULL COMMENT 'Unique ID assigned to each alumni',
  `fname` varchar(50) NOT NULL COMMENT 'First name of alumni',
  `mname` varchar(50) DEFAULT NULL COMMENT 'Middle name of alumni',
  `lname` varchar(50) NOT NULL COMMENT 'Last name of alumni',
  `kld_email` varchar(100) NOT NULL COMMENT 'Official KLD email address',
  `home_address` varchar(255) DEFAULT NULL COMMENT 'Primary home address of alumni',
  `primary_phone` varchar(15) DEFAULT NULL COMMENT 'Primary phone number',
  `secondary_phone` varchar(15) DEFAULT NULL COMMENT 'Secondary phone number',
  `gender` enum('Male','Female','Other') DEFAULT 'Other' COMMENT 'Gender of alumni',
  `date_of_birth` date DEFAULT NULL COMMENT 'Date of birth of alumni',
  `graduation_year` year(4) DEFAULT NULL COMMENT 'Graduation year',
  `degree_obtained` varchar(100) DEFAULT NULL COMMENT 'Degree obtained by alumni',
  `employment_status` enum('Unemployed','Employed','Pursued Studies') DEFAULT 'Unemployed' COMMENT 'Current employment status',
  `company_name` varchar(100) DEFAULT NULL COMMENT 'Company name if employed',
  `job_title` varchar(100) DEFAULT NULL COMMENT 'Job title if employed',
  `job_description` text DEFAULT NULL COMMENT 'Brief description of job duties if employed',
  `reason_future_plans` text DEFAULT NULL COMMENT 'Reason for current status and future plans',
  `motivation_for_studies` text DEFAULT NULL COMMENT 'Motivation for pursuing further studies',
  `degree_or_program` varchar(100) DEFAULT NULL COMMENT 'Further studies degree or program',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Record creation timestamp',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Record update timestamp',
  `profile_picture` varchar(255) DEFAULT NULL COMMENT 'Path or URL of the profile picture'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni_profile_table`
--

INSERT INTO `alumni_profile_table` (`id`, `timestamp`, `username`, `email`, `alumni_id`, `fname`, `mname`, `lname`, `kld_email`, `home_address`, `primary_phone`, `secondary_phone`, `gender`, `date_of_birth`, `graduation_year`, `degree_obtained`, `employment_status`, `company_name`, `job_title`, `job_description`, `reason_future_plans`, `motivation_for_studies`, `degree_or_program`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, '2024-11-02 14:09:21', 'test@gmail.com', 'test@gmail.com', 'KLDAA-202499999', 'z', 'z', 'z', 'z', 'z', 'z', 'z', 'Male', '2024-11-06', '2001', 'z', 'Employed', 'z', '', '', '', '', '', '2024-11-03 05:54:08', '2024-11-06 10:06:29', 'school-solid.jpg'),
(2, '2024-11-02 15:15:00', 'johnsmith@email.com', 'johnsmith@email.com', 'KLDAA-202400039', 'a', 'a', 'a', 'john.smith@kld.edu.ph', '123 Elm St., Springfield, USA', '1234567890', 'n/a', 'Female', '1995-01-15', '2024', 'Bachelor of Science in Computer Science', 'Unemployed', 'Tech Solutions', 'Software Developer', 'Developing web applications', 'To enhance my technical skills', 'Programming', 'MSc in Computer Science', '2024-11-03 05:56:30', '2024-11-06 10:22:10', 'kld-logo.png'),
(3, '2024-11-02 15:20:12', 'jane.doe@email.com', 'jane.doe@email.com', 'KLDAA-202400040', 'Jane', NULL, 'Doe', 'jane.doe@kld.edu.ph', '456 Oak St., Springfield, USA', '2345678901', 'n/a', 'Female', '1998-03-22', '2024', 'Bachelor of Arts in Graphic Design', '', NULL, NULL, NULL, 'To expand my portfolio', 'Creativity', 'Graphic Design', '2024-11-03 05:56:30', '2024-11-03 05:56:30', NULL),
(4, '2024-11-02 15:25:30', 'alice.jones@email.com', 'alice.jones@email.com', 'KLDAA-202400041', 'Alice', 'B.', 'Jones', 'alice.jones@kld.edu.ph', '789 Pine St., Springfield, USA', '3456789012', 'n/a', 'Female', '1996-07-19', '2024', 'Bachelor of Science in Nursing', 'Employed', 'City Hospital', 'Registered Nurse', 'Patient care and administration', 'To further my career in healthcare', 'Healthcare', 'Master in Nursing', '2024-11-03 05:56:30', '2024-11-03 05:56:30', NULL),
(5, '2024-11-02 15:30:45', 'robert.brown@email.com', 'robert.brown@email.com', 'KLDAA-202400042', 'Robert', 'C.', 'Brown', 'robert.brown@kld.edu.ph', '101 Maple St., Springfield, USA', '4567890123', 'n/a', 'Male', '1994-09-10', '2024', 'Bachelor of Science in Business Administration', 'Unemployed', NULL, NULL, NULL, 'To find a suitable position in management', 'Business', 'MBA in Business Management', '2024-11-03 05:56:30', '2024-11-03 05:56:30', NULL),
(6, '2024-11-02 15:35:55', 'michael.smith@email.com', 'michael.smith@email.com', 'KLDAA-202400043', 'Michael', NULL, 'Smith', 'michael.smith@kld.edu.ph', '202 Cherry St., Springfield, USA', '5678901234', 'n/a', 'Male', '1997-02-28', '2024', 'Bachelor of Arts in English Literature', 'Pursued Studies', NULL, NULL, NULL, 'To become an author', 'Literature', 'Master in English Literature', '2024-11-03 05:56:30', '2024-11-03 05:56:30', NULL),
(7, '2024-11-02 16:40:00', 'charlie.brown@email.com', 'charlie.brown@email.com', 'KLDAA-202400044', 'Charlie', NULL, 'Brown', 'charlie.brown@kld.edu.ph', '321 Walnut St Springfield USA', '6789012345', 'n/a', 'Male', '1992-04-15', '2024', 'Bachelor of Science in Mathematics', 'Employed', 'MathWorks', 'Data Analyst', 'Analyzing data for insights', 'To advance in data science', 'Mathematics', 'MSc in Data Science', '2024-11-03 06:01:01', '2024-11-03 06:01:01', NULL),
(8, '2024-11-02 16:45:12', 'olivia.johnson@email.com', 'olivia.johnson@email.com', 'KLDAA-202400045', 'Olivia', 'A.', 'Johnson', 'olivia.johnson@kld.edu.ph', '654 Cedar St Springfield USA', '7890123456', 'n/a', 'Female', '1995-06-20', '2024', 'Bachelor of Arts in History', 'Unemployed', 'acn', 'test', 'test', 'To pursue a career in education', 'History', 'Master in Education', '2024-11-03 06:01:01', '2024-11-03 13:02:32', NULL),
(10, '2024-11-02 16:55:45', 'sophia.brown@email.com', 'sophia.brown@email.com', 'KLDAA-202400047', 'Sophia', 'K.', 'Brown', 'sophia.brown@kld.edu.ph', '135 Oak St Springfield USA', '1234567890', 'n/a', 'Female', '1990-08-30', '2024', 'Bachelor of Science in Chemistry', 'Pursued Studies', NULL, NULL, NULL, 'To further my studies in chemistry', 'Chemistry', 'PhD in Chemistry', '2024-11-03 06:01:01', '2024-11-03 06:01:01', NULL),
(11, '2024-11-02 17:00:10', 'james.thompson@email.com', 'james.thompson@email.com', 'KLDAA-202400048', 'James', NULL, 'Thompson', 'james.thompson@kld.edu.ph', '246 Birch St Springfield USA', '2345678901', 'n/a', 'Male', '1994-05-25', '2024', 'Bachelor of Science in Physics', 'Employed', 'Physics Corp', 'Research Scientist', 'Conducting experiments', 'To contribute to scientific knowledge', 'Physics', 'MSc in Physics', '2024-11-03 06:01:01', '2024-11-03 06:01:01', NULL),
(12, '2024-11-02 17:05:30', 'madison.wilson@email.com', 'madison.wilson@email.com', 'KLDAA-202400049', 'Madison', NULL, 'Wilson', 'madison.wilson@kld.edu.ph', '369 Maple St Springfield USA', '3456789012', 'n/a', 'Female', '1996-11-10', '2024', 'Bachelor of Science in Nursing', 'Unemployed', NULL, NULL, NULL, 'To gain practical experience', 'Healthcare', 'Master in Nursing', '2024-11-03 06:01:01', '2024-11-03 06:01:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newsarticle`
--

CREATE TABLE `newsarticle` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` varchar(50) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('Draft','Published','Archived') DEFAULT 'Draft',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsarticle`
--

INSERT INTO `newsarticle` (`id`, `title`, `content`, `author_id`, `published_at`, `status`, `start_date`, `end_date`, `image`, `created_at`, `updated_at`) VALUES
(11, 'Exploring the Future of Technology', 'This article discusses the future of technology and its impact on various industries.', 'KLDAA-202499999', '2024-11-01 08:00:00', 'Published', '2024-11-01 00:00:00', '2024-12-01 00:00:00', 'https://media.istockphoto.com/id/498168409/de/foto/sommer-strand-mit-strafish-und-muscheln.jpg?s=612x612&w=0&k=20&c=R2ELvM5Wd6g-sO4qdksGNJJwgjXFzdldpm-IJUTWmi4=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(12, 'The Role of Education in Society', 'Education plays a crucial role in shaping the future of any society. In this article, we explore its importance.', 'KLDAA-202499999', '2024-11-02 10:30:00', 'Published', '2024-11-02 00:00:00', '2024-11-30 00:00:00', 'https://media.istockphoto.com/id/508578343/de/foto/seashell-auf-dem-strand.jpg?s=612x612&w=0&k=20&c=LL0ucuLq94akkG3eJ7y6vqfmcajBKKmAJ0CeAd73VAQ=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(13, 'The Evolution of Social Media', 'Social media platforms have evolved drastically over the past decade. We examine this evolution and its implications.', 'KLDAA-202499999', '2024-11-03 12:00:00', 'Published', '2024-11-03 00:00:00', '2024-12-03 00:00:00', 'https://media.istockphoto.com/id/479566204/de/foto/muscheln-am-strand.jpg?s=612x612&w=0&k=20&c=MffN22mEtqHHQKNf-C_crM3ncUxRNP0ZSXLnuMi08OA=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(14, 'Health and Wellness Tips for 2024', 'Stay fit and healthy with these expert wellness tips for the new year.', 'KLDAA-202499999', '2024-11-04 14:00:00', 'Published', '2024-11-04 00:00:00', '2024-12-04 00:00:00', 'https://media.istockphoto.com/id/1265176558/de/foto/nahaufnahme-einer-muschel-am-strand.jpg?s=612x612&w=0&k=20&c=LcBQd-pVS4AedJt4pOmmsnX_bPeXlPov0rG8Wh34cS8=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(15, 'Sustainable Practices in Business', 'Learn how businesses are adopting sustainable practices to reduce their carbon footprint and improve efficiency.', 'KLDAA-202499999', '2024-11-05 16:00:00', 'Published', '2024-11-05 00:00:00', '2024-12-05 00:00:00', 'https://media.istockphoto.com/id/1016816470/de/foto/sonne-seafoam-muscheln.jpg?s=612x612&w=0&k=20&c=021vcUKLgUmjgJJeRrKz4ZLjOk7hR5URljpXKJE6D_Y=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(16, 'Advancements in Artificial Intelligence', 'AI is changing the way we live and work. In this article, we explore recent advancements in AI technology.', 'KLDAA-202499999', '2024-11-06 18:00:00', 'Published', '2024-11-06 00:00:00', '2024-12-06 00:00:00', 'https://media.istockphoto.com/id/498168409/de/foto/sommer-strand-mit-strafish-und-muscheln.jpg?s=612x612&w=0&k=20&c=R2ELvM5Wd6g-sO4qdksGNJJwgjXFzdldpm-IJUTWmi4=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(17, 'The Importance of Mental Health Awareness', 'Mental health awareness is more important than ever. This article delves into the current state of mental health in society.', 'KLDAA-202499999', '2024-11-07 09:00:00', 'Published', '2024-11-07 00:00:00', '2024-12-07 00:00:00', 'https://media.istockphoto.com/id/508578343/de/foto/seashell-auf-dem-strand.jpg?s=612x612&w=0&k=20&c=LL0ucuLq94akkG3eJ7y6vqfmcajBKKmAJ0CeAd73VAQ=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(18, 'Smart Cities: The Future of Urban Living', 'As the world becomes more urbanized, smart cities are emerging as the solution to modern urban challenges.', 'KLDAA-202499999', '2024-11-08 11:00:00', 'Published', '2024-11-08 00:00:00', '2024-12-08 00:00:00', 'https://media.istockphoto.com/id/479566204/de/foto/muscheln-am-strand.jpg?s=612x612&w=0&k=20&c=MffN22mEtqHHQKNf-C_crM3ncUxRNP0ZSXLnuMi08OA=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(19, 'The Power of Networking for Career Growth', 'Networking can play a huge role in career development. We look at strategies for effective networking.', 'KLDAA-202499999', '2024-11-09 13:00:00', 'Published', '2024-11-09 00:00:00', '2024-12-09 00:00:00', 'https://media.istockphoto.com/id/1265176558/de/foto/nahaufnahme-einer-muschel-am-strand.jpg?s=612x612&w=0&k=20&c=LcBQd-pVS4AedJt4pOmmsnX_bPeXlPov0rG8Wh34cS8=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(20, 'The Impact of Global Warming on Our Environment', 'Global warming is a pressing issue that requires urgent action. In this article, we explore its effects and potential solutions.', 'KLDAA-202499999', '2024-11-10 15:00:00', 'Published', '2024-11-10 00:00:00', '2024-12-10 00:00:00', 'https://media.istockphoto.com/id/1016816470/de/foto/sonne-seafoam-muscheln.jpg?s=612x612&w=0&k=20&c=021vcUKLgUmjgJJeRrKz4ZLjOk7hR5URljpXKJE6D_Y=', '2024-11-07 08:53:18', '2024-11-07 09:21:00'),
(21, 'The Future of Work: Trends to Watch', 'The future of work is rapidly changing with advancements in automation and remote work. This article explores the key trends to watch.', 'KLDAA-202499999', '2024-11-11 08:00:00', 'Published', '2024-11-11 00:00:00', '2024-12-11 00:00:00', 'https://media.istockphoto.com/id/498168409/de/foto/sommer-strand-mit-strafish-und-muscheln.jpg?s=612x612&w=0&k=20&c=R2ELvM5Wd6g-sO4qdksGNJJwgjXFzdldpm-IJUTWmi4=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(22, 'Innovations in Renewable Energy', 'Renewable energy is becoming a central part of our efforts to combat climate change. This article reviews the latest innovations.', 'KLDAA-202499999', '2024-11-12 09:30:00', 'Published', '2024-11-12 00:00:00', '2024-12-12 00:00:00', 'https://media.istockphoto.com/id/508578343/de/foto/seashell-auf-dem-strand.jpg?s=612x612&w=0&k=20&c=LL0ucuLq94akkG3eJ7y6vqfmcajBKKmAJ0CeAd73VAQ=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(23, 'The Importance of Cybersecurity', 'As technology advances, so do the threats. In this article, we discuss the importance of cybersecurity and how to protect your data.', 'KLDAA-202499999', '2024-11-13 10:45:00', 'Published', '2024-11-13 00:00:00', '2024-12-13 00:00:00', 'https://media.istockphoto.com/id/479566204/de/foto/muscheln-am-strand.jpg?s=612x612&w=0&k=20&c=MffN22mEtqHHQKNf-C_crM3ncUxRNP0ZSXLnuMi08OA=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(24, 'How to Build a Personal Brand', 'Building a strong personal brand can help you stand out in your career. This article provides actionable tips for doing just that.', 'KLDAA-202499999', '2024-11-14 11:00:00', 'Published', '2024-11-14 00:00:00', '2024-12-14 00:00:00', 'https://media.istockphoto.com/id/1265176558/de/foto/nahaufnahme-einer-muschel-am-strand.jpg?s=612x612&w=0&k=20&c=LcBQd-pVS4AedJt4pOmmsnX_bPeXlPov0rG8Wh34cS8=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(25, 'Advancements in Healthcare Technology', 'Technology has revolutionized healthcare. This article looks at the latest advancements and their potential impact.', 'KLDAA-202499999', '2024-11-15 12:15:00', 'Published', '2024-11-15 00:00:00', '2024-12-15 00:00:00', 'https://media.istockphoto.com/id/1016816470/de/foto/sonne-seafoam-muscheln.jpg?s=612x612&w=0&k=20&c=021vcUKLgUmjgJJeRrKz4ZLjOk7hR5URljpXKJE6D_Y=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(26, 'The Rise of E-commerce in 2024', 'E-commerce continues to grow at an exponential rate. This article explores the trends that are shaping the industry in 2024.', 'KLDAA-202499999', '2024-11-16 13:30:00', 'Published', '2024-11-16 00:00:00', '2024-12-16 00:00:00', 'https://media.istockphoto.com/id/498168409/de/foto/sommer-strand-mit-strafish-und-muscheln.jpg?s=612x612&w=0&k=20&c=R2ELvM5Wd6g-sO4qdksGNJJwgjXFzdldpm-IJUTWmi4=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(27, 'Artificial Intelligence in Education', 'AI is making waves in education. In this article, we examine how AI is transforming the learning experience.', 'KLDAA-202499999', '2024-11-17 14:00:00', 'Published', '2024-11-17 00:00:00', '2024-12-17 00:00:00', 'https://media.istockphoto.com/id/508578343/de/foto/seashell-auf-dem-strand.jpg?s=612x612&w=0&k=20&c=LL0ucuLq94akkG3eJ7y6vqfmcajBKKmAJ0CeAd73VAQ=', '2024-11-07 09:07:20', '2024-11-07 09:21:00'),
(28, 'How to Stay Productive While Working From Home', 'Remote work requires a different set of strategies for maintaining productivity. This article offers tips for success.', 'KLDAA-202499999', '2024-11-18 15:45:00', 'Published', '2024-11-18 00:00:00', '2024-12-18 00:00:00', 'https://media.istockphoto.com/id/479566204/de/foto/muscheln-am-strand.jpg?s=612x612&w=0&k=20&c=MffN22mEtqHHQKNf-C_crM3ncUxRNP0ZSXLnuMi08OA=', '2024-11-07 09:07:20', '2024-11-07 09:21:01'),
(29, 'The Impact of Social Media on Mental Health', 'Social media can have both positive and negative effects on mental health. This article looks at the psychological impacts of social media use.', 'KLDAA-202499999', '2024-11-19 16:20:00', 'Published', '2024-11-19 00:00:00', '2024-12-19 00:00:00', 'https://media.istockphoto.com/id/1265176558/de/foto/nahaufnahme-einer-muschel-am-strand.jpg?s=612x612&w=0&k=20&c=LcBQd-pVS4AedJt4pOmmsnX_bPeXlPov0rG8Wh34cS8=', '2024-11-07 09:07:20', '2024-11-07 09:21:01'),
(30, 'The Importance of Financial Literacy', 'Financial literacy is key to managing your finances effectively. This article explains why financial literacy matters and how to improve it.', 'KLDAA-202499999', '2024-11-20 17:40:00', 'Published', '2024-11-20 00:00:00', '2024-12-20 00:00:00', 'https://media.istockphoto.com/id/1016816470/de/foto/sonne-seafoam-muscheln.jpg?s=612x612&w=0&k=20&c=021vcUKLgUmjgJJeRrKz4ZLjOk7hR5URljpXKJE6D_Y=', '2024-11-07 09:07:20', '2024-11-07 09:21:01'),
(32, 'asd', 'asd', 'KLDAA-202499999', '2024-11-25 17:32:00', 'Archived', '2024-11-06 17:32:00', '2024-11-25 17:32:00', 'asdasda', '2024-11-07 02:34:13', '2024-11-07 02:34:13'),
(34, 'qwe', 'qwe', 'KLDAA-202499999', '2024-11-13 17:37:00', 'Archived', '2024-10-19 21:38:00', '2024-11-22 17:38:00', 'image/672c8a9b0c821.jpg', '2024-11-07 02:38:35', '2024-11-07 02:38:35'),
(36, 'zxc', 'zxc', 'KLDAA-202499999', '2024-11-27 17:41:00', 'Published', '2024-11-27 17:41:00', '2024-11-23 17:41:00', '672c8b66699b7.jpg', '2024-11-07 02:41:58', '2024-11-07 02:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `users_access`
--

CREATE TABLE `users_access` (
  `id` int(11) NOT NULL,
  `alumni_id` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `userpassword` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_access`
--

INSERT INTO `users_access` (`id`, `alumni_id`, `username`, `email`, `userpassword`, `is_verified`, `is_active`, `created_at`, `updated_at`, `last_login`, `session_token`, `is_admin`) VALUES
(13, 'KLDAA-202499999', 'test', 'test@gmail.com', '$2y$10$K./kMQc6/Dp4h7V2ir7hdO1hTL.ua1D99wY7es607/Nhc7VMgXSaG', 1, 1, '2024-11-03 07:15:21', '2024-11-07 08:16:10', '2024-11-07 08:16:10', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni_profile_table`
--
ALTER TABLE `alumni_profile_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `alumni_id` (`alumni_id`),
  ADD UNIQUE KEY `kld_email` (`kld_email`);

--
-- Indexes for table `newsarticle`
--
ALTER TABLE `newsarticle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `users_access`
--
ALTER TABLE `users_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_alumni_id` (`alumni_id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni_profile_table`
--
ALTER TABLE `alumni_profile_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `newsarticle`
--
ALTER TABLE `newsarticle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users_access`
--
ALTER TABLE `users_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `newsarticle`
--
ALTER TABLE `newsarticle`
  ADD CONSTRAINT `newsarticle_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `alumni_profile_table` (`alumni_id`);

--
-- Constraints for table `users_access`
--
ALTER TABLE `users_access`
  ADD CONSTRAINT `users_access_ibfk_1` FOREIGN KEY (`alumni_id`) REFERENCES `alumni_profile_table` (`alumni_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
