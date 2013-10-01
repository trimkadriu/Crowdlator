-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2013 at 01:53 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `crowdlator`
--

-- --------------------------------------------------------

--
-- Table structure for table `audios`
--

CREATE TABLE IF NOT EXISTS `audios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `audio_id` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `permalink_url` varchar(512) NOT NULL,
  `download_url` varchar(512) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `choosen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`project_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `drafts`
--

CREATE TABLE IF NOT EXISTS `drafts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `draft_text` mediumtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `permission` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `idja` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`) VALUES
(1, 'admin/projects/create_project'),
(2, 'admin/projects/assign_editors'),
(3, 'admin/projects/edit_project'),
(4, 'admin/users/list_users'),
(5, 'admin/users/change_user_role'),
(6, 'admin/projects/delete_project'),
(8, 'admin/projects/tasks_list'),
(9, 'admin/editors/get_editors_filter'),
(10, 'admin/translate/task_id'),
(11, 'admin/translate/my_translations'),
(12, 'admin/projects/list_projects'),
(13, 'admin/projects/list_tasks'),
(14, 'admin/translate/drafts'),
(15, 'admin/translate/translations'),
(16, 'admin/projects/upload_video'),
(17, 'admin/projects/check_project'),
(18, 'admin/translate/editor_edit_translation'),
(19, 'admin/translate/set_reviewed'),
(20, 'admin/translate/set_approved'),
(21, 'admin/translate/translations_approved'),
(22, 'admin/translate/remove_translation'),
(23, 'admin/translate/vote'),
(24, 'admin/translate/vote_translations'),
(25, 'admin/translate/draft_list'),
(26, 'admin/translate/delete_draft'),
(27, 'admin/translate/choose_translations'),
(28, 'admin/translate/chose_translation'),
(29, 'admin/projects/projects_status'),
(30, 'admin/translate/audio_audition'),
(31, 'admin/translate/audition'),
(32, 'admin/translate/my_audios'),
(33, 'admin/translate/choose_audio'),
(34, 'admin/projects/generate_video'),
(35, 'admin/projects/generate_download_video_link');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL,
  `project_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `project_description` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `translate_from_language` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `translate_to_language` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `translations_per_task` int(2) NOT NULL,
  `microtasks_by` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `break_text` int(2) unsigned NOT NULL,
  `text` longtext NOT NULL,
  `translated_text` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `hash_tags` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `video_id` varchar(15) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `admin_id_2` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'administrator'),
(2, 'editor'),
(3, 'translator'),
(4, 'super editor');

-- --------------------------------------------------------

--
-- Table structure for table `roles_x_permissions`
--

CREATE TABLE IF NOT EXISTS `roles_x_permissions` (
  `id_role` int(2) unsigned NOT NULL,
  `id_permission` int(11) unsigned NOT NULL,
  KEY `id_role` (`id_role`),
  KEY `id_permission` (`id_permission`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles_x_permissions`
--

INSERT INTO `roles_x_permissions` (`id_role`, `id_permission`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(4, 8),
(2, 8),
(3, 8),
(3, 8),
(3, 8),
(1, 9),
(3, 10),
(3, 11),
(1, 12),
(3, 12),
(2, 13),
(3, 14),
(2, 15),
(1, 16),
(1, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(1, 23),
(2, 23),
(3, 23),
(4, 23),
(1, 24),
(2, 24),
(3, 24),
(4, 24),
(3, 25),
(3, 26),
(4, 27),
(4, 28),
(1, 29),
(4, 29),
(3, 30),
(3, 31),
(3, 32),
(1, 31),
(2, 31),
(4, 31),
(4, 33),
(1, 34),
(1, 35),
(4, 34),
(4, 35);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `editor_id` int(11) unsigned DEFAULT NULL,
  `text` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `project_id` (`project_id`),
  KEY `editor_id` (`editor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) unsigned NOT NULL,
  `translated_text` mediumtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `choosen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `address` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `city` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `country` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `username` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `reset` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `role_id` int(2) unsigned NOT NULL,
  `profile_pic` varchar(120) COLLATE latin1_general_ci DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `username_4` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `username_2` (`username`),
  KEY `username_3` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `address`, `city`, `country`, `username`, `password`, `email`, `activated`, `reset`, `role_id`, `profile_pic`, `date_created`) VALUES
(1, 'Admin User', 'Admin''s Address', 'Prishtine', 'Kosova', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin@email.com', 1, '', 1, '', '2013-04-30 22:00:00'),
(2, 'Translator User', 'Translator''s Address', 'Prishtine', 'Kosova', 'translator', '5f4dcc3b5aa765d61d8327deb882cf99', 'translator@email.com', 1, '', 3, '', '2013-04-30 22:00:00'),
(4, 'Editor User', 'Editor''s Address', 'Prishtine', 'Kosova', 'editor', '5f4dcc3b5aa765d61d8327deb882cf99', 'editor@email.com', 1, '', 2, '', '2013-05-03 22:00:00'),
(5, 'Super Editor User', 'Super Editor''s Address', 'Prishtine', 'Kosova', 'supereditor', '5f4dcc3b5aa765d61d8327deb882cf99', 'supereditor@email.com', 1, '', 4, '', '2013-05-03 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) unsigned DEFAULT NULL,
  `audio_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `type` varchar(25) NOT NULL,
  `up_vote` tinyint(1) DEFAULT '0',
  `down_vote` tinyint(1) DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  KEY `audio_id` (`audio_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audios`
--
ALTER TABLE `audios`
  ADD CONSTRAINT `audios_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `audios_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `drafts`
--
ALTER TABLE `drafts`
  ADD CONSTRAINT `drafts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drafts_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roles_x_permissions`
--
ALTER TABLE `roles_x_permissions`
  ADD CONSTRAINT `roles_x_permissions_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_x_permissions_ibfk_3` FOREIGN KEY (`id_permission`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_5` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `tasks_ibfk_6` FOREIGN KEY (`editor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `translations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votes_ibfk_4` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`),
  ADD CONSTRAINT `votes_ibfk_5` FOREIGN KEY (`audio_id`) REFERENCES `audios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
