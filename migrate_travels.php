<?php
require 'config/db.php';
$db = db();

try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS travels (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            travel_number VARCHAR(50) NOT NULL,
            employee_id INT UNSIGNED NOT NULL,
            date_of_request DATETIME DEFAULT CURRENT_TIMESTAMP,
            from_date DATE NULL,
            to_date DATE NULL,
            number_of_days INT NULL,
            travel_type ENUM('Local', 'Domestic', 'International') NULL,
            travel_priority ENUM('Low', 'Medium', 'High') NULL,
            purpose_category ENUM('Client Meeting', 'Site Visit', 'Vendor', 'Exhibition', 'Other') NULL,
            location_city VARCHAR(100) NULL,
            location_state VARCHAR(100) NULL,
            location_country VARCHAR(100) NULL,
            multiple_locations TEXT NULL,
            mode_of_travel ENUM('Flight', 'Train', 'Car', 'Bus', 'Other') NULL,
            travel_status ENUM('Planned', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Planned',
            
            meeting_agenda TEXT NULL,
            meeting_with_type ENUM('Customer', 'Lead', 'Vendor', 'Internal') NULL,
            meeting_datetime DATETIME NULL,
            meeting_venue TEXT NULL,
            
            meeting_outcome ENUM('Successful', 'Pending', 'Rejected') NULL,
            customer_interest_level ENUM('Hot', 'Warm', 'Cold') NULL,
            discussion_summary TEXT NULL,
            client_requirement TEXT NULL,
            quotation_required ENUM('Yes', 'No') DEFAULT 'No',
            expected_business_value DECIMAL(15,2) DEFAULT 0.00,
            expected_closure_date DATE NULL,
            follow_up_needed ENUM('Yes', 'No') DEFAULT 'No',
            follow_up_date DATE NULL,
            follow_up_assigned_to INT UNSIGNED NULL,
            next_action_plan TEXT NULL,
            customer_feedback TEXT NULL,
            deal_status ENUM('Open', 'Negotiation', 'Won', 'Lost') NULL,
            
            expense_booking_required ENUM('Yes', 'No') DEFAULT 'No',
            expense_category ENUM('Travel', 'Food', 'Hotel', 'Fuel', 'Client Entertainment', 'Other') NULL,
            estimated_budget DECIMAL(15,2) DEFAULT 0.00,
            actual_expense_amount DECIMAL(15,2) DEFAULT 0.00,
            advance_taken DECIMAL(15,2) DEFAULT 0.00,
            payment_done_by ENUM('Employee', 'Company') NULL,
            reimbursement_required ENUM('Yes', 'No') DEFAULT 'No',
            expense_date DATE NULL,
            expense_vendor_name VARCHAR(255) NULL,
            payment_method ENUM('Cash', 'UPI', 'Card', 'Bank') NULL,
            gst_applicable ENUM('Yes', 'No') DEFAULT 'No',
            gst_number VARCHAR(50) NULL,
            expense_notes TEXT NULL,
            expense_approval_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
            
            overall_approval_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
            approved_by INT UNSIGNED NULL,
            
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (follow_up_assigned_to) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS travel_documents (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            travel_id INT UNSIGNED NOT NULL,
            document_type ENUM('Travel Tickets', 'Hotel Bills', 'Food Bills', 'Fuel Receipts', 'Client Documents', 'MOM', 'Photos', 'Visiting Card', 'Expense Bills', 'Signed Documents', 'Other') NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            description TEXT NULL,
            uploaded_by INT UNSIGNED NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (travel_id) REFERENCES travels(id) ON DELETE CASCADE,
            FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    echo "Travels and Travel Documents tables created successfully.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
