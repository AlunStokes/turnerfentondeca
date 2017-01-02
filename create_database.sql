-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2017 at 12:00 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simplelogin`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int(15) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `body` varchar(500) NOT NULL,
  `page` varchar(50) NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(5) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(72) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) DEFAULT NULL,
  `student_number` int(6) DEFAULT NULL,
  `grade` int(2) DEFAULT NULL,
  `alumnus` tinyint(1) DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirm_code` varchar(32) DEFAULT NULL,
  `password_reset_code` varchar(32) DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `writtens` tinyint(1) DEFAULT NULL,
  `can_bring_device` tinyint(1) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `unlocked_exam` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_individuals`
--

CREATE TABLE `attendance_individuals` (
  `id` int(10) NOT NULL,
  `session_id` int(10) NOT NULL,
  `student_number` int(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_sessions`
--

CREATE TABLE `attendance_sessions` (
  `id` int(10) NOT NULL,
  `attendance_code` varchar(255) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL,
  `num_users` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `class_posts`
--

CREATE TABLE `class_posts` (
  `id` int(10) NOT NULL,
  `poster` int(6) DEFAULT NULL,
  `message` text NOT NULL,
  `class` varchar(50) DEFAULT 'all',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `json_message` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `created_exams`
--

CREATE TABLE `created_exams` (
  `exam_id` int(5) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `num_questions` int(5) NOT NULL,
  `exam_type` varchar(25) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `unlocked` tinyint(1) NOT NULL DEFAULT '1',
  `show_score` tinyint(1) NOT NULL DEFAULT '1',
  `include_stats` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `created_exams_questions`
--

CREATE TABLE `created_exams_questions` (
  `id` int(5) NOT NULL,
  `exam_id` int(5) NOT NULL,
  `question_id` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(10) NOT NULL,
  `student_number` int(6) NOT NULL,
  `exam_id` int(5) NOT NULL DEFAULT '0',
  `percentage` decimal(4,0) NOT NULL,
  `score` int(4) NOT NULL,
  `total` int(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `student_number` int(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(5) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(72) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `student_number` int(6) DEFAULT NULL,
  `grade` int(2) NOT NULL,
  `alumnus` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `cluster` varchar(25) DEFAULT NULL,
  `event` varchar(25) DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password_reset_code` varchar(32) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `last_online` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(10) NOT NULL,
  `question` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions_answers`
--

CREATE TABLE `questions_answers` (
  `question_id` int(10) NOT NULL,
  `answer` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions_attempted`
--

CREATE TABLE `questions_attempted` (
  `id` int(10) NOT NULL,
  `student_number` int(6) NOT NULL,
  `question_id` int(10) NOT NULL,
  `correct` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions_cluster`
--

CREATE TABLE `questions_cluster` (
  `question_id` int(10) NOT NULL,
  `cluster` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions_explanations`
--

CREATE TABLE `questions_explanations` (
  `quesiton_id` int(10) NOT NULL,
  `explanation` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions_options`
--

CREATE TABLE `questions_options` (
  `question_id` int(10) NOT NULL,
  `option_a` varchar(250) NOT NULL,
  `option_b` varchar(250) NOT NULL,
  `option_c` varchar(250) NOT NULL,
  `option_d` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seen_alert`
--

CREATE TABLE `seen_alert` (
  `id` int(15) NOT NULL,
  `student_number` int(6) NOT NULL,
  `alert_id` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `id` int(10) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_statistics`
--

CREATE TABLE `user_statistics` (
  `student_number` int(6) NOT NULL,
  `num_correct_marketing` int(10) NOT NULL DEFAULT '0',
  `num_attempted_marketing` int(10) NOT NULL DEFAULT '0',
  `num_correct_businessadmin` int(10) NOT NULL DEFAULT '0',
  `num_attempted_businessadmin` int(10) NOT NULL DEFAULT '0',
  `num_correct_finance` int(10) NOT NULL DEFAULT '0',
  `num_attempted_finance` int(10) NOT NULL DEFAULT '0',
  `num_correct_hospitality` int(10) NOT NULL DEFAULT '0',
  `num_attempted_hospitality` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `attendance_individuals`
--
ALTER TABLE `attendance_individuals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_sessions`
--
ALTER TABLE `attendance_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_posts`
--
ALTER TABLE `class_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `created_exams`
--
ALTER TABLE `created_exams`
  ADD PRIMARY KEY (`exam_id`),
  ADD UNIQUE KEY `exam_name` (`exam_name`);

--
-- Indexes for table `created_exams_questions`
--
ALTER TABLE `created_exams_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `questions_answers`
--
ALTER TABLE `questions_answers`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `questions_attempted`
--
ALTER TABLE `questions_attempted`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_cluster`
--
ALTER TABLE `questions_cluster`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `questions_explanations`
--
ALTER TABLE `questions_explanations`
  ADD PRIMARY KEY (`quesiton_id`);

--
-- Indexes for table `questions_options`
--
ALTER TABLE `questions_options`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `seen_alert`
--
ALTER TABLE `seen_alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_statistics`
--
ALTER TABLE `user_statistics`
  ADD PRIMARY KEY (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=424;
--
-- AUTO_INCREMENT for table `attendance_individuals`
--
ALTER TABLE `attendance_individuals`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `attendance_sessions`
--
ALTER TABLE `attendance_sessions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `class_posts`
--
ALTER TABLE `class_posts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `created_exams`
--
ALTER TABLE `created_exams`
  MODIFY `exam_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `created_exams_questions`
--
ALTER TABLE `created_exams_questions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8101;
--
-- AUTO_INCREMENT for table `questions_answers`
--
ALTER TABLE `questions_answers`
  MODIFY `question_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8101;
--
-- AUTO_INCREMENT for table `questions_attempted`
--
ALTER TABLE `questions_attempted`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;
--
-- AUTO_INCREMENT for table `questions_cluster`
--
ALTER TABLE `questions_cluster`
  MODIFY `question_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13048;
--
-- AUTO_INCREMENT for table `questions_explanations`
--
ALTER TABLE `questions_explanations`
  MODIFY `quesiton_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `questions_options`
--
ALTER TABLE `questions_options`
  MODIFY `question_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8101;
--
-- AUTO_INCREMENT for table `seen_alert`
--
ALTER TABLE `seen_alert`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
