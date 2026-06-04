-- ============================================================
-- SushobhaCRM - Seed / Demo Data
-- Run AFTER schema.sql
-- ============================================================
USE `sushobha_crm`;

-- ── Roles ──────────────────────────────────────────────────────
INSERT INTO `roles` (`id`,`name`,`slug`,`permissions`) VALUES
(1,'Super Admin','super_admin','{}'),
(2,'Admin','admin','{}'),
(3,'Manager','manager','{"customers":["view","create","edit"],"prospects":["view","create","edit","delete"],"quotations":["view","create","edit"],"orders":["view","create"],"products":["view","create"]}'),
(4,'Accountant','accountant','{"accounts":["view","create","edit"],"invoices":["view","create","edit"],"expenses":["view","create","edit"],"reports":["view"]}'),
(5,'User','user','{"customers":["view"],"quotations":["view"],"orders":["view"]}');

-- ── Users (passwords = Admin@123) ─────────────────────────────
INSERT INTO `users` (`id`,`role_id`,`name`,`email`,`password`,`phone`,`is_active`) VALUES
(1,1,'Super Admin','superadmin@sushobha.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','+91 99999 00001',1),
(2,2,'Ravi Kumar','admin@sushobha.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','+91 98765 43210',1),
(3,3,'Priya Sharma','manager@sushobha.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','+91 91234 56789',1),
(4,4,'Anita Nair','accounts@sushobha.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','+91 80123 45678',1),
(5,5,'Suresh Reddy','user@sushobha.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','+91 70987 65432',1);

-- ── Settings ───────────────────────────────────────────────────
INSERT INTO `settings` (`key`,`value`) VALUES
('company_name','Sushobha Business Solutions'),
('company_email','info@sushobha.com'),
('company_phone','+91 98765 43210'),
('company_address','123 Business Park, Bengaluru, Karnataka - 560001'),
('company_gst','29ABCDE1234F1Z5'),
('company_website','https://www.sushobha.com'),
('currency_symbol','₹'),
('tax_name','GST'),
('invoice_prefix','INV'),
('order_prefix','ORD'),
('quote_prefix','QT');

-- ── Product Categories ─────────────────────────────────────────
INSERT INTO `product_categories` (`id`,`name`) VALUES
(1,'Software & IT'),
(2,'Hardware'),
(3,'Consulting Services'),
(4,'Annual Maintenance'),
(5,'Digital Marketing');

-- ── Products ───────────────────────────────────────────────────
INSERT INTO `products` (`id`,`category_id`,`sku`,`name`,`description`,`type`,`unit`,`purchase_price`,`selling_price`,`tax_rate`,`stock_qty`,`is_active`) VALUES
(1,1,'SW-001','CRM Software License','Annual CRM license for 5 users','service','License',8000,15000,18,0,1),
(2,1,'SW-002','ERP Implementation','Complete ERP setup and training','service','Project',50000,120000,18,0,1),
(3,2,'HW-001','Dell Laptop i5','Dell Inspiron 15, 8GB RAM, 512GB SSD','product','Nos',45000,58000,18,12,1),
(4,2,'HW-002','HP LaserJet Printer','HP LaserJet Pro M404dn','product','Nos',15000,20000,18,5,1),
(5,2,'HW-003','Netgear WiFi Router','Netgear AC1750 Dual Band Router','product','Nos',3500,5500,18,20,1),
(6,3,'CS-001','IT Consulting - Hourly','IT infrastructure consulting per hour','service','Hour',0,2500,18,0,1),
(7,3,'CS-002','Website Development','Complete business website','service','Project',0,35000,18,0,1),
(8,4,'AMC-001','Annual Maintenance Contract','Hardware and software AMC per year','service','Year',0,18000,18,0,1),
(9,5,'DM-001','SEO Package - Monthly','Search engine optimization per month','service','Month',0,12000,18,0,1),
(10,5,'DM-002','Social Media Management','3 platforms, 20 posts/month','service','Month',0,8000,18,0,1),
(11,2,'HW-004','Samsung Monitor 24"','Full HD IPS Display 24 inch','product','Nos',8000,12000,18,8,1),
(12,2,'HW-005','Keyboard & Mouse Combo','Logitech wireless combo','product','Nos',1200,2000,18,30,1);

-- ── Expense Categories ─────────────────────────────────────────
INSERT INTO `expense_categories` (`id`,`name`) VALUES
(1,'Office Rent'),
(2,'Salaries'),
(3,'Utilities'),
(4,'Travel & Conveyance'),
(5,'Marketing & Advertising'),
(6,'Software Subscriptions'),
(7,'Office Supplies'),
(8,'Miscellaneous');

-- ── Customers ──────────────────────────────────────────────────
INSERT INTO `customers` (`id`,`customer_code`,`name`,`company`,`email`,`phone`,`gst_number`,`address`,`city`,`state`,`pincode`,`status`,`created_by`) VALUES
(1,'CUST0001','Arjun Mehta','TechVision Pvt Ltd','arjun@techvision.in','9876543210','27AABCT1332L1ZY','101 Tech Park, Andheri East','Mumbai','Maharashtra','400069','active',2),
(2,'CUST0002','Sneha Patel','Global Traders','sneha@globaltraders.com','9876500001','24AAACP1234F1ZE','34 Commerce St, Navrangpura','Ahmedabad','Gujarat','380009','active',2),
(3,'CUST0003','Vikram Singh','Horizon Industries','vikram@horizon.co.in','9988776655','07AAACH1234A1ZQ','45 Industrial Area, Phase II','Delhi','Delhi','110020','active',3),
(4,'CUST0004','Deepika Rao','SunRise Exports','deepika@sunrise.com','8877665544','29AABCS1234A1ZP','78 Export Zone, Whitefield','Bengaluru','Karnataka','560066','active',3),
(5,'CUST0005','Rahul Joshi','PinPoint Solutions','rahul@pinpoint.in','7766554433','06AABCP1234F1ZA','12 Cyber Hub, Gurgaon','Gurugram','Haryana','122002','active',2),
(6,'CUST0006','Pooja Nair','Coastal Enterprises','pooja@coastal.com','9900112233','32AAACF1234G1ZK','56 Marine Drive','Kochi','Kerala','682001','active',3),
(7,'CUST0007','Suresh Iyer','BrightFuture Edu','suresh@brightfuture.org','8800990011','33AAABF1234H1ZR','90 College Road','Chennai','Tamil Nadu','600006','active',2),
(8,'CUST0008','Kavya Sharma','NexGen Retail','kavya@nexgen.com','7700889900','08AABHM1234J1ZA','23 MG Road','Pune','Maharashtra','411001','active',3),
(9,'CUST0009','Arun Kumar','Delta Constructions','arun@delta.in','9988001122','36AAADM1234K1ZP','55 Ring Road','Hyderabad','Telangana','500032','active',2),
(10,'CUST0010','Meena Krishnan','Apex Pharma','meena@apexpharma.com','8811223344','21AAACG1234L1ZX','88 Pharma Park, MIDC','Pune','Maharashtra','411018','active',3);

-- ── Prospects ──────────────────────────────────────────────────
INSERT INTO `prospects` (`id`,`name`,`company`,`email`,`phone`,`source`,`status`,`priority`,`expected_value`,`follow_up_date`,`assigned_to`,`notes`,`created_by`) VALUES
(1,'Rohit Bansal','StartUp Hub','rohit@startuphub.in','9876123450','referral','new','high',50000,'2026-05-15',3,'Interested in ERP implementation',2),
(2,'Ananya Gupta','Green Energy Co','ananya@greenenergy.com','8766123450','website','contacted','medium',25000,'2026-05-12',3,'Demo scheduled for next week',2),
(3,'Karan Malhotra','Prime Logistics','karan@primelogistics.in','7656123450','cold_call','qualified','high',80000,'2026-05-20',3,'Wants AMC contract + hardware',2),
(4,'Sonal Verma','BlueSky Technologies','sonal@bluesky.tech','9546123450','social_media','proposal','medium',35000,'2026-05-18',5,'Sent proposal, waiting response',3),
(5,'Manish Tiwari','RedStar Retail','manish@redstar.com','8436123450','exhibition','negotiation','high',120000,'2026-05-10',3,'Price negotiation in progress',2),
(6,'Lakshmi Devi','Heritage Hotels','lakshmi@heritage.in','7326123450','referral','won','medium',45000,NULL,3,'Converted to customer',2),
(7,'Nitin Kapoor','Metro Builders','nitin@metro.in','9216123450','cold_call','lost','low',30000,NULL,5,'Budget not approved this year',2);

-- ── Vendors ────────────────────────────────────────────────────
INSERT INTO `vendors` (`id`,`vendor_code`,`name`,`company`,`email`,`phone`,`gst_number`,`city`,`state`,`status`,`created_by`) VALUES
(1,'VEN0001','Amit Jain','Dell India Pvt Ltd','amit.jain@dell.com','9111234567','07AAACL1234H1ZD','Delhi','Delhi','active',2),
(2,'VEN0002','Pradeep Nair','HP India','pradeep@hp.com','9222345678','29AABCH1234A1ZN','Bengaluru','Karnataka','active',2),
(3,'VEN0003','Ritu Agarwal','Logitech India','ritu@logitech.com','9333456789','27AAACL1234J1ZR','Mumbai','Maharashtra','active',2),
(4,'VEN0004','Vijay Sharma','Netgear India','vijay@netgear.com','9444567890','24AABCN1234K1ZV','Ahmedabad','Gujarat','active',2),
(5,'VEN0005','Sanjay Mehta','Samsung India Electronics','sanjay@samsung.com','9555678901','07AAACS1234L1ZS','Delhi','Delhi','active',2);

-- ── Quotations ─────────────────────────────────────────────────
INSERT INTO `quotations` (`id`,`quote_number`,`customer_id`,`status`,`valid_until`,`subtotal`,`discount_type`,`discount_value`,`discount_amount`,`tax_amount`,`total`,`notes`,`created_by`) VALUES
(1,'QT0001',1,'accepted','2026-05-30',135000,'percent',5,6750,23085,151335,'Thank you for your business!',2),
(2,'QT0002',2,'sent','2026-05-25',75000,'fixed',0,0,13500,88500,'Products as discussed',2),
(3,'QT0003',3,'draft','2026-06-01',58000,'fixed',2000,2000,10080,66080,NULL,3),
(4,'QT0004',4,'rejected','2026-04-30',45000,'percent',10,4500,7290,47790,'Price revision requested',3),
(5,'QT0005',5,'converted','2026-04-15',120000,'fixed',5000,5000,20700,135700,'Software + hardware bundle',2);

-- ── Quotation Items ────────────────────────────────────────────
INSERT INTO `quotation_items` (`quotation_id`,`product_id`,`description`,`qty`,`unit`,`unit_price`,`tax_rate`,`tax_amount`,`line_total`) VALUES
(1,2,'ERP Implementation',1,'Project',120000,18,21600,141600),
(1,6,'IT Consulting - 10 Hours',10,'Hour',1350,18,2430,15930),
(2,3,'Dell Laptop i5 - 1 unit',1,'Nos',58000,18,10440,68440),
(2,4,'HP LaserJet Printer',1,'Nos',17000,18,3060,20060),
(3,1,'CRM Software License - 3 users',3,'License',15000,18,8100,53100),
(3,9,'SEO Package - 3 months',3,'Month',12000,18,6480,42480),
(5,2,'ERP Implementation',1,'Project',100000,18,18000,118000),
(5,3,'Dell Laptop i5 x 5',5,'Nos',4000,18,3600,23600);

-- ── Orders ─────────────────────────────────────────────────────
INSERT INTO `orders` (`id`,`order_number`,`quotation_id`,`customer_id`,`status`,`payment_status`,`subtotal`,`tax_amount`,`total`,`paid_amount`,`created_by`) VALUES
(1,'ORD0001',5,5,'delivered','paid',115000,20700,135700,135700,2),
(2,'ORD0002',1,1,'processing','partial',128250,23085,151335,75000,2),
(3,'ORD0003',NULL,7,'pending','unpaid',35000,6300,41300,0,3),
(4,'ORD0004',NULL,9,'delivered','paid',58000,10440,68440,68440,3),
(5,'ORD0005',NULL,3,'processing','partial',120000,21600,141600,50000,2);

-- ── Order Items ────────────────────────────────────────────────
INSERT INTO `order_items` (`order_id`,`product_id`,`description`,`qty`,`unit_price`,`tax_rate`,`tax_amount`,`line_total`) VALUES
(1,2,'ERP Implementation',1,100000,18,18000,118000),
(1,3,'Dell Laptop i5',1,15000,18,2700,17700),
(2,2,'ERP Implementation',1,120000,18,21600,141600),
(2,6,'IT Consulting - 5 hrs',5,2250,18,2025,13275),
(3,7,'Website Development',1,35000,18,6300,41300),
(4,3,'Dell Laptop i5',1,58000,18,10440,68440),
(5,1,'CRM Software License',5,15000,18,13500,88500),
(5,6,'IT Consulting - 20 hrs',20,1650,18,5940,39540);

-- ── Invoices ───────────────────────────────────────────────────
INSERT INTO `invoices` (`id`,`invoice_number`,`order_id`,`customer_id`,`status`,`issued_date`,`due_date`,`subtotal`,`tax_amount`,`total`,`paid_amount`,`balance_due`,`created_by`) VALUES
(1,'INV0001',1,5,'paid','2026-04-01','2026-04-30',115000,20700,135700,135700,0,2),
(2,'INV0002',2,1,'partial','2026-04-10','2026-05-10',128250,23085,151335,75000,76335,2),
(3,'INV0003',3,7,'sent','2026-04-20','2026-05-20',35000,6300,41300,0,41300,3),
(4,'INV0004',4,9,'paid','2026-03-15','2026-04-15',58000,10440,68440,68440,0,3),
(5,'INV0005',5,3,'partial','2026-04-25','2026-05-25',120000,21600,141600,50000,91600,2);

-- ── Payments ───────────────────────────────────────────────────
INSERT INTO `payments` (`invoice_id`,`customer_id`,`amount`,`payment_date`,`method`,`reference`,`created_by`) VALUES
(1,5,135700,'2026-04-28','bank_transfer','NEFT2026042801',2),
(2,1,75000,'2026-04-20','cheque','CHQ001234',2),
(4,9,68440,'2026-04-10','upi','UPI2026041001',3),
(5,3,50000,'2026-05-01','bank_transfer','NEFT2026050101',2);

-- ── Purchases ──────────────────────────────────────────────────
INSERT INTO `purchases` (`id`,`purchase_number`,`vendor_id`,`status`,`payment_status`,`purchase_date`,`subtotal`,`tax_amount`,`total`,`paid_amount`,`created_by`) VALUES
(1,'PUR0001',1,'received','paid','2026-04-05',225000,40500,265500,265500,2),
(2,'PUR0002',3,'received','paid','2026-04-12',24000,4320,28320,28320,2),
(3,'PUR0003',2,'pending','unpaid','2026-05-01',75000,13500,88500,0,2);

-- ── Purchase Items ─────────────────────────────────────────────
INSERT INTO `purchase_items` (`purchase_id`,`product_id`,`description`,`qty`,`unit_price`,`tax_rate`,`tax_amount`,`line_total`) VALUES
(1,3,'Dell Laptop i5',5,45000,18,40500,265500),
(2,12,'Keyboard & Mouse Combo',20,1200,18,4320,28320),
(3,4,'HP LaserJet Printer',5,15000,18,13500,88500);

-- ── Expenses ───────────────────────────────────────────────────
INSERT INTO `expenses` (`category_id`,`title`,`amount`,`expense_date`,`payment_method`,`description`,`created_by`) VALUES
(1,'Office Rent - April 2026',25000,'2026-04-01','bank_transfer','Monthly office rent',2),
(3,'Electricity Bill - April',3500,'2026-04-05','bank_transfer','Electricity charges',2),
(4,'Team Dinner - Client Visit',4200,'2026-04-10','card','Team dinner after client demo',3),
(5,'Google Ads Campaign',15000,'2026-04-01','card','April digital marketing budget',2),
(6,'Adobe Creative Cloud',3540,'2026-04-01','card','Annual subscription / 12',2),
(7,'Printer Ink & Toner',1800,'2026-04-15','cash','Office supplies',3),
(1,'Office Rent - May 2026',25000,'2026-05-01','bank_transfer','Monthly office rent',2),
(3,'Internet Bill - April',1200,'2026-04-01','bank_transfer','Broadband charges',2),
(4,'Fuel Reimbursement',2500,'2026-04-20','cash','Sales team travel',3),
(8,'Miscellaneous Expenses',800,'2026-04-25','cash','Petty cash expenses',2);

-- ── Employees ──────────────────────────────────────────────────
INSERT INTO `employees` (`id`,`user_id`,`emp_code`,`name`,`email`,`phone`,`department`,`designation`,`join_date`,`salary`,`status`) VALUES
(1,2,'EMP0001','Ravi Kumar','ravi@sushobha.com','+91 98765 43210','Management','General Manager','2022-01-01',75000,'active'),
(2,3,'EMP0002','Priya Sharma','priya@sushobha.com','+91 91234 56789','Sales','Sales Manager','2022-06-01',55000,'active'),
(3,4,'EMP0003','Anita Nair','anita@sushobha.com','+91 80123 45678','Finance','Senior Accountant','2023-01-15',50000,'active'),
(4,5,'EMP0004','Suresh Reddy','suresh@sushobha.com','+91 70987 65432','IT','Software Developer','2023-07-01',45000,'active'),
(5,NULL,'EMP0005','Kiran Bhat','kiran@sushobha.com','+91 99887 76655','Sales','Sales Executive','2024-01-01',35000,'active');

-- ── Attendance (last 5 days) ───────────────────────────────────
INSERT INTO `attendance` (`employee_id`,`date`,`status`,`check_in`,`check_out`) VALUES
(1,CURDATE()-INTERVAL 1 DAY,'present','09:02:00','18:10:00'),
(2,CURDATE()-INTERVAL 1 DAY,'present','09:15:00','18:05:00'),
(3,CURDATE()-INTERVAL 1 DAY,'present','09:30:00','18:00:00'),
(4,CURDATE()-INTERVAL 1 DAY,'leave',NULL,NULL),
(5,CURDATE()-INTERVAL 1 DAY,'present','09:05:00','18:20:00'),
(1,CURDATE()-INTERVAL 2 DAY,'present','09:00:00','18:00:00'),
(2,CURDATE()-INTERVAL 2 DAY,'half_day','09:00:00','13:00:00'),
(3,CURDATE()-INTERVAL 2 DAY,'present','09:10:00','18:05:00'),
(4,CURDATE()-INTERVAL 2 DAY,'present','09:00:00','18:00:00'),
(5,CURDATE()-INTERVAL 2 DAY,'absent',NULL,NULL);

-- ── Salary Records ─────────────────────────────────────────────
INSERT INTO `salary_records` (`employee_id`,`month`,`year`,`basic_salary`,`allowances`,`deductions`,`net_salary`,`payment_date`,`payment_method`,`status`) VALUES
(1,4,2026,75000,5000,8000,72000,'2026-05-01','bank_transfer','paid'),
(2,4,2026,55000,3000,5500,52500,'2026-05-01','bank_transfer','paid'),
(3,4,2026,50000,2500,5000,47500,'2026-05-01','bank_transfer','paid'),
(4,4,2026,45000,2000,4500,42500,'2026-05-01','bank_transfer','paid'),
(5,4,2026,35000,1500,3500,33000,'2026-05-01','bank_transfer','paid');

-- ── Notifications ─────────────────────────────────────────────
INSERT INTO `notifications` (`user_id`,`title`,`message`,`type`,`link`,`is_read`) VALUES
(2,'New Lead Assigned','Rohit Bansal from StartUp Hub has been assigned to you','info','/modules/prospects/view.php?id=1',0),
(2,'Invoice Overdue','Invoice INV0002 for TechVision is overdue by 10 days','warning','/modules/invoices/view.php?id=2',0),
(2,'Payment Received','Payment of ₹75,000 received from TechVision Pvt Ltd','success','/modules/payments/',0),
(3,'Quotation Rejected','QT0004 was rejected by SunRise Exports','danger','/modules/quotations/view.php?id=4',0),
(2,'Low Stock Alert','Dell Laptop i5 stock is below minimum level','warning','/modules/products/',0);

-- ── Activity Logs ──────────────────────────────────────────────
INSERT INTO `activity_logs` (`user_id`,`module`,`action`,`description`,`record_id`,`ip_address`) VALUES
(2,'customers','create','Created customer TechVision Pvt Ltd',1,'127.0.0.1'),
(2,'quotations','create','Created quotation QT0001 for TechVision',1,'127.0.0.1'),
(2,'orders','create','Converted quotation to order ORD0001',1,'127.0.0.1'),
(2,'invoices','create','Generated invoice INV0001',1,'127.0.0.1'),
(3,'prospects','create','Added new lead: Rohit Bansal',1,'127.0.0.1'),
(2,'payments','create','Recorded payment from TechVision - ₹1,35,700',1,'127.0.0.1');

-- ── Tasks ─────────────────────────────────────────────────────
INSERT INTO `tasks` (`title`,`description`,`assigned_to`,`created_by`,`priority`,`status`,`due_date`) VALUES
('Follow up with Karan Malhotra','Negotiate AMC contract terms',3,2,'high','pending','2026-05-20'),
('Prepare Q2 Sales Report','Compile all sales data for Q2 2026',4,2,'medium','in_progress','2026-05-15'),
('Server Maintenance','Schedule downtime for server updates',5,2,'medium','pending','2026-05-12'),
('Send Invoice Reminder','Follow up on unpaid INV0002 and INV0003',4,2,'high','pending','2026-05-11'),
('Product Demo for Ananya Gupta','Demo ERP for Green Energy Co',3,2,'high','pending','2026-05-12');
