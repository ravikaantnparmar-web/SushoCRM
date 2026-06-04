-- Full System Backup - DB Part
-- Generated: 2026-05-13 12:28:34

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
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('1', '2', 'customers', 'create', 'Created customer TechVision Pvt Ltd', '1', '127.0.0.1', NULL, '2026-05-10 02:04:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('2', '2', 'quotations', 'create', 'Created quotation QT0001 for TechVision', '1', '127.0.0.1', NULL, '2026-05-10 02:04:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('3', '2', 'orders', 'create', 'Converted quotation to order ORD0001', '1', '127.0.0.1', NULL, '2026-05-10 02:04:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('4', '2', 'invoices', 'create', 'Generated invoice INV0001', '1', '127.0.0.1', NULL, '2026-05-10 02:04:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('5', NULL, 'prospects', 'create', 'Added new lead: Rohit Bansal', '1', '127.0.0.1', NULL, '2026-05-10 02:04:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('6', '2', 'payments', 'create', 'Recorded payment from TechVision - Ôé╣1,35,700', '1', '127.0.0.1', NULL, '2026-05-10 02:04:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('7', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 02:19:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('8', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 02:56:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('9', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 02:58:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('10', '2', 'customers', 'create', 'Created customer: Test Customer', '11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 02:59:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('11', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 11:03:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('12', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 11:25:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('13', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 11:25:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('14', '2', 'invoices', 'update', 'Updated invoice: INV0001', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 12:11:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('15', '2', 'purchases', 'create', 'Created purchase order: PO-20260510-997', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 12:16:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('16', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 12:35:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('17', '2', 'tasks', 'update', 'Moved task \'Send Invoice Reminder\' to completed via Kanban', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 12:51:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('18', '2', 'tasks', 'update', 'Moved task \'Send Invoice Reminder\' to pending via Kanban', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 12:51:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('19', '2', 'tasks', 'update', 'Updated task: Order Cement', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 12:52:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('20', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 13:46:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('21', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 13:47:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('22', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 13:48:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('23', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 13:50:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('24', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 13:51:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('25', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 13:58:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('26', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 14:00:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('27', '2', 'tasks', 'update', 'Moved task \'Prepare Q2 Sales Report\' to completed via Kanban', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 14:01:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('28', '2', 'projects', 'create', 'Created project: Madhav Park', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 14:03:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('29', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 14:50:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('30', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 15:03:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('31', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 15:07:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('32', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 15:08:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('33', '2', 'customers', 'create', 'Created customer via quick-add: Test Customer Quick Add', '12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 15:08:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('34', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 15:24:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('35', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 15:25:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('36', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:18:00');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('37', '2', 'projects', 'create', 'Created project: PRJ-202605-636', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:27:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('38', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:40:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('39', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:40:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('40', '2', 'projects', 'update', 'Updated project: PRJ-202605-636', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:41:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('41', '2', 'projects', 'create', 'Created project: PRJ-202605-519', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:47:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('42', '2', 'projects', 'update', 'Updated project: PRJ-202605-519', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:53:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('43', '2', 'projects', 'update', 'Updated project: PRJ-202605-519', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 18:58:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('44', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 19:45:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('45', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 20:36:00');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('46', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 20:36:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('47', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 20:40:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('48', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 20:40:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('49', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 20:56:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('50', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:01:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('51', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:01:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('52', '2', 'employees', 'update', 'Updated employee: Ravi Kumar', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:01:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('53', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:06:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('54', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:21:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('55', '2', 'employees', 'update', 'Updated employee: Ravikaant Parmar', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:25:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('56', '2', 'employees', 'update', 'Updated employee: Ravikaant Parmar', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:26:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('57', '2', 'employees', 'update', 'Updated employee: Parvez Hashmi', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:27:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('58', '2', 'employees', 'update', 'Updated employee: Girish Kumar Solanki', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:28:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('59', '2', 'employees', 'update', 'Updated employee: Ranjit Srivastav', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:29:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('60', '2', 'employees', 'update', 'Updated employee: Nehaal Kinariwala', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:30:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('61', '2', 'employees', 'update', 'Updated employee: Ravikaant Parmar', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:33:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('62', '2', 'employees', 'update', 'Updated employee: Parvez Hashmi', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:33:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('63', '2', 'employees', 'update', 'Updated employee: Girish Kumar Solanki', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:34:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('64', '2', 'employees', 'update', 'Updated employee: Neeraj Sharma', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:35:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('65', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:41:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('66', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:41:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('67', '2', 'settings', 'backup', 'Database backup created: db_backup_2026-05-10_214152.sql', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:41:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('68', '2', 'settings', 'backup', 'Program files backup created: files_backup_2026-05-10_214155.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:41:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('69', '2', 'settings', 'backup', 'Database backup created: db_backup_2026-05-10_214207.sql', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:42:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('70', '2', 'settings', 'backup', 'Program files backup created: files_backup_2026-05-10_214209.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:42:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('71', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_214423', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:44:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('72', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_214446', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:44:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('73', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_214639', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:46:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('74', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_214654', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:46:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('75', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_214935', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:49:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('76', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_215329', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 21:53:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('77', '2', 'employees', 'update', 'Updated employee: Parvez Hashmi', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:02:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('78', '2', 'employees', 'update', 'Updated employee: Girish Kumar Solanki', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:02:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('79', '2', 'employees', 'update', 'Updated employee: Ranjit Srivastav', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:03:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('80', '2', 'employees', 'update', 'Updated employee: Ravikaant Parmar', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:03:18');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('81', '2', 'settings', 'backup', 'Full system backup created: 2026-05-10_220349', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:03:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('82', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:12:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('83', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:12:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('84', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:13:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('85', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:13:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('86', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:17:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('87', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:17:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('88', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:20:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('89', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:20:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('90', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 22:25:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('91', '2', 'auth', 'login', 'User logged in: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:44:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('92', '2', 'auth', 'update', 'User changed their password', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:45:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('93', '2', 'settings', 'create', 'Added user: parvez@sushobha.com', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:54:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('94', '2', 'settings', 'create', 'Added user: ravikaant@sushobha.com', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:55:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('95', '2', 'settings', 'delete', 'Deleted user ID: 4', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:56:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('96', '2', 'settings', 'delete', 'Deleted user ID: 3', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:56:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('97', '2', 'settings', 'delete', 'Deleted user ID: 1', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:56:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('98', '2', 'settings', 'delete', 'Deleted user ID: 5', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:56:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('99', '2', 'settings', 'create', 'Added user: girish@sushobha.com', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:57:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('100', '2', 'settings', 'create', 'Added user: ranjit@sushobha.com', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:58:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('101', '2', 'settings', 'create', 'Added user: neeraj@sushobha.com', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:58:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('102', '2', 'settings', 'update', 'Reset password for user ID: 7', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:58:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('103', '2', 'auth', 'logout', 'User logged out: admin@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:59:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('104', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:59:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('105', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 22:59:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('106', '8', 'auth', 'login', 'User logged in: girish@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 23:00:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('107', '8', 'auth', 'logout', 'User logged out: girish@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-05-10 23:00:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('108', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:01:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('109', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:01:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('110', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:10:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('111', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:11:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('112', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:13:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('113', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:13:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('114', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:16:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('115', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:16:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('116', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:17:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('117', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:17:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('118', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:26:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('119', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:32:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('120', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:33:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('121', '6', 'settings', 'backup', 'Full system backup created: 2026-05-10_233334', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:33:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('122', '6', 'settings', 'backup', 'Full system backup created: 2026-05-10_233731', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:37:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('123', '6', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-10_233731.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:37:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('124', '6', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-10_233731.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:37:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('125', '6', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-10_233731.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:37:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('126', '6', 'settings', 'download', 'Backup downloaded: db_backup_2026-05-10_233731.sql', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:37:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('127', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-10 23:42:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('128', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 00:59:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('129', '7', 'settings', 'backup', 'Full system backup created: 2026-05-11_010141', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 01:01:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('130', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 01:03:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('131', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 01:03:18');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('132', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 01:19:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('133', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 01:20:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('135', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:14:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('136', '7', 'projects', 'update', 'Updated project: PRJ-202605-519', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:20:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('137', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:50:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('138', '7', 'prospects', 'update', 'Updated lead: Manish Tiwari', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:54:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('139', '7', 'prospects', 'update', 'Updated lead: Manish Tiwari', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:55:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('140', '7', 'prospects', 'update', 'Updated lead: Manish Tiwari', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:56:27');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('141', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:04:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('142', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:04:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('143', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:07:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('144', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:29:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('145', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:29:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('146', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:30:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('147', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:35:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('148', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:36:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('149', '7', 'settings', 'backup', 'Full system backup created: 2026-05-11_103655', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:36:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('150', '7', 'settings', 'download', 'Backup downloaded: db_backup_2026-05-11_103655.sql', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:37:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('151', '7', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-11_103655.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:37:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('152', '7', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-11_103655.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:37:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('153', '7', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-11_103655.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:37:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('154', '7', 'prospects', 'update', 'Updated lead: Ananya Gupta', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:43:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('155', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:21:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('156', '7', 'settings', 'backup', 'Full system backup created: 2026-05-11_112508', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:25:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('157', '7', 'settings', 'backup', 'Full system backup created: 2026-05-11_113108', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:31:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('158', '7', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-11_113108.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:31:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('159', '7', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-11_113108.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:31:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('160', '7', 'settings', 'download', 'Backup downloaded: files_backup_2026-05-11_113108.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:31:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('161', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:35:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('162', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:35:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('163', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:36:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('164', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 11:38:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('165', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:13:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('166', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:20:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('167', '7', 'customers', 'edit', 'Updated customer: Test Customer Quick Add', '12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:21:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('168', '7', 'settings', 'backup', 'Full system backup created: 2026-05-11_202134', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:21:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('169', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:34:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('170', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:34:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('171', '7', 'settings', 'restore', 'Program files restored from: files_backup_2026-05-11_202134.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:35:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('172', '7', 'tasks', 'update', 'Updated task: Send Invoice Reminder', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:37:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('173', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:37:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('174', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:37:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('175', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:39:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('176', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 20:39:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('177', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:19:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('178', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:20:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('179', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:21:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('180', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:21:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('181', '7', 'prospects', 'update', 'Updated lead: Rohit Bansal', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:24:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('182', '7', 'quotations', 'update', 'Updated quotation: QT0001', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:28:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('183', '7', 'quotations', 'update', 'Updated quotation: QT0001', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:28:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('184', '7', 'prospects', 'create', 'Created lead: Ravindra Gajjar', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:39:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('185', '7', 'tasks', 'update', 'Moved task \'Server Maintenance\' to in_progress via Kanban', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:50:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('186', '7', 'tasks', 'update', 'Moved task \'Send Invoice Reminder\' to completed via Kanban', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:50:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('187', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:53:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('188', '9', 'auth', 'login', 'User logged in: ranjit@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:53:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('189', '9', 'auth', 'logout', 'User logged out: ranjit@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:54:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('190', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 21:54:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('191', '6', 'settings', 'update', 'Updated user info: parvez@sushobha.com', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 22:02:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('192', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 22:38:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('193', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 23:39:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('194', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 23:54:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('195', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 09:48:03');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('196', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 12:08:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('197', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 13:36:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('198', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 13:56:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('199', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 13:56:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('200', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 14:11:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('201', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 14:11:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('202', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 15:12:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('203', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 15:45:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('204', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 15:46:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('205', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 15:58:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('206', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 15:58:18');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('207', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 17:43:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('208', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:53:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('209', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 20:44:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('210', '7', 'settings', 'backup', 'Full system backup created: 2026-05-12_204820', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 20:48:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('211', '7', 'products', 'delete', 'Deleted product: Annual Maintenance Contract', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:11:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('212', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:45:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('213', '7', 'products', 'delete', 'Deleted product: CRM Software License', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('214', '7', 'products', 'delete', 'Deleted product: Dell Laptop i5', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('215', '7', 'products', 'delete', 'Deleted product: ERP Implementation', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('216', '7', 'products', 'delete', 'Deleted product: HP LaserJet Printer', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('217', '7', 'products', 'delete', 'Deleted product: IT Consulting - Hourly', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('218', '7', 'products', 'delete', 'Deleted product: Keyboard & Mouse Combo', '12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('219', '7', 'products', 'delete', 'Deleted product: Netgear WiFi Router', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('220', '7', 'products', 'delete', 'Deleted product: Samsung Monitor 24\"', '11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:46:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('221', '7', 'products', 'delete', 'Deleted product: SEO Package - Monthly', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:47:00');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('222', '7', 'products', 'delete', 'Deleted product: Social Media Management', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:47:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('223', '7', 'products', 'delete', 'Deleted product: Website Development', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:47:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('224', '7', 'products', 'create', 'Created product: LED Mirror', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 21:48:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('225', '7', 'products', 'category_create', 'Created category via AJAX: Partition Systems', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:01:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('226', '7', 'products', 'category_create', 'Created category via AJAX: Shower Enclosures', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:01:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('227', '7', 'products', 'category_create', 'Created category via AJAX: Flush Doors', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:01:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('228', '7', 'products', 'category_create', 'Created category via AJAX: Cabinets & Storage', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:01:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('229', '7', 'products', 'category_create', 'Created category via AJAX: LED Mirrors', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:01:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('230', '7', 'products', 'category_create', 'Created category via AJAX: Luminated Walls', '11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:02:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('231', '7', 'products', 'category_create', 'Created category via AJAX: Glass Surface', '12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:02:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('232', '7', 'products', 'category_create', 'Created category via AJAX: Wooden Products', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:02:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('233', '7', 'products', 'category_create', 'Created category via AJAX: Trunkey Projects', '14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:02:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('234', '7', 'products', 'category_create', 'Created category via AJAX: Marble & Tiles', '15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:02:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('235', '7', 'products', 'update', 'Updated product: LED Mirror', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:02:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('236', '7', 'products', 'update', 'Updated product: LED Mirror', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:03:03');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('237', '7', 'products', 'update', 'Updated product: LED Mirror', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:03:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('238', '7', 'quotations', 'update', 'Updated quotation: QT0001', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:05:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('239', '7', 'quotations', 'delete', 'Deleted quotation: QT0002', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:06:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('240', '7', 'quotations', 'delete', 'Deleted quotation: QT0003', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:06:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('241', '7', 'quotations', 'delete', 'Deleted quotation: QT0004', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:06:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('242', '7', 'quotations', 'delete', 'Deleted quotation: QT0005', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:06:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('243', '7', 'quotations', 'create', 'Created quotation: QT0002', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:07:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('244', '7', 'quotations', 'delete', 'Deleted quotation: QT0002', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:07:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('245', '7', 'quotations', 'delete', 'Deleted quotation: QT0001', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:07:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('246', '7', 'products', 'update', 'Updated product: LED Mirror', '13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:09:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('247', '7', 'quotations', 'create', 'Created quotation: QT0001', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 22:10:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('248', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 23:20:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('249', '7', 'settings', 'backup', 'Full system backup created: 2026-05-12_232613', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 23:26:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('250', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_000231', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:02:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('251', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:21:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('252', '7', 'settings', 'restore', 'Program files restored from: files_backup_2026-05-13_000231.zip.015d2dd4', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:22:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('253', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_002819', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:28:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('254', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:28:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('255', '6', 'auth', 'login', 'User logged in: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:28:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('256', '6', 'auth', 'logout', 'User logged out: parvez@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:33:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('257', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:34:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('258', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:42:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('259', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:42:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('260', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:47:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('261', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:48:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('262', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 00:48:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('263', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_011705', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 01:17:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('264', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_011849', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 01:18:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('265', '7', 'auth', 'logout', 'User logged out: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 01:19:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('266', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 09:23:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('267', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_095433', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 09:54:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('268', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 10:24:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('269', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_104108', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 10:41:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('270', '7', 'settings', 'backup', 'Full system backup created: 2026-05-13_104919', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 10:49:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('271', '7', 'settings', 'restore', 'Program files restored from: files_backup_2026-05-13_104919.zip', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 11:03:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('272', '7', 'auth', 'login', 'User logged in: ravikaant@sushobha.com', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 11:28:52');


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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `announcement_comments` (`id`, `announcement_id`, `user_id`, `comment`, `created_at`) VALUES ('1', '1', '6', 'Congratulations....', '2026-05-11 21:21:22');


DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('Management Note','Announcement','Policy Update','Target Reminder','Operational Alert') NOT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Low',
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `announcements` (`id`, `title`, `content`, `category`, `priority`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'Congratulations', 'We are pleased to announce that our organization is now digitally equipped with advanced operational and management tools designed to improve productivity, communication, workflow tracking, and overall efficiency across all departments. These systems have been implemented to streamline our daily operations, enhance transparency, reduce manual processes, and ensure better coordination within teams.\r\n\r\nAll employees are requested to actively adopt and utilize the application to its fullest potential in their day-to-day activities. Proper usage of the platform will help maintain accurate records, improve response time, enable effective collaboration, and support organizational growth through a more structured and technology-driven approach.\r\n\r\nWe encourage everyone to adhere to the digital processes and ensure timely updates, task management, reporting, and communication through the system. Your cooperation and commitment toward embracing this digital transformation will contribute significantly to operational excellence and the long-term success of the organization.', 'Management Note', 'High', '1', '2', '2026-05-10 20:40:27', '2026-05-10 20:40:27');


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

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('1', '1', '2026-05-09', 'present', '09:02:00', '18:10:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('2', '2', '2026-05-09', 'present', '09:15:00', '18:05:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('3', '3', '2026-05-09', 'present', '09:30:00', '18:00:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('4', '4', '2026-05-09', 'leave', NULL, NULL, NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('5', '5', '2026-05-09', 'present', '09:05:00', '18:20:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('6', '1', '2026-05-08', 'present', '09:00:00', '18:00:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('7', '2', '2026-05-08', 'half_day', '09:00:00', '13:00:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('8', '3', '2026-05-08', 'present', '09:10:00', '18:05:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('9', '4', '2026-05-08', 'present', '09:00:00', '18:00:00', NULL, '2026-05-10 02:04:09');
INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in`, `check_out`, `notes`, `created_at`) VALUES ('10', '5', '2026-05-08', 'absent', NULL, NULL, NULL, '2026-05-10 02:04:09');


DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(20) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `alternate_contact` varchar(255) DEFAULT NULL,
  `alt_phone` varchar(20) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `gst_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `customer_type` enum('Dealer','Distributor','Architect','Interior Designer','Builder','Contractor','Retail Customer','Corporate Client','Vendor/Supplier','Channel Partner') DEFAULT 'Retail Customer',
  `business_category` enum('Residential','Commercial','Hospitality','Retail','Industrial') DEFAULT 'Residential',
  `industry_type` enum('Construction','Interior Design','Real Estate','Furniture','Luxury Products','Architecture Firm') DEFAULT 'Real Estate',
  `preferred_communication` enum('Call','WhatsApp','Email','Meeting') DEFAULT 'Call',
  `credit_limit` decimal(12,2) DEFAULT 0.00,
  `outstanding_balance` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `google_maps_location` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `gps_accuracy` decimal(8,2) DEFAULT NULL,
  `gps_address` text DEFAULT NULL,
  `gps_captured_at` datetime DEFAULT NULL,
  `status` enum('Prospect','Active','Inactive','Lost','Blacklisted') DEFAULT 'Active',
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_code` (`customer_code`),
  KEY `created_by` (`created_by`),
  KEY `idx_customer_name` (`name`),
  KEY `idx_customer_email` (`email`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'CUST0001', 'Arjun Mehta', 'TechVision Pvt Ltd', NULL, 'arjun@techvision.in', NULL, '9876543210', NULL, NULL, NULL, '27AABCT1332L1ZY', NULL, '101 Tech Park, Andheri East', 'Mumbai', 'Maharashtra', '400069', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2', '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'CUST0002', 'Sneha Patel', 'Global Traders', NULL, 'sneha@globaltraders.com', NULL, '9876500001', NULL, NULL, NULL, '24AAACP1234F1ZE', NULL, '34 Commerce St, Navrangpura', 'Ahmedabad', 'Gujarat', '380009', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2', '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'CUST0003', 'Vikram Singh', 'Horizon Industries', NULL, 'vikram@horizon.co.in', NULL, '9988776655', NULL, NULL, NULL, '07AAACH1234A1ZQ', NULL, '45 Industrial Area, Phase II', 'Delhi', 'Delhi', '110020', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'CUST0004', 'Deepika Rao', 'SunRise Exports', NULL, 'deepika@sunrise.com', NULL, '8877665544', NULL, NULL, NULL, '29AABCS1234A1ZP', NULL, '78 Export Zone, Whitefield', 'Bengaluru', 'Karnataka', '560066', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'CUST0005', 'Rahul Joshi', 'PinPoint Solutions', NULL, 'rahul@pinpoint.in', NULL, '7766554433', NULL, NULL, NULL, '06AABCP1234F1ZA', NULL, '12 Cyber Hub, Gurgaon', 'Gurugram', 'Haryana', '122002', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2', '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('6', 'CUST0006', 'Pooja Nair', 'Coastal Enterprises', NULL, 'pooja@coastal.com', NULL, '9900112233', NULL, NULL, NULL, '32AAACF1234G1ZK', NULL, '56 Marine Drive', 'Kochi', 'Kerala', '682001', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('7', 'CUST0007', 'Suresh Iyer', 'BrightFuture Edu', NULL, 'suresh@brightfuture.org', NULL, '8800990011', NULL, NULL, NULL, '33AAABF1234H1ZR', NULL, '90 College Road', 'Chennai', 'Tamil Nadu', '600006', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2', '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('8', 'CUST0008', 'Kavya Sharma', 'NexGen Retail', NULL, 'kavya@nexgen.com', NULL, '7700889900', NULL, NULL, NULL, '08AABHM1234J1ZA', NULL, '23 MG Road', 'Pune', 'Maharashtra', '411001', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('9', 'CUST0009', 'Arun Kumar', 'Delta Constructions', NULL, 'arun@delta.in', NULL, '9988001122', NULL, NULL, NULL, '36AAADM1234K1ZP', NULL, '55 Ring Road', 'Hyderabad', 'Telangana', '500032', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2', '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('10', 'CUST0010', 'Meena Krishnan', 'Apex Pharma', NULL, 'meena@apexpharma.com', NULL, '8811223344', NULL, NULL, NULL, '21AAACG1234L1ZX', NULL, '88 Pharma Park, MIDC', 'Pune', 'Maharashtra', '411018', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, '2026-05-10 02:04:09', '2026-05-10 02:52:49');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('11', 'CUST0011', 'Test Customer', 'Test Corp', NULL, 'test@test.com', NULL, '1234567890', NULL, NULL, NULL, '', '', '', '', '', '', 'India', 'Dealer', 'Commercial', 'Construction', 'WhatsApp', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL, NULL, 'Prospect', '2', '2026-05-10 02:59:07', '2026-05-10 02:59:07');
INSERT INTO `customers` (`id`, `customer_code`, `name`, `company`, `contact_person`, `email`, `website`, `phone`, `alternate_contact`, `alt_phone`, `whatsapp_number`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `country`, `customer_type`, `business_category`, `industry_type`, `preferred_communication`, `credit_limit`, `outstanding_balance`, `notes`, `google_maps_location`, `latitude`, `longitude`, `gps_accuracy`, `gps_address`, `gps_captured_at`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('12', 'CUST0012', 'Test Customer Quick Add', '', NULL, '', '', '', NULL, NULL, '', '', '', '', '', '', '', 'India', 'Retail Customer', 'Residential', 'Real Estate', 'Call', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2', '2026-05-10 15:08:49', '2026-05-11 20:21:19');


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
  `access_rights` varchar(255) DEFAULT '["Read"]',
  `role_id` int(11) DEFAULT 5,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_code` (`emp_code`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`, `access_rights`, `role_id`) VALUES ('1', '2', 'EMP0001', 'Parvez Hashmi', 'parvez@sushobha.com', '+91 9898549909', 'Management', 'Sales', '2026-01-01', '100000.00', 'monthly', '', NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-10 02:04:09', '2026-05-10 22:02:23', '[\"Read\",\"Write\",\"Modify\",\"View\",\"Approve\"]', '2');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`, `access_rights`, `role_id`) VALUES ('2', NULL, 'EMP0002', 'Girish Kumar Solanki', 'girish@sushobha.com', '', 'Sales', 'Sales - Domestic', '2026-06-01', '100000.00', 'monthly', '', NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-10 02:04:09', '2026-05-10 22:02:52', '[\"Read\",\"Write\",\"Modify\",\"View\"]', '3');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`, `access_rights`, `role_id`) VALUES ('3', NULL, 'EMP0003', 'Ranjit Srivastav', 'rks@sushobha.com', '', 'Sales', 'Dealer Network Mgmt', '2026-01-15', '100000.00', 'monthly', '', NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-10 02:04:09', '2026-05-10 22:03:08', '[\"Read\",\"Write\",\"Modify\",\"View\"]', '3');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`, `access_rights`, `role_id`) VALUES ('4', NULL, 'EMP0004', 'Neeraj Sharma', 'neeraj@sushobha.com', '', 'Partnere', 'Wooden Partner', '2026-07-01', '100000.00', 'monthly', '', NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-10 02:04:09', '2026-05-10 21:35:29', '[\"Read\",\"Write\"]', '5');
INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `name`, `email`, `phone`, `department`, `designation`, `join_date`, `salary`, `salary_type`, `address`, `emergency_contact`, `photo`, `bank_name`, `bank_account`, `bank_ifsc`, `status`, `created_at`, `updated_at`, `access_rights`, `role_id`) VALUES ('5', NULL, 'EMP0005', 'Ravikaant Parmar', 'kiran@sushobha.com', '+91 9898549909', 'Sales', 'IT Marketing support', '2026-01-01', '100000.00', 'monthly', '', NULL, NULL, NULL, NULL, NULL, 'active', '2026-05-10 02:04:09', '2026-05-10 22:03:18', '[\"Read\",\"Write\",\"Modify\",\"Delete\",\"View\",\"Approve\"]', '1');


DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('1', 'Office Rent', NULL, '2026-05-10 02:04:09');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('3', 'Utilities', NULL, '2026-05-10 02:04:09');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('4', 'Travel & Conveyance', NULL, '2026-05-10 02:04:09');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('5', 'Marketing & Advertising', NULL, '2026-05-10 02:04:09');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('6', 'Software Subscriptions', NULL, '2026-05-10 02:04:09');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('7', 'Office Supplies', NULL, '2026-05-10 02:04:09');
INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES ('8', 'Miscellaneous', NULL, '2026-05-10 02:04:09');


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

INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('1', '1', 'Office Rent - April 2026', '25000.00', '2026-04-01', 'bank_transfer', NULL, 'Monthly office rent', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('2', '3', 'Electricity Bill - April', '3500.00', '2026-04-05', 'bank_transfer', NULL, 'Electricity charges', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('3', '4', 'Team Dinner - Client Visit', '4200.00', '2026-04-10', 'card', NULL, 'Team dinner after client demo', NULL, NULL, '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('4', '5', 'Google Ads Campaign', '15000.00', '2026-04-01', 'card', NULL, 'April digital marketing budget', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('5', '6', 'Adobe Creative Cloud', '3540.00', '2026-04-01', 'card', NULL, 'Annual subscription / 12', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('6', '7', 'Printer Ink & Toner', '1800.00', '2026-04-15', 'cash', NULL, 'Office supplies', NULL, NULL, '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('7', '1', 'Office Rent - May 2026', '25000.00', '2026-05-01', 'bank_transfer', NULL, 'Monthly office rent', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('8', '3', 'Internet Bill - April', '1200.00', '2026-04-01', 'bank_transfer', NULL, 'Broadband charges', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('9', '4', 'Fuel Reimbursement', '2500.00', '2026-04-20', 'cash', NULL, 'Sales team travel', NULL, NULL, '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `expenses` (`id`, `category_id`, `title`, `amount`, `expense_date`, `payment_method`, `reference`, `description`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES ('10', '8', 'Miscellaneous Expenses', '800.00', '2026-04-25', 'cash', NULL, 'Petty cash expenses', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');


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
  `discount` decimal(12,2) DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('4', '2', NULL, 'Office Space Planning', '3.00', 'Days', '12000.00', '18.00', '6480.00', '0.00', '36000.00', '0');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('5', '2', NULL, 'Premium Office Furniture', '1.00', 'Set', '65000.00', '18.00', '11700.00', '0.00', '65000.00', '1');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('6', '2', NULL, 'Lighting & Electrical Work', '1.00', 'Job', '27250.00', '18.00', '4905.00', '0.00', '27250.00', '2');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('7', '3', NULL, 'Classroom Furniture', '10.00', 'Sets', '2500.00', '18.00', '4500.00', '0.00', '25000.00', '0');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('8', '3', NULL, 'Whiteboard & Display Setup', '2.00', 'Nos', '7500.00', '18.00', '2700.00', '0.00', '15000.00', '1');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('9', '4', NULL, 'Site Inspection & Consulting', '4.00', 'Days', '5000.00', '18.00', '3600.00', '0.00', '20000.00', '0');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('10', '4', NULL, 'Construction Planning & Drawings', '1.00', 'Job', '38000.00', '18.00', '6840.00', '0.00', '38000.00', '1');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('11', '5', NULL, 'Residential Interior Design', '1.00', 'Job', '75000.00', '18.00', '13500.00', '0.00', '75000.00', '0');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('12', '5', NULL, 'Premium Décor Items', '1.00', 'Set', '30000.00', '18.00', '5400.00', '0.00', '30000.00', '1');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('13', '5', NULL, 'Installation & Setup', '1.00', 'Job', '15000.00', '18.00', '2700.00', '0.00', '15000.00', '2');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('14', '1', NULL, 'Annual Maintenance Contract', '5.00', 'Year', '18000.00', '18.00', '16200.00', '0.00', '90000.00', '0');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('15', '1', NULL, 'CRM Software License', '1.00', 'License', '15000.00', '18.00', '2700.00', '0.00', '15000.00', '1');
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('16', '1', NULL, 'Dell Laptop i5', '1.00', 'Nos', '58000.00', '18.00', '10440.00', '0.00', '58000.00', '2');


DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(30) NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `status` enum('draft','sent','paid','partial','overdue','cancelled') DEFAULT 'draft',
  `issued_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `discount_type` enum('fixed','percent') DEFAULT 'fixed',
  `discount_value` decimal(12,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `paid_amount` decimal(12,2) DEFAULT 0.00,
  `balance_due` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
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

INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `discount_type`, `discount_value`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'INV0001', '1', '5', 'partial', '2026-04-01', '2026-04-30', '163000.00', '0.00', 'fixed', '0.00', '29340.00', '192340.00', '135700.00', '56640.00', '', '1. Payment is due within 7 days.\r\n2. Please include invoice number on your check.', '2', '2026-05-10 02:04:09', '2026-05-10 12:11:16');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `discount_type`, `discount_value`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'INV0002', '2', '1', 'partial', '2026-04-10', '2026-05-10', '128250.00', '0.00', 'fixed', '0.00', '23085.00', '151335.00', '75000.00', '76335.00', NULL, '1. Payment is due within 7 days.\n2. Please include invoice number on your check.', '2', '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `discount_type`, `discount_value`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'INV0003', '3', '7', 'sent', '2026-04-20', '2026-05-20', '35000.00', '0.00', 'fixed', '0.00', '6300.00', '41300.00', '0.00', '41300.00', NULL, '1. Payment is due within 7 days.\n2. Please include invoice number on your check.', NULL, '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `discount_type`, `discount_value`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'INV0004', '4', '9', 'paid', '2026-03-15', '2026-04-15', '58000.00', '0.00', 'fixed', '0.00', '10440.00', '68440.00', '68440.00', '0.00', NULL, '1. Payment is due within 7 days.\n2. Please include invoice number on your check.', NULL, '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `customer_id`, `status`, `issued_date`, `due_date`, `subtotal`, `discount_amount`, `discount_type`, `discount_value`, `tax_amount`, `total`, `paid_amount`, `balance_due`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'INV0005', '5', '3', 'partial', '2026-04-25', '2026-05-25', '120000.00', '0.00', 'fixed', '0.00', '21600.00', '141600.00', '50000.00', '91600.00', NULL, '1. Payment is due within 7 days.\n2. Please include invoice number on your check.', '2', '2026-05-10 02:04:09', '2026-05-10 12:03:32');


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
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_contacts_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('11', '1', 'Engineer', 'Jayram Mohan rai', NULL, '+91 9898549909', NULL, NULL, NULL, NULL, '1', '2026-05-12 15:26:39');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('12', '2', 'Owner', 'Radhe Krishna', NULL, '9898549909', NULL, NULL, NULL, NULL, '1', '2026-05-12 15:28:05');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('13', '3', 'Owner', 'Radhe Krishna', NULL, '9898549909', NULL, NULL, NULL, NULL, '1', '2026-05-12 15:28:44');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('14', '4', 'Owner', 'Radhe Krishna', NULL, '9898549909', NULL, NULL, NULL, NULL, '1', '2026-05-12 15:28:50');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('15', '4', 'Manager', 'Mahndra Singh Dhoni', 'Owner', '+919898549909', NULL, NULL, NULL, NULL, '0', '2026-05-12 15:49:45');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('16', '4', 'Manager', 'Mahndra Singh Dhoni', 'Owner', '+919898549909', NULL, NULL, NULL, NULL, '0', '2026-05-12 15:49:55');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('17', '4', 'Manager', 'Mahndra Singh Dhoni', 'Owner', '+919898549909', NULL, NULL, NULL, NULL, '0', '2026-05-12 15:50:20');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('18', '4', 'Manager', 'Mahndra Singh Dhoni', 'Owner', '+919898549909', NULL, NULL, NULL, NULL, '0', '2026-05-12 15:50:26');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('19', '4', 'Manager', 'Vijay Rajput', 'DGM', '+919898549909', NULL, NULL, NULL, NULL, '0', '2026-05-12 15:53:15');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('20', '5', 'Owner', 'Nirmeet Kacheria', NULL, '9898989898', NULL, NULL, NULL, NULL, '1', '2026-05-12 16:00:01');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('21', '6', 'Owner', 'Nirmeet Kacheria', NULL, '9898989898', NULL, NULL, NULL, NULL, '1', '2026-05-12 16:00:53');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('22', '6', 'Manager', 'Mahendra Kunjrawala', 'owner', '+919898898988', NULL, NULL, NULL, NULL, '0', '2026-05-12 16:02:18');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('27', '8', 'Owner', 'Krishna Kanth', NULL, '+91 9898549909', NULL, NULL, 'krishna@gmail.com', NULL, '1', '2026-05-12 23:29:30');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('28', '9', 'Owner', 'Krishna Kanth', NULL, '+91 9898549909', NULL, NULL, 'krishna@gmail.com', NULL, '1', '2026-05-12 23:29:52');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('29', '10', 'Owner', 'Krishna Kanth', NULL, '+91 9898549909', NULL, NULL, 'krishna@gmail.com', NULL, '1', '2026-05-12 23:30:00');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('30', '11', 'Owner', 'Krishna Kanth', NULL, '+91 9898549909', NULL, NULL, 'krishna@gmail.com', NULL, '1', '2026-05-12 23:30:13');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('42', '12', 'Entrepreneur', 'Krishna Kanth', NULL, '+91 9898549909', '', '', 'krishna@gmail.com', '', '1', '2026-05-12 23:38:50');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('131', '13', 'Owner', 'Krishna Mondal', NULL, '9012548540', '', NULL, 'krishna@gmail.com', '[\"uploads\\/leads\\/cards\\/6a03726c861d4_ChatGPT_Image_May_6__2026__11_16_45_AM.png\"]', '1', '2026-05-13 10:01:26');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('135', '7', 'Owner', 'chootalal', NULL, '+919898989898', '', '', '', '[\"uploads\\/leads\\/cards\\/6a03fed7be42b_ChatGPT_Image_May_1__2026__07_04_38_PM.png\",\"uploads\\/leads\\/cards\\/6a03fed7be7ec_ChatGPT_Image_May_1__2026__11_24_04_AM.png\"]', '1', '2026-05-13 10:02:23');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('143', '13', 'Owner', 'Rajednra prasad', 'Manager', '98989549909', NULL, NULL, NULL, NULL, '0', '2026-05-13 10:32:51');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('144', '14', 'Owner', 'Dinesh Parmar', NULL, '+91 9898545427', '', '', 'dinesh@gmail.com', '[\"uploads\\/leads\\/cards\\/6a03712e42024_ChatGPT_Image_May_12__2026__02_48_17_PM.png\"]', '1', '2026-05-13 11:29:24');
INSERT INTO `lead_contacts` (`id`, `lead_id`, `contact_type`, `name`, `designation`, `mobile`, `alt_mobile`, `whatsapp`, `email`, `visiting_card`, `is_primary`, `created_at`) VALUES ('145', '14', 'Owner', 'damodar pahelwan', NULL, '9898549909', '', '', '', '[\"uploads\\/leads\\/cards\\/\\/6a040a139fd8c_ChatGPT_Image_May_8__2026__10_22_59_AM.png\"]', '0', '2026-05-13 11:29:24');


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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_documents_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('1', '13', 'uploads/leads/6a0381ab7fb0e_ChatGPT_Image_May_12__2026__02_48_17_PM.png', 'ChatGPT Image May 12, 2026, 02_48_17 PM.png', 'image/png', 'Site Media', NULL, 'Mobile', '2026-05-13 01:08:19');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('2', '14', 'uploads/leads/6a0381eba7210_img2.jpg', 'img2.jpg', 'image/jpeg', 'Site Media', NULL, 'Mobile', '2026-05-13 01:09:23');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('3', '14', 'uploads/leads/6a0381ebaa223_img1.jpg', 'img1.jpg', 'image/jpeg', 'Site Media', NULL, 'Mobile', '2026-05-13 01:09:23');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('4', '14', 'uploads/leads/6a0381ebafb19_img3.jpg', 'img3.jpg', 'image/jpeg', 'Site Media', NULL, 'Mobile', '2026-05-13 01:09:23');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('5', '14', 'uploads/leads/6a0381ebb97fb_img3.png', 'img3.png', 'image/png', 'Site Media', NULL, 'Mobile', '2026-05-13 01:09:23');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('6', '14', 'uploads/leads/6a0383754fa94_img3.jpg', 'img3.jpg', 'image/jpeg', 'Site Media', NULL, 'Mobile', '2026-05-13 01:15:57');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('7', '7', 'uploads/leads/6a0383a6a01c1_img2.png', 'img2.png', 'image/png', 'Site Media', NULL, 'Device', '2026-05-13 01:16:46');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('8', '7', 'uploads/leads/6a0383a6a6040_Se01.png', 'Se01.png', 'image/png', 'Site Media', NULL, 'Device', '2026-05-13 01:16:46');
INSERT INTO `lead_documents` (`id`, `lead_id`, `file_path`, `file_name`, `file_type`, `category`, `remark`, `uploaded_from`, `created_at`) VALUES ('9', '7', 'uploads/leads/6a0383a6ac827_ChatGPT_Image_May_7__2026__07_11_10_PM.png', 'ChatGPT Image May 7, 2026, 07_11_10 PM.png', 'image/png', 'Site Media', NULL, 'Device', '2026-05-13 01:16:46');


DROP TABLE IF EXISTS `lead_interested_products`;
CREATE TABLE `lead_interested_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `lead_interested_products_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('11', '1', 'Flush Doors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('12', '2', 'Flush Doors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('13', '2', 'Cabinets & Storage Solutions');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('14', '3', 'Flush Doors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('15', '3', 'Cabinets & Storage Solutions');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('16', '4', 'Flush Doors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('17', '4', 'Cabinets & Storage Solutions');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('18', '6', 'Partition Systems');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('19', '6', 'Flush Doors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('33', '12', 'Partition Systems');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('116', '13', 'Shower Enclosures');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('117', '13', 'Flush Doors');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('120', '7', 'Partition Systems');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('121', '7', 'Shower Enclosures');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('124', '14', 'Partition Systems');
INSERT INTO `lead_interested_products` (`id`, `lead_id`, `product_name`) VALUES ('125', '14', 'Flush Doors');


DROP TABLE IF EXISTS `lead_meetings`;
CREATE TABLE `lead_meetings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `meeting_with_id` int(10) unsigned DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `priority` varchar(20) DEFAULT 'Medium',
  `meeting_with` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `followup_date` date DEFAULT NULL,
  `project_start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `sales_stage` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `created_by` (`created_by`),
  KEY `meeting_with_id` (`meeting_with_id`),
  CONSTRAINT `lead_meetings_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_meetings_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lead_meetings_ibfk_3` FOREIGN KEY (`meeting_with_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('1', '4', NULL, 'Quoted', 'Medium', 'Radhe Krishna', 'Office Meeting', 'dadfadf', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 15:37:11');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('4', '4', NULL, 'Quoted', 'Medium', 'Radhe Krishna', 'Office Meeting', 'dadfadf', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 15:40:52');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('5', '4', NULL, 'Won', 'Medium', 'Radhe Krishna', 'Telephonic', 'We are Planning to place an Order', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 15:47:07');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('6', '4', NULL, 'Won', 'Medium', 'Radhe Krishna', 'Virtual Meeting', 'I was there been for 2 hours', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 15:48:44');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('7', '4', NULL, 'Won', 'Medium', 'Radhe Krishna', 'Virtual Meeting', 'I was there been for 2 hours', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 15:52:03');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('8', '4', NULL, 'Won', 'Medium', 'Vijay Rajput', 'Office Meeting', 'Meeetging done', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 15:53:34');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('9', '6', NULL, 'New', 'Medium', 'Nirmeet Kacheria', 'Office Meeting', 'Planning', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 16:01:46');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('10', '6', NULL, 'New', 'Medium', 'Mahendra Kunjrawala', 'Telephonic', 'Meeting', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 16:02:36');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('11', '7', NULL, 'Scheduled', 'Medium', 'chootalal', 'Negotiation', 'Requirement Gathering', NULL, NULL, NULL, NULL, NULL, '7', '2026-05-12 18:25:12');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('12', '7', NULL, 'New', 'Medium', 'Dhruv Ravikant Parmar', 'Office Meeting', 'Planning ', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 18:26:28');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('13', '7', NULL, 'Quoted', 'Medium', 'chootalal', 'Site Visit', 'dddd', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 18:28:43');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('14', '12', NULL, 'Negotiation', 'Medium', 'Krishna Kanth', 'Office Meeting', 'Meeeting organized', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 23:38:02');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('15', '13', NULL, 'Negotiation', 'Medium', 'Krishna', 'Office Meeting', 'Vusit', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 23:42:05');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('16', '13', NULL, 'Won', 'Medium', NULL, 'Office Meeting', 'dgsdf', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 23:42:20');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('17', '14', NULL, 'New', 'Medium', 'Dinesh Parmar', 'Office Meeting', 'Lead', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 23:46:19');
INSERT INTO `lead_meetings` (`id`, `lead_id`, `meeting_with_id`, `status`, `priority`, `meeting_with`, `type`, `purpose`, `followup_date`, `project_start_date`, `expected_completion_date`, `sales_stage`, `notes`, `created_by`, `created_at`) VALUES ('18', '13', NULL, 'In Progress', 'Medium', 'Rajednra prasad', 'Office Meeting', 'Meeting with personal reason', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-13 10:33:50');


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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('1', '1', '7', 'Created', 'Lead created with ID: LEAD000001', NULL, '2026-05-12 15:13:51');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('2', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:14:47');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('3', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:16:18');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('4', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:17:20');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('5', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:20:08');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('6', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:20:24');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('7', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:22:26');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('8', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:22:46');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('9', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:24:06');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('10', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:24:25');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('11', '1', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 15:26:39');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('12', '1', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 15:27:22');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('13', '2', '7', 'Created', 'Lead created with ID: LEAD000002', NULL, '2026-05-12 15:28:05');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('14', '3', '7', 'Created', 'Lead created with ID: LEAD000003', NULL, '2026-05-12 15:28:44');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('15', '4', '7', 'Created', 'Lead created with ID: LEAD000004', NULL, '2026-05-12 15:28:50');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('16', '4', NULL, 'Meeting', 'Recorded Office Meeting with Radhe Krishna. Status updated to Quoted.', NULL, '2026-05-12 15:37:11');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('17', '4', NULL, 'Meeting', 'Recorded Office Meeting with Radhe Krishna. Status updated to Quoted.', NULL, '2026-05-12 15:40:52');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('18', '4', NULL, 'Meeting', 'Recorded Telephonic with Radhe Krishna. Status updated to Won.', NULL, '2026-05-12 15:47:07');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('19', '4', NULL, 'Meeting', 'Recorded Virtual Meeting with Radhe Krishna. Status updated to Won.', NULL, '2026-05-12 15:48:44');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('20', '4', NULL, 'Meeting', 'Recorded Virtual Meeting with Radhe Krishna. Status updated to Won.', NULL, '2026-05-12 15:52:03');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('21', '4', NULL, 'Meeting', 'Recorded Office Meeting with Vijay Rajput. Status updated to Won.', NULL, '2026-05-12 15:53:34');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('22', '4', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 15:58:34');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('23', '3', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 15:58:36');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('24', '2', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 15:58:38');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('25', '5', '7', 'Created', 'Lead created with ID: LEAD000005', NULL, '2026-05-12 16:00:01');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('26', '6', '7', 'Created', 'Lead created with ID: LEAD000006', NULL, '2026-05-12 16:00:53');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('27', '6', NULL, 'Meeting', 'Recorded Office Meeting with Nirmeet Kacheria. Status updated to New.', NULL, '2026-05-12 16:01:46');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('28', '6', NULL, 'Meeting', 'Recorded Telephonic with Mahendra Kunjrawala. Status updated to New.', NULL, '2026-05-12 16:02:36');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('29', '6', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 18:22:42');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('30', '5', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 18:22:45');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('31', '7', '7', 'Created', 'Lead created with ID: LEAD000007', NULL, '2026-05-12 18:25:12');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('32', '7', NULL, 'Meeting', 'Recorded Office Meeting with Dhruv Ravikant Parmar. Status updated to New.', NULL, '2026-05-12 18:26:28');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('33', '7', NULL, 'Meeting', 'Recorded Site Visit with chootalal. Status updated to Quoted.', NULL, '2026-05-12 18:28:43');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('34', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 18:36:55');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('35', '8', '7', 'Created', 'Lead created with ID: LEAD000008', NULL, '2026-05-12 23:29:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('36', '9', '7', 'Created', 'Lead created with ID: LEAD000009', NULL, '2026-05-12 23:29:52');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('37', '10', '7', 'Created', 'Lead created with ID: LEAD000010', NULL, '2026-05-12 23:30:00');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('38', '11', '7', 'Created', 'Lead created with ID: LEAD000011', NULL, '2026-05-12 23:30:13');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('39', '12', '7', 'Created', 'Lead created with ID: LEAD000012', NULL, '2026-05-12 23:30:33');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('40', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:31:10');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('41', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:31:32');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('42', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:31:38');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('43', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:33:39');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('44', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:36:11');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('45', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:36:22');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('46', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:36:35');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('47', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:36:46');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('48', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:37:10');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('49', '12', NULL, 'Meeting', 'Recorded Office Meeting with Krishna Kanth. Status updated to Negotiation.', NULL, '2026-05-12 23:38:02');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('50', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:38:37');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('51', '12', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:38:50');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('52', '12', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 23:39:25');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('53', '11', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 23:39:27');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('54', '10', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 23:39:28');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('55', '9', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 23:39:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('56', '8', '7', 'Deleted', 'Lead moved to archive (soft deleted).', NULL, '2026-05-12 23:39:32');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('57', '13', '7', 'Created', 'Lead created with ID: LEAD000013', NULL, '2026-05-12 23:41:33');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('58', '13', NULL, 'Meeting', 'Recorded Office Meeting with Krishna. Status updated to Negotiation.', NULL, '2026-05-12 23:42:05');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('59', '13', NULL, 'Meeting', 'Recorded Office Meeting with . Status updated to Won.', NULL, '2026-05-12 23:42:20');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('60', '14', '7', 'Created', 'Lead created with ID: LEAD000014', NULL, '2026-05-12 23:45:18');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('61', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:45:54');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('62', '14', NULL, 'Meeting', 'Recorded Office Meeting with Dinesh Parmar. Status updated to New.', NULL, '2026-05-12 23:46:19');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('63', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:46:58');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('64', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:48:25');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('65', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:48:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('66', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:49:43');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('67', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:49:48');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('68', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-12 23:57:58');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('69', '13', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:03:16');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('70', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:04:04');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('71', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:05:02');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('72', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:08:06');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('73', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:08:15');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('74', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:08:47');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('75', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:09:23');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('76', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:27:41');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('77', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:27:50');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('78', '7', '6', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:29:34');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('79', '13', '6', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:30:16');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('80', '13', '6', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:30:46');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('81', '13', '6', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:31:18');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('82', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:34:56');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('83', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:39:17');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('84', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:40:19');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('85', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:43:35');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('86', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:43:59');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('87', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:44:41');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('88', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:46:44');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('89', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:46:47');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('90', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:47:21');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('91', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:47:25');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('92', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:51:04');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('93', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:53:23');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('94', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 00:53:28');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('95', '13', '7', 'Document', 'Uploaded 1 new document(s).', NULL, '2026-05-13 01:08:19');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('96', '13', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 01:08:30');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('97', '14', '7', 'Document', 'Uploaded 4 new document(s).', NULL, '2026-05-13 01:09:23');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('98', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 01:09:54');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('99', '14', '7', 'Document', 'Uploaded 1 new document(s).', NULL, '2026-05-13 01:15:57');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('100', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 01:16:06');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('101', '7', '7', 'Document', 'Uploaded 3 new document(s).', NULL, '2026-05-13 01:16:46');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('102', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 01:16:51');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('103', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 01:18:03');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('104', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 01:18:16');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('105', '13', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 10:01:09');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('106', '13', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 10:01:26');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('107', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 10:02:20');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('108', '7', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 10:02:23');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('109', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 10:16:21');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('110', '13', NULL, 'Meeting', 'Recorded Office Meeting with Rajednra prasad. Status updated to In Progress.', NULL, '2026-05-13 10:33:50');
INSERT INTO `lead_timeline` (`id`, `lead_id`, `user_id`, `action_type`, `description`, `metadata`, `created_at`) VALUES ('111', '14', '7', 'Updated', 'Lead details updated.', NULL, '2026-05-13 11:29:24');


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
  `next_followup_date` date DEFAULT NULL,
  `actual_followup_date` date DEFAULT NULL,
  `assigned_to` int(10) unsigned DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_type` varchar(100) DEFAULT NULL,
  `industry_type` varchar(100) DEFAULT NULL,
  `business_category` varchar(100) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `gst_number` varchar(50) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('1', 'LEAD000001', '2026-05-12', 'Interior Work', 'Residential', 'Facebook', 'Digital Marketing', 'Warm Lead', 'Catalogue Sent', '2026-06-12', '2026-08-12', '2026-05-13', '8', NULL, 'Individual', 'Manufacturing', 'B2B', NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 'India', NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100000.00', NULL, NULL, '7', '7', '2026-05-12 15:13:51', '2026-05-12 15:27:22', '2026-05-12 15:27:22');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('2', 'LEAD000002', '2026-05-12', 'Blueprint', 'Residential', 'Facebook', 'Digital Marketing', 'Warm Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '8', NULL, 'Individual', 'Manufacturing', 'B2B', NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 'India', NULL, '0.00', NULL, NULL, NULL, NULL, NULL, 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 15:28:05', '2026-05-12 15:58:38', '2026-05-12 15:58:38');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('3', 'LEAD000003', '2026-05-12', 'Blueprint', 'Residential', 'Facebook', 'Digital Marketing', 'Warm Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-21', '8', NULL, 'Individual', 'Manufacturing', 'B2B', NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 'India', NULL, '0.00', NULL, NULL, NULL, NULL, NULL, 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 15:28:44', '2026-05-12 15:58:36', '2026-05-12 15:58:36');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('4', 'LEAD000004', '2026-05-12', 'Blueprint', 'Residential', 'Facebook', 'Digital Marketing', 'Warm Lead', 'Won', '2026-06-12', '2026-08-12', '2026-05-19', '8', NULL, 'Individual', 'Manufacturing', 'B2B', NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 'India', NULL, '0.00', NULL, NULL, NULL, NULL, NULL, 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 15:28:50', '2026-05-12 15:58:34', '2026-05-12 15:58:34');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('5', 'LEAD000005', '2026-05-12', 'Blueprint', 'Residential', 'Website', 'Digital Marketing', 'Warm Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '8', 'Agniforma Pvt Ltd.', 'Pvt Ltd', 'Manufacturing', 'B2B', NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, 'Gujarat', 'India', '382424', '0.00', '23.11201140', '72.59417324', '23.1120113984254, 72.59417324160144', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120113984254,72.59417324160144', 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 16:00:01', '2026-05-12 18:22:45', '2026-05-12 18:22:45');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('6', 'LEAD000006', '2026-05-12', 'Blueprint', 'Residential', 'Website', 'Digital Marketing', 'Warm Lead', 'New', '2026-06-12', '2026-08-12', '2026-05-19', '8', 'Agniforma Pvt Ltd.', 'Pvt Ltd', 'Manufacturing', 'B2B', NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, 'Gujarat', 'India', '382424', '0.00', '23.11201140', '72.59417324', '23.1120113984254, 72.59417324160144', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120113984254,72.59417324160144', 'Bespoke', NULL, '1000000.00', '2 months', NULL, '7', '7', '2026-05-12 16:00:53', '2026-05-12 18:22:42', '2026-05-12 18:22:42');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('7', 'LEAD000007', '2026-05-12', 'Bedroom Work', 'Commercial', 'Website', 'Digital Marketing', 'Warm Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-10', '8', 'Agniforma Pvt Ltd.', 'Pvt Ltd', 'Manufacturing', 'B2B', 'parmardhruv96@gmail.com', NULL, NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'chandkheda', 'ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11200615', '72.59418254', '23.112006148861457, 72.59418254053337', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.112006148861457,72.59418254053337', NULL, NULL, '200000.00', NULL, NULL, '7', '7', '2026-05-12 18:25:12', '2026-05-13 00:43:35', NULL);
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('8', 'LEAD000008', '2026-05-12', 'Bedroom Work', 'Commercial', 'Instagram', 'Digital Marketing', 'Hot Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '6', 'Span Tiles', 'Individual', 'Trading', 'B2B', 'kishan@spantiles.com', 'https://www.spantiles.com', NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'Radhe Swami Road', 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201924', '72.59420087', '23.1120192441068, 72.59420087082489', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120192441068,72.59420087082489', 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 23:29:30', '2026-05-12 23:39:32', '2026-05-12 23:39:32');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('9', 'LEAD000009', '2026-05-12', 'Bedroom Work', 'Commercial', 'Instagram', 'Digital Marketing', 'Hot Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '6', 'Span Tiles', 'Individual', 'Trading', 'B2B', 'kishan@spantiles.com', 'https://spantiles.com/', NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'Radhe Swami Road', 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201924', '72.59420087', '23.1120192441068, 72.59420087082489', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120192441068,72.59420087082489', 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 23:29:52', '2026-05-12 23:39:30', '2026-05-12 23:39:30');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('10', 'LEAD000010', '2026-05-12', 'Bedroom Work', 'Commercial', 'Instagram', 'Digital Marketing', 'Hot Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '6', 'Span Tiles', 'Individual', 'Trading', 'B2B', 'kishan@spantiles.com', NULL, NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'Radhe Swami Road', 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201924', '72.59420087', '23.1120192441068, 72.59420087082489', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120192441068,72.59420087082489', 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 23:30:00', '2026-05-12 23:39:28', '2026-05-12 23:39:28');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('11', 'LEAD000011', '2026-05-12', 'Bedroom Work', 'Commercial', 'Instagram', 'Digital Marketing', 'Hot Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '6', 'Span Tiles', 'Individual', 'Trading', 'B2B', 'kishan@spantiles.com', NULL, NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'Radhe Swami Road', 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201924', '72.59420087', '23.1120192441068, 72.59420087082489', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120192441068,72.59420087082489', 'Bespoke', NULL, '0.00', NULL, NULL, '7', '7', '2026-05-12 23:30:13', '2026-05-12 23:39:27', '2026-05-12 23:39:27');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('12', 'LEAD000012', '2026-05-12', 'Bedroom Work', 'Commercial', 'Instagram', 'Digital Marketing', 'Hot Lead', 'Open', '2026-06-12', '2026-08-12', '2026-05-19', '6', 'Span Tiles', 'Individual', 'Trading', 'B2B', 'kishan@spantiles.com', 'www.spantiles.com', NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'Radhe Swami Road', 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201924', '72.59420087', '23.1120192441068, 72.59420087082489', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120192441068,72.59420087082489', NULL, NULL, '2052515.00', NULL, NULL, '7', '7', '2026-05-12 23:30:33', '2026-05-12 23:39:25', '2026-05-12 23:39:25');
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('13', 'LEAD000013', '2026-05-12', 'Glass Work', 'Institutional', 'Instagram', 'Digital Marketing', 'Cold Lead', 'In Progress', '2026-06-12', '2026-08-12', '2026-05-20', '8', 'Span Tiles', 'Partnership', 'Trading', 'B2C', 'krishna@spantiles.com', 'spantiles.com', NULL, 'Active', 'A-102, Swagat Status 2', 'Off New CG Road, Near Doon Blossom School', 'Radhe Swami Road', 'Chandkheda Ahmedabad', 'Gujarat', 'India', '382424', '0.00', '23.11201924', '72.59420087', '23.1120192441068, 72.59420087082489', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.1120192441068,72.59420087082489', NULL, NULL, '2000000.00', NULL, NULL, '7', '7', '2026-05-12 23:41:33', '2026-05-13 10:33:50', NULL);
INSERT INTO `leads` (`id`, `lead_code`, `lead_date`, `site_stage`, `project_type`, `lead_type`, `lead_source`, `lead_priority`, `lead_status`, `expected_closing_date`, `next_followup_date`, `actual_followup_date`, `assigned_to`, `company_name`, `company_type`, `industry_type`, `business_category`, `company_email`, `company_website`, `gst_number`, `company_status`, `address_line1`, `address_line2`, `area`, `city`, `state`, `country`, `pincode`, `approx_area_sqft`, `lat`, `lng`, `google_location`, `google_address`, `google_maps_link`, `product_type`, `requirement_description`, `estimated_budget`, `purchase_timeline`, `competitor_info`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES ('14', 'LEAD000014', '2026-05-12', 'Flooring Work', 'Commercial', 'Facebook', 'Contractor', 'Hot Lead', 'Qualified', '2026-06-12', '2026-08-12', '2026-05-19', '8', 'Enso Pvt Ltd.', 'Partnership', 'Manufacturing', 'B2B', 'dinesh@enso.com', 'enso.com', NULL, 'Active', 'A12, Sayona City, ', 'Raigard', 'city place', 'bunglo', 'Gujarat', 'India', '382424', '0.00', '23.11201308', '72.59418658', '23.11201307668002, 72.59418658176085', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', 'https://www.google.com/maps?q=23.11201307668002,72.59418658176085', NULL, NULL, '2524525.00', NULL, NULL, '7', '7', '2026-05-12 23:45:18', '2026-05-13 01:18:16', NULL);


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

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('1', '2', 'New Lead Assigned', 'Rohit Bansal from StartUp Hub has been assigned to you', 'info', '/modules/prospects/view.php?id=1', '0', '2026-05-10 02:04:09');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('2', '2', 'Invoice Overdue', 'Invoice INV0002 for TechVision is overdue by 10 days', 'warning', '/modules/invoices/view.php?id=2', '0', '2026-05-10 02:04:09');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('3', '2', 'Payment Received', 'Payment of Ôé╣75,000 received from TechVision Pvt Ltd', 'success', '/modules/payments/', '0', '2026-05-10 02:04:09');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`) VALUES ('5', '2', 'Low Stock Alert', 'Dell Laptop i5 stock is below minimum level', 'warning', '/modules/products/', '0', '2026-05-10 02:04:09');


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
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('3', '2', NULL, 'ERP Implementation', '1.00', 'Nos', '120000.00', '18.00', '21600.00', '141600.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('4', '2', NULL, 'IT Consulting - 5 hrs', '5.00', 'Nos', '2250.00', '18.00', '2025.00', '13275.00');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('5', '3', NULL, 'Website Development', '1.00', 'Nos', '35000.00', '18.00', '6300.00', '41300.00');
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
  `terms` text DEFAULT NULL,
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

INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `terms`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'ORD0001', NULL, '5', 'delivered', NULL, 'paid', '115000.00', 'fixed', '0.00', '0.00', '20700.00', '0.00', '135700.00', '135700.00', NULL, '1. Delivery subject to stock availability.\n2. Payment: 100% advance.', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `terms`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'ORD0002', NULL, '1', 'processing', NULL, 'partial', '128250.00', 'fixed', '0.00', '0.00', '23085.00', '0.00', '151335.00', '75000.00', NULL, '1. Delivery subject to stock availability.\n2. Payment: 100% advance.', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `terms`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'ORD0003', NULL, '7', 'pending', NULL, 'unpaid', '35000.00', 'fixed', '0.00', '0.00', '6300.00', '0.00', '41300.00', '0.00', NULL, '1. Delivery subject to stock availability.\n2. Payment: 100% advance.', NULL, NULL, '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `terms`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'ORD0004', NULL, '9', 'delivered', NULL, 'paid', '58000.00', 'fixed', '0.00', '0.00', '10440.00', '0.00', '68440.00', '68440.00', NULL, '1. Delivery subject to stock availability.\n2. Payment: 100% advance.', NULL, NULL, '2026-05-10 02:04:09', '2026-05-10 12:03:32');
INSERT INTO `orders` (`id`, `order_number`, `quotation_id`, `customer_id`, `status`, `delivery_date`, `payment_status`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `shipping_charges`, `total`, `paid_amount`, `notes`, `terms`, `shipping_address`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'ORD0005', NULL, '3', 'processing', NULL, 'partial', '120000.00', 'fixed', '0.00', '0.00', '21600.00', '0.00', '141600.00', '50000.00', NULL, '1. Delivery subject to stock availability.\n2. Payment: 100% advance.', NULL, '2', '2026-05-10 02:04:09', '2026-05-10 12:03:32');


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

INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('1', '1', '5', '135700.00', '2026-04-28', 'bank_transfer', 'NEFT2026042801', NULL, '2', '2026-05-10 02:04:09');
INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('2', '2', '1', '75000.00', '2026-04-20', 'cheque', 'CHQ001234', NULL, '2', '2026-05-10 02:04:09');
INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('3', '4', '9', '68440.00', '2026-04-10', 'upi', 'UPI2026041001', NULL, NULL, '2026-05-10 02:04:09');
INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_by`, `created_at`) VALUES ('4', '5', '3', '50000.00', '2026-05-01', 'bank_transfer', 'NEFT2026050101', NULL, '2', '2026-05-10 02:04:09');


DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('6', 'Partition Systems', '', '2026-05-12 22:01:19');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('7', 'Shower Enclosures', '', '2026-05-12 22:01:29');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('8', 'Flush Doors', '', '2026-05-12 22:01:37');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('9', 'Cabinets & Storage', '', '2026-05-12 22:01:46');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('10', 'LED Mirrors', '', '2026-05-12 22:01:53');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('11', 'Luminated Walls', '', '2026-05-12 22:02:01');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('12', 'Glass Surface', '', '2026-05-12 22:02:07');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('13', 'Wooden Products', '', '2026-05-12 22:02:14');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('14', 'Trunkey Projects', '', '2026-05-12 22:02:22');
INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`) VALUES ('15', 'Marble & Tiles', '', '2026-05-12 22:02:29');


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('product','service') DEFAULT 'product',
  `unit` varchar(30) DEFAULT 'Nos',
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

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `type`, `unit`, `purchase_price`, `selling_price`, `tax_rate`, `stock_qty`, `min_stock`, `image`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES ('13', '10', 'LEDMR01', 'LED Mirror', 'LED Mirror 4k with White and Golden Light with Clear glass with design', 'product', 'Pcs', '15000.00', '25000.00', '18.00', '1.00', '2.00', NULL, '1', '7', '2026-05-12 21:48:21', '2026-05-12 22:09:14');


DROP TABLE IF EXISTS `project_boq`;
CREATE TABLE `project_boq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `item_description` text NOT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `estimated_qty` decimal(10,2) DEFAULT 0.00,
  `estimated_rate` decimal(12,2) DEFAULT 0.00,
  `estimated_amount` decimal(15,2) DEFAULT 0.00,
  `actual_qty` decimal(10,2) DEFAULT 0.00,
  `actual_rate` decimal(12,2) DEFAULT 0.00,
  `actual_amount` decimal(15,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_boq_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_boq` (`id`, `project_id`, `item_description`, `unit`, `estimated_qty`, `estimated_rate`, `estimated_amount`, `actual_qty`, `actual_rate`, `actual_amount`, `notes`, `created_at`, `updated_at`) VALUES ('1', '1', 'Cement Bags', 'Bags', '1000.00', '400.00', '400000.00', '0.00', '0.00', '0.00', NULL, '2026-05-10 12:48:17', '2026-05-10 12:48:17');


DROP TABLE IF EXISTS `project_images`;
CREATE TABLE `project_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `project_images_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_images` (`id`, `project_id`, `file_name`, `file_path`, `description`, `uploaded_by`, `created_at`) VALUES ('1', '1', 'Location.jpg', 'uploads/projects/proj_1_1778399200_1994.jpg', 'Initial foundation and crane setup', '2', '2026-05-10 13:16:40');
INSERT INTO `project_images` (`id`, `project_id`, `file_name`, `file_path`, `description`, `uploaded_by`, `created_at`) VALUES ('2', '1', 'logo.jpg', 'uploads/projects/proj_1_1778399213_3868.jpg', '', '2', '2026-05-10 13:16:53');
INSERT INTO `project_images` (`id`, `project_id`, `file_name`, `file_path`, `description`, `uploaded_by`, `created_at`) VALUES ('3', '1', 'WhatsApp Image 2026-04-24 at 11.49.07 PM.jpeg', 'uploads/projects/proj_1_1778399236_3829.jpeg', '', '2', '2026-05-10 13:17:16');


DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_number` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `project_type` varchar(50) DEFAULT NULL,
  `project_category` varchar(50) DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `stage` enum('planning','design','execution','finishing','handover','completed') DEFAULT 'planning',
  `description` text DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `status` enum('planning','in_progress','on_hold','completed','cancelled') DEFAULT 'planning',
  `start_date` date DEFAULT NULL,
  `target_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `expected_duration` varchar(100) DEFAULT NULL,
  `completion_percentage` int(3) DEFAULT 0,
  `budget` decimal(15,2) DEFAULT 0.00,
  `project_cost` decimal(15,2) DEFAULT 0.00,
  `manager_id` int(10) unsigned DEFAULT NULL,
  `site_address` text DEFAULT NULL,
  `site_city` varchar(100) DEFAULT NULL,
  `site_state` varchar(100) DEFAULT NULL,
  `site_pincode` varchar(20) DEFAULT NULL,
  `google_maps_location` text DEFAULT NULL,
  `site_contact_person` varchar(100) DEFAULT NULL,
  `site_contact_number` varchar(50) DEFAULT NULL,
  `site_engineer_name_number` varchar(150) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_number` (`project_number`),
  KEY `customer_id` (`customer_id`),
  KEY `manager_id` (`manager_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `projects` (`id`, `project_number`, `name`, `project_type`, `project_category`, `priority`, `stage`, `description`, `customer_id`, `status`, `start_date`, `target_end_date`, `actual_end_date`, `expected_duration`, `completion_percentage`, `budget`, `project_cost`, `manager_id`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'PRJ-2026-001', 'Skyline Tower Construction', NULL, NULL, 'medium', 'planning', NULL, NULL, 'in_progress', NULL, NULL, NULL, NULL, '0', '5000000.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-10 12:48:17', '2026-05-10 12:48:17');
INSERT INTO `projects` (`id`, `project_number`, `name`, `project_type`, `project_category`, `priority`, `stage`, `description`, `customer_id`, `status`, `start_date`, `target_end_date`, `actual_end_date`, `expected_duration`, `completion_percentage`, `budget`, `project_cost`, `manager_id`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'Madhav Park', 'Madhav Park Industries', 'Commercial', 'Turnkey', 'high', 'design', '', '2', 'in_progress', '2026-05-10', NULL, NULL, NULL, '0', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2026-05-10 14:03:45', '2026-05-10 14:03:45');
INSERT INTO `projects` (`id`, `project_number`, `name`, `project_type`, `project_category`, `priority`, `stage`, `description`, `customer_id`, `status`, `start_date`, `target_end_date`, `actual_end_date`, `expected_duration`, `completion_percentage`, `budget`, `project_cost`, `manager_id`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'PRJ-202605-636', 'Madhav Baug', 'Residential', 'Interior', 'medium', 'execution', '45 house scheme', '2', 'in_progress', '2026-05-10', '2026-05-15', NULL, '6 months', '20', '0.00', '0.00', NULL, 'Sangath Scheme', 'Ahmedabad', 'Gujarat', '382424', NULL, NULL, NULL, NULL, NULL, '2', '2026-05-10 18:27:46', '2026-05-10 18:27:46');
INSERT INTO `projects` (`id`, `project_number`, `name`, `project_type`, `project_category`, `priority`, `stage`, `description`, `customer_id`, `status`, `start_date`, `target_end_date`, `actual_end_date`, `expected_duration`, `completion_percentage`, `budget`, `project_cost`, `manager_id`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'PRJ-202605-519', 'Pragati Nagar', 'Residential', 'Interior', 'high', 'planning', '', '2', 'planning', '2026-05-10', NULL, NULL, NULL, '0', '0.00', '0.00', NULL, 'A-102\r\nSwagat Status 2', 'Ahmedabad', 'Gujarat', '382424', 'https://www.google.com/maps/dir/23.1204452,72.6056925/Sushobha,+1st+Floor,+Shayona+Silver+Estate,+12A,+near+Silver+Oak+University,+Gota,+Ahmedabad,+Gujarat+382481/@23.1237013,72.6032893,15z/data=!4m8!4m7!1m0!1m5!1m1!1s0x395e8329b768ad73:0x47c8a08d37c1ed7!2m2!1d72.5343935!2d23.0866934?entry=ttu&g_ep=EgoyMDI2MDUwNi4wIKXMDSoASAFQAw%3D%3D', NULL, NULL, NULL, NULL, '2', '2026-05-10 18:47:44', '2026-05-10 18:53:51');


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prospect_followups` (`id`, `prospect_id`, `user_id`, `type`, `notes`, `next_follow_up`, `created_at`) VALUES ('1', '1', '7', 'meeting', 'Cusomer will final on next day', '2026-05-12', '2026-05-11 09:53:15');
INSERT INTO `prospect_followups` (`id`, `prospect_id`, `user_id`, `type`, `notes`, `next_follow_up`, `created_at`) VALUES ('2', '5', '7', 'meeting', 'Customer intersted', '2026-05-12', '2026-05-11 09:54:48');
INSERT INTO `prospect_followups` (`id`, `prospect_id`, `user_id`, `type`, `notes`, `next_follow_up`, `created_at`) VALUES ('3', '8', '7', 'email', 'Email product portfolio and catalogues', '2026-05-14', '2026-05-11 21:41:44');


DROP TABLE IF EXISTS `prospect_images`;
CREATE TABLE `prospect_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` int(10) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `prospect_id` (`prospect_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `prospect_images_ibfk_1` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prospect_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `prospect_images` (`id`, `prospect_id`, `file_name`, `file_path`, `description`, `uploaded_by`, `created_at`) VALUES ('1', '1', 'WhatsApp Image 2026-04-24 at 11.49.07 PM.jpeg', 'uploads/prospects/prosp_1_1778514924_6403.jpeg', '', '7', '2026-05-11 21:25:24');
INSERT INTO `prospect_images` (`id`, `prospect_id`, `file_name`, `file_path`, `description`, `uploaded_by`, `created_at`) VALUES ('2', '8', 'Location.jpg', 'uploads/prospects/prosp_8_1778515811_3618.jpg', 'Current Scenario. Only foundation done so far', '7', '2026-05-11 21:40:11');


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
  `site_address` text DEFAULT NULL,
  `site_city` varchar(100) DEFAULT NULL,
  `site_state` varchar(100) DEFAULT NULL,
  `site_pincode` varchar(20) DEFAULT NULL,
  `google_maps_location` text DEFAULT NULL,
  `site_contact_person` varchar(100) DEFAULT NULL,
  `site_contact_number` varchar(50) DEFAULT NULL,
  `site_engineer_name_number` varchar(150) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `gps_accuracy` float DEFAULT NULL,
  `gps_captured_at` timestamp NULL DEFAULT NULL,
  `gps_address` text DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'Rohit Bansal', 'StartUp Hub', 'rohit@startuphub.in', '9876123450', 'referral', 'new', 'high', '50000.00', '2026-05-12', '8', 'Interested in ERP implementation', 'Sushobha, 1st Floor, Shayona Silver Estate, 12A, near Silver Oak University, Gota, Ahmedabad, Gujarat 382481', 'Ahmedabad', 'Gujarat', '382424', 'https://www.google.com/maps?q=23.11200581,72.59416794', NULL, NULL, NULL, '23.11200581', '72.59416794', '92', '2026-05-11 15:54:16', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', '', NULL, NULL, '2', '2026-05-10 02:04:09', '2026-05-11 21:24:56');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'Ananya Gupta', 'Green Energy Co', 'ananya@greenenergy.com', '8766123450', 'website', 'contacted', 'medium', '25000.00', '2026-05-12', NULL, 'Demo scheduled for next week', 'SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', NULL, 'Gujarat', '382424', 'https://www.google.com/maps?q=23.11200001,72.59413976', NULL, NULL, NULL, '23.11200001', '72.59413976', '87', '2026-05-11 05:12:11', 'SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', '', NULL, NULL, '2', '2026-05-10 02:04:09', '2026-05-11 10:43:21');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'Karan Malhotra', 'Prime Logistics', 'karan@primelogistics.in', '7656123450', 'cold_call', 'qualified', 'high', '80000.00', '2026-05-20', NULL, 'Wants AMC contract + hardware', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'Sonal Verma', 'BlueSky Technologies', 'sonal@bluesky.tech', '9546123450', 'social_media', 'proposal', 'medium', '35000.00', '2026-05-18', NULL, 'Sent proposal, waiting response', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'Manish Tiwari', 'RedStar Retail', 'manish@redstar.com', '8436123450', 'exhibition', 'proposal', 'high', '120000.00', '2026-05-12', '8', 'Price negotiation in progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '2', '2026-05-10 02:04:09', '2026-05-11 09:56:27');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('6', 'Lakshmi Devi', 'Heritage Hotels', 'lakshmi@heritage.in', '7326123450', 'referral', 'won', 'medium', '45000.00', NULL, NULL, 'Converted to customer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('7', 'Nitin Kapoor', 'Metro Builders', 'nitin@metro.in', '9216123450', 'cold_call', 'lost', 'low', '30000.00', NULL, NULL, 'Budget not approved this year', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `prospects` (`id`, `name`, `company`, `email`, `phone`, `source`, `status`, `priority`, `expected_value`, `follow_up_date`, `assigned_to`, `notes`, `site_address`, `site_city`, `site_state`, `site_pincode`, `google_maps_location`, `site_contact_person`, `site_contact_number`, `site_engineer_name_number`, `latitude`, `longitude`, `gps_accuracy`, `gps_captured_at`, `gps_address`, `lost_reason`, `converted_customer_id`, `converted_at`, `created_by`, `created_at`, `updated_at`) VALUES ('8', 'Ravindra Gajjar', '8th Dimension', 'Ravi.Gaj@gmail.com', '9898098980', 'referral', 'contacted', 'high', '0.00', '2026-05-14', '6', '', 'Karaka Building, Navrangpura', 'Ahmedabad', 'Gujarat', '380009', 'https://www.google.com/maps?q=23.11200863,72.59416894', NULL, NULL, NULL, '23.11200863', '72.59416894', '92', '2026-05-11 16:04:45', 'Doom Blossom Road, SabarmatiTaluka, Ahmedabad, Gujarat, 382424, India', NULL, NULL, NULL, '7', '2026-05-11 21:39:07', '2026-05-11 21:41:44');


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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('1', '1', NULL, 'Dell Laptop i5', '5.00', 'Nos', '45000.00', '18.00', '40500.00', '265500.00');
INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('2', '2', NULL, 'Keyboard & Mouse Combo', '20.00', 'Nos', '1200.00', '18.00', '4320.00', '28320.00');
INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('3', '3', NULL, 'HP LaserJet Printer', '5.00', 'Nos', '15000.00', '18.00', '13500.00', '88500.00');
INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `line_total`) VALUES ('4', '4', NULL, 'Annual Maintenance Contract', '51.00', 'Year', '120.00', '999.99', '72705.60', '6120.00');


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
  `terms` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_number` (`purchase_number`),
  KEY `vendor_id` (`vendor_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'PUR0001', '1', 'received', 'paid', '2026-04-05', '225000.00', '40500.00', '265500.00', '265500.00', NULL, '1. Please supply items as per specifications.\n2. Payment will be processed within 30 days of delivery.', '2', '2026-05-10 02:04:09', '2026-05-10 12:20:39');
INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'PUR0002', '3', 'received', 'paid', '2026-04-12', '24000.00', '4320.00', '28320.00', '28320.00', NULL, '1. Please supply items as per specifications.\n2. Payment will be processed within 30 days of delivery.', '2', '2026-05-10 02:04:09', '2026-05-10 12:20:39');
INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'PUR0003', '2', 'pending', 'unpaid', '2026-05-01', '75000.00', '13500.00', '88500.00', '0.00', NULL, '1. Please supply items as per specifications.\n2. Payment will be processed within 30 days of delivery.', '2', '2026-05-10 02:04:09', '2026-05-10 12:20:39');
INSERT INTO `purchases` (`id`, `purchase_number`, `vendor_id`, `status`, `payment_status`, `purchase_date`, `subtotal`, `tax_amount`, `total`, `paid_amount`, `notes`, `terms`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'PO-20260510-997', '1', 'received', 'unpaid', '2026-05-10', '6120.00', '72705.60', '78825.60', '1000.00', '', '1. Please supply items as per specifications.\n2. Payment will be processed within 30 days of delivery.', '2', '2026-05-10 12:16:40', '2026-05-10 12:20:39');


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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `qty`, `unit`, `unit_price`, `tax_rate`, `tax_amount`, `discount`, `line_total`, `sort_order`) VALUES ('17', '7', '13', 'LED Mirror', '5.00', 'Pcs', '25000.00', '18.00', '22500.00', '0.00', '125000.00', '0');


DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_number` varchar(30) NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `status` enum('draft','sent','accepted','rejected','converted','expired') DEFAULT 'draft',
  `valid_until` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount_type` enum('fixed','percent') DEFAULT 'fixed',
  `discount_value` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `quote_number` (`quote_number`),
  KEY `customer_id` (`customer_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_quote_status` (`status`),
  CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quotations` (`id`, `quote_number`, `customer_id`, `status`, `valid_until`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax_amount`, `total`, `notes`, `terms`, `created_by`, `sent_at`, `created_at`, `updated_at`) VALUES ('7', 'QT0001', '1', 'draft', '2026-06-11', '125000.00', 'fixed', '0.00', '0.00', '22500.00', '147500.00', 'LED Mirror with 4k white and Gold colour', '1. Quotation valid for 30 days.\r\n2. Payment: 100% advance.', '7', NULL, '2026-05-12 22:10:39', '2026-05-12 22:10:39');


DROP TABLE IF EXISTS `receipt_categories`;
CREATE TABLE `receipt_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `receipt_categories` (`id`, `name`, `is_active`, `created_at`) VALUES ('1', 'Sales Receipt', '1', '2026-05-10 20:20:54');
INSERT INTO `receipt_categories` (`id`, `name`, `is_active`, `created_at`) VALUES ('2', 'Advance Payment', '1', '2026-05-10 20:20:54');
INSERT INTO `receipt_categories` (`id`, `name`, `is_active`, `created_at`) VALUES ('3', 'Service Income', '1', '2026-05-10 20:20:54');
INSERT INTO `receipt_categories` (`id`, `name`, `is_active`, `created_at`) VALUES ('4', 'Consultancy Fee', '1', '2026-05-10 20:20:54');
INSERT INTO `receipt_categories` (`id`, `name`, `is_active`, `created_at`) VALUES ('5', 'Other Income', '1', '2026-05-10 20:20:54');


DROP TABLE IF EXISTS `receipts`;
CREATE TABLE `receipts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `receipt_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer','card','cheque','upi','other') DEFAULT 'cash',
  `reference` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `receipts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `receipt_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



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

INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('1', 'Super Admin', 'super_admin', '{}', '2026-05-10 02:04:09');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('2', 'Admin', 'admin', '{}', '2026-05-10 02:04:09');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('3', 'Manager', 'manager', '{\"customers\":[\"view\",\"create\",\"edit\"],\"prospects\":[\"view\",\"create\",\"edit\",\"delete\"],\"quotations\":[\"view\",\"create\",\"edit\"],\"orders\":[\"view\",\"create\"],\"products\":[\"view\",\"create\"]}', '2026-05-10 02:04:09');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('4', 'Accountant', 'accountant', '{\"accounts\":[\"view\",\"create\",\"edit\"],\"invoices\":[\"view\",\"create\",\"edit\"],\"expenses\":[\"view\",\"create\",\"edit\"],\"reports\":[\"view\"]}', '2026-05-10 02:04:09');
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`) VALUES ('5', 'User', 'user', '{\"customers\":[\"view\"],\"quotations\":[\"view\"],\"orders\":[\"view\"]}', '2026-05-10 02:04:09');


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

INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('1', '1', '4', '2026', '75000.00', '5000.00', '8000.00', '72000.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-10 02:04:09');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('2', '2', '4', '2026', '55000.00', '3000.00', '5500.00', '52500.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-10 02:04:09');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('3', '3', '4', '2026', '50000.00', '2500.00', '5000.00', '47500.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-10 02:04:09');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('4', '4', '4', '2026', '45000.00', '2000.00', '4500.00', '42500.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-10 02:04:09');
INSERT INTO `salary_records` (`id`, `employee_id`, `month`, `year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `payment_date`, `payment_method`, `status`, `notes`, `created_at`) VALUES ('5', '5', '4', '2026', '35000.00', '1500.00', '3500.00', '33000.00', '2026-05-01', 'bank_transfer', 'paid', NULL, '2026-05-10 02:04:09');


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('1', 'company_name', 'Sushobha Business Solutions', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('2', 'company_email', 'info@sushobha.com', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('3', 'company_phone', '+91 98765 43210', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('4', 'company_address', '123 Business Park, Bengaluru, Karnataka - 560001', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('5', 'company_gst', '29ABCDE1234F1Z5', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('6', 'company_website', 'https://www.sushobha.com', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('7', 'currency_symbol', 'Ôé╣', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('8', 'tax_name', 'GST', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('9', 'invoice_prefix', 'INV', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('10', 'order_prefix', 'ORD', '2026-05-10 02:04:09');
INSERT INTO `settings` (`id`, `key`, `value`, `updated_at`) VALUES ('11', 'quote_prefix', 'QT', '2026-05-10 02:04:09');


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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('1', 'Follow up with Karan Malhotra', 'Negotiate AMC contract terms', NULL, '2', NULL, NULL, 'high', 'pending', '2026-05-20', NULL, '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('2', 'Prepare Q2 Sales Report', 'Compile all sales data for Q2 2026', NULL, '2', NULL, NULL, 'medium', 'completed', '2026-05-15', '2026-05-10 14:01:11', '2026-05-10 02:04:09', '2026-05-10 14:01:11');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('3', 'Server Maintenance', 'Schedule downtime for server updates', NULL, '2', NULL, NULL, 'medium', 'in_progress', '2026-05-12', NULL, '2026-05-10 02:04:09', '2026-05-11 21:50:20');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('4', 'Send Invoice Reminder', 'Follow up on unpaid INV0002 and INV0003', '8', '2', 'customers', NULL, 'high', 'completed', '2026-05-11', '2026-05-11 21:50:25', '2026-05-10 02:04:09', '2026-05-11 21:50:25');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('5', 'Product Demo for Ananya Gupta', 'Demo ERP for Green Energy Co', NULL, '2', NULL, NULL, 'high', 'pending', '2026-05-12', NULL, '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('6', 'Finalize Architecture Plans', NULL, NULL, NULL, NULL, NULL, 'high', 'in_progress', NULL, NULL, '2026-05-10 12:48:17', '2026-05-10 12:48:17');
INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `related_module`, `related_id`, `priority`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES ('7', 'Order Cement', '', NULL, NULL, NULL, NULL, 'medium', 'pending', NULL, NULL, '2026-05-10 12:48:17', '2026-05-10 12:52:14');


DROP TABLE IF EXISTS `travel_documents`;
CREATE TABLE `travel_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `travel_id` int(10) unsigned NOT NULL,
  `document_type` enum('Travel Tickets','Hotel Bills','Food Bills','Fuel Receipts','Client Documents','MOM','Photos','Visiting Card','Expense Bills','Signed Documents','Other') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `travel_id` (`travel_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `travel_documents_ibfk_1` FOREIGN KEY (`travel_id`) REFERENCES `travels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_documents_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `travels`;
CREATE TABLE `travels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `travel_number` varchar(50) NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `date_of_request` datetime DEFAULT current_timestamp(),
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `number_of_days` int(11) DEFAULT NULL,
  `travel_type` enum('Local','Domestic','International') DEFAULT NULL,
  `travel_priority` enum('Low','Medium','High') DEFAULT NULL,
  `purpose_category` enum('Client Meeting','Site Visit','Vendor','Exhibition','Other') DEFAULT NULL,
  `location_city` varchar(100) DEFAULT NULL,
  `location_state` varchar(100) DEFAULT NULL,
  `location_country` varchar(100) DEFAULT NULL,
  `multiple_locations` text DEFAULT NULL,
  `mode_of_travel` enum('Flight','Train','Car','Bus','Other') DEFAULT NULL,
  `travel_status` enum('Planned','Ongoing','Completed','Cancelled') DEFAULT 'Planned',
  `meeting_agenda` text DEFAULT NULL,
  `meeting_with_type` enum('Customer','Lead','Vendor','Internal') DEFAULT NULL,
  `meeting_datetime` datetime DEFAULT NULL,
  `meeting_venue` text DEFAULT NULL,
  `meeting_purpose` text DEFAULT NULL,
  `followup_datetime` datetime DEFAULT NULL,
  `meeting_outcome` enum('Successful','Pending','Rejected') DEFAULT NULL,
  `customer_interest_level` enum('Hot','Warm','Cold') DEFAULT NULL,
  `discussion_summary` text DEFAULT NULL,
  `client_requirement` text DEFAULT NULL,
  `quotation_required` enum('Yes','No') DEFAULT 'No',
  `expected_business_value` decimal(15,2) DEFAULT 0.00,
  `expected_closure_date` date DEFAULT NULL,
  `follow_up_needed` enum('Yes','No') DEFAULT 'No',
  `follow_up_date` date DEFAULT NULL,
  `follow_up_assigned_to` int(10) unsigned DEFAULT NULL,
  `next_action_plan` text DEFAULT NULL,
  `customer_feedback` text DEFAULT NULL,
  `deal_status` enum('Open','Negotiation','Won','Lost') DEFAULT NULL,
  `expense_booking_required` enum('Yes','No') DEFAULT 'No',
  `expense_category` enum('Travel','Food','Hotel','Fuel','Client Entertainment','Other') DEFAULT NULL,
  `estimated_budget` decimal(15,2) DEFAULT 0.00,
  `actual_expense_amount` decimal(15,2) DEFAULT 0.00,
  `advance_taken` decimal(15,2) DEFAULT 0.00,
  `payment_done_by` enum('Employee','Company') DEFAULT NULL,
  `reimbursement_required` enum('Yes','No') DEFAULT 'No',
  `expense_date` date DEFAULT NULL,
  `expense_vendor_name` varchar(255) DEFAULT NULL,
  `payment_method` enum('Cash','UPI','Card','Bank') DEFAULT NULL,
  `gst_applicable` enum('Yes','No') DEFAULT 'No',
  `gst_number` varchar(50) DEFAULT NULL,
  `expense_notes` text DEFAULT NULL,
  `expense_approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `overall_approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `follow_up_assigned_to` (`follow_up_assigned_to`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `travels_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travels_ibfk_2` FOREIGN KEY (`follow_up_assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `travels_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



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
  `access_rights` varchar(255) DEFAULT '["Read"]',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('2', '2', 'Ravi Kumar', 'admin@sushobha.com', '$2y$10$9YfV2y/hCRXu/l59LbN5teaVruiTX4tysooCM1Kw81ehtXlxMYw86', '+91 98765 43210', NULL, '1', NULL, NULL, NULL, '2026-05-10 22:44:36', '0', '2026-05-10 22:42:04', '2026-05-10 02:04:09', '2026-05-10 22:45:43', 'Read');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('6', '2', 'Parvez Hashmi', 'parvez@sushobha.com', '$2y$10$aJUePzaABRBBKKrZX/MlbelyGwmni.oIxQIj5ZLxO.SFkMafHHd8S', NULL, NULL, '1', NULL, NULL, NULL, '2026-05-13 00:28:46', '0', NULL, '2026-05-10 22:54:56', '2026-05-13 00:28:46', '[\"Read\",\"Write\",\"Modify\",\"Delete\",\"View\",\"Approve\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('7', '2', 'Ravikaant', 'ravikaant@sushobha.com', '$2y$10$n5Y2kNQFAh6xmfR.KslsJu0ohgV4P0YvVo4I0xy9PooHQbvSZy2Q2', NULL, NULL, '1', NULL, NULL, NULL, '2026-05-13 11:28:52', '0', NULL, '2026-05-10 22:55:58', '2026-05-13 11:28:52', '[\"Read\",\"Write\",\"Modify\",\"Delete\",\"View\",\"Approve\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('8', '3', 'Girish Kumar', 'girish@sushobha.com', '$2y$10$jIXN6E.biAvLjqswqvjd6.nwhm33iIuLpUOG8/DiNV0rx03Z5Rcbm', NULL, NULL, '1', NULL, NULL, NULL, '2026-05-10 23:00:02', '0', NULL, '2026-05-10 22:57:29', '2026-05-10 23:00:02', '[\"Read\",\"Write\",\"View\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('9', '3', 'Ranjit Srivastav', 'ranjit@sushobha.com', '$2y$10$Slwp.ltkuO7SbjfGz0UR2etfO65tLy0JCHcyFpPBI5zbQxQlMimvm', NULL, NULL, '1', NULL, NULL, NULL, '2026-05-11 21:53:16', '0', NULL, '2026-05-10 22:58:11', '2026-05-11 21:53:16', '[\"Read\",\"Write\",\"View\"]');
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `reset_token`, `reset_token_expires`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `access_rights`) VALUES ('10', '5', 'Neeraj Sharmar', 'neeraj@sushobha.com', '$2y$10$ICk1KWapVKRvnaut/DAJm./gjaDpF4wwFmlDM8Q00SDZjTzFZxQTm', NULL, NULL, '1', NULL, NULL, NULL, NULL, '0', NULL, '2026-05-10 22:58:44', '2026-05-10 22:58:44', '[\"Read\",\"Write\",\"View\"]');


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

INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'VEN0001', 'Amit Jain', 'Dell India Pvt Ltd', 'amit.jain@dell.com', '9111234567', '07AAACL1234H1ZD', NULL, NULL, 'Delhi', 'Delhi', NULL, NULL, NULL, NULL, '77825.60', NULL, 'active', '2', '2026-05-10 02:04:09', '2026-05-10 12:16:40');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'VEN0002', 'Pradeep Nair', 'HP India', 'pradeep@hp.com', '9222345678', '29AABCH1234A1ZN', NULL, NULL, 'Bengaluru', 'Karnataka', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'VEN0003', 'Ritu Agarwal', 'Logitech India', 'ritu@logitech.com', '9333456789', '27AAACL1234J1ZR', NULL, NULL, 'Mumbai', 'Maharashtra', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'VEN0004', 'Vijay Sharma', 'Netgear India', 'vijay@netgear.com', '9444567890', '24AABCN1234K1ZV', NULL, NULL, 'Ahmedabad', 'Gujarat', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');
INSERT INTO `vendors` (`id`, `vendor_code`, `name`, `company`, `email`, `phone`, `gst_number`, `pan_number`, `address`, `city`, `state`, `pincode`, `bank_name`, `bank_account`, `bank_ifsc`, `outstanding_balance`, `notes`, `status`, `created_by`, `created_at`, `updated_at`) VALUES ('5', 'VEN0005', 'Sanjay Mehta', 'Samsung India Electronics', 'sanjay@samsung.com', '9555678901', '07AAACS1234L1ZS', NULL, NULL, 'Delhi', 'Delhi', NULL, NULL, NULL, NULL, '0.00', NULL, 'active', '2', '2026-05-10 02:04:09', '2026-05-10 02:04:09');


