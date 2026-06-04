-- Full System Backup - DB Part
-- Generated: 2026-06-02 20:56:48

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `record_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_log_module` (`module`),
  KEY `idx_log_created` (`created_at`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('1', '2', 'customers', 'create', 'Created customer TechVision Pvt Ltd', '1', '127.0.0.1', NULL, '2026-05-31 06:13:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('2', '2', 'quotations', 'create', 'Created quotation QT0001 for TechVision', '1', '127.0.0.1', NULL, '2026-05-31 06:13:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('3', '2', 'orders', 'create', 'Converted quotation to order ORD0001', '1', '127.0.0.1', NULL, '2026-05-31 06:13:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('4', '2', 'invoices', 'create', 'Generated invoice INV0001', '1', '127.0.0.1', NULL, '2026-05-31 06:13:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('5', '3', 'prospects', 'create', 'Added new lead: Rohit Bansal', '1', '127.0.0.1', NULL, '2026-05-31 06:13:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('6', '2', 'payments', 'create', 'Recorded payment from TechVision - ₹1,35,700', '1', '127.0.0.1', NULL, '2026-05-31 06:13:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('7', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 06:17:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('8', '1', 'auth', 'update', 'User changed their password', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 06:26:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('9', '1', 'settings', 'restore', 'Program files restored from: files_backup_2026-05-19_182134.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 06:27:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('10', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 09:02:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('11', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 19:50:03');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('12', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 20:00:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('13', '1', 'settings', 'restore', 'Program files restored from: files_backup_2026-05-13_122834.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 20:00:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('14', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 22:20:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('15', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 22:21:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('16', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 22:22:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('17', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 23:56:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('18', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 23:57:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('19', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 01:19:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('20', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 01:26:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('21', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 01:27:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('22', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 01:53:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('23', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 01:53:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('24', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 02:54:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('25', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 03:40:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('26', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 03:44:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('27', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 03:47:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('28', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 03:54:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('29', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 05:20:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('30', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 12:54:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('31', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 14:09:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('32', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 15:15:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('33', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 16:19:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('34', '1', 'settings', 'backup', 'Full system backup created: 2026-06-01_163633', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 16:36:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('35', '1', 'settings', 'restore', 'Program files restored from: files_backup_2026-06-01_163633.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 16:53:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('36', '1', 'settings', 'restore', 'Program files restored from: files_backup_2026-05-19_182134.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 16:54:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('37', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:21:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('38', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:28:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('39', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:28:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('40', '1', 'settings', 'restore', 'Program files restored from: files_backup_2026-06-01_163633.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:28:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('41', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:28:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('42', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:29:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('43', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:32:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('44', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 17:32:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('45', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 19:04:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('46', '1', 'settings', 'backup', 'Full system backup created: 2026-06-01_194314', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 19:43:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('47', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 20:05:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('48', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 21:30:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('49', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 22:49:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('50', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 22:51:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('51', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 22:52:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('52', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 22:55:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('53', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 22:55:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('54', '1', 'settings', 'backup', 'Full system backup created: 2026-06-01_231357', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:13:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('55', '1', 'settings', 'download', 'Backup downloaded: files_backup_2026-06-01_231357.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:14:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('56', '1', 'settings', 'download', 'Backup downloaded: files_backup_2026-06-01_231357.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:14:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('57', '1', 'settings', 'download', 'Backup downloaded: files_backup_2026-06-01_231357.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:14:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('58', '1', 'settings', 'download', 'Backup downloaded: files_backup_2026-06-01_231357.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:14:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('59', '1', 'settings', 'download', 'Backup downloaded: db_backup_2026-06-01_231357.sql', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:14:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('60', '1', 'customers', 'create', 'Created customer: RAVIANT N PARMAR', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:24:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('61', '1', 'products', 'delete', 'Deleted product: Annual Maintenance Contract', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:33:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('62', '1', 'products', 'delete', 'Deleted product: CRM Software License', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:33:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('63', '1', 'products', 'delete', 'Deleted product: Dell Laptop i5', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:33:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('64', '1', 'products', 'delete', 'Deleted product: ERP Implementation', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:33:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('65', '1', 'products', 'delete', 'Deleted product: HP LaserJet Printer', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:33:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('66', '1', 'products', 'delete', 'Deleted product: IT Consulting - Hourly', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:33:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('67', '1', 'invoices', 'delete', 'Deleted invoice: INV0002', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:40:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('68', '1', 'quotations', 'delete', 'Deleted quotation: QT0001', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:41:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('69', '1', 'orders', 'delete', 'Deleted order: ORD0002', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:41:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('70', '1', 'products', 'delete', 'Deleted product: Keyboard & Mouse Combo', '12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:41:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('71', '1', 'products', 'delete', 'Deleted product: Netgear WiFi Router', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:41:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('72', '1', 'products', 'delete', 'Deleted product: Samsung Monitor 24\"', '11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:41:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('73', '1', 'products', 'delete', 'Deleted product: SEO Package - Monthly', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:41:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('74', '1', 'products', 'update', 'Updated product: Social Media Management', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:55:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('75', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:07:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('76', '1', 'products', 'update', 'Updated product: Shower Enclousure', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:16:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('77', '1', 'products', 'update', 'Updated product: Flush Door', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:17:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('78', '1', 'products', 'create', 'Created product: Partition Systems', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:21:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('79', '1', 'products', 'update', 'Updated product: Flush Door', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:21:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('80', '1', 'products', 'update', 'Updated product: Flush Door', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:21:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('81', '1', 'products', 'update', 'Updated product: Shower Enclousure', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:21:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('82', '1', 'quotations', 'create', 'Created quotation: QT0006', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:54:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('83', '1', 'quotations', 'update', 'Updated quotation: QT0006', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 00:58:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('84', '1', 'quotations', 'create', 'Created quotation: QT0007', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 01:00:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('85', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 01:05:00');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('86', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 01:06:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('87', '1', 'customers', 'create', 'Created customer: Mahendra Singh Rathore', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 01:30:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('88', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:16:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('89', '1', 'quotations', 'create', 'Created quotation: QT0008', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:17:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('90', '1', 'quotations', 'create', 'Created quotation: QT0009', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:33:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('91', '1', 'quotations', 'delete', 'Deleted quotation: QT0008', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:33:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('92', '1', 'quotations', 'delete', 'Deleted quotation: QT0009', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:33:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('93', '1', 'quotations', 'delete', 'Deleted quotation: QT0007', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:33:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('94', '1', 'quotations', 'delete', 'Deleted quotation: QT0006', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:33:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('95', '1', 'quotations', 'create', 'Created quotation: QT0001', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:34:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('96', '1', 'products', 'update', 'Updated product: Flush Door', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:35:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('97', '1', 'quotations', 'create', 'Created quotation: QT0002', '11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 11:35:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('98', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 13:00:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('99', '1', 'customers', 'edit', 'Updated customer: spantiles.com', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 13:36:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('100', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 14:03:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('101', '1', 'quotations', 'update', 'Updated quotation: QT0001', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 14:45:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('102', '1', 'quotations', 'update', 'Updated quotation: QT0002', '14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 14:58:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('103', '1', 'settings', 'update', 'Reset password for user ID: 1', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:00:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('104', '1', 'auth', 'login', 'User logged in: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:04:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('105', '1', 'settings', 'update', 'Updated user info: ravikaant@sushobha.com', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:05:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('106', '1', 'settings', 'update', 'Updated user info: parvez@sushobha.com', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:06:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('107', '1', 'settings', 'update', 'Updated user info: girish@sushobha.com', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:07:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('108', '1', 'settings', 'update', 'Updated user info: ranjit@sushobha.com', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:08:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('109', '1', 'auth', 'logout', 'User logged out: superadmin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:08:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('110', '4', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 15:09:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('111', '4', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 16:19:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('112', '4', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 17:55:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('113', '4', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 19:05:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('114', '4', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-02 20:37:23');


DROP TABLE IF EXISTS `address_types`;
CREATE TABLE `address_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Site Address', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Office Address', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Home Address', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Billing Address', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Shipping Address', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Registered Address', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `address_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Warehouse Address', '7', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `announcement_comments`;
CREATE TABLE `announcement_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `announcement_id` (`announcement_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `announcement_comments_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcement_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `priority` varchar(50) DEFAULT 'Normal',
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `announcements` (`id`, `title`, `content`, `category`, `priority`, `is_active`, `created_by`, `created_at`) VALUES ('1', 'Welcome to SUSHOBHA CRM', 'Welcome to the newly updated dashboard! Please explore the new features.', 'System', 'High', '1', '1', '2026-05-31 06:23:59');


DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','half_day','leave','holiday') DEFAULT 'present',
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_attendance` (`employee_id`,`date`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('1', '1', '2026-05-30', 'present', '09:02:00', '18:10:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('2', '2', '2026-05-30', 'present', '09:15:00', '18:05:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('3', '3', '2026-05-30', 'present', '09:30:00', '18:00:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('4', '4', '2026-05-30', 'leave', NULL, NULL, NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('5', '5', '2026-05-30', 'present', '09:05:00', '18:20:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('6', '1', '2026-05-29', 'present', '09:00:00', '18:00:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('7', '2', '2026-05-29', 'half_day', '09:00:00', '13:00:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('8', '3', '2026-05-29', 'present', '09:10:00', '18:05:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('9', '4', '2026-05-29', 'present', '09:00:00', '18:00:00', NULL, '2026-05-31 06:13:13');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('10', '5', '2026-05-29', 'absent', NULL, NULL, NULL, '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `business_categories`;
CREATE TABLE `business_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `business_categories` (`id`, `category_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'B2B', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `business_categories` (`id`, `category_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'B2C', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `business_categories` (`id`, `category_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'D2C', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `business_categories` (`id`, `category_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Export', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `business_categories` (`id`, `category_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Import', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `business_categories` (`id`, `category_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'B2G', '6', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `company_statuses`;
CREATE TABLE `company_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(100) NOT NULL,
  `color_code` varchar(20) DEFAULT '#64748b',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_name` (`status_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `company_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Active', '#22c55e', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `company_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Inactive', '#94a3b8', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `company_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Prospect', '#3b82f6', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `company_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Blacklisted', '#ef4444', '4', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `company_types`;
CREATE TABLE `company_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Individual', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Partnership', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Proprietorship\r\n', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Pvt Ltd', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Ltd (Public Limited)', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Government Organization', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Trust / NGO', '7', '1', '2026-05-31 23:51:17');
INSERT INTO `company_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Other', '8', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `contact_relations`;
CREATE TABLE `contact_relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(10) unsigned NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(10) unsigned NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_relation_unique` (`contact_id`,`entity_type`,`entity_id`),
  CONSTRAINT `contact_relations_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('2', '2', 'lead', '3', 'Owner', '0', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('3', '3', 'lead', '4', 'Owner', '1', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('4', '4', 'lead', '5', 'Owner', '1', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('5', '3', 'lead', '5', 'Architect', '0', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('6', '1', 'customer', '1', 'Owner', '1', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('7', '2', 'customer', '1', 'Owner', '0', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('8', '3', 'customer', '2', 'Owner', '1', '2026-06-02 15:59:32');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('22', '5', 'lead', '4', 'Entrepreneur', '0', '2026-06-02 20:43:06');
INSERT INTO `contact_relations` (`id`, `contact_id`, `entity_type`, `entity_id`, `role`, `is_primary`, `created_at`) VALUES ('23', '6', 'lead', '4', 'Architect', '0', '2026-06-02 20:43:49');


DROP TABLE IF EXISTS `contact_types`;
CREATE TABLE `contact_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Owner', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Entrepreneur', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Manager', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Architect', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Engineer', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Contractor', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Purchase Head', '7', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Site Supervisor', '8', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Interior Designer', '9', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Builder', '10', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'Consultant', '11', '1', '2026-05-31 23:51:17');
INSERT INTO `contact_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Other', '12', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_type` varchar(100) DEFAULT 'Other',
  `name` varchar(255) NOT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `gst_number` varchar(50) DEFAULT NULL,
  `visiting_card` text DEFAULT NULL,
  `social_links` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_contact_mobile_name` (`mobile`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contacts` (`id`, `contact_type`, `name`, `organization_name`, `designation`, `mobile`, `whatsapp`, `email`, `address`, `city`, `state`, `pincode`, `website`, `gst_number`, `visiting_card`, `social_links`, `created_at`, `updated_at`) VALUES ('1', 'Architect', 'RAVIANT N PARMAR', 'Global Traders', NULL, '09012548540', '9898989999', 'krishna@gmail.com', 'A-102\r\nSwagat Status 2', 'Ahmedabad', 'Gujarat', '382424', NULL, NULL, NULL, NULL, '2026-06-02 15:59:32', '2026-06-02 19:24:11');
INSERT INTO `contacts` (`id`, `contact_type`, `name`, `organization_name`, `designation`, `mobile`, `whatsapp`, `email`, `address`, `city`, `state`, `pincode`, `website`, `gst_number`, `visiting_card`, `social_links`, `created_at`, `updated_at`) VALUES ('2', 'Owner', 'Dhruv Ravikant Parmar', NULL, NULL, '07043382023', NULL, 'parmardhruv96@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-02 15:59:32', '2026-06-02 15:59:32');
INSERT INTO `contacts` (`id`, `contact_type`, `name`, `organization_name`, `designation`, `mobile`, `whatsapp`, `email`, `address`, `city`, `state`, `pincode`, `website`, `gst_number`, `visiting_card`, `social_links`, `created_at`, `updated_at`) VALUES ('3', 'Owner', 'Mahendra Singh Rathore', '', 'Employee', '9887596515', '9887596515', 'mahendra@gmail.com', '', '', '', '', '', NULL, '[\"uploads\\/leads\\/cards\\/6a1d619d69bd9_ChatGPT_Image_May_12__2026__02_48_17_PM.png\"]', NULL, '2026-06-02 15:59:32', '2026-06-02 19:43:48');
INSERT INTO `contacts` (`id`, `contact_type`, `name`, `organization_name`, `designation`, `mobile`, `whatsapp`, `email`, `address`, `city`, `state`, `pincode`, `website`, `gst_number`, `visiting_card`, `social_links`, `created_at`, `updated_at`) VALUES ('4', 'Owner', 'Ranjit Srivastava', NULL, 'Owner', '9855498542', NULL, 'ranjit@camrola.com', NULL, NULL, NULL, NULL, NULL, NULL, '[\"uploads\\/leads\\/cards\\/6a1de34897947_ChatGPT_Image_May_12__2026__02_48_17_PM.png\"]', NULL, '2026-06-02 15:59:32', '2026-06-02 15:59:32');
INSERT INTO `contacts` (`id`, `contact_type`, `name`, `organization_name`, `designation`, `mobile`, `whatsapp`, `email`, `address`, `city`, `state`, `pincode`, `website`, `gst_number`, `visiting_card`, `social_links`, `created_at`, `updated_at`) VALUES ('5', 'Entrepreneur', 'Ravindra Gajjar', 'Global Traders', NULL, '09898098980', '9898989999', 'ravikaantnparmar@gmail.com', 'A-102\r\nSwagat Status 2', 'Ahmedabad', 'Gujarat', '382424', '', NULL, NULL, NULL, '2026-06-02 19:43:17', '2026-06-02 19:43:17');
INSERT INTO `contacts` (`id`, `contact_type`, `name`, `organization_name`, `designation`, `mobile`, `whatsapp`, `email`, `address`, `city`, `state`, `pincode`, `website`, `gst_number`, `visiting_card`, `social_links`, `created_at`, `updated_at`) VALUES ('6', 'Architect', 'Vijay N Parmar', 'ABC Consultants', 'Owner', '9898549898', '9898549898', 'ravikant.parmar@ymail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-02 20:43:49', '2026-06-02 20:45:30');


DROP TABLE IF EXISTS `customer_addresses`;
CREATE TABLE `customer_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `address_type` varchar(100) DEFAULT NULL,
  `address_line1` text DEFAULT NULL,
  `address_line2` text DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `pincode` varchar(20) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `google_location` text DEFAULT NULL,
  `google_address` text DEFAULT NULL,
  `google_maps_link` text DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `fk_cust_address` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `customer_contacts`;
CREATE TABLE `customer_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `contact_type` varchar(100) DEFAULT 'Primary',
  `name` varchar(255) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `alt_mobile` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `visiting_card` text DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `organization_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `fk_cust_contact` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customer_contacts` (`id`, `customer_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('1', '1', 'Owner', 'RAVIANT N PARMAR', NULL, '9898549909', NULL, '9898549909', 'krishna@gmail.com', NULL, '1', '2026-06-01 23:24:14', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `customer_contacts` (`id`, `customer_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('2', '1', 'Owner', 'Dhruv Ravikant Parmar', NULL, '07043382023', NULL, NULL, 'parmardhruv96@gmail.com', NULL, '0', '2026-06-01 23:24:14', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `customer_contacts` (`id`, `customer_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('3', '2', 'Owner', 'Mahendra Singh Rathore', 'Employee', '9887596515', NULL, '9887596515', 'mahendra@gmail.com', NULL, '1', '2026-06-02 01:30:59', NULL, NULL, NULL, NULL, NULL, NULL);


DROP TABLE IF EXISTS `customer_documents`;
CREATE TABLE `customer_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `uploaded_from` enum('Mobile','Device') DEFAULT 'Device',
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `fk_cust_doc` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `customer_meetings`;
CREATE TABLE `customer_meetings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `priority` varchar(20) DEFAULT 'Medium',
  `meeting_with` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `followup_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `fk_cust_meeting` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `customer_types`;
CREATE TABLE `customer_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Dealer', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Distributor', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Architect', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Interior Designer', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Builder', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Contractor', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Retail Customer', '7', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Corporate Client', '8', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Vendor/Supplier', '9', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Channel Partner', '10', '1', '2026-05-31 23:51:17');
INSERT INTO `customer_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'End User', '11', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_lead_id` int(10) unsigned DEFAULT NULL,
  `customer_code` varchar(50) NOT NULL,
  `customer_date` date NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_type` varchar(100) DEFAULT NULL,
  `industry_type` varchar(100) DEFAULT NULL,
  `business_category` varchar(100) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `gst_number` varchar(50) DEFAULT NULL,
  `tin_number` varchar(50) DEFAULT NULL,
  `company_status` varchar(50) DEFAULT 'Active',
  `address_line1` text DEFAULT NULL,
  `address_line2` text DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `pincode` varchar(20) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `google_location` text DEFAULT NULL,
  `google_address` text DEFAULT NULL,
  `google_maps_link` text DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `requirement_description` text DEFAULT NULL,
  `converted_from_lead` tinyint(1) DEFAULT 1,
  `assigned_to` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_code` (`customer_code`),
  KEY `source_lead_id` (`source_lead_id`),
  KEY `assigned_to` (`assigned_to`),
  CONSTRAINT `fk_cust_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_cust_lead` FOREIGN KEY (`source_lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` (`id`, `source_lead_id`, `customer_code`, `customer_date`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `tin_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `converted_from_lead`, `assigned_to`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('1', '3', 'CUST0001', '2026-06-01', 'spantiles.com', 'Retail Customer', 'Interior Design', 'B2B', 'krishna@gmail.com', 'shreepad.com', '', '', 'Active', 'A-102, Swagat Status 2\r\nOff New CG Road, Near Doon Blossom School', NULL, NULL, 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', NULL, NULL, NULL, NULL, NULL, NULL, 'Converted from Lead: LEAD000001', '1', NULL, '1', NULL, '2026-06-01 23:24:14', '2026-06-01 23:24:14', NULL);
INSERT INTO `customers` (`id`, `source_lead_id`, `customer_code`, `customer_date`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `tin_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `converted_from_lead`, `assigned_to`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('2', '4', 'CUST0002', '2026-06-02', 'Absolute Consultatns Pvt Ltd.', 'Retail Customer', 'Manufacturing', 'B2C', 'vishal@absolute.com', 'www.absolute.com', 'AHD54857DFGA', '', 'Active', 'Karaka Building, Navrangpura\r\nNear Farki Hotel', NULL, NULL, 'Ahmedabad', 'Gujarat', 'India', '380009', NULL, NULL, NULL, NULL, NULL, NULL, 'Converted from Lead: LEAD000002\r\nThey want to close this deal very fast', '1', NULL, '1', NULL, '2026-06-02 01:30:59', '2026-06-02 01:30:59', NULL);


DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `emp_code` varchar(20) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT 0.00,
  `salary_type` enum('monthly','weekly','daily','hourly') DEFAULT 'monthly',
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(30) DEFAULT NULL,
  `bank_ifsc` varchar(15) DEFAULT NULL,
  `status` enum('active','inactive','terminated') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_code` (`emp_code`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`) VALUES ('1', '2', 'EMP0001', 'Ravi Kumar', 'ravi@sushobha.com', '+91 98765 43210', 'Management', 'General Manager', '2022-01-01', '75000.00', 'monthly', NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`) VALUES ('2', '3', 'EMP0002', 'Priya Sharma', 'priya@sushobha.com', '+91 91234 56789', 'Sales', 'Sales Manager', '2022-06-01', '55000.00', 'monthly', NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`) VALUES ('3', '4', 'EMP0003', 'Anita Nair', 'anita@sushobha.com', '+91 80123 45678', 'Finance', 'Senior Accountant', '2023-01-15', '50000.00', 'monthly', NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`) VALUES ('4', '5', 'EMP0004', 'Suresh Reddy', 'suresh@sushobha.com', '+91 70987 65432', 'IT', 'Software Developer', '2023-07-01', '45000.00', 'monthly', NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`) VALUES ('5', NULL, 'EMP0005', 'Kiran Bhat', 'kiran@sushobha.com', '+91 99887 76655', 'Sales', 'Sales Executive', '2024-01-01', '35000.00', 'monthly', NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('1', 'Office Rent', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('2', 'Salaries', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('3', 'Utilities', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('4', 'Travel & Conveyance', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('5', 'Marketing & Advertising', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('6', 'Software Subscriptions', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('7', 'Office Supplies', NULL, '2026-05-31 06:13:13');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('8', 'Miscellaneous', NULL, '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer','card','cheque','other') DEFAULT 'cash',
  `reference` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_expense_date` (`expense_date`),
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('1', '1', 'Office Rent - April 2026', '25000.00', '2026-04-01', 'bank_transfer', NULL, 'Monthly office rent', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('2', '3', 'Electricity Bill - April', '3500.00', '2026-04-05', 'bank_transfer', NULL, 'Electricity charges', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('3', '4', 'Team Dinner - Client Visit', '4200.00', '2026-04-10', 'card', NULL, 'Team dinner after client demo', NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('4', '5', 'Google Ads Campaign', '15000.00', '2026-04-01', 'card', NULL, 'April digital marketing budget', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('5', '6', 'Adobe Creative Cloud', '3540.00', '2026-04-01', 'card', NULL, 'Annual subscription / 12', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('6', '7', 'Printer Ink & Toner', '1800.00', '2026-04-15', 'cash', NULL, 'Office supplies', NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('7', '1', 'Office Rent - May 2026', '25000.00', '2026-05-01', 'bank_transfer', NULL, 'Monthly office rent', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('8', '3', 'Internet Bill - April', '1200.00', '2026-04-01', 'bank_transfer', NULL, 'Broadband charges', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('9', '4', 'Fuel Reimbursement', '2500.00', '2026-04-20', 'cash', NULL, 'Sales team travel', NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('10', '8', 'Miscellaneous Expenses', '800.00', '2026-04-25', 'cash', NULL, 'Petty cash expenses', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `industry_types`;
CREATE TABLE `industry_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Manufacturing', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Trading', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Service', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Retail', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Wholesale', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Construction', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Interior Design', '7', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Real Estate', '8', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Furniture', '9', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Luxury Products', '10', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'Architecture Firm', '11', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Hospitality', '12', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('13', 'IT & Technology', '13', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('14', 'Healthcare', '14', '1', '2026-05-31 23:51:17');
INSERT INTO `industry_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('15', 'Education', '15', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `interested_products`;
CREATE TABLE `interested_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(200) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_name` (`product_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Partition Systems', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Shower Enclosures', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Flush Doors', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Cabinets & Storage Solutions', '4', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Illuminated Walls', '5', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Glass Surface', '6', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'LED Mirrors', '7', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Modular Kitchen', '8', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Wardrobe', '9', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Vanity Units', '10', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'Decorative Panels', '11', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Office Partitions', '12', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('13', 'Sliding Doors', '13', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('14', 'Cladding Panels', '14', '1', '2026-05-31 23:51:16');
INSERT INTO `interested_products` (`id`, `product_name`, `sort_order`, `is_active`, `created_at`) VALUES ('15', 'Handrails & Balustrades', '15', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(250) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) DEFAULT 'Nos',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(30) NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `status` enum('draft','sent','paid','partial','overdue','cancelled') DEFAULT 'draft',
  `issued_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `paid_amount` decimal(12,2) DEFAULT 0.00,
  `balance_due` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `order_id` (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_invoice_status` (`status`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `invoices_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'INV0001', '1', '5', 'paid', '2026-04-01', '2026-04-30', '115000.00', '0.00', '20700.00', '135700.00', '135700.00', '0.00', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'INV0003', '3', '7', 'sent', '2026-04-20', '2026-05-20', '35000.00', '0.00', '6300.00', '41300.00', '0.00', '41300.00', NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'INV0004', '4', '9', 'paid', '2026-03-15', '2026-04-15', '58000.00', '0.00', '10440.00', '68440.00', '68440.00', '0.00', NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'INV0005', '5', '3', 'partial', '2026-04-25', '2026-05-25', '120000.00', '0.00', '21600.00', '141600.00', '50000.00', '91600.00', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `lead_addresses`;
CREATE TABLE `lead_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `address_type` varchar(100) DEFAULT NULL,
  `address_line1` text DEFAULT NULL,
  `address_line2` text DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `pincode` varchar(20) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `google_location` text DEFAULT NULL,
  `google_address` text DEFAULT NULL,
  `google_maps_link` text DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_addresses_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_addresses` (`id`, `lead_id`, `address_type`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `is_primary`, `created_at`, `updated_at`) VALUES ('30', '3', 'Office Address', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', NULL, 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '23.11201398', '72.59417376', '23.112014, 72.594174', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.112013977773106,72.59417376294273', '1', '2026-06-01 21:45:00', '2026-06-01 21:45:00');
INSERT INTO `lead_addresses` (`id`, `lead_id`, `address_type`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `is_primary`, `created_at`, `updated_at`) VALUES ('31', '4', 'Office Address', 'Karaka Building, Navrangpura', 'Near Farki Hotel', 'Navrangpura', 'Ahmedabad', 'Gujarat', 'India', '380009', '23.11201163', '72.59416969', '23.112012, 72.594170', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.11201163388454,72.5941696900821', '1', '2026-06-02 01:08:29', '2026-06-02 01:08:29');
INSERT INTO `lead_addresses` (`id`, `lead_id`, `address_type`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `is_primary`, `created_at`, `updated_at`) VALUES ('32', '5', 'Office Address', '516, Gala empire, Opp TV Tower, Metro Station, ', 'Drive In Road,', 'Thaltej', 'Ahmedabad', 'Gujarat', 'India', '380054', '23.11201163', '72.59416969', '23.112012, 72.594170', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.11201163388454,72.5941696900821', '1', '2026-06-02 01:23:44', '2026-06-02 01:23:44');


DROP TABLE IF EXISTS `lead_contacts`;
CREATE TABLE `lead_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `contact_type` varchar(100) DEFAULT 'Primary',
  `name` varchar(255) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `alt_mobile` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `visiting_card` text DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `organization_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_contacts_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('47', '3', 'Owner', 'RAVIANT N PARMAR', NULL, '09012548540', NULL, NULL, 'krishna@gmail.com', '[\"uploads\\/leads\\/cards\\/\\/6a1d5f49546a3_ChatGPT_Image_May_12__2026__02_48_17_PM.png\",\"uploads\\/leads\\/cards\\/\\/6a1d5f6ed3c84_WhatsApp_Image_2026-05-07_at_9.39.08_PM.jpeg\"]', '1', '2026-06-01 21:45:00', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('48', '3', 'Owner', 'Dhruv Ravikant Parmar', NULL, '07043382023', NULL, NULL, 'parmardhruv96@gmail.com', NULL, '0', '2026-06-01 21:45:00', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('49', '4', 'Owner', 'Mahendra Singh Rathore', 'Employee', '9887596515', NULL, '9887596515', 'mahendra@gmail.com', '[\"uploads\\/leads\\/cards\\/6a1d619d69bd9_ChatGPT_Image_May_12__2026__02_48_17_PM.png\"]', '1', '2026-06-02 01:08:29', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('50', '5', 'Owner', 'Ranjit Srivastava', 'Owner', '9855498542', NULL, NULL, 'ranjit@camrola.com', '[\"uploads\\/leads\\/cards\\/6a1de34897947_ChatGPT_Image_May_12__2026__02_48_17_PM.png\"]', '1', '2026-06-02 01:23:44', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`, `organization_name`, `address`, `city`, `state`, `pincode`, `website`) VALUES ('51', '5', 'Architect', 'Mahendra Sandhu', 'Manager', '9898989999', NULL, '9898989999', 'mahendra@gmail.com', NULL, '0', '2026-06-02 15:28:15', NULL, NULL, NULL, NULL, NULL, NULL);


DROP TABLE IF EXISTS `lead_documents`;
CREATE TABLE `lead_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `uploaded_from` enum('Mobile','Device') DEFAULT 'Device',
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_documents_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `uploaded_by`, `created_at`) VALUES ('1', '3', 'uploads/leads/6a1ca67154aab_cv.jpeg', 'cv.jpeg', 'image/jpeg', 'Site Media', '', 'Device', '1', '2026-06-01 02:51:53');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `uploaded_by`, `created_at`) VALUES ('2', '4', 'uploads/leads/6a1d62577d0b5_shricjw.png', 'shricjw.png', 'image/png', 'Site Media', '', 'Device', '1', '2026-06-01 16:13:35');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `uploaded_by`, `created_at`) VALUES ('3', '3', 'uploads/leads/6a1d6274043d4_shricjw.png', 'shricjw.png', 'image/png', 'Site Media', '', 'Mobile', '1', '2026-06-01 16:14:04');


DROP TABLE IF EXISTS `lead_interested_products`;
CREATE TABLE `lead_interested_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_interested_products_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('86', '3', 'Shower Enclosures');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('87', '3', 'LED Mirrors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('88', '3', 'Modular Kitchen');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('89', '4', 'Partition Systems');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('90', '4', 'Glass Surface');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('91', '4', 'Vanity Units');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('92', '5', 'Shower Enclosures');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('93', '5', 'Illuminated Walls');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('94', '5', 'LED Mirrors');


DROP TABLE IF EXISTS `lead_meetings`;
CREATE TABLE `lead_meetings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `priority` varchar(20) DEFAULT 'Medium',
  `meeting_with` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `followup_date` datetime DEFAULT NULL,
  `project_start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `sales_stage` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `lead_meetings_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_meetings_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_meetings` (`id`, `lead_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('1', '3', 'High', 'Medium', 'Dhruv', 'Telephonic', 'Test', '2026-06-10 12:00:00', NULL, NULL, 'Stage 3', NULL, '1', '2026-06-01 02:51:53');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('2', '3', 'High', 'Medium', 'Dhruv Ravikant Parmar', 'Telephonic', 'tuyty', '2026-07-09 00:00:00', NULL, NULL, 'Qualification', NULL, '1', '2026-06-01 03:29:45');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('3', '4', 'High', 'Medium', 'Mahendra Singh Rathore', 'Office Meeting', 'Customer demainds many things with unwanted Rates', '2026-06-10 00:00:00', NULL, NULL, 'Proposal', NULL, '1', '2026-06-01 16:10:29');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('4', '4', 'High', 'Medium', 'Mahendra Singh Rathore', 'Office Meeting', 'Meeting Completed', '2026-06-03 00:00:00', NULL, NULL, 'Proposal', NULL, '1', '2026-06-01 19:41:30');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('9', '5', 'Urgent', 'Medium', 'Ranjit Srivastava', 'Quotation Review', 'We can win the Lead', '2026-06-10 01:17:00', NULL, NULL, 'Negotiation', NULL, '1', '2026-06-02 01:23:44');


DROP TABLE IF EXISTS `lead_priorities`;
CREATE TABLE `lead_priorities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `priority_name` varchar(100) NOT NULL,
  `color_code` varchar(20) DEFAULT '#64748b',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `priority_name` (`priority_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_priorities` (`id`, `priority_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Cold Lead', '#93c5fd', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_priorities` (`id`, `priority_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Warm Lead', '#fdba74', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_priorities` (`id`, `priority_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Hot Lead', '#f87171', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_priorities` (`id`, `priority_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Lost Lead', '#94a3b8', '7', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `lead_product_types`;
CREATE TABLE `lead_product_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_product_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Bespoke', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_product_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Turnkey', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_product_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Supply Only', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_product_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Labour Only', '4', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `lead_sources`;
CREATE TABLE `lead_sources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_name` varchar(150) NOT NULL,
  `color_code` varchar(20) DEFAULT '#64748b',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `source_name` (`source_name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Website', '#3b82f6', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Facebook', '#8b5cf6', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'WhatsApp', '#f59e0b', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Instagram', '#f97316', '4', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Google', '#4285F4', '5', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Referral', '#E1306C', '6', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Exhibition', '#1877F2', '7', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_sources` (`id`, `source_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Direct Call\r\n', '#64748b', '8', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `lead_statuses`;
CREATE TABLE `lead_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(100) NOT NULL,
  `color_code` varchar(20) DEFAULT '#64748b',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_name` (`status_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'New Lead', '#3b82f6', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Contact Attempted ', '#6366f1', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Qualified', '#64748b', '3', '1', '2026-06-01 19:16:54');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Warm Lead', '#64748b', '4', '1', '2026-06-01 19:16:54');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Cold Lead', '#64748b', '6', '1', '2026-06-01 19:17:45');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Lost Lead', '#64748b', '7', '1', '2026-06-01 19:17:45');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Hot Lead', '#64748b', '8', '1', '2026-06-01 19:18:00');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Proposal Sent', '#22c55e', '10', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'Won', '#16a34a', '11', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Contacted', '#ef4444', '12', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `lead_timeline`;
CREATE TABLE `lead_timeline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `action_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `lead_timeline_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_timeline_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('1', '3', '1', 'Created', 'Lead LEAD000001 created.', NULL, '2026-06-01 02:51:53');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('2', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 03:16:15');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('3', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 03:17:10');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('4', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 03:29:45');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('5', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 03:39:47');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('6', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 13:28:40');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('7', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 13:42:54');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('8', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 14:11:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('9', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 14:19:31');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('10', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 14:27:42');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('11', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 14:33:31');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('12', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 14:39:09');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('13', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 14:39:36');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('14', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 15:59:42');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('15', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:01:45');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('16', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:02:13');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('17', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:03:34');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('18', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:04:25');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('19', '4', '1', 'Created', 'Lead LEAD000002 created.', NULL, '2026-06-01 16:10:29');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('20', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:12:36');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('21', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:13:35');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('22', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:14:04');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('23', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:50:22');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('24', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:50:55');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('25', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:51:32');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('26', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 16:52:05');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('27', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 19:05:16');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('28', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 19:29:32');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('29', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Qualified', NULL, '2026-06-01 19:40:18');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('30', '4', '1', 'Meeting', 'Meeting recorded: Office Meeting with Mahendra Singh Rathore. Status → Won. Next follow-up: 2026-06-03', NULL, '2026-06-01 19:41:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('31', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: New Lead', NULL, '2026-06-01 19:54:32');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('32', '3', '1', 'Meeting', 'Meeting recorded: Telephonic with RAVIANT N PARMAR. Status → Warm Lead. Next follow-up: 2026-08-04T19:55', NULL, '2026-06-01 19:55:24');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('33', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Warm Lead', NULL, '2026-06-01 19:56:24');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('34', '3', '1', 'Meeting', 'Meeting deleted: Telephonic', NULL, '2026-06-01 20:12:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('35', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Contact Attempted ', NULL, '2026-06-01 20:12:47');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('36', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Cold Lead', NULL, '2026-06-01 20:13:39');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('37', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Warm Lead', NULL, '2026-06-01 20:13:56');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('39', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Won', NULL, '2026-06-01 20:20:35');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('40', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Won', NULL, '2026-06-01 20:22:06');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('41', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Qualified', NULL, '2026-06-01 20:25:47');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('42', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Hot Lead', NULL, '2026-06-01 20:26:33');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('43', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Qualified', NULL, '2026-06-01 20:26:48');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('44', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Hot Lead', NULL, '2026-06-01 20:27:01');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('45', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Cold Lead', NULL, '2026-06-01 20:27:13');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('46', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Won', NULL, '2026-06-01 20:28:36');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('47', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Qualified', NULL, '2026-06-01 20:31:58');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('48', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Contact Attempted ', NULL, '2026-06-01 20:32:07');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('49', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Hot Lead', NULL, '2026-06-01 20:32:19');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('50', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Hot Lead', NULL, '2026-06-01 20:32:33');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('51', '3', '1', 'Meeting', 'Meeting deleted: Demo', NULL, '2026-06-01 21:44:48');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('52', '3', '1', 'Meeting', 'Meeting deleted: Demo', NULL, '2026-06-01 21:44:52');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('53', '3', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-01 21:45:00');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('54', '3', '1', 'Update', 'Meeting updated (Telephonic). Status updated to: Won', NULL, '2026-06-01 21:45:29');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('55', '4', '1', 'Updated', 'Lead details updated.', NULL, '2026-06-02 01:08:29');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('56', '4', '1', 'Update', 'Meeting updated (Office Meeting). Status updated to: Won', NULL, '2026-06-02 01:09:02');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('57', '5', '1', 'Created', 'Lead LEAD000003 created.', NULL, '2026-06-02 01:23:44');


DROP TABLE IF EXISTS `lead_types`;
CREATE TABLE `lead_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `color_code` varchar(20) DEFAULT '#64748b',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Website', '#3b82f6', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Instagram', '#E1306C', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Facebook', '#1877F2', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Google Ads', '#4285F4', '4', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'WhatsApp', '#25D366', '5', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Referral', '#22c55e', '6', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Dealer Network', '#a78bfa', '7', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Exhibition', '#06b6d4', '8', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Cold Calling', '#94a3b8', '9', '1', '2026-05-31 23:51:16');
INSERT INTO `lead_types` (`id`, `type_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Export Inquiry', '#0ea5e9', '10', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_code` varchar(50) NOT NULL,
  `lead_date` date NOT NULL,
  `site_stage` varchar(100) DEFAULT NULL,
  `project_type` varchar(100) DEFAULT NULL,
  `lead_type` varchar(100) DEFAULT NULL,
  `lead_source` varchar(100) DEFAULT NULL,
  `lead_priority` varchar(100) DEFAULT NULL,
  `lead_status` varchar(100) DEFAULT NULL,
  `expected_closing_date` date DEFAULT NULL,
  `next_followup_date` datetime DEFAULT NULL,
  `actual_followup_date` datetime DEFAULT NULL,
  `assigned_to` int(10) unsigned DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_type` varchar(100) DEFAULT NULL,
  `industry_type` varchar(100) DEFAULT NULL,
  `business_category` varchar(100) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `gst_number` varchar(50) DEFAULT NULL,
  `tin_number` varchar(50) DEFAULT NULL,
  `company_status` varchar(50) DEFAULT 'Active',
  `address_line1` text DEFAULT NULL,
  `address_line2` text DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `pincode` varchar(20) DEFAULT NULL,
  `approx_area_sqft` decimal(15,2) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `google_location` text DEFAULT NULL,
  `google_address` text DEFAULT NULL,
  `google_maps_link` text DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `requirement_description` text DEFAULT NULL,
  `estimated_budget` decimal(15,2) DEFAULT 0.00,
  `purchase_timeline` varchar(100) DEFAULT NULL,
  `competitor_info` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `converted_date` datetime DEFAULT NULL,
  `converted_by` int(10) unsigned DEFAULT NULL,
  `is_converted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_code` (`lead_code`),
  KEY `assigned_to` (`assigned_to`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `idx_lead_status` (`lead_status`),
  KEY `idx_lead_priority` (`lead_priority`),
  KEY `idx_lead_date` (`lead_date`),
  CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `tin_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `converted_date`, `converted_by`, `is_converted`) VALUES ('3', 'LEAD000001', '2026-06-01', 'Flooring Work', 'Residential', NULL, 'Facebook', 'Warm Lead', 'Won', NULL, '2026-06-04 00:00:00', '2026-07-09 00:00:00', '4', 'spantiles.com', 'Individual', 'Interior Design', 'B2B', 'krishna@gmail.com', 'shreepad.com', NULL, NULL, 'Inactive', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', NULL, 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201398', '72.59417376', '23.112014, 72.594174', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.112013977773106,72.59417376294273', 'Bespoke', NULL, '120000.00', '2 months', NULL, '1', '1', '2026-06-01 02:51:53', '2026-06-01 23:24:14', NULL, '2026-06-01 23:24:14', '1', '1');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `tin_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `converted_date`, `converted_by`, `is_converted`) VALUES ('4', 'LEAD000002', '2026-06-01', 'Structure Construction', 'Commercial', NULL, 'Facebook', 'Warm Lead', 'Won', NULL, '2026-07-16 00:00:00', '2026-06-03 00:00:00', '3', 'Absolute Consultatns Pvt Ltd.', 'Proprietorship\r\n', 'Manufacturing', NULL, 'vishal@absolute.com', 'www.absolute.com', 'AHD54857DFGA', NULL, 'Active', 'Karaka Building, Navrangpura', 'Near Farki Hotel', 'Navrangpura', 'Ahmedabad', 'Gujarat', 'India', '380009', NULL, '23.11201163', '72.59416969', '23.112012, 72.594170', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.11201163388454,72.5941696900821', 'Bespoke', 'They want to close this deal very fast', '5000000.00', 'Immediate', 'Somani tiles already floated', '1', '1', '2026-06-01 16:10:29', '2026-06-02 01:30:59', NULL, '2026-06-02 01:30:59', '1', '1');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `tin_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `converted_date`, `converted_by`, `is_converted`) VALUES ('5', 'LEAD000003', '2026-06-02', 'Plastering Stage', 'Residential', NULL, 'Website', 'Hot Lead', 'Warm Lead', NULL, '2026-06-05 01:17:00', NULL, '4', 'Camrola Pvt Ltd.', 'Pvt Ltd', 'Construction', NULL, 'rajesh@sushobha.com', 'sushobha.com', 'AHD54857DFGA', NULL, 'Active', '516, Gala empire, Opp TV Tower, Metro Station, ', 'Drive In Road,', 'Thaltej', 'Ahmedabad', 'Gujarat', 'India', '380054', NULL, '23.11201163', '72.59416969', '23.112012, 72.594170', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.11201163388454,72.5941696900821', 'Bespoke', 'Inauguration Planning for this month only', '500000.00', 'Immediate', 'Somani Tiles is chasing for favorable price', '1', NULL, '2026-06-02 01:23:44', '2026-06-02 01:23:44', NULL, NULL, NULL, '0');


DROP TABLE IF EXISTS `meeting_statuses`;
CREATE TABLE `meeting_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(100) NOT NULL,
  `color_code` varchar(20) DEFAULT '#64748b',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_name` (`status_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `meeting_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Urgent', '#3b82f6', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'High', '#22c55e', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Medium', '#f59e0b', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Low', '#ef4444', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Informal', '#8b5cf6', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_statuses` (`id`, `status_name`, `color_code`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'KYC', '#8b5cf6', '6', '0', '2026-06-01 15:35:56');


DROP TABLE IF EXISTS `meeting_types`;
CREATE TABLE `meeting_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Site Visit', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Office Meeting', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Telephonic', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Virtual Meeting', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Quotation Review', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Negotiation', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Project Review', '7', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Follow-up', '8', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Dealer Network', '9', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Client Discussion', '10', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'Material Discussion', '11', '1', '2026-05-31 23:51:17');
INSERT INTO `meeting_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Demo', '12', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `record_id` int(10) unsigned NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_notes_module` (`module`,`record_id`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `message` text DEFAULT NULL,
  `type` enum('info','success','warning','danger') DEFAULT 'info',
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('1', '2', 'New Lead Assigned', 'Rohit Bansal from StartUp Hub has been assigned to you', 'info', '/modules/prospects/view.php?id=1', '0', '2026-05-31 06:13:13');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('2', '2', 'Invoice Overdue', 'Invoice INV0002 for TechVision is overdue by 10 days', 'warning', '/modules/invoices/view.php?id=2', '0', '2026-05-31 06:13:13');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('3', '2', 'Payment Received', 'Payment of ₹75,000 received from TechVision Pvt Ltd', 'success', '/modules/payments/', '0', '2026-05-31 06:13:13');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('4', '3', 'Quotation Rejected', 'QT0004 was rejected by SunRise Exports', 'danger', '/modules/quotations/view.php?id=4', '0', '2026-05-31 06:13:13');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('5', '2', 'Low Stock Alert', 'Dell Laptop i5 stock is below minimum level', 'warning', '/modules/products/', '0', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(250) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) DEFAULT 'Nos',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('1', '1', NULL, 'ERP Implementation', '1.00', 'Nos', '100000.00', '18.00', '18000.00', '118000.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('2', '1', NULL, 'Dell Laptop i5', '1.00', 'Nos', '15000.00', '18.00', '2700.00', '17700.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('5', '3', '7', 'Website Development', '1.00', 'Nos', '35000.00', '18.00', '6300.00', '41300.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('6', '4', NULL, 'Dell Laptop i5', '1.00', 'Nos', '58000.00', '18.00', '10440.00', '68440.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('7', '5', NULL, 'CRM Software License', '5.00', 'Nos', '15000.00', '18.00', '13500.00', '88500.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('8', '5', NULL, 'IT Consulting - 20 hrs', '20.00', 'Nos', '1650.00', '18.00', '5940.00', '39540.00');


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(30) NOT NULL,
  `quotation_id` int(10) unsigned DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `delivery_date` date DEFAULT NULL,
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount_type` enum('fixed','percent') DEFAULT 'fixed',
  `discount_value` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `shipping_charges` decimal(10,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `paid_amount` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `customer_id` (`customer_id`),
  KEY `quotation_id` (`quotation_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_order_status` (`status`),
  KEY `idx_order_payment` (`payment_status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'ORD0001', NULL, '5', 'delivered', NULL, 'paid', '115000.00', 'fixed', '0.00', '0.00', '20700.00', '0.00', '135700.00', '135700.00', NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'ORD0003', NULL, '7', 'pending', NULL, 'unpaid', '35000.00', 'fixed', '0.00', '0.00', '6300.00', '0.00', '41300.00', '0.00', NULL, NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'ORD0004', NULL, '9', 'delivered', NULL, 'paid', '58000.00', 'fixed', '0.00', '0.00', '10440.00', '0.00', '68440.00', '68440.00', NULL, NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'ORD0005', NULL, '3', 'processing', NULL, 'partial', '120000.00', 'fixed', '0.00', '0.00', '21600.00', '0.00', '141600.00', '50000.00', NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `method` enum('cash','bank_transfer','cheque','upi','card','other') DEFAULT 'cash',
  `reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `customer_id` (`customer_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('1', '1', '5', '135700.00', '2026-04-28', 'bank_transfer', 'NEFT2026042801', NULL, '2', '2026-05-31 06:13:13');
INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('3', '4', '9', '68440.00', '2026-04-10', 'upi', 'UPI2026041001', NULL, '3', '2026-05-31 06:13:13');
INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('4', '5', '3', '50000.00', '2026-05-01', 'bank_transfer', 'NEFT2026050101', NULL, '2', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('1', 'Engg Home Decor Products', NULL, '2026-05-31 06:13:13');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('2', 'Wooden Furniture', NULL, '2026-05-31 06:13:13');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('3', 'Ceramic Tiles', NULL, '2026-05-31 06:13:13');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('4', 'Premium Statues', NULL, '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('product','service') DEFAULT 'product',
  `unit` varchar(30) DEFAULT 'Nos',
  `package_qty` decimal(10,2) DEFAULT 1.00,
  `purchase_price` decimal(12,2) DEFAULT 0.00,
  `selling_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 18.00,
  `stock_qty` decimal(10,2) DEFAULT 0.00,
  `min_stock` decimal(10,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_product_name` (`name`),
  KEY `idx_product_sku` (`sku`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `brand`, `description`, `type`, `unit`, `package_qty`, `purchase_price`, `selling_price`, `tax_rate`, `stock_qty`, `min_stock`, `image`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES ('7', '1', 'CS-001', 'Flush Door', 'Susho', 'Flush Door', 'product', 'Nos', '1.00', '150000.00', '210000.00', '12.00', '0.00', '0.00', 'uploads/products/6a1dd3de49ce2_2805202605.webp', '1', NULL, '2026-05-31 06:13:13', '2026-06-02 11:35:09');
INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `brand`, `description`, `type`, `unit`, `package_qty`, `purchase_price`, `selling_price`, `tax_rate`, `stock_qty`, `min_stock`, `image`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES ('10', '1', 'DM-002', 'Shower Enclousure', 'Susho', '3 platforms, 20 posts/month', 'product', 'Nos', '1.00', '100000.00', '130500.00', '18.00', '0.00', '0.00', 'uploads/products/6a1dd37b29507_cg125.png', '1', NULL, '2026-05-31 06:13:13', '2026-06-02 00:21:57');
INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `brand`, `description`, `type`, `unit`, `package_qty`, `purchase_price`, `selling_price`, `tax_rate`, `stock_qty`, `min_stock`, `image`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES ('13', '1', 'DM-003', 'Partition Systems', 'Enso', '', 'product', 'Nos', '1.00', '180000.00', '270000.00', '18.00', '0.00', '0.00', 'uploads/products/6a1dd4a83ffa9_cfdadfads.png', '1', '1', '2026-06-02 00:21:20', '2026-06-02 00:21:20');


DROP TABLE IF EXISTS `project_types`;
CREATE TABLE `project_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Residential', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Commercial', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Industrial', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Institutional', '4', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Real Estate', '5', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Turnkey', '6', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Hospitality', '7', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Renovation', '8', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Interior', '9', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Landscape', '10', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'PMC', '11', '1', '2026-05-31 23:51:16');
INSERT INTO `project_types` (`id`, `type_name`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Design Consultancy', '12', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `prospect_followups`;
CREATE TABLE `prospect_followups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `type` enum('call','email','meeting','demo','other') DEFAULT 'call',
  `notes` text DEFAULT NULL,
  `next_follow_up` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `prospect_id` (`prospect_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `prospect_followups_ibfk_1` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prospect_followups_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `prospects`;
CREATE TABLE `prospects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `source` enum('website','referral','cold_call','social_media','exhibition','other') DEFAULT 'other',
  `status` enum('new','contacted','qualified','proposal','negotiation','won','lost') DEFAULT 'new',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `expected_value` decimal(12,2) DEFAULT 0.00,
  `follow_up_date` date DEFAULT NULL,
  `assigned_to` int(10) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `lost_reason` text DEFAULT NULL,
  `converted_customer_id` int(10) unsigned DEFAULT NULL,
  `converted_at` datetime DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `converted_customer_id` (`converted_customer_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_prospect_status` (`status`),
  CONSTRAINT `prospects_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospects_ibfk_2` FOREIGN KEY (`converted_customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospects_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'Rohit Bansal', 'StartUp Hub', 'rohit@startuphub.in', '9876123450', 'referral', 'new', 'high', '50000.00', '2026-05-15', '3', 'Interested in ERP implementation', NULL, NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'Ananya Gupta', 'Green Energy Co', 'ananya@greenenergy.com', '8766123450', 'website', 'contacted', 'medium', '25000.00', '2026-05-12', '3', 'Demo scheduled for next week', NULL, NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'Karan Malhotra', 'Prime Logistics', 'karan@primelogistics.in', '7656123450', 'cold_call', 'qualified', 'high', '80000.00', '2026-05-20', '3', 'Wants AMC contract + hardware', NULL, NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'Sonal Verma', 'BlueSky Technologies', 'sonal@bluesky.tech', '9546123450', 'social_media', 'proposal', 'medium', '35000.00', '2026-05-18', '5', 'Sent proposal, waiting response', NULL, NULL, NULL, '3', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'Manish Tiwari', 'RedStar Retail', 'manish@redstar.com', '8436123450', 'exhibition', 'negotiation', 'high', '120000.00', '2026-05-10', '3', 'Price negotiation in progress', NULL, NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('6', 'Lakshmi Devi', 'Heritage Hotels', 'lakshmi@heritage.in', '7326123450', 'referral', 'won', 'medium', '45000.00', NULL, '3', 'Converted to customer', NULL, NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('7', 'Nitin Kapoor', 'Metro Builders', 'nitin@metro.in', '9216123450', 'cold_call', 'lost', 'low', '30000.00', NULL, '5', 'Budget not approved this year', NULL, NULL, NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE `purchase_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(250) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) DEFAULT 'Nos',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('1', '1', NULL, 'Dell Laptop i5', '5.00', 'Nos', '45000.00', '18.00', '40500.00', '265500.00');
INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('2', '2', NULL, 'Keyboard & Mouse Combo', '20.00', 'Nos', '1200.00', '18.00', '4320.00', '28320.00');
INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('3', '3', NULL, 'HP LaserJet Printer', '5.00', 'Nos', '15000.00', '18.00', '13500.00', '88500.00');


DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_number` varchar(30) NOT NULL,
  `vendor_id` int(10) unsigned NOT NULL,
  `status` enum('pending','received','partial','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `purchase_date` date NOT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `paid_amount` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_number` (`purchase_number`),
  KEY `vendor_id` (`vendor_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'PUR0001', '1', 'received', 'paid', '2026-04-05', '225000.00', '40500.00', '265500.00', '265500.00', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'PUR0002', '3', 'received', 'paid', '2026-04-12', '24000.00', '4320.00', '28320.00', '28320.00', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'PUR0003', '2', 'pending', 'unpaid', '2026-05-01', '75000.00', '13500.00', '88500.00', '0.00', NULL, '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `quotation_items`;
CREATE TABLE `quotation_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(250) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) DEFAULT 'Nos',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `quotation_id` (`quotation_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotation_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('14', '10', '7', 'Flush Door', '1.00', 'Nos', '210000.00', '18.00', '35910.00', '0.00', '210000.00', '0');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('15', '10', '13', 'Partition Systems', '1.00', 'Nos', '270000.00', '18.00', '46170.00', '0.00', '270000.00', '1');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('16', '10', '10', 'Shower Enclousure', '1.00', 'Nos', '130500.00', '18.00', '22315.50', '0.00', '130500.00', '2');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('17', '11', '7', 'Flush Door', '1.00', 'Nos', '210000.00', '12.00', '23940.00', '0.00', '210000.00', '0');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('18', '11', '10', 'Shower Enclousure', '1.00', 'Nos', '130500.00', '18.00', '22315.50', '0.00', '130500.00', '1');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('19', '12', '7', 'Flush Door', '1.00', 'Nos', '210000.00', '12.00', '0.00', '0.00', '210000.00', '0');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('20', '12', '10', 'Shower Enclousure', '1.00', 'Nos', '130500.00', '18.00', '0.00', '0.00', '130500.00', '1');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('24', '13', '7', 'Flush Door', '1.00', 'Nos', '180000.00', '18.00', '30780.00', '0.00', '180000.00', '0');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('25', '13', '13', 'Partition Systems', '1.00', 'Nos', '200000.00', '18.00', '34200.00', '0.00', '200000.00', '1');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('26', '13', '10', 'Shower Enclousure', '1.00', 'Nos', '130500.00', '18.00', '22315.50', '0.00', '130500.00', '2');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('29', '14', '7', 'Flush Door', '1.00', 'Nos', '1000000.00', '12.00', '114000.00', '0.00', '1000000.00', '0');
INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('30', '14', '10', 'Shower Enclousure', '1.00', 'Nos', '130500.00', '18.00', '22315.50', '0.00', '130500.00', '1');


DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_number` varchar(30) NOT NULL,
  `version` int(11) DEFAULT 1,
  `revision_reason` varchar(255) DEFAULT NULL,
  `is_latest` tinyint(1) DEFAULT 1,
  `customer_id` int(10) unsigned NOT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `status` enum('draft','sent','accepted','rejected','converted','expired') DEFAULT 'draft',
  `valid_until` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount_type` enum('fixed','percent') DEFAULT 'fixed',
  `discount_value` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_quote_version` (`quote_number`,`version`),
  KEY `customer_id` (`customer_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_quote_status` (`status`),
  CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quotations` (`id`, `quote_number`, `version`, `revision_reason`, `is_latest`, `customer_id`, `project_name`, `contact_person`, `status`, `valid_until`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `total`, `notes`, `revision_notes`, `terms`, `created_by`, `sent_at`, `created_at`, `updated_at`) VALUES ('10', 'QT0001', '1', NULL, '0', '1', NULL, NULL, 'sent', '2026-07-02', '610500.00', 'percent', '5.00', '30525.00', '104395.50', '684370.50', '', NULL, '1. Quotation valid for 30 days.\r\n2. Payment: 100% advance.', '1', NULL, '2026-06-02 11:34:11', '2026-06-02 14:44:45');
INSERT INTO `quotations` (`id`, `quote_number`, `version`, `revision_reason`, `is_latest`, `customer_id`, `project_name`, `contact_person`, `status`, `valid_until`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `total`, `notes`, `revision_notes`, `terms`, `created_by`, `sent_at`, `created_at`, `updated_at`) VALUES ('11', 'QT0002', '1', NULL, '0', '2', NULL, NULL, 'draft', '2026-07-02', '340500.00', 'percent', '5.00', '17025.00', '46255.50', '369730.50', '', NULL, '1. Quotation valid for 30 days.\r\n2. Payment: 100% advance.', '1', NULL, '2026-06-02 11:35:58', '2026-06-02 14:37:56');
INSERT INTO `quotations` (`id`, `quote_number`, `version`, `revision_reason`, `is_latest`, `customer_id`, `project_name`, `contact_person`, `status`, `valid_until`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `total`, `notes`, `revision_notes`, `terms`, `created_by`, `sent_at`, `created_at`, `updated_at`) VALUES ('12', 'QT0002', '2', NULL, '0', '2', NULL, NULL, 'draft', '2026-07-02', '340500.00', 'percent', '5.00', '17025.00', '46255.50', '369730.50', '', 'Customer seems high price of the same', '1. Quotation valid for 30 days.\r\n2. Payment: 100% advance.', '1', NULL, '2026-06-02 14:37:56', '2026-06-02 14:58:37');
INSERT INTO `quotations` (`id`, `quote_number`, `version`, `revision_reason`, `is_latest`, `customer_id`, `project_name`, `contact_person`, `status`, `valid_until`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `total`, `notes`, `revision_notes`, `terms`, `created_by`, `sent_at`, `created_at`, `updated_at`) VALUES ('13', 'QT0001', '2', NULL, '1', '1', '', '', 'draft', '2026-07-02', '510500.00', 'percent', '5.00', '25525.00', '87295.50', '572270.50', '', 'Customer wants another discount', '1. Quotation valid for 30 days.\r\n2. Payment: 100% advance.', '1', NULL, '2026-06-02 14:44:45', '2026-06-02 14:45:08');
INSERT INTO `quotations` (`id`, `quote_number`, `version`, `revision_reason`, `is_latest`, `customer_id`, `project_name`, `contact_person`, `status`, `valid_until`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `total`, `notes`, `revision_notes`, `terms`, `created_by`, `sent_at`, `created_at`, `updated_at`) VALUES ('14', 'QT0002', '3', NULL, '1', '2', '', '', 'draft', '2026-07-02', '1130500.00', 'percent', '5.00', '56525.00', '136315.50', '1210290.50', '', 'Price change', '1. Quotation valid for 30 days.\r\n2. Payment: 100% advance.', '1', NULL, '2026-06-02 14:58:37', '2026-06-02 14:58:44');


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('1', 'Super Admin', 'super_admin', '{}', '2026-05-31 06:13:12');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('2', 'Admin', 'admin', '{}', '2026-05-31 06:13:12');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('3', 'Manager', 'manager', '{\"customers\":[\"view\",\"create\",\"edit\"],\"prospects\":[\"view\",\"create\",\"edit\",\"delete\"],\"quotations\":[\"view\",\"create\",\"edit\"],\"orders\":[\"view\",\"create\"],\"products\":[\"view\",\"create\"]}', '2026-05-31 06:13:12');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('4', 'Accountant', 'accountant', '{\"accounts\":[\"view\",\"create\",\"edit\"],\"invoices\":[\"view\",\"create\",\"edit\"],\"expenses\":[\"view\",\"create\",\"edit\"],\"reports\":[\"view\"]}', '2026-05-31 06:13:12');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('5', 'User', 'user', '{\"customers\":[\"view\"],\"quotations\":[\"view\"],\"orders\":[\"view\"]}', '2026-05-31 06:13:12');


DROP TABLE IF EXISTS `salary_records`;
CREATE TABLE `salary_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `basic_salary` decimal(12,2) DEFAULT 0.00,
  `allowances` decimal(12,2) DEFAULT 0.00,
  `deductions` decimal(12,2) DEFAULT 0.00,
  `net_salary` decimal(12,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','cheque') DEFAULT 'bank_transfer',
  `status` enum('pending','paid') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_salary` (`employee_id`,`month`,`year`),
  CONSTRAINT `salary_records_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('1', '1', '4', '2026', '75000.00', '5000.00', '8000.00', '72000.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-31 06:13:13');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('2', '2', '4', '2026', '55000.00', '3000.00', '5500.00', '52500.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-31 06:13:13');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('3', '3', '4', '2026', '50000.00', '2500.00', '5000.00', '47500.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-31 06:13:13');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('4', '4', '4', '2026', '45000.00', '2000.00', '4500.00', '42500.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-31 06:13:13');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('5', '5', '4', '2026', '35000.00', '1500.00', '3500.00', '33000.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `sales_stages`;
CREATE TABLE `sales_stages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `stage_name` (`stage_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Inquiry', '1', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Qualification', '2', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Requirement Gathering', '3', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Proposal', '4', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Negotiation', '5', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Conversion', '6', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Execution', '7', '1', '2026-05-31 23:51:17');
INSERT INTO `sales_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Completed', '8', '1', '2026-05-31 23:51:17');


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('1', 'company_name', 'Sushobha Business Solutions', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('2', 'company_email', 'info@sushobha.com', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('3', 'company_phone', '+91 98765 43210', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('4', 'company_address', '123 Business Park, Bengaluru, Karnataka - 560001', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('5', 'company_gst', '29ABCDE1234F1Z5', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('6', 'company_website', 'https://www.sushobha.com', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('7', 'currency_symbol', '₹', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('8', 'tax_name', 'GST', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('9', 'invoice_prefix', 'INV', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('10', 'order_prefix', 'ORD', '2026-05-31 06:13:13');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('11', 'quote_prefix', 'QT', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `site_stages`;
CREATE TABLE `site_stages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(150) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `stage_name` (`stage_name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('1', 'Planning Stage', '1', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('2', 'Design & Approval Stage', '2', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('3', 'Ready for Material Procurement', '3', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('4', 'Foundation Work', '4', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('5', 'Structure Construction', '5', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('6', 'Masonry & Wall Work', '6', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('7', 'Electrical & Plumbing Stage', '7', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('8', 'Plastering Stage', '8', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('9', 'Flooring Stage', '9', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('10', 'Glass Work', '10', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('11', 'Flooring Work', '11', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('12', 'Doors & Windows Installation', '12', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('13', 'Interior & Furniture Work', '13', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('14', 'Painting & Finishing Stage', '14', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('15', 'Final Finishing', '15', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('16', 'Project Near Completion', '16', '1', '2026-05-31 23:51:16');
INSERT INTO `site_stages` (`id`, `stage_name`, `sort_order`, `is_active`, `created_at`) VALUES ('17', 'Completed Project', '17', '1', '2026-05-31 23:51:16');


DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `related_module` varchar(50) DEFAULT NULL,
  `related_id` int(10) unsigned DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('1', 'Follow up with Karan Malhotra', 'Negotiate AMC contract terms', '3', '2', NULL, NULL, 'high', 'pending', '2026-05-20', NULL, '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('2', 'Prepare Q2 Sales Report', 'Compile all sales data for Q2 2026', '4', '2', NULL, NULL, 'medium', 'in_progress', '2026-05-15', NULL, '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('3', 'Server Maintenance', 'Schedule downtime for server updates', '5', '2', NULL, NULL, 'medium', 'pending', '2026-05-12', NULL, '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('4', 'Send Invoice Reminder', 'Follow up on unpaid INV0002 and INV0003', '4', '2', NULL, NULL, 'high', 'pending', '2026-05-11', NULL, '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('5', 'Product Demo for Ananya Gupta', 'Demo ERP for Green Energy Co', '3', '2', NULL, NULL, 'high', 'pending', '2026-05-12', NULL, '2026-05-31 06:13:13', '2026-05-31 06:13:13');


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT 5,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `access_rights` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('1', '1', 'Super Admin', 'superadmin@sushobha.com', '$2y$10$ExXGVaTwLzQwAUCkBAUACOh5B2CwGK4YmrBgWgHrsiBUEWYImAHuC', '+91 99999 00001', NULL, '1', NULL, NULL, NULL, '2026-06-02 15:04:24', '0', NULL, '2026-05-31 06:13:12', '2026-06-02 15:04:24', '[\"Read\",\"Write\",\"Modify\",\"Delete\",\"View\",\"Approve\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('2', '5', 'Ranjit Srivastava', 'ranjit@sushobha.com', '$2y$12$sqR4oPuMeUdXvbJSXJCes.CJOnf.YAShRRKoqMaMDbry34w0bupBG', '+91 98765 43210', NULL, '1', NULL, NULL, NULL, NULL, '0', NULL, '2026-05-31 06:13:12', '2026-06-02 15:08:32', '[\"Read\",\"Write\",\"View\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('3', '5', 'Girish Patel', 'girish@sushobha.com', '$2y$12$sqR4oPuMeUdXvbJSXJCes.CJOnf.YAShRRKoqMaMDbry34w0bupBG', '+91 91234 56789', NULL, '1', NULL, NULL, NULL, NULL, '0', NULL, '2026-05-31 06:13:12', '2026-06-02 15:07:13', '[\"Read\",\"Write\",\"Modify\",\"View\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('4', '2', 'Ravikant Parmar', 'ravikaant@sushobha.com', '$2y$12$sqR4oPuMeUdXvbJSXJCes.CJOnf.YAShRRKoqMaMDbry34w0bupBG', '+91 80123 45678', NULL, '1', NULL, NULL, NULL, '2026-06-02 20:37:23', '0', NULL, '2026-05-31 06:13:12', '2026-06-02 20:37:23', '[\"Read\",\"Write\",\"Modify\",\"Delete\",\"View\",\"Approve\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('5', '1', 'Parvez Hashmi', 'parvez@sushobha.com', '$2y$12$sqR4oPuMeUdXvbJSXJCes.CJOnf.YAShRRKoqMaMDbry34w0bupBG', '+91 70987 65432', NULL, '1', NULL, NULL, NULL, NULL, '0', NULL, '2026-05-31 06:13:12', '2026-06-02 15:06:21', '[\"Read\",\"Write\",\"Modify\",\"Delete\",\"View\",\"Approve\"]');


DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_code` varchar(20) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gst_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(30) DEFAULT NULL,
  `bank_ifsc` varchar(15) DEFAULT NULL,
  `outstanding_balance` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_code` (`vendor_code`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'VEN0001', 'Amit Jain', 'Dell India Pvt Ltd', 'amit.jain@dell.com', '9111234567', '07AAACL1234H1ZD', NULL, NULL, 'Delhi', 'Delhi', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'VEN0002', 'Pradeep Nair', 'HP India', 'pradeep@hp.com', '9222345678', '29AABCH1234A1ZN', NULL, NULL, 'Bengaluru', 'Karnataka', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'VEN0003', 'Ritu Agarwal', 'Logitech India', 'ritu@logitech.com', '9333456789', '27AAACL1234J1ZR', NULL, NULL, 'Mumbai', 'Maharashtra', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'VEN0004', 'Vijay Sharma', 'Netgear India', 'vijay@netgear.com', '9444567890', '24AABCN1234K1ZV', NULL, NULL, 'Ahmedabad', 'Gujarat', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'VEN0005', 'Sanjay Mehta', 'Samsung India Electronics', 'sanjay@samsung.com', '9555678901', '07AAACS1234L1ZS', NULL, NULL, 'Delhi', 'Delhi', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-31 06:13:13', '2026-05-31 06:13:13');


