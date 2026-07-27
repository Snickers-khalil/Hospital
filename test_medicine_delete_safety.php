<?php
// Medicine Delete Safety Check Test Script
// Access this via: your-domain/test_medicine_delete_safety.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Medicine Delete Safety Check Test</h2>";

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

// Load medicine model
$CI->load->model('medicine_model');

// Get some medicines to test
$medicines = $CI->medicine_model->getMedicine();
echo "<h3>Testing Medicine Delete Safety Check</h3>";

if (empty($medicines)) {
    echo "<p style='color: orange;'>⚠️ No medicines found. Please add some medicines first.</p>";
    echo "<p>Go to: <a href='medicine/addMedicineView'>Add Medicine</a></p>";
    exit;
}

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Medicine ID</th>";
echo "<th>Medicine Name</th>";
echo "<th>Purchase Items</th>";
echo "<th>Batches</th>";
echo "<th>Sales</th>";
echo "<th>Prescriptions</th>";
echo "<th>Can Delete</th>";
echo "<th>Test Link</th>";
echo "</tr>";

foreach ($medicines as $medicine) {
    $related_records = $CI->medicine_model->checkMedicineRelatedRecords($medicine->id);
    $can_delete = empty($related_records);
    
    echo "<tr>";
    echo "<td>" . $medicine->id . "</td>";
    echo "<td><strong>" . $medicine->name . "</strong></td>";
    echo "<td>" . (isset($related_records['purchase_items']) ? $related_records['purchase_items'] : '0') . "</td>";
    echo "<td>" . (isset($related_records['batches']) ? $related_records['batches'] : '0') . "</td>";
    echo "<td>" . (isset($related_records['sales']) ? $related_records['sales'] : '0') . "</td>";
    echo "<td>" . (isset($related_records['prescriptions']) ? $related_records['prescriptions'] : '0') . "</td>";
    echo "<td style='color: " . ($can_delete ? 'green' : 'red') . ";'>" . ($can_delete ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td><a href='medicine/checkDeleteSafety?id=" . $medicine->id . "' target='_blank'>Test API</a></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Test the Delete Safety Check:</h3>";
echo "<ol>";
echo "<li>Go to <a href='medicine'>Medicine List</a></li>";
echo "<li>Click on any delete button (trash icon)</li>";
echo "<li>A modal should appear showing:</li>";
echo "<ul>";
echo "<li><strong>If safe to delete:</strong> Green alert with checkmark and 'Confirm Delete' button</li>";
echo "<li><strong>If not safe:</strong> Red alert listing all related records with no delete button</li>";
echo "</ul>";
echo "<li>If safe to delete, click 'Confirm Delete' to proceed</li>";
echo "<li>If not safe, close the modal and delete related records first</li>";
echo "</ol>";

echo "<h3>Related Records Check:</h3>";
echo "<p>The system checks for the following related records:</p>";
echo "<ul>";
echo "<li><strong>Purchase Items:</strong> Records in medicine_purchase_items table</li>";
echo "<li><strong>Batches:</strong> Records in medicine_batches table</li>";
echo "<li><strong>Sales:</strong> Records in pharmacy_payment table (pharmacy sales)</li>";
echo "<li><strong>Prescriptions:</strong> Records in prescription table (if exists)</li>";
echo "</ul>";

echo "<h3>Safety Features:</h3>";
echo "<ul>";
echo "<li>✅ Prevents accidental deletion of medicines with related data</li>";
echo "<li>✅ Shows clear information about what records are preventing deletion</li>";
echo "<li>✅ Provides visual feedback with icons and colors</li>";
echo "<li>✅ Only allows deletion when it's safe to do so</li>";
echo "<li>✅ Maintains data integrity across the system</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>API Endpoint:</h3>";
echo "<p><code>GET medicine/checkDeleteSafety?id={medicine_id}</code></p>";
echo "<p>Returns JSON with:</p>";
echo "<pre>";
echo '{
    "can_delete": true/false,
    "related_records": {
        "purchase_items": 5,
        "batches": 3,
        "sales": 2,
        "prescriptions": 1
    },
    "medicine_name": "Medicine Name"
}';
echo "</pre>";
?>
