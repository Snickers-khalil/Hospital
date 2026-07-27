<?php
// Medicine Submenu Language Test Script
// Access this via: your-domain/test_medicine_submenu.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Medicine Submenu Language Test</h2>";

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

// Comprehensive list of all medicine submenu keywords
$submenu_keywords = array(
    // Supplier Management
    'suppliers' => 'Suppliers',
    'supplier' => 'Supplier',
    'contact' => 'Contact',
    'person' => 'Person',
    'phone' => 'Phone',
    'email' => 'Email',
    'city' => 'City',
    'credit' => 'Credit',
    'limit' => 'Limit',
    'status' => 'Status',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'basic' => 'Basic',
    'information' => 'Information',
    'mobile' => 'Mobile',
    'state' => 'State',
    'country' => 'Country',
    'postal_code' => 'Postal Code',
    'financial' => 'Financial',
    'tax' => 'Tax',
    'gst' => 'GST',
    'bank' => 'Bank',
    'account' => 'Account',
    'license' => 'License',
    'drug' => 'Drug',
    'e.g.' => 'e.g.',
    'net' => 'Net',
    'days' => 'Days',
    'save' => 'Save',
    'cancel' => 'Cancel',
    
    // Purchase Orders
    'purchase_order' => 'Purchase Order',
    'purchases' => 'Purchases',
    'purchase_date' => 'Purchase Date',
    'expected' => 'Expected',
    'delivery' => 'Delivery',
    'terms' => 'Terms',
    'notes' => 'Notes',
    'additional' => 'Additional',
    'or' => 'or',
    'instructions' => 'Instructions',
    'items' => 'Items',
    'unit' => 'Unit',
    'cost' => 'Cost',
    'batch' => 'Batch',
    'number' => 'Number',
    'action' => 'Action',
    'subtotal' => 'Subtotal',
    'transport' => 'Transport',
    'charges' => 'Charges',
    'other' => 'Other',
    'summary' => 'Summary',
    'select' => 'Select',
    'no' => 'No',
    'purchase_orders' => 'Purchase Orders',
    'invoice_no' => 'Invoice No',
    'total_amount' => 'Total Amount',
    'paid_amount' => 'Paid Amount',
    'balance' => 'Balance',
    'purchase_status' => 'Purchase Status',
    'payment_status' => 'Payment Status',
    'pending' => 'Pending',
    'ordered' => 'Ordered',
    'received' => 'Received',
    'partial' => 'Partial',
    'paid' => 'Paid',
    'cancelled' => 'Cancelled',
    'receive' => 'Receive',
    'view' => 'View',
    'expected_delivery' => 'Expected Delivery',
    'not_specified' => 'Not Specified',
    'not_provided' => 'Not Provided',
    'invoice_date' => 'Invoice Date',
    'supplier_contact' => 'Supplier Contact',
    'manufacturer' => 'Manufacturer',
    'back_to' => 'Back to',
    
    // Batches
    'batches' => 'Batches',
    'manufacturing' => 'Manufacturing',
    'current' => 'Current',
    'out_of_stock' => 'Out of Stock',
    'expired' => 'Expired',
    'expiring_soon' => 'Expiring Soon',
    'to' => 'to',
    'ago' => 'ago',
    'expires' => 'Expires',
    'today' => 'Today',
    
    // Expiring Medicines
    'expiring' => 'Expiring',
    'medicines' => 'Medicines',
    
    // Common UI Elements
    'add' => 'Add',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'submit' => 'Submit',
    'home' => 'Home',
    'all' => 'All',
    'description' => 'Description',
    'options' => 'Options',
    'actions' => 'Actions',
    'id' => 'ID',
    'date' => 'Date',
    'create' => 'Create',
    'add_new' => 'Add New',
    'new' => 'New',
    'name' => 'Name',
    'company' => 'Company',
    'address' => 'Address',
    'medicine' => 'Medicine',
    'generic' => 'Generic',
    'selling' => 'Selling',
    'price' => 'Price',
    'quantity' => 'Quantity',
    'expiry' => 'Expiry',
    'stock' => 'Stock',
    'purchase' => 'Purchase',
    'invoice' => 'Invoice',
    'payment' => 'Payment'
);

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Language Key</th>";
echo "<th>Expected Value</th>";
echo "<th>Actual Value</th>";
echo "<th>Status</th>";
echo "</tr>";

$missing_count = 0;
$total_count = count($submenu_keywords);

foreach ($submenu_keywords as $key => $expected) {
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
    foreach ($submenu_keywords as $key => $expected) {
        $actual = lang($key);
        if (!$actual || $actual === '') {
            echo "<li><code>" . $key . "</code> - Expected: " . $expected . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color: green;'>🎉 All medicine submenu keywords are working correctly!</p>";
}

echo "<hr>";
echo "<h3>Test Medicine Submenu Pages:</h3>";
echo "<ul>";
echo "<li><a href='medicine/suppliers'>Suppliers</a></li>";
echo "<li><a href='medicine/addSupplierView'>Add Supplier</a></li>";
echo "<li><a href='medicine/purchases'>Purchases</a></li>";
echo "<li><a href='medicine/addPurchaseView'>Add Purchase Order</a></li>";
echo "<li><a href='medicine/batches'>Batches</a></li>";
echo "<li><a href='medicine/expiringMedicines'>Expiring Medicines</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<p>If any keywords are missing, they need to be added to the language file:</p>";
echo "<p><code>application/language/" . $current_language . "/system_syntax_lang.php</code></p>";
echo "<p>Current language: <strong>" . $current_language . "</strong></p>";
?>
