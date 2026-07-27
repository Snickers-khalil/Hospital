<?php
// Medicine Module Language Test Script
// Access this via: your-domain/test_medicine_language.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Medicine Module Language Test</h2>";

// Check if user is logged in
if (!$CI->ion_auth->logged_in()) {
    echo "<p style='color: red;'>❌ User not logged in. Please login first.</p>";
    echo "<p>Go to: <a href='auth/login'>Login Page</a></p>";
    exit;
}

echo "<p style='color: green;'>✅ User is logged in</p>";

// Get hospital_id from session
$hospital_id = $CI->session->userdata('hospital_id');
echo "<p>Hospital ID: <strong>" . $hospital_id . "</strong></p>";

// Check current language
$CI->db->where('hospital_id', $hospital_id);
$settings = $CI->db->get('settings')->row();
$current_language = $settings ? $settings->language : 'english';

echo "<p>Current Language: <strong>" . $current_language . "</strong></p>";

// Test all medicine module language functions
echo "<h3>Medicine Module Language Tests:</h3>";

$medicine_keywords = array(
    // Basic Medicine Fields
    'medicine' => 'Medicine',
    'name' => 'Name',
    'category' => 'Category',
    'price' => 'Price',
    'quantity' => 'Quantity',
    'generic_name' => 'Generic Name',
    'company' => 'Company',
    'effects' => 'Effects',
    'expiry_date' => 'Expiry Date',
    
    // Medicine Module Specific
    'store' => 'Store',
    'box' => 'Box',
    'purchase' => 'Purchase',
    'selling' => 'Selling',
    'expiry' => 'Expiry',
    'alerts' => 'Alerts',
    'stock_out' => 'Stock Out',
    'load' => 'Load',
    'low' => 'Low',
    'levels' => 'Levels',
    'with' => 'With',
    'names_and_related_information' => 'Names and Related Information',
    'categories' => 'Categories',
    'generic' => 'Generic',
    'stock' => 'Stock',
    
    // Actions and Navigation
    'add_medicine' => 'Add Medicine',
    'edit_medicine' => 'Edit Medicine',
    'medicine_list' => 'Medicine List',
    'medicine_category' => 'Medicine Category',
    'add_medicine_category' => 'Add Medicine Category',
    'edit_medicine_category' => 'Edit Medicine Category',
    'medicine_categories' => 'Medicine Categories',
    'create_medicine_category' => 'Create Medicine Category',
    'add_new_medicine' => 'Add New Medicine',
    'medicine_stock_alert' => 'Medicine Stock Alert',
    'load_medicine' => 'Load Medicine',
    'medicine_loaded' => 'Medicine Loaded',
    
    // Common UI Elements
    'add' => 'Add',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'submit' => 'Submit',
    'home' => 'Home',
    'all' => 'All',
    'medicines' => 'Medicines',
    'description' => 'Description',
    'options' => 'Options',
    'actions' => 'Actions',
    'id' => 'ID',
    'date' => 'Date',
    'create' => 'Create',
    'add_new' => 'Add New',
    'new' => 'New',
    
    // Confirmation Messages
    'are_you_sure_you_want_to_delete_this_item' => 'Are you sure you want to delete this item?',
    
    // Pharmacy Related
    'selected_items' => 'Selected Items',
    'select_item' => 'Select Item',
    'sub_total' => 'Sub Total',
    'discount' => 'Discount',
    'total' => 'Total',
    'amount_received' => 'Amount Received'
);

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Language Key</th>";
echo "<th>Expected Value</th>";
echo "<th>Actual Value</th>";
echo "<th>Status</th>";
echo "</tr>";

$missing_count = 0;
$total_count = count($medicine_keywords);

foreach ($medicine_keywords as $key => $expected) {
    $actual = lang($key);
    $status = ($actual && $actual !== '') ? '✅' : '❌';
    $status_color = ($actual && $actual !== '') ? 'green' : 'red';
    
    if (!$actual || $actual === '') {
        $missing_count++;
    }
    
    echo "<tr>";
    echo "<td><code>" . $key . "</code></td>";
    echo "<td>" . $expected . "</td>";
    echo "<td>" . ($actual ?: '<em>empty</em>') . "</td>";
    echo "<td style='color: " . $status_color . ";'>" . $status . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Summary:</h3>";
echo "<p>Total Keywords Tested: <strong>" . $total_count . "</strong></p>";
echo "<p>Working Keywords: <strong style='color: green;'>" . ($total_count - $missing_count) . "</strong></p>";
echo "<p>Missing Keywords: <strong style='color: red;'>" . $missing_count . "</strong></p>";

if ($missing_count > 0) {
    echo "<h3>Missing Keywords:</h3>";
    echo "<p style='color: red;'>The following keywords are missing or empty:</p>";
    echo "<ul>";
    foreach ($medicine_keywords as $key => $expected) {
        $actual = lang($key);
        if (!$actual || $actual === '') {
            echo "<li><code>" . $key . "</code> - Expected: " . $expected . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color: green;'>🎉 All medicine module keywords are working correctly!</p>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<p>If any keywords are missing, they need to be added to the language file:</p>";
echo "<p><code>application/language/" . $current_language . "/system_syntax_lang.php</code></p>";
echo "<p>Current language: <strong>" . $current_language . "</strong></p>";
?>
