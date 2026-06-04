<?php
// Lead Management — DB-Driven Master Dropdown Loader
// All values now come from master tables (replaces hardcoded arrays)

require_once __DIR__ . '/../../config/db.php';

/**
 * Fetch a flat list of values from a master table.
 * Falls back to hardcoded defaults if table doesn't exist yet.
 */
function getMasterList(string $table, string $col, string $orderCol = 'sort_order'): array {
    try {
        $stmt = db()->prepare("SELECT `$col` FROM `$table` WHERE is_active = 1 ORDER BY `$orderCol` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Fetch master list as id=>name pairs (for dropdowns with IDs).
 */
function getMasterPairs(string $table, string $colId, string $colName): array {
    try {
        $stmt = db()->prepare("SELECT `$colId`, `$colName` FROM `$table` WHERE is_active = 1 ORDER BY sort_order ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// ── Lead Master Dropdowns ──────────────────────────────────
$leadStatuses   = getMasterList('lead_statuses',       'status_name');
$leadPriorities = getMasterList('lead_priorities',      'priority_name');
$leadSources    = getMasterList('lead_sources',         'source_name');
$leadTypes      = getMasterList('lead_types',           'type_name');
$siteStages     = getMasterList('site_stages',          'stage_name');
$projectTypes   = getMasterList('project_types',        'type_name');
$productTypes   = getMasterList('lead_product_types',   'type_name');
$interestedProducts = getMasterList('interested_products', 'product_name');
$salesStages    = getMasterList('sales_stages',         'stage_name');

// ── Contact & Company Dropdowns ───────────────────────────
$contactTypes       = getMasterList('contact_types',       'type_name');
$companyTypes       = getMasterList('company_types',       'type_name');
$industryTypes      = getMasterList('industry_types',      'type_name');
$businessCategories = getMasterList('business_categories', 'category_name');
$companyStatuses    = getMasterList('company_statuses',    'status_name');

// ── Meeting Dropdowns ─────────────────────────────────────
$meetingTypes    = getMasterList('meeting_types',    'type_name');
$meetingStatuses = getMasterList('meeting_priorities', 'priority_name');

// ── Customer Module Dropdowns ─────────────────────────────
$customerTypes  = getMasterList('customer_types', 'type_name');
$addressTypes   = getMasterList('address_types',  'type_name');

// ── Fallback defaults if DB tables not yet migrated ───────
if (empty($leadStatuses)) {
    $leadStatuses = ['New','Open','Contact Attempt','Contacted','Followup','Qualified',
        'Proposal Sent','Catalogue Sent','Negotiation','Won','Closed','Lost','Duplicate','Junk'];
}
if (empty($leadPriorities)) {
    $leadPriorities = ['Cold Lead','Warm Lead','Hot Lead','Qualified Lead','Converted Lead','Hold Lead','Lost Lead'];
}
if (empty($leadSources)) {
    $leadSources = ['Digital Marketing','Architect','Builder','Contractor','Google Ads',
        'Instagram','Facebook','Offline Marketing','Exhibition','Newspaper','Referral',
        'Dealer','Employee','Existing Customer'];
}
if (empty($leadTypes)) {
    $leadTypes = ['Website','Instagram','Facebook','Google Ads','WhatsApp','Referral',
        'Dealer Network','Exhibition','Cold Calling','Export Inquiry'];
}
if (empty($siteStages)) {
    $siteStages = ['Blueprint','Estimation Stage','3D Discussion','Interior Work','False Ceiling Work',
        'Modular Kitchen','Bedroom Work','Living Hall','Bath & Toilet Installation',
        'Glass Work','Flooring Work','Painting Work','Electrical Work','Plumbing Work',
        'Final Finishing','Renovation Work','Possession Stage'];
}
if (empty($projectTypes)) {
    $projectTypes = ['Residential','Commercial','Industrial','Institutional','Real Estate',
        'Turnkey','Hospitality','Renovation','Interior','Landscape','PMC','Design Consultancy'];
}
if (empty($productTypes)) {
    $productTypes = ['Bespoke','Turnkey','Supply Only','Labour Only'];
}
if (empty($interestedProducts)) {
    $interestedProducts = ['Partition Systems','Shower Enclosures','Flush Doors',
        'Cabinets & Storage Solutions','Illuminated Walls','Glass Surface','LED Mirrors',
        'Modular Kitchen','Wardrobe','Vanity Units','Decorative Panels','Office Partitions'];
}
if (empty($meetingTypes)) {
    $meetingTypes = ['Site Visit','Office Meeting','Telephonic','Virtual Meeting',
        'Quotation Review','Negotiation','Project Review','Follow-up',
        'Dealer Network','Client Discussion','Material Discussion','Demo'];
}
if (empty($meetingStatuses)) {
    $meetingStatuses = ['Urgent','High','Medium','Low'];
}
if (empty($contactTypes)) {
    $contactTypes = ['Owner','Entrepreneur','Manager','Architect','Engineer','Contractor',
        'Purchase Head','Site Supervisor','Interior Designer','Builder','Consultant','Other'];
}
if (empty($companyTypes)) {
    $companyTypes = ['Individual','Partnership','Pvt Ltd','Ltd','LLP','Proprietorship'];
}
if (empty($industryTypes)) {
    $industryTypes = ['Manufacturing','Trading','Service','Retail','Wholesale',
        'Construction','Interior Design','Real Estate','Furniture','Luxury Products','Architecture Firm'];
}
if (empty($businessCategories)) {
    $businessCategories = ['B2B','B2C','D2C','Export','Import'];
}
if (empty($salesStages)) {
    $salesStages = ['Inquiry','Qualification','Requirement Gathering','Proposal',
        'Negotiation','Conversion','Execution','Completed'];
}
if (empty($customerTypes)) {
    $customerTypes = ['Dealer','Distributor','Architect','Interior Designer','Builder',
        'Contractor','Retail Customer','Corporate Client','Vendor/Supplier','Channel Partner'];
}
if (empty($addressTypes)) {
    $addressTypes = ['Site Address','Office Address','Home Address','Billing Address',
        'Shipping Address','Registered Address','Warehouse Address'];
}
if (empty($companyStatuses)) {
    $companyStatuses = ['Active','Inactive','Prospect','Blacklisted'];
}
