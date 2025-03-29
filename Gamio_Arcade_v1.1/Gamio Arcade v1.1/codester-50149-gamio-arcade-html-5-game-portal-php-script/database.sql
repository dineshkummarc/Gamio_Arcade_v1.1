CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `code` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `ads` (`id`, `name`, `type`, `code`, `status`) VALUES
(1, 'Box Size Ad', 1, '<div class=\"flex ai jc\">\r\n	<div class=\"bannerSpace banner-300 mt15\">\r\n		<a href=\"#\" class=\"flex fwrap w100 ai jc\"><span class=\"banner-text\">Advertise here</span></a>\r\n	</div>\r\n</div>', 1),
(2, 'Long Banner', 2, '<div class=\"flex ai jc\">\r\n<div class=\"bannerSpace banner-728\">\r\n<a href=\"#\" class=\"flex w100 ai jcb\"><span class=\"banner-text\">Advertise here</span></a>\r\n</div>\r\n</div>', 1);

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `descrip` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `answer` longtext DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `Slug` varchar(255) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Md5` varchar(255) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `Instructions` mediumtext DEFAULT NULL,
  `Thumbnail` mediumtext DEFAULT NULL,
  `ActiveThumbnail` int(11) DEFAULT 0,
  `Featured` varchar(5) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `SubType` varchar(255) DEFAULT NULL,
  `Mobile` varchar(255) DEFAULT NULL,
  `MobileMode` varchar(255) DEFAULT NULL,
  `Height` varchar(255) DEFAULT NULL,
  `Width` varchar(255) DEFAULT NULL,
  `Https` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `Url` mediumtext DEFAULT NULL,
  `Asset` mediumtext DEFAULT NULL,
  `Category` mediumtext DEFAULT NULL,
  `Tag` mediumtext DEFAULT NULL,
  `Company` varchar(50) DEFAULT NULL,
  `Played` int(11) DEFAULT 0,
  `Playtime` int(11) DEFAULT 0,
  `image_store` int(11) DEFAULT 0,
  `image_store_url` varchar(255) DEFAULT NULL,
  `updated` varchar(255) DEFAULT NULL,
  `created` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `game_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `long_desc` longtext DEFAULT NULL,
  `total_game` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `game_categories` (`id`, `name`, `slug`, `description`, `long_desc`, `total_game`) VALUES
(1, 'Arcade', 'arcade', NULL, NULL, NULL),
(2, 'Action', 'action', NULL, NULL, NULL),
(3, 'Adventure', 'adventure', NULL, NULL, NULL),
(4, 'Shooting', 'shooting', NULL, NULL, NULL),
(5, 'Girl', 'girl', NULL, NULL, NULL),
(6, 'Puzzle', 'puzzle', NULL, NULL, NULL),
(7, 'Sport', 'sport', NULL, NULL, NULL),
(8, 'Horror', 'horror', NULL, NULL, NULL),
(9, 'Board', 'board', NULL, NULL, NULL);

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `descrip` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `pages` (`id`, `title`, `prefix`, `content`, `created`, `updated`, `descrip`) VALUES
(1, 'Terms and Conditions', 'terms-and-conditions', 'Type your content here.', NULL, NULL, NULL),
(2, 'Privacy Policy', 'privacy-policy', 'Type your content here.', NULL, NULL, NULL);

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `infoemail` varchar(255) DEFAULT NULL,
  `supportemail` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `default_language` varchar(255) DEFAULT NULL,
  `default_template` varchar(255) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `white_logo` text DEFAULT NULL,
  `require_email_verify` int(11) DEFAULT NULL,
  `require_document_verify` int(11) DEFAULT NULL,
  `enable_recaptcha` int(11) DEFAULT NULL,
  `recaptcha_publickey` varchar(255) DEFAULT NULL,
  `recaptcha_privatekey` varchar(255) DEFAULT NULL,
  `live_chat_code` text DEFAULT NULL,
  `google_analytics_code` text DEFAULT NULL,
  `header` text DEFAULT NULL,
  `footer` text DEFAULT NULL,
  `gamepix_id` varchar(255) DEFAULT NULL,
  `footer_section_h1` varchar(255) DEFAULT NULL,
  `footer_section_text` longtext DEFAULT NULL,
  `footer_section_h2` longtext DEFAULT NULL,
  `footer_section_text2` longtext DEFAULT NULL,
  `footer_section_h3` longtext DEFAULT NULL,
  `footer_section_text3` longtext DEFAULT NULL,
  `footer_section_h4` longtext DEFAULT NULL,
  `footer_section_text4` longtext DEFAULT NULL,
  `footer_section_h5` longtext DEFAULT NULL,
  `footer_section_text5` longtext DEFAULT NULL,
  `leaderboard` int(11) DEFAULT NULL,
  `instagram` varchar(300) NOT NULL DEFAULT '#',
  `facebook` varchar(300) NOT NULL DEFAULT '#',
  `twitter` varchar(300) NOT NULL DEFAULT '#',
  `youtube` varchar(300) NOT NULL DEFAULT '#',
  `new_page` longtext DEFAULT NULL,
  `toppick_page` longtext DEFAULT NULL,
  `trending_page` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE `traffic_sources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `source_name` varchar(255) DEFAULT NULL,
  `today` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_recovery` varchar(255) DEFAULT NULL,
  `document_verified` int(11) DEFAULT NULL,
  `email_verified` int(11) DEFAULT NULL,
  `email_hash` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `account_level` int(11) DEFAULT NULL,
  `account_user` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `signup_time` int(11) DEFAULT NULL,
  `2fa_auth` int(11) DEFAULT NULL,
  `2fa_auth_login` int(11) DEFAULT NULL,
  `2fa_auth_send` int(11) DEFAULT NULL,
  `2fa_auth_withdrawal` int(11) DEFAULT NULL,
  `googlecode` varchar(255) DEFAULT NULL,
  `wallet_passphrase` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `ref1` int(11) DEFAULT NULL,
  `avatar` varchar(400) DEFAULT NULL,
  `Playtime` int(11) DEFAULT 0,
  `Played` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE `users_logs` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `u_field_1` varchar(255) DEFAULT NULL,
  `u_field_2` varchar(255) DEFAULT NULL,
  `u_field_3` varchar(255) DEFAULT NULL,
  `u_field_4` varchar(255) DEFAULT NULL,
  `u_field_5` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `gid` int(11) DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `game_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `traffic_sources`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users_logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `game_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `traffic_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `users_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;