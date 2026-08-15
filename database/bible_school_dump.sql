-- ========================================================
-- Bible School Platform - Complete MySQL Database Dump
-- Fully compatible with InfinityFree / phpMyAdmin
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `migration` varchar(255) not null, `batch` int(11) not null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `email` varchar(255) not null, `role` varchar(255) check (`role` in ('admin', 'servant', 'student', 'parent')) not null default 'student', `phone` varchar(255), `avatar` varchar(255), `gender` varchar(255) check (`gender` in ('male', 'female')) not null default 'male', `birth_date` date, `address` text, `pending_children_info` text, `is_active` tinyint(1) not null default '1', `email_verified_at` timestamp NULL DEFAULT NULL, `password` varchar(255) not null, `remember_token` varchar(255), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp NULL DEFAULT NULL, primary key (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (`id` varchar(255) not null, `user_id` int(11), `ip_address` varchar(255), `user_agent` text, `payload` text not null, `last_activity` int(11) not null, primary key (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (`key` varchar(255) not null, `value` text not null, `expiration` int(11) not null, primary key (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` int(11) not null, primary key (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `queue` varchar(255) not null, `payload` text not null, `attempts` int(11) not null, `reserved_at` int(11), `available_at` int(11) not null, `created_at` int(11) not null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` int(11) not null, `pending_jobs` int(11) not null, `failed_jobs` int(11) not null, `failed_job_ids` text not null, `options` text, `cancelled_at` int(11), `created_at` int(11) not null, `finished_at` int(11), primary key (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `uuid` varchar(255) not null, `connection` varchar(255) not null, `queue` varchar(255) not null, `payload` text not null, `exception` text not null, `failed_at` timestamp NULL DEFAULT NULL not null default CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `personal_access_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `tokenable_type` varchar(255) not null, `tokenable_id` int(11) not null, `name` varchar(255) not null, `token` varchar(255) not null, `abilities` text, `last_used_at` timestamp NULL DEFAULT NULL, `expires_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `academic_years`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `is_current` tinyint(1) not null default '1', `start_date` date, `end_date` date, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `stages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `stages`;
CREATE TABLE `stages` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `description` text, `order` int(11) not null default '1', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `grades`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `stage_id` int(11) not null, `name` varchar(255) not null, `order` int(11) not null default '1', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`stage_id`) references `stages`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `classes`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `grade_id` int(11) not null, `name` varchar(255) not null, `room` varchar(255), `servant_id` int(11), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`grade_id`) references `grades`(`id`) on delete cascade, foreign key(`servant_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `students`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` int(11) not null, `stage_id` int(11), `grade_id` int(11), `class_id` int(11), `parent_id` int(11), `servant_id` int(11), `code` varchar(255) not null, `birth_date` date, `address` varchar(255), `notes` text, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`stage_id`) references `stages`(`id`) on delete set null, foreign key(`grade_id`) references `grades`(`id`) on delete set null, foreign key(`class_id`) references `classes`(`id`) on delete set null, foreign key(`parent_id`) references `users`(`id`) on delete set null, foreign key(`servant_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `curricula`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `curricula`;
CREATE TABLE `curricula` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) not null, `stage_id` int(11), `grade_id` int(11), `academic_year_id` int(11), `description` text, `cover_image` varchar(255), `is_published` tinyint(1) not null default '1', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`stage_id`) references `stages`(`id`) on delete cascade, foreign key(`grade_id`) references `grades`(`id`) on delete cascade, foreign key(`academic_year_id`) references `academic_years`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `units`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `curriculum_id` int(11) not null, `title` varchar(255) not null, `term` int(11) not null default '1', `description` text, `order` int(11) not null default '1', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`curriculum_id`) references `curricula`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `lessons`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `unit_id` int(11) not null, `title` varchar(255) not null, `description` text, `content` text, `bible_verse` varchar(255), `memory_verse` varchar(255), `objectives` text, `cover_image` varchar(255), `video_url` varchar(255), `pdf_file` varchar(255), `order` int(11) not null default '1', `status` varchar(255) check (`status` in ('draft', 'published')) not null default 'published', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`unit_id`) references `units`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `lesson_progress`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lesson_progress`;
CREATE TABLE `lesson_progress` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `student_id` int(11) not null, `lesson_id` int(11) not null, `status` varchar(255) check (`status` in ('in_progress', 'completed')) not null default 'completed', `completed_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`student_id`) references `students`(`id`) on delete cascade, foreign key(`lesson_id`) references `lessons`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `quizzes`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `class_id` int(11), `lesson_id` int(11), `title` varchar(255) not null, `description` text, `duration_minutes` int(11) not null default '15', `passing_score` int(11) not null default '50', `total_marks` int(11) not null default '100', `created_by` int(11), `is_published` tinyint(1) not null default '1', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`class_id`) references `classes`(`id`) on delete cascade, foreign key(`lesson_id`) references `lessons`(`id`) on delete cascade, foreign key(`created_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `exams`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `class_id` int(11), `title` varchar(255) not null, `stage_id` int(11), `grade_id` int(11), `curriculum_id` int(11), `duration_minutes` int(11) not null default '30', `passing_score` int(11) not null default '50', `total_marks` int(11) not null default '100', `start_date` date, `end_date` date, `is_published` tinyint(1) not null default '1', `created_by` int(11), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`class_id`) references `classes`(`id`) on delete cascade, foreign key(`stage_id`) references `stages`(`id`) on delete cascade, foreign key(`grade_id`) references `grades`(`id`) on delete cascade, foreign key(`curriculum_id`) references `curricula`(`id`) on delete cascade, foreign key(`created_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `questions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `quiz_id` int(11), `exam_id` int(11), `question_text` text not null, `question_type` varchar(255) check (`question_type` in ('multiple_choice', 'true_false', 'short_answer')) not null default 'multiple_choice', `options` text, `correct_answer` text not null, `explanation` text, `marks` int(11) not null default '10', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`quiz_id`) references `quizzes`(`id`) on delete cascade, foreign key(`exam_id`) references `exams`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `quiz_attempts`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quiz_attempts`;
CREATE TABLE `quiz_attempts` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `quiz_id` int(11) not null, `student_id` int(11) not null, `score` int(11) not null default '0', `total_marks` int(11) not null default '100', `percentage` numeric not null default '0', `passed` tinyint(1) not null default '0', `answers` text, `completed_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`quiz_id`) references `quizzes`(`id`) on delete cascade, foreign key(`student_id`) references `students`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `exam_attempts`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `exam_attempts`;
CREATE TABLE `exam_attempts` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `exam_id` int(11) not null, `student_id` int(11) not null, `score` int(11) not null default '0', `total_marks` int(11) not null default '100', `percentage` numeric not null default '0', `passed` tinyint(1) not null default '0', `answers` text, `completed_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`exam_id`) references `exams`(`id`) on delete cascade, foreign key(`student_id`) references `students`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `attendance_records`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE `attendance_records` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `class_id` int(11) not null, `student_id` int(11) not null, `recorded_by` int(11), `date` date not null, `status` varchar(255) check (`status` in ('present', 'absent', 'late', 'excused')) not null default 'present', `notes` varchar(255), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`class_id`) references `classes`(`id`) on delete cascade, foreign key(`student_id`) references `students`(`id`) on delete cascade, foreign key(`recorded_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `student_points`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `student_points`;
CREATE TABLE `student_points` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `student_id` int(11) not null, `given_by` int(11), `amount` int(11) not null, `reason` varchar(255) not null, `category` varchar(255) not null default 'general', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`student_id`) references `students`(`id`) on delete cascade, foreign key(`given_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `achievements`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `achievements`;
CREATE TABLE `achievements` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) not null, `description` text, `icon` varchar(255), `badge_code` varchar(255) not null, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `student_achievements`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `student_achievements`;
CREATE TABLE `student_achievements` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `student_id` int(11) not null, `achievement_id` int(11) not null, `awarded_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`student_id`) references `students`(`id`) on delete cascade, foreign key(`achievement_id`) references `achievements`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `bible_verses`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bible_verses`;
CREATE TABLE `bible_verses` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `text` text not null, `reference` varchar(255) not null, `stage_id` int(11), `grade_id` int(11), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`stage_id`) references `stages`(`id`) on delete set null, foreign key(`grade_id`) references `grades`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `student_verse_progress`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `student_verse_progress`;
CREATE TABLE `student_verse_progress` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `student_id` int(11) not null, `bible_verse_id` int(11) not null, `status` varchar(255) check (`status` in ('pending', 'in_review', 'completed', 'excellent')) not null default 'pending', `notes` text, `checked_by` int(11), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`student_id`) references `students`(`id`) on delete cascade, foreign key(`bible_verse_id`) references `bible_verses`(`id`) on delete cascade, foreign key(`checked_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `news`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) not null, `content` text not null, `cover_image` varchar(255), `author_id` int(11), `is_published` tinyint(1) not null default '1', `published_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`author_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `events`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) not null, `description` text, `event_type` varchar(255) check (`event_type` in ('class', 'exam', 'trip', 'activity', 'church_event', 'meeting')) not null default 'activity', `start_time` timestamp NULL DEFAULT NULL not null, `end_time` timestamp NULL DEFAULT NULL, `location` varchar(255), `stage_id` int(11), `class_id` int(11), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`stage_id`) references `stages`(`id`) on delete set null, foreign key(`class_id`) references `classes`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `messages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `sender_id` int(11) not null, `receiver_id` int(11) not null, `student_id` int(11), `message` text not null, `is_read` tinyint(1) not null default '0', `read_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`sender_id`) references `users`(`id`) on delete cascade, foreign key(`receiver_id`) references `users`(`id`) on delete cascade, foreign key(`student_id`) references `students`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `notifications`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` int(11) not null, `title` varchar(255) not null, `message` text not null, `type` varchar(255) check (`type` in ('attendance', 'grade', 'exam', 'lesson', 'announcement', 'achievement', 'event', 'general')) not null default 'general', `is_read` tinyint(1) not null default '0', `read_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `class_servant`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `class_servant`;
CREATE TABLE `class_servant` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `class_id` int(11) not null, `servant_id` int(11) not null, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`class_id`) references `classes`(`id`) on delete cascade, foreign key(`servant_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `spiritual_journals`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `spiritual_journals`;
CREATE TABLE `spiritual_journals` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `student_id` int(11) not null, `title` varchar(255) not null, `content` text not null, `mood` varchar(255), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`student_id`) references `students`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `prayer_requests`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `prayer_requests`;
CREATE TABLE `prayer_requests` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `student_id` int(11) not null, `servant_id` int(11), `title` varchar(255) not null, `details` text not null, `status` varchar(255) check (`status` in ('pending', 'praying', 'answered')) not null default 'pending', `servant_notes` text, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`student_id`) references `students`(`id`) on delete cascade, foreign key(`servant_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `event_registrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `event_registrations`;
CREATE TABLE `event_registrations` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `event_id` int(11) not null, `user_id` int(11) not null, `student_id` int(11), `status` varchar(255) check (`status` in ('registered', 'confirmed', 'cancelled')) not null default 'registered', `notes` text, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`event_id`) references `events`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`student_id`) references `students`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `event_photos`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `event_photos`;
CREATE TABLE `event_photos` (`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `event_id` int(11) not null, `title` varchar(255) not null, `image_url` varchar(255) not null, `caption` varchar(255), `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, foreign key(`event_id`) references `events`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `migrations`
-- --------------------------------------------------------
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_01_01_000001_create_academic_structures_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_01_01_000002_create_student_profiles_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_01_01_000003_create_curriculum_and_lessons_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_01_01_000004_create_quizzes_and_exams_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_01_01_000005_create_attendance_and_points_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_01_01_000006_create_verses_news_events_messages_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_01_01_000007_create_class_servant_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_01_01_000008_create_spiritual_journals_and_prayers_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_01_01_000009_create_event_registrations_and_photos_table', '1');

-- --------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'د. يوسف صبحي', 'admin@bibleschool.com', 'admin', '01223456789', NULL, 'male', NULL, NULL, NULL, '1', NULL, '$2y$12$NW32ZlbDhGQHXNSZY6JLAObaAmtvEkdLO.VSTOPPOaZltyA/7X4a6', NULL, '2026-08-15 14:06:50', '2026-08-15 14:06:50');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'أ. مينا سامي', 'servant@bibleschool.com', 'servant', '01223456788', NULL, 'male', NULL, NULL, NULL, '1', NULL, '$2y$12$Hd/8xqToWBCpqNYdWgDbGO4T/b1OQ93DqnL7uIrAiwtdYAVPXl.mC', NULL, '2026-08-15 14:06:50', '2026-08-15 14:06:50');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('3', 'تاسوني مريم كميل', 'servant2@bibleschool.com', 'servant', '01223456787', NULL, 'female', NULL, NULL, NULL, '1', NULL, '$2y$12$HRRPLDMfmlYA6nwWBq2dbu1MZIU1A91V62BgXZgU6t7ISOXE2KqYe', NULL, '2026-08-15 14:06:51', '2026-08-15 14:06:51');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('4', 'م. مجدي عادل', 'parent@bibleschool.com', 'parent', '01223456786', NULL, 'male', NULL, NULL, NULL, '1', NULL, '$2y$12$vOBCQjtFXGc2.5GlYroRUeyNP2NtRpATUBl895fusX9OHxy5w9oDm', NULL, '2026-08-15 14:06:51', '2026-08-15 14:06:51');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('5', 'د. هاني توفيق', 'parent2@bibleschool.com', 'parent', '01223456785', NULL, 'male', NULL, NULL, NULL, '1', NULL, '$2y$12$pMGRopWarpJozNSpeW6pF.SYejuhKn9Jg1aHiIp.jj1wA8X29VHkC', NULL, '2026-08-15 14:06:51', '2026-08-15 14:06:51');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('6', 'مارك مجدي', 'student@bibleschool.com', 'student', '01223456784', NULL, 'male', NULL, NULL, NULL, '1', NULL, '$2y$12$qKh6F0WNvfzio/jDaQH//eHIHBKF/d3kKoG3xEhxHhPaSOTApyk/K', NULL, '2026-08-15 14:06:51', '2026-08-15 14:06:51');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('7', 'مارينا مجدي', 'student2@bibleschool.com', 'student', '01223456783', NULL, 'female', NULL, NULL, NULL, '1', NULL, '$2y$12$3AEy01dI1d..ro8Fdq6t7OYbBKXeE/yYiAnEmEBIa5DSX5olF9QpS', NULL, '2026-08-15 14:06:51', '2026-08-15 14:06:51');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('8', 'بيتر هاني', 'student3@bibleschool.com', 'student', '01223456782', NULL, 'male', NULL, NULL, NULL, '1', NULL, '$2y$12$QUbXRAmG9TtJh49d5nGJnOnIHKFqH2FeQ/SrK.xMGw7YMN0E7K0.m', NULL, '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `avatar`, `gender`, `birth_date`, `address`, `pending_children_info`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('9', 'سارة هاني', 'student4@bibleschool.com', 'student', '01223456781', NULL, 'female', NULL, NULL, NULL, '1', NULL, '$2y$12$G9aZv0/U8c8iQVJJ4c5huOM1MAf52eG2fp0fYV1QLPI1W.P5HWyhC', NULL, '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `academic_years`
-- --------------------------------------------------------
INSERT INTO `academic_years` (`id`, `name`, `is_current`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES ('1', '2025/2026', '1', '2025-09-01 00:00:00', '2026-06-30 00:00:00', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `stages`
-- --------------------------------------------------------
INSERT INTO `stages` (`id`, `name`, `description`, `order`, `created_at`, `updated_at`) VALUES ('1', 'المرحلة الابتدائية', 'الصفوف من الأول إلى السادس الابتدائي', '1', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `stages` (`id`, `name`, `description`, `order`, `created_at`, `updated_at`) VALUES ('2', 'المرحلة الإعدادية', 'الصفوف من الأول إلى الثالث الإعدادي', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `stages` (`id`, `name`, `description`, `order`, `created_at`, `updated_at`) VALUES ('3', 'المرحلة الثانوية', 'الصفوف من الأول إلى الثالث الثانوي', '3', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `grades`
-- --------------------------------------------------------
INSERT INTO `grades` (`id`, `stage_id`, `name`, `order`, `created_at`, `updated_at`) VALUES ('1', '1', 'الصف الخامس الابتدائي', '5', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `grades` (`id`, `stage_id`, `name`, `order`, `created_at`, `updated_at`) VALUES ('2', '1', 'الصف السادس الابتدائي', '6', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `grades` (`id`, `stage_id`, `name`, `order`, `created_at`, `updated_at`) VALUES ('3', '2', 'الصف الأول الإعدادي', '1', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `classes`
-- --------------------------------------------------------
INSERT INTO `classes` (`id`, `grade_id`, `name`, `room`, `servant_id`, `created_at`, `updated_at`) VALUES ('1', '2', 'فصل القديس مارمرقس', 'قاعة 101', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `classes` (`id`, `grade_id`, `name`, `room`, `servant_id`, `created_at`, `updated_at`) VALUES ('2', '1', 'فصل القديسة مريم العذراء', 'قاعة 102', '3', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `students`
-- --------------------------------------------------------
INSERT INTO `students` (`id`, `user_id`, `stage_id`, `grade_id`, `class_id`, `parent_id`, `servant_id`, `code`, `birth_date`, `address`, `notes`, `created_at`, `updated_at`) VALUES ('1', '6', '1', '2', '1', '4', '2', 'STU-1001', '2014-05-12 00:00:00', 'القاهرة، مصر', 'طالب متميز في ألحان الكنيسة وحفظ الكتاب المقدس', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `students` (`id`, `user_id`, `stage_id`, `grade_id`, `class_id`, `parent_id`, `servant_id`, `code`, `birth_date`, `address`, `notes`, `created_at`, `updated_at`) VALUES ('2', '7', '1', '1', '2', '4', '3', 'STU-1002', '2015-08-20 00:00:00', 'القاهرة، مصر', 'مواظبة جداً على الحضور والأنشطة', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `students` (`id`, `user_id`, `stage_id`, `grade_id`, `class_id`, `parent_id`, `servant_id`, `code`, `birth_date`, `address`, `notes`, `created_at`, `updated_at`) VALUES ('3', '8', '1', '2', '1', '5', '2', 'STU-1003', '2014-03-15 00:00:00', 'الجيزة، مصر', 'محاط بالاهتمام ومحب لدراسة الكتاب', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `students` (`id`, `user_id`, `stage_id`, `grade_id`, `class_id`, `parent_id`, `servant_id`, `code`, `birth_date`, `address`, `notes`, `created_at`, `updated_at`) VALUES ('4', '9', '1', '1', '2', '5', '3', 'STU-1004', '2015-11-04 00:00:00', 'الجيزة، مصر', 'هادئة ومتميزة في الاختبارات الكتابية', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `curricula`
-- --------------------------------------------------------
INSERT INTO `curricula` (`id`, `title`, `stage_id`, `grade_id`, `academic_year_id`, `description`, `cover_image`, `is_published`, `created_at`, `updated_at`) VALUES ('1', 'منهج التربية الكنسية - الصف السادس الابتدائي', '1', '2', '1', 'منهج دراسي شامل يتناول دراسة أسفار العهد القديم والعهد الجديد وطقس الكنيسة والألحان.', 'curriculum_cover.jpg', '1', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `units`
-- --------------------------------------------------------
INSERT INTO `units` (`id`, `curriculum_id`, `title`, `term`, `description`, `order`, `created_at`, `updated_at`) VALUES ('1', '1', 'الوحدة الأولى: حياة الإيمان والإرادة', '1', 'دروس عن شخصيات كتابية عاشت الإيمان الحقيقي', '1', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `units` (`id`, `curriculum_id`, `title`, `term`, `description`, `order`, `created_at`, `updated_at`) VALUES ('2', '1', 'الوحدة الثانية: رحلات الأنبياء والرسل', '1', 'رحلات خروج الشعب ورحلات القديس بولس الرسول', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `lessons`
-- --------------------------------------------------------
INSERT INTO `lessons` (`id`, `unit_id`, `title`, `description`, `content`, `bible_verse`, `memory_verse`, `objectives`, `cover_image`, `video_url`, `pdf_file`, `order`, `status`, `created_at`, `updated_at`) VALUES ('1', '1', 'الدرس الأول: إبراهيم رجل الإيمان والطاعة', 'كيف أطاع أبونا إبراهيم نداء الله وخرج وهو لا يعلم إلى أين يذهب.', '<h2>دعوة إبراهيم والإيمان الحقيقي</h2><p>دعاء الله لإبراهيم لكي يترك أرضه وعشيرته ويمضي إلى الأرض التي يريه إياها. اتسم إبراهيم بالإيمان القوي والاتكال الكامل على الله في كل خطوات حياته.</p><h3>نقاط الدرس الرئيسية:</h3><ul><li>الطاعة الفورية لنداء الرب</li><li>بناء المذبح في كل مكان نزل فيه</li><li>الوعد الإلهي بالنصرة والبركة</li></ul>', 'تك 12: 1-9', '«بِالإِيمَانِ إِبْرَاهِيمُ لَمَّا دُعِيَ أَطَاعَ أَنْ يَخْرُجَ إِلَى الْمَكَانِ الَّذِي كَانَ عَتِيدًا أَنْ يَأْخُذَهُ مِيرَاثًا» (عب 11: 8)', '[\"\\u0623\\u0646 \\u064a\\u0641\\u0647\\u0645 \\u0627\\u0644\\u0637\\u0627\\u0644\\u0628 \\u0645\\u0639\\u0646\\u0649 \\u0627\\u0644\\u0625\\u064a\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\",\"\\u0623\\u0646 \\u064a\\u062d\\u0641\\u0638 \\u0622\\u064a\\u0629 \\u0627\\u0644\\u062f\\u0631\\u0633\",\"\\u0623\\u0646 \\u064a\\u0637\\u0628\\u0642 \\u0627\\u0644\\u0637\\u0627\\u0639\\u0629 \\u0644\\u0644\\u0647 \\u0641\\u064a \\u062d\\u064a\\u0627\\u062a\\u0647 \\u0627\\u0644\\u064a\\u0648\\u0645\\u064a\\u0629\"]', 'lesson1.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'lessons/abraham_lesson.pdf', '1', 'published', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `lessons` (`id`, `unit_id`, `title`, `description`, `content`, `bible_verse`, `memory_verse`, `objectives`, `cover_image`, `video_url`, `pdf_file`, `order`, `status`, `created_at`, `updated_at`) VALUES ('2', '1', 'الدرس الثاني: يوسف الصديق والأمانة في الغربة', 'حياة يوسف في بيت فوتيفار وفي السجن وتدبير الله الصالح.', '<h2>أمانة يوسف في كل ظروف الحياة</h2><p>كان الرب مع يوسف فكان رجلاً ناجحاً. حفظ يوسف طهارته وأمانته رغم التجارب الشديدة والغربة، ورفض الخطيئة قائلاً: كيف أصنع هذا الشر العظيم وأخطئ إلى الله؟</p>', 'تك 39: 1-23', '«كَيْفَ أَصْنَعُ هذَا الشَّرَّ الْعَظِيمَ وَأَخْطِئُ إِلَى اللهِ؟» (تك 39: 9)', '[\"\\u0623\\u0646 \\u064a\\u062a\\u0639\\u0644\\u0645 \\u0627\\u0644\\u0637\\u0627\\u0644\\u0628 \\u0623\\u0647\\u0645\\u064a\\u0629 \\u0627\\u0644\\u0637\\u0647\\u0627\\u0631\\u0629 \\u0648\\u0627\\u0644\\u0646\\u0632\\u0627\\u0647\\u0629\",\"\\u0623\\u0646 \\u064a\\u062f\\u0631\\u0643 \\u0623\\u0646 \\u0627\\u0644\\u0644\\u0647 \\u0645\\u0639 \\u0627\\u0644\\u0623\\u0645\\u0646\\u0627\\u0621 \\u062f\\u0627\\u0626\\u0645\\u0627\\u064b\"]', 'lesson2.jpg', NULL, NULL, '2', 'published', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `lessons` (`id`, `unit_id`, `title`, `description`, `content`, `bible_verse`, `memory_verse`, `objectives`, `cover_image`, `video_url`, `pdf_file`, `order`, `status`, `created_at`, `updated_at`) VALUES ('3', '2', 'الدرس الثالث: موسى النبي وعبور البحر الأحمر', 'خروج شعب الله من مصر وتدخل الله الإلهي العجيب.', '<h2>قوة الصلاة وقيادة الرب</h2><p>وقف موسى والشعب أمام البحر الأحمر والعدو خلفهم، فقال لهم موسى: الرب يقاتل عنكم وأنتم تصمتون. فشق الرب البحر وعبر الشعب في اليابسة.</p>', 'خر 14: 1-31', '«الرَّبُّ يُقَاتِلُ عَنْكُمْ وَأَنْتُمْ تَصْمُتُونَ» (خر 14: 14)', '[\"\\u0627\\u0644\\u062b\\u0642\\u0629 \\u0641\\u064a \\u0642\\u062f\\u0631\\u0629 \\u0627\\u0644\\u0644\\u0647 \\u0639\\u0644\\u0649 \\u0625\\u0646\\u0642\\u0627\\u0630 \\u0623\\u0648\\u0644\\u0627\\u062f\\u0647\",\"\\u062a\\u0637\\u0628\\u064a\\u0642 \\u0627\\u0644\\u0635\\u0644\\u0627\\u0629 \\u0648\\u0642\\u062a \\u0627\\u0644\\u0634\\u062f\\u0629\"]', 'lesson3.jpg', NULL, NULL, '1', 'published', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `lesson_progress`
-- --------------------------------------------------------
INSERT INTO `lesson_progress` (`id`, `student_id`, `lesson_id`, `status`, `completed_at`, `created_at`, `updated_at`) VALUES ('1', '1', '1', 'completed', '2026-08-10 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `lesson_progress` (`id`, `student_id`, `lesson_id`, `status`, `completed_at`, `created_at`, `updated_at`) VALUES ('2', '1', '2', 'completed', '2026-08-13 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `lesson_progress` (`id`, `student_id`, `lesson_id`, `status`, `completed_at`, `created_at`, `updated_at`) VALUES ('3', '2', '1', 'completed', '2026-08-11 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `quizzes`
-- --------------------------------------------------------
INSERT INTO `quizzes` (`id`, `class_id`, `lesson_id`, `title`, `description`, `duration_minutes`, `passing_score`, `total_marks`, `created_by`, `is_published`, `created_at`, `updated_at`) VALUES ('1', NULL, '1', 'اختبار قصير: إبراهيم رجل الإيمان', 'اختبار تقييمي لقياس مدى استيعاب درس إبراهيم رجل الإيمان', '15', '60', '30', '2', '1', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `exams`
-- --------------------------------------------------------
INSERT INTO `exams` (`id`, `class_id`, `title`, `stage_id`, `grade_id`, `curriculum_id`, `duration_minutes`, `passing_score`, `total_marks`, `start_date`, `end_date`, `is_published`, `created_by`, `created_at`, `updated_at`) VALUES ('1', NULL, 'امتحان المنتصف - التربية الكنسية (الفصل الدراسي الأول)', '1', '2', '1', '45', '50', '100', '2026-08-08 14:06:52', '2026-08-22 14:06:52', '1', '1', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `questions`
-- --------------------------------------------------------
INSERT INTO `questions` (`id`, `quiz_id`, `exam_id`, `question_text`, `question_type`, `options`, `correct_answer`, `explanation`, `marks`, `created_at`, `updated_at`) VALUES ('1', '1', NULL, 'ما هي المدينة التي خرج منها إبراهيم؟', 'multiple_choice', '[\"\\u0623\\u0648\\u0631 \\u0627\\u0644\\u0643\\u0644\\u062f\\u0627\\u0646\\u064a\\u064a\\u0646\",\"\\u0623\\u0648\\u0631\\u0634\\u0644\\u064a\\u0645\",\"\\u0623\\u0631\\u064a\\u062d\\u0627\",\"\\u062f\\u0645\\u0634\\u0642\"]', 'أور الكلدانيين', 'خرج إبراهيم من أور الكلدانيين بحسب سفر التكوين 12.', '10', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `questions` (`id`, `quiz_id`, `exam_id`, `question_text`, `question_type`, `options`, `correct_answer`, `explanation`, `marks`, `created_at`, `updated_at`) VALUES ('2', '1', NULL, 'إبراهيم بنى مذبحاً للرب في كل مكان نزل فيه.', 'true_false', '[\"\\u0635\\u0648\\u0627\\u0628\",\"\\u062e\\u0637\\u0623\"]', 'صواب', 'كان إبراهيم يبني مذبحاً ويدعو باسم الرب في كل محطة.', '10', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `questions` (`id`, `quiz_id`, `exam_id`, `question_text`, `question_type`, `options`, `correct_answer`, `explanation`, `marks`, `created_at`, `updated_at`) VALUES ('3', '1', NULL, 'اكمل الآية: باليمين إبراهيم لما دعي أطاع أن يخرج إلى المكان الذي كان عتيداً أن يأخذه...', 'multiple_choice', '[\"\\u0645\\u064a\\u0631\\u0627\\u062b\\u0627\\u064b\",\"\\u0647\\u062f\\u064a\\u0629\",\"\\u0645\\u0623\\u0648\\u0649\",\"\\u0645\\u0644\\u0643\\u0627\\u064b\"]', 'ميراثاً', 'عب 11: 8', '10', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `questions` (`id`, `quiz_id`, `exam_id`, `question_text`, `question_type`, `options`, `correct_answer`, `explanation`, `marks`, `created_at`, `updated_at`) VALUES ('4', NULL, '1', 'من هو الأب الملقب بأبي الآباء ورجل الإيمان؟', 'multiple_choice', '[\"\\u0625\\u0628\\u0631\\u0627\\u0647\\u064a\\u0645\",\"\\u064a\\u0639\\u0642\\u0648\\u0628\",\"\\u0625\\u0633\\u062d\\u0642\",\"\\u0645\\u0648\\u0633\\u0649\"]', 'إبراهيم', 'يسمى أبونا إبراهيم بأبي الآباء.', '50', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `questions` (`id`, `quiz_id`, `exam_id`, `question_text`, `question_type`, `options`, `correct_answer`, `explanation`, `marks`, `created_at`, `updated_at`) VALUES ('5', NULL, '1', 'ماذا قال يوسف عندما تعرض للتجربة في بيت فوتيفار؟', 'multiple_choice', '[\"\\u0643\\u064a\\u0641 \\u0623\\u0635\\u0646\\u0639 \\u0647\\u0630\\u0627 \\u0627\\u0644\\u0634\\u0631 \\u0627\\u0644\\u0639\\u0638\\u064a\\u0645 \\u0648\\u0623\\u062e\\u0637\\u0626 \\u0625\\u0644\\u0649 \\u0627\\u0644\\u0644\\u0647\\u061f\",\"\\u0623\\u0646\\u0627 \\u0644\\u0627 \\u0623\\u0633\\u062a\\u0637\\u064a\\u0639 \\u0627\\u0644\\u0625\\u062c\\u0627\\u0628\\u0629\",\"\\u0627\\u0644\\u0631\\u0628 \\u064a\\u0642\\u0627\\u062a\\u0644 \\u0639\\u0646\\u0643\\u0645 \\u0648\\u0623\\u0646\\u062a\\u0645 \\u062a\\u0635\\u0645\\u062a\\u0648\\u0646\",\"\\u0644\\u0627 \\u062a\\u062e\\u0627\\u0641\\u0648\\u0627 \\u0648\\u0642\\u0641\\u0648\\u0627 \\u0648\\u0627\\u0646\\u0638\\u0631\\u0648\\u0627 \\u062e\\u0644\\u0627\\u0635 \\u0627\\u0644\\u0631\\u0628\"]', 'كيف أصنع هذا الشر العظيم وأخطئ إلى الله؟', 'سفر التكوين 39: 9', '50', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `quiz_attempts`
-- --------------------------------------------------------
INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `student_id`, `score`, `total_marks`, `percentage`, `passed`, `answers`, `completed_at`, `created_at`, `updated_at`) VALUES ('1', '1', '1', '30', '30', '100', '1', '{\"1\":\"\\u0623\\u0648\\u0631 \\u0627\\u0644\\u0643\\u0644\\u062f\\u0627\\u0646\\u064a\\u064a\\u0646\",\"2\":\"\\u0635\\u0648\\u0627\\u0628\",\"3\":\"\\u0645\\u064a\\u0631\\u0627\\u062b\\u0627\\u064b\"}', '2026-08-12 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `student_id`, `score`, `total_marks`, `percentage`, `passed`, `answers`, `completed_at`, `created_at`, `updated_at`) VALUES ('2', '1', '3', '20', '30', '66.67', '1', '{\"1\":\"\\u0623\\u0648\\u0631 \\u0627\\u0644\\u0643\\u0644\\u062f\\u0627\\u0646\\u064a\\u064a\\u0646\",\"2\":\"\\u062e\\u0637\\u0623\",\"3\":\"\\u0645\\u064a\\u0631\\u0627\\u062b\\u0627\\u064b\"}', '2026-08-14 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `exam_attempts`
-- --------------------------------------------------------
INSERT INTO `exam_attempts` (`id`, `exam_id`, `student_id`, `score`, `total_marks`, `percentage`, `passed`, `answers`, `completed_at`, `created_at`, `updated_at`) VALUES ('1', '1', '1', '100', '100', '100', '1', '{\"1\":\"\\u0625\\u0628\\u0631\\u0627\\u0647\\u064a\\u0645\",\"2\":\"\\u0643\\u064a\\u0641 \\u0623\\u0635\\u0646\\u0639 \\u0647\\u0630\\u0627 \\u0627\\u0644\\u0634\\u0631 \\u0627\\u0644\\u0639\\u0638\\u064a\\u0645 \\u0648\\u0623\\u062e\\u0637\\u0626 \\u0625\\u0644\\u0649 \\u0627\\u0644\\u0644\\u0647\\u061f\"}', '2026-08-13 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `attendance_records`
-- --------------------------------------------------------
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('1', '1', '1', '2', '2026-08-01 00:00:00', 'present', 'حضور مبكر ومشاركة فعالة', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('2', '1', '3', '2', '2026-08-01 00:00:00', 'present', 'تأخر 10 دقائق', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('3', '2', '2', '3', '2026-08-01 00:00:00', 'present', 'حاضرة بنشاط', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('4', '2', '4', '3', '2026-08-01 00:00:00', 'present', 'حاضرة بنشاط', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('5', '1', '1', '2', '2026-08-08 00:00:00', 'present', 'حضور مبكر ومشاركة فعالة', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('6', '1', '3', '2', '2026-08-08 00:00:00', 'present', 'تأخر 10 دقائق', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('7', '2', '2', '3', '2026-08-08 00:00:00', 'present', 'حاضرة بنشاط', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('8', '2', '4', '3', '2026-08-08 00:00:00', 'present', 'حاضرة بنشاط', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('9', '1', '1', '2', '2026-08-15 00:00:00', 'present', 'حضور مبكر ومشاركة فعالة', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('10', '1', '3', '2', '2026-08-15 00:00:00', 'late', 'تأخر 10 دقائق', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('11', '2', '2', '3', '2026-08-15 00:00:00', 'present', 'حاضرة بنشاط', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `attendance_records` (`id`, `class_id`, `student_id`, `recorded_by`, `date`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('12', '2', '4', '3', '2026-08-15 00:00:00', 'present', 'حاضرة بنشاط', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `student_points`
-- --------------------------------------------------------
INSERT INTO `student_points` (`id`, `student_id`, `given_by`, `amount`, `reason`, `category`, `created_at`, `updated_at`) VALUES ('1', '1', '2', '10', 'حضور مبكر والتزام في الفصل', 'attendance', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_points` (`id`, `student_id`, `given_by`, `amount`, `reason`, `category`, `created_at`, `updated_at`) VALUES ('2', '1', '2', '10', 'تفوق في اختبار درس إبراهيم', 'quiz', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_points` (`id`, `student_id`, `given_by`, `amount`, `reason`, `category`, `created_at`, `updated_at`) VALUES ('3', '1', '2', '5', 'تسميع آية عب 11: 8 ممتاز', 'verse', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_points` (`id`, `student_id`, `given_by`, `amount`, `reason`, `category`, `created_at`, `updated_at`) VALUES ('4', '2', '3', '10', 'مشاركة متميزة في الإجابات', 'behavior', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `achievements`
-- --------------------------------------------------------
INSERT INTO `achievements` (`id`, `title`, `description`, `icon`, `badge_code`, `created_at`, `updated_at`) VALUES ('1', 'عالم الكتاب المقدس', 'الحصول على الدرجة النهائية في 3 اختبارات كتابية متتالية', 'fas fa-book-bible', 'bible_scholar', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `achievements` (`id`, `title`, `description`, `icon`, `badge_code`, `created_at`, `updated_at`) VALUES ('2', 'المواظب المثالي', 'حضور جميع حصص التربية الكنسية خلال الشهر دون غياب', 'fas fa-calendar-check', 'perfect_attendance', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `achievements` (`id`, `title`, `description`, `icon`, `badge_code`, `created_at`, `updated_at`) VALUES ('3', 'حافظ الآيات', 'تسميع 10 آيات كتابية بامتياز', 'fas fa-award', 'memory_master', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `student_achievements`
-- --------------------------------------------------------
INSERT INTO `student_achievements` (`id`, `student_id`, `achievement_id`, `awarded_at`, `created_at`, `updated_at`) VALUES ('1', '1', '1', '2026-08-12 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_achievements` (`id`, `student_id`, `achievement_id`, `awarded_at`, `created_at`, `updated_at`) VALUES ('2', '1', '2', '2026-08-14 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_achievements` (`id`, `student_id`, `achievement_id`, `awarded_at`, `created_at`, `updated_at`) VALUES ('3', '2', '2', '2026-08-14 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `bible_verses`
-- --------------------------------------------------------
INSERT INTO `bible_verses` (`id`, `text`, `reference`, `stage_id`, `grade_id`, `created_at`, `updated_at`) VALUES ('1', 'بِالإِيمَانِ إِبْرَاهِيمُ لَمَّا دُعِيَ أَطَاعَ أَنْ يَخْرُجَ إِلَى الْمَكَانِ الَّذِي كَانَ عَتِيدًا أَنْ يَأْخُذَهُ مِيرَاثًا', 'عبرانيين 11: 8', '1', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `bible_verses` (`id`, `text`, `reference`, `stage_id`, `grade_id`, `created_at`, `updated_at`) VALUES ('2', 'الرَّبُّ يُقَاتِلُ عَنْكُمْ وَأَنْتُمْ تَصْمُتُونَ', 'خروج 14: 14', '1', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `bible_verses` (`id`, `text`, `reference`, `stage_id`, `grade_id`, `created_at`, `updated_at`) VALUES ('3', 'كَيْفَ أَصْنَعُ هذَا الشَّرَّ الْعَظِيمَ وَأَخْطِئُ إِلَى اللهِ؟', 'تكوين 39: 9', '1', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `student_verse_progress`
-- --------------------------------------------------------
INSERT INTO `student_verse_progress` (`id`, `student_id`, `bible_verse_id`, `status`, `notes`, `checked_by`, `created_at`, `updated_at`) VALUES ('1', '1', '1', 'excellent', 'حافظ للآية بالشواهد وبصوت واضح', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_verse_progress` (`id`, `student_id`, `bible_verse_id`, `status`, `notes`, `checked_by`, `created_at`, `updated_at`) VALUES ('2', '1', '2', 'completed', 'حافظ للآية جيد جداً', '2', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `student_verse_progress` (`id`, `student_id`, `bible_verse_id`, `status`, `notes`, `checked_by`, `created_at`, `updated_at`) VALUES ('3', '2', '1', 'excellent', 'تسميع ممتازة', '3', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `news`
-- --------------------------------------------------------
INSERT INTO `news` (`id`, `title`, `content`, `cover_image`, `author_id`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('1', 'افتتاح العام الدراسي الجديد بمدرسة الكتاب المقدس', 'نرحب بجميع أبنائنا الطلاب وأولياء أمورهم في بداية عام دراسي بروحي وتعليمي متميز ملؤه البركة والنمو في معرفة كلمة الله.', 'news_opening.jpg', '1', '1', '2026-08-05 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `news` (`id`, `title`, `content`, `cover_image`, `author_id`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES ('2', 'مسابقة حفظ الكتاب المقدس السنوية', 'تعلن الكنيسة عن بدء التجهيز للمسابقة السنوية لحفظ أسفار الكتاب المقدس وتوزيع الهدايا والدروع للمتفوقين.', 'news_contest.jpg', '1', '1', '2026-08-11 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `events`
-- --------------------------------------------------------
INSERT INTO `events` (`id`, `title`, `description`, `event_type`, `start_time`, `end_time`, `location`, `stage_id`, `class_id`, `created_at`, `updated_at`) VALUES ('1', 'رحلة دراسية واستكشافية لمكتبة الدير', 'رحلة خاصة لطلاب المرحلة الابتدائية للاطلاع على المخطوطات والكتب الروحية القديمة.', 'trip', '2026-08-20 08:00:52', '2026-08-20 17:00:52', 'دير القديس أنبا بيشوي - وادي النطرون', '1', NULL, '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `events` (`id`, `title`, `description`, `event_type`, `start_time`, `end_time`, `location`, `stage_id`, `class_id`, `created_at`, `updated_at`) VALUES ('2', 'اختبار نهاية الشهر في التربية الكنسية', 'امتحان شامل لجميع الصفوف في الوحدات الأولى والثانية.', 'exam', '2026-08-25 16:00:52', '2026-08-25 18:00:52', 'مبنى قاعات التربية الكنسية', '1', NULL, '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `messages`
-- --------------------------------------------------------
INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `student_id`, `message`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES ('1', '4', '2', '1', 'سلام ونعمة أستاذ مينا، أود الاطمئنان على مستوى مارك في تسميع الألحان وآيات هذا الأسبوع.', '1', '2026-08-14 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `student_id`, `message`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES ('2', '2', '4', '1', 'أهلاً يا باشمهندس مجدي، مارك ممتاز جداً ومواظب وحصل على شارة \"عالم الكتاب المقدس\" هذا الأسبوع!', '0', NULL, '2026-08-15 14:06:52', '2026-08-15 14:06:52');

-- --------------------------------------------------------
-- Dumping data for table `notifications`
-- --------------------------------------------------------
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES ('1', '6', 'وسام جديد!', 'تم منحك وسام \"عالم الكتاب المقدس\" لتفوقك في الاختبارات الكتابية.', 'achievement', '0', NULL, '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES ('2', '6', 'نقاط جديدة!', 'تم إضافة 10 نقاط لحسابك بواسطة أ. مينا سامي لتفوقك في الاختبار.', 'general', '1', '2026-08-14 14:06:52', '2026-08-15 14:06:52', '2026-08-15 14:06:52');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES ('3', '4', 'تقرير الحضور', 'تم تسجيل حضور الطالب مارك مجدي بنجاح اليوم.', 'attendance', '0', NULL, '2026-08-15 14:06:53', '2026-08-15 14:06:53');

-- --------------------------------------------------------
-- Dumping data for table `class_servant`
-- --------------------------------------------------------
INSERT INTO `class_servant` (`id`, `class_id`, `servant_id`, `created_at`, `updated_at`) VALUES ('1', '1', '2', NULL, NULL);
INSERT INTO `class_servant` (`id`, `class_id`, `servant_id`, `created_at`, `updated_at`) VALUES ('2', '2', '3', NULL, NULL);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
