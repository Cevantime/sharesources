--liquibase formatted sql logicalFilePath:changeLog.sql
--changeset installer:init_database
CREATE TABLE IF NOT EXISTS `configurations` (
  `key` varchar(150) NOT NULL,
  `value` text NOT NULL,
  `description` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--changeset module:install_flashmessages_0_init
CREATE TABLE IF NOT EXISTS `flash_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'info',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--changeset module:install_memberspace_0_init
-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'root');

-- --------------------------------------------------------

--
-- Structure de la table `links_groups_rights`
--

CREATE TABLE IF NOT EXISTS `links_groups_rights` (
  `group_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`right_id`),
  KEY `right_id` (`right_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `links_groups_rights`
--

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `links_users_groups`
--

CREATE TABLE IF NOT EXISTS `links_users_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `links_users_groups`
--

INSERT INTO `links_users_groups` (`user_id`, `group_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `links_users_rights`
--

CREATE TABLE IF NOT EXISTS `links_users_rights` (
  `user_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT 'the group by which this right was obtained 0 if not obtained by any group',
  PRIMARY KEY (`user_id`,`right_id`,`group_id`),
  KEY `right_id` (`right_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Structure de la table `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `type` varchar(150) DEFAULT '*',
  `object_key` varchar(150) DEFAULT '*',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_type` (`name`(1),`type`,`object_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `rights`
--

INSERT INTO `rights` (`id`, `name`, `type`, `object_key`) VALUES
(1, '*', '*', '*');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `uniq_login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`) VALUES
(1, 'root', '$2y$10$37DKwcBVUHOUUvohiWMAJegw4sTBtS5veTrRlVJpmobpsNgLbigJW', 'admin@noreply.com');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table 
--
ALTER TABLE `links_groups_rights`
  ADD CONSTRAINT `links_groups_rights_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `links_groups_rights_ibfk_2` FOREIGN KEY (`right_id`) REFERENCES `rights` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `links_users_groups`
--
ALTER TABLE `links_users_groups`
  ADD CONSTRAINT `links_users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `links_users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `links_users_rights`
--
ALTER TABLE `links_users_rights`
  ADD CONSTRAINT `links_users_rights_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `links_users_rights_ibfk_2` FOREIGN KEY (`right_id`) REFERENCES `rights` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset module:install_memberspace_1_add_posts
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
--changeset module:install_memberspace_2_add_confirmed_to_users
ALTER TABLE `users` ADD `confirmed` TINYINT NOT NULL DEFAULT '0';
--changeset module:install_memberspace_3_remove_group_id_from_link_users_rights
ALTER TABLE `links_users_rights` DROP `group_id`;
--changeset module:install_memberspace_4_change_password_length_for_users
ALTER TABLE `users` CHANGE `password` `password` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

--changeset module:install_bo_0_init
--
-- Structure de la table `users_admin`
--

CREATE TABLE IF NOT EXISTS `users_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `forname` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users_admin`
--

INSERT INTO `users_admin` (`id`, `name`, `forname`) VALUES
(1, 'Admin', 'Admin');

--
-- Contraintes pour la table `users_admin`
--
ALTER TABLE `users_admin`
  ADD CONSTRAINT `users_admin_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



--changeset thibault:add_teach_sessions
CREATE TABLE `teach_sessions` ( `id` INT NOT NULL , `name` VARCHAR(100) NOT NULL , `date_start` INT NOT NULL , `date_end` INT NOT NULL , UNIQUE (`name`)) ENGINE = InnoDB;

--changeset thibault:add_primary_key_to_teach_sessions
ALTER TABLE `teach_sessions` ADD PRIMARY KEY (`id`);
ALTER TABLE `teach_sessions` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

--changeset alto:add_courses
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--changeset thibault:add_bbcode_columns_to_courses
ALTER TABLE `courses` ADD `description_bbcode` TEXT NOT NULL ,
ADD `content_bbcode` TEXT NOT NULL ;

--changeset thibault:add_keywords_for_courses
CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_content` (`content`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links_courses_keywords` (
  `course_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  PRIMARY KEY (`course_id`,`keyword_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `links_courses_keywords`
  ADD CONSTRAINT `links_courses_keywords_ibfk_2` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `links_courses_keywords_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `courses` ADD `keywords` VARCHAR( 255 ) NOT NULL ;

--changeset thibault:add_tags
CREATE TABLE `keywords_tag` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `keywords_tag`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `keywords_tag`
  ADD CONSTRAINT `keywords_tag_ibfk_1` FOREIGN KEY (`id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_label_to_tags
ALTER TABLE `keywords_tag` ADD `label` VARCHAR(50) NOT NULL AFTER `id`; 

--changeset thibault:add_alias_to_courses
ALTER TABLE `courses` ADD `alias` VARCHAR( 255 ) NOT NULL ;

--changeset thibault:add_constraints_courses_to_posts

ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_uniq_index_to_alias_for_courses
ALTER TABLE `courses` ADD UNIQUE `uniq_alias` (`alias`);

--changeset thibault:add_link_courses_tags
CREATE TABLE `links_courses_tags` (
  `course_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `links_courses_tags`
  ADD PRIMARY KEY (`course_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);


ALTER TABLE `links_courses_tags`
  ADD CONSTRAINT `links_courses_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `keywords_tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `links_courses_tags_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_tags_in_courses
ALTER TABLE `courses` ADD `tags` VARCHAR(255) NULL AFTER `keywords`;

--changeset thibault:remove_foreign_key_cons_for_key_word_tags
ALTER TABLE keywords_tag DROP FOREIGN KEY keywords_tag_ibfk_1;
ALTER TABLE `keywords_tag` ADD `alias` VARCHAR(50) NOT NULL AFTER `id`;
RENAME TABLE `keywords_tag` TO `tags`;

--changeset thibault:add_auto_inc_for_tags
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `tags` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

--changeset thibault:add_index_on_alias_for_tags
ALTER TABLE `tags` ADD INDEX (`alias`);
--changeset module:install_blog_0_init
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
--
-- Structure de la table `posts_blog`
--

CREATE TABLE IF NOT EXISTS `posts_blog` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `posts_blog`
--
ALTER TABLE `posts_blog`
  ADD CONSTRAINT `posts_blog_ibfk_1` FOREIGN KEY (`id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

--changeset thibault:add_categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `courses` ADD UNIQUE (`category_id`);
--changeset module:install_filebrowser_0_init
CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_folder` tinyint(4) NOT NULL DEFAULT '0',
  `file` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `infos` text,
  `hierarchy` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `type` (`type`);

ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--changeset thibault:change_category_id_in_courses_to_uniq
ALTER TABLE `courses` DROP INDEX `category_id`, ADD INDEX `category_id` (`category_id`) USING BTREE;

--changeset thibault:add_publish_to_courses
ALTER TABLE `courses` ADD `publish` TINYINT NOT NULL DEFAULT '0' AFTER `content_bbcode`;

--changeset thibault:add_insert_right_see_published_course
INSERT INTO `groups` (`id`, `name`) VALUES (2, 'users') ;

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('see', 'course', 'model[course]::isPublished({object})');

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES ('2', LAST_INSERT_ID());

--changeset thibault:add_link_files_courses
CREATE TABLE `link_courses_files` (
  `course_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `link_courses_files`
  ADD PRIMARY KEY (`course_id`,`file_id`),
  ADD KEY `file_id` (`file_id`);

ALTER TABLE `link_courses_files`
  ADD CONSTRAINT `link_courses_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`),
  ADD CONSTRAINT `link_courses_files_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_users_id_to_teach_session
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
ALTER TABLE `teach_sessions` ADD `user_id` INT NOT NULL AFTER `id`, ADD INDEX (`user_id`);
ALTER TABLE `teach_sessions` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
--changeset thibault:add_link_users_teach_sessions
CREATE TABLE `link_users_teach_sessions` (
  `user_id` int(11) NOT NULL,
  `teach_session_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `link_users_teach_sessions`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `teach_session_id` (`teach_session_id`);

ALTER TABLE `link_users_teach_sessions`
  ADD CONSTRAINT `link_users_teach_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `link_users_teach_sessions_ibfk_2` FOREIGN KEY (`teach_session_id`) REFERENCES `teach_sessions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--changeset thibault:remove_dummy_user_id_from_session
ALTER TABLE teach_sessions DROP FOREIGN KEY teach_sessions_ibfk_1 ; 
ALTER TABLE `teach_sessions` DROP `user_id`;

--changeset thibault:set_teachsession_to_userconstraint
ALTER TABLE link_users_teach_sessions DROP FOREIGN KEY link_users_teach_sessions_ibfk_2;
ALTER TABLE `teach_sessions` CHANGE `id` `id` INT(11) NOT NULL;
ALTER TABLE `teach_sessions` ADD FOREIGN KEY (`id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:reset_constraint_link_users_teach_sessions_ibfk_2
ALTER TABLE `link_users_teach_sessions` ADD FOREIGN KEY (`teach_session_id`) REFERENCES `teach_sessions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_default_value_for_keywords_at_courses
ALTER TABLE `courses` CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

--changeset thibault:add_webforce_users
CREATE TABLE `webforce_users` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `webforce_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `webforce_users`
  ADD CONSTRAINT `webforce_users_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_webforce_teachers_table
CREATE TABLE `webforce_teachers` (
  `id` int(11) NOT NULL,
  `current_teachsession` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `webforce_teachers`
  ADD PRIMARY KEY (`id`);

--changeset thibault:add_fornam_and_name_to_teachers
ALTER TABLE `webforce_teachers` ADD `forname` VARCHAR(100) NOT NULL AFTER `id`, ADD `name` VARCHAR(100) NOT NULL AFTER `forname`;

--changeset thibault:add_nullable_for_current_session_on_teachers
ALTER TABLE `webforce_teachers` CHANGE `current_teachsession` `current_teachsession` INT(11) NULL;

--changeset thibault:add_teachers_group_and_assign_rights_to_it
INSERT INTO `groups` (`id`, `name`) VALUES (NULL, 'teachers');

SET @teacherGroupId := LAST_INSERT_ID();

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'see', 'teachsession', '*');

SET @seeSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @seeSessionRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'add', 'teachsession', '*');

SET @addSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @addSessionRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'edit', 'teachsession', 'model[teachsession]::isSharedTo({object},{user})');

SET @editSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @editSessionRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'grab', 'teachsession', '*');

SET @grabSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @grabSessionRight) ; 

--changeset thibault:add_deletecascade_webforce_users
ALTER TABLE `webforce_teachers` ADD FOREIGN KEY (`id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_avatar_to_teachers
ALTER TABLE `webforce_teachers` ADD `avatar` VARCHAR(255) NOT NULL DEFAULT 'uploads/avatars/default.png' AFTER `name`;

--changeset thibault:add_archive_and_set_current_rights_for_teachers_to_sessions
UPDATE `groups` SET `name` = 'teacher' WHERE `name` = 'teachers' ;

SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'archive', 'teachsession', 'model[teachsession]::isSharedTo({object},{user})');

SET @archiveSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @archiveSessionRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'set_current', 'teachsession', 'model[teachsession]::isSharedTo({object},{user})');

SET @setCurrentSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @setCurrentSessionRight) ; 

--changeset thibault:change_right_name_to_update_for_edit_teach_session_right
UPDATE `rights` SET `name` = 'update' WHERE `name` = 'edit' and `type` = 'teachsession' ;



--changeset module:install_memberspace_6_change_index_size_for_right_name_type
ALTER TABLE `rights` DROP INDEX `name_type`, ADD UNIQUE `name_type` (`name`, `type`, `object_key`) USING BTREE;

--changeset thibault:add_courses_shares_and_rights
CREATE TABLE `shares_courses_teachers` (
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `shares_courses_teachers`
  ADD PRIMARY KEY (`course_id`,`teacher_id`),
  ADD KEY `teacher_id` (`teacher_id`);

ALTER TABLE `shares_courses_teachers`
  ADD CONSTRAINT `shares_courses_teachers_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shares_courses_teachers_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `webforce_teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'see', 'course', '*');

SET @seeCourseRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @seeCourseRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'read', 'course', 'model[course]::isSharedTo({object},{user})');

SET @readCourseRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @readCourseRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'share_to_teacher', 'course', 'model[webforceuser]::isAuthor({object},{user})');

SET @shareToTeacherCourseRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @shareToTeacherCourseRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'share_to_teachsession', 'course', 'model[webforceuser]::isAuthor({object},{user})');

SET @shareToTeachSessionRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @shareToTeachSessionRight) ; 

--changeset thibault:change_right_key_for_share_to_teachsession
UPDATE rights SET object_key = 'model[course]::isSharedTo({object},{user})' WHERE `name` = 'share_to_teachsession';

--changeset thibault:add_right_to_add_course_for_teachers
SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'add', 'course', '*');

SET @addCourseRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @addCourseRight) ; 

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'update', 'course', 'model[webforceuser]::isAuthor({object},{user})');

SET @addCourseRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @addCourseRight) ; 

-- changeset thibault:update_course_update_right_for_teacher_to_any
UPDATE rights SET `name` = '*' WHERE `name` = 'update' AND `type` = 'course' AND object_key = 'model[webforceuser]::isAuthor({object},{user})';


--changeset thibault:add_any_right_for_teacher_on_files
SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'see', 'file', '*');

SET @seeFileRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @seeFileRight) ;

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, '*', 'file', 'model[filebrowser/file]::isOwnedBy({object},{user})');

SET @seeFileRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @seeFileRight) ;


--changeset thibault:add_save_right_for_teacher_on_files

SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, 'add', 'file', '*');

SET @seeFileRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @seeFileRight) ;


--changeset thibault:add_share_teachers_session
CREATE TABLE `shares_courses_teachsessions` (
  `course_id` int(11) NOT NULL,
  `teach_session_id` int(11) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `shares_courses_teachsessions`
  ADD PRIMARY KEY (`course_id`,`teach_session_id`);

ALTER TABLE `shares_courses_teachsessions`
  ADD CONSTRAINT `shares_courses_teachsessions_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:replace_right_is_published_by_is_session_shared_to
UPDATE rights SET object_key = 'model[course]::isSharedToSession({object}, {user})' WHERE `name` = 'see' and `type`='course' and object_key = 'model[course]::isPublished({object})';

--changeset thibault:fix_right_is_session_shared_to
UPDATE rights SET object_key = 'model[course]::isSharedToSession({object},{user})' WHERE `name` = 'see' and `type`='course' and object_key = 'model[course]::isSharedToSession({object}, {user})';

--changeset thibault:add_right_category_and_edit_self_teachers
SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, '*', 'categories', '*');

SET @allCategoriesRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @allCategoriesRight) ;

INSERT INTO rights (`id`,`name`,`type`,`object_key`) VALUES (NULL, '*', 'webforceteacher', 'model[webforceuser]::isSelf({object},{user})');

SET @ownProfileRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (group_id, right_id) VALUES (@teacherGroupId, @ownProfileRight) ;

--changeset thibault:update_right_set_type_category_where_categories
UPDATE rights SET `type`= 'category' WHERE type = 'categories' ;

--changeset thibault:set_avatar_for_webforce_user_and_not_only_sessions
ALTER TABLE `webforce_teachers` DROP `avatar`;
ALTER TABLE `webforce_users` ADD `avatar` VARCHAR(255) NOT NULL ;

--changeset thibault:add_rights_for_pdfs_and_files
SELECT id INTO @teacherGroupId FROM `groups` WHERE `name` = 'teacher';
SELECT id INTO @sessionGroupId FROM `groups` WHERE `name` = 'users';

INSERT INTO `rights` (`id`, `name`, `type`, `object_key`) VALUES (NULL, 'see_pdfs', 'course', 'model[course]::isSharedToSession({object},{user})');

SET @seePdfsSessionRight := LAST_INSERT_ID();

INSERT INTO `rights` (`id`, `name`, `type`, `object_key`) VALUES (NULL, 'see_pdfs', 'course', 'model[course]::isSharedTo({object},{user})');

SET @seePdfsTeacherRight := LAST_INSERT_ID();

INSERT INTO `rights` (`id`, `name`, `type`, `object_key`) VALUES (NULL, 'see_files', 'course', 'model[course]::isSharedToSession({object},{user})');

SET @seeFilesSessionRight := LAST_INSERT_ID();

INSERT INTO `rights` (`id`, `name`, `type`, `object_key`) VALUES (NULL, 'see_files', 'course', 'model[course]::isSharedTo({object},{user})');

SET @seeFilesTeacherRight := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@teacherGroupId, @seePdfsTeacherRight),(@teacherGroupId, @seeFilesTeacherRight),(@sessionGroupId,@seePdfsSessionRight),(@sessionGroupId,@seeFilesSessionRight);

--changeset thibault:add_image_to_categories
ALTER TABLE `categories` ADD `image` VARCHAR(255) NOT NULL;

--changeset thibault:add_nullage_on_blogpost_image
ALTER TABLE `posts_blog` CHANGE `image` `image` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

--changeset thibault:add_notifications
CREATE TABLE `notifications` ( `id` INT NOT NULL AUTO_INCREMENT , `type` ENUM('NEW_COURSE','NEW_PUBLIC_COURSE','NEW_COURSE_SHARE_TEACHER','NEW_COURSE_SHARE_SESSION','CUSTOM') NOT NULL , `visibility` INT NOT NULL , `infos` TEXT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `users_have_notifications` (
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users_have_notifications`
  ADD PRIMARY KEY (`user_id`,`notification_id`),
  ADD KEY `notification_id` (`notification_id`);

ALTER TABLE `users_have_notifications`
  ADD CONSTRAINT `users_have_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_have_notifications_ibfk_2` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`);

CREATE TABLE `users_saw_notifications` (
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users_saw_notifications`
  ADD PRIMARY KEY (`user_id`,`notification_id`),
  ADD KEY `notification_id` (`notification_id`);

ALTER TABLE `users_saw_notifications`
  ADD CONSTRAINT `users_saw_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_saw_notifications_ibfk_2` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_created_time_to_notifications
ALTER TABLE `notifications` ADD `created_time` INT NOT NULL;

--changeset thibault:add_delete_cascade_on_user_have_notification_notification_id_constraint
ALTER TABLE `users_have_notifications` DROP FOREIGN KEY `users_have_notifications_ibfk_2`; 
ALTER TABLE `users_have_notifications` ADD CONSTRAINT `users_have_notifications_ibfk_2` FOREIGN KEY (`notification_id`) REFERENCES `notifications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:avatar_can_now_be_null
ALTER TABLE `webforce_users` CHANGE `avatar` `avatar` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

--changeset thibault:add_color_to_categories
ALTER TABLE `categories` ADD `color` VARCHAR(10) NOT NULL;

--changeset thibault:add_user_root
INSERT INTO `webforce_users` (`id`, `avatar`) VALUES ('1', NULL);

--changeset thibault:add_administrators_group
INSERT INTO `groups` (`id`, `name`) VALUES (NULL, 'administrators');

SET @adminGroupId := LAST_INSERT_ID();

SELECT id INTO @allCatRightId FROM `rights` WHERE `name` = '*' AND `type`='category' AND `object_key`='*';

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','course','*');

SET @allCourseRightId := LAST_INSERT_ID();

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','webforceteacher','*');

SET @allTeachersRightId := LAST_INSERT_ID();

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','teachsession','*');

SET @allSessionsRightId := LAST_INSERT_ID();

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','file','*');

SET @allFilesRightId := LAST_INSERT_ID();

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('see','bo/admin','*');

SET @seeAdminsRightId := LAST_INSERT_ID();

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','bo/admin','model[webforceuser]::isSelf({object},{user})');

SET @selfAdminRightId := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@adminGroupId, @allCatRightId),(@adminGroupId, @allCourseRightId),(@adminGroupId, @allTeachersRightId),(@adminGroupId, @allSessionsRightId),(@adminGroupId, @allFilesRightId),(@adminGroupId, @seeAdminsRightId),(@adminGroupId, @selfAdminRightId);

--changeset thibault:change_type_of_right_admin
UPDATE `rights` SET `type`='webforceadmin' WHERE `type`='bo/admin';

--changeset thibault:add_table_webforce_admins

CREATE TABLE `webforce_admins` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `webforce_admins`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `webforce_admins`
  ADD CONSTRAINT `webforce_admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:change_col_name_admin_primary
ALTER TABLE `webforce_admins` CHANGE `user_id` `id` INT(11) NOT NULL;

--changeset thibault:add_right_add_admin_to_admin
SELECT id INTO @adminGroupId FROM groups WHERE `name` = 'administrators';

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','webforceadmin','*');

SET @addAdminRightId := LAST_INSERT_ID();

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','configuration','*');

SET @configurationRightId := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@adminGroupId, @addAdminRightId),(@adminGroupId, @configurationRightId);

--changeset thibault:remove_right_do_any_with_admin_from_admins
SELECT id INTO @adminGroupId FROM groups WHERE `name` = 'administrators';
SELECT id INTO @doAnyAdminRightId FROM rights WHERE `name` = '*' AND `type`='webforceadmin' AND `object_key`='*';

DELETE FROM `links_groups_rights` WHERE group_id = @adminGroupId AND right_id = @doAnyAdminRightId;

--changeset thibault:add_delete_casacade_link_courses_files
ALTER TABLE `link_courses_files` DROP FOREIGN KEY `link_courses_files_ibfk_2`; 
ALTER TABLE `link_courses_files` ADD CONSTRAINT `link_courses_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--changeset thibault:add_teacher_root
INSERT INTO `webforce_teachers` (`id`, `forname`, `name`) VALUES ('1', 'Admin','Admin');

--changeset thibault:add_webforce_admin_root
INSERT INTO `webforce_admins` (`id`) VALUES ('1');

--changeset thibault:add_messages
CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `from_id` int(11) DEFAULT NULL,
  `to_id` int(11) NOT NULL,
  `from_forname` varchar(100) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--changeset thibault:add_forgotten_auto_inc_on_id_messages
ALTER TABLE `messages` ADD PRIMARY KEY(`id`);
ALTER TABLE `messages` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

--changeset thibault:add_right_any_any_notifications_to_admins
SELECT id INTO @adminGroupId FROM groups WHERE `name` = 'administrators';

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('*','notification','*');

SET @addAdminRightId := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@adminGroupId, @addAdminRightId);

--changeset thibault:add_right_see_teachers_to_teachers
SELECT id INTO @teachersGroupId FROM groups WHERE `name` = 'teacher';

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('see','webforceteacher','*');

SET @seeTeachersRightId := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@teachersGroupId, @seeTeachersRightId);

--changeset thibault:add_right_see_notification_for_webforceusers
SELECT id INTO @teachersGroupId FROM groups WHERE `name` = 'teacher';
SELECT id INTO @usersGroupId FROM groups WHERE `name` = 'users';
SELECT id INTO @adminGroupId FROM groups WHERE `name` = 'administrators';

INSERT INTO `rights` (`name`, `type`, `object_key`) VALUES ('see','notification','model[notification]::isVisibleByUser({object},{user})');

SET @seeNotificationRightId := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@teachersGroupId, @seeNotificationRightId),(@usersGroupId, @seeNotificationRightId),(@adminGroupId, @seeNotificationRightId);

--changeset thibault:remove_rights_for_teachers_to_edit_or_delete_categories

SELECT id INTO @anyOncatRightId FROM rights WHERE `name` = '*' AND `type` = 'category' AND `object_key` = '*' ;
SELECT id INTO @teacherGroupId FROM groups WHERE `name` = 'teacher';

DELETE FROM `links_groups_rights` WHERE group_id = @teacherGroupId AND right_id = @anyOncatRightId;

INSERT INTO rights (`name`,`type`, `object_key`) VALUES ('see', 'category', '*');

SET @seeCatRightId := LAST_INSERT_ID();

INSERT INTO `links_groups_rights` (`group_id`, `right_id`) VALUES (@teacherGroupId, @seeCatRightId);

--changeset thibault:add_preferences_to_teachers
ALTER TABLE `webforce_teachers` ADD `preferences` TEXT NOT NULL;

