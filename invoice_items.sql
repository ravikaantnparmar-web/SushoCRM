CREATE TABLE invoice_items (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  invoice_id int(10) unsigned NOT NULL,
  product_id int(10) unsigned DEFAULT NULL,
  description varchar(250) NOT NULL,
  qty decimal(10,2) NOT NULL DEFAULT 1.00,
  unit varchar(30) DEFAULT 'Nos',
  unit_price decimal(12,2) NOT NULL DEFAULT 0.00,
  tax_rate decimal(5,2) DEFAULT 0.00,
  tax_amount decimal(12,2) DEFAULT 0.00,
  discount decimal(10,2) DEFAULT 0.00,
  line_total decimal(12,2) NOT NULL DEFAULT 0.00,
  sort_order int(11) DEFAULT 0,
  PRIMARY KEY (id),
  KEY invoice_id (invoice_id),
  KEY product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
