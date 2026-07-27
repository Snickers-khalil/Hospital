<?php
// Simple test script to check medicine endpoint
// Access this via: your-domain/test_medicine_endpoint.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Medicine Endpoint Test</h2>";

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

// Check if hospital_id is valid
if (empty($hospital_id) || $hospital_id == 'superadmin') {
    echo "<p style='color: red;'>❌ Invalid hospital_id. Current user might be superadmin or session issue.</p>";
    exit;
}

// Load medicine model
$CI->load->model('medicine_model');

// Test 1: Check total medicines in database
echo "<h3>Database Check:</h3>";
$CI->db->select('COUNT(*) as total');
$CI->db->from('medicine');
$total_medicines = $CI->db->get()->row()->total;
echo "<p>Total medicines in database: <strong>" . $total_medicines . "</strong></p>";

// Test 2: Check medicines for current hospital
$CI->db->select('COUNT(*) as total');
$CI->db->from('medicine');
$CI->db->where('hospital_id', $hospital_id);
$hospital_medicines = $CI->db->get()->row()->total;
echo "<p>Medicines for current hospital: <strong>" . $hospital_medicines . "</strong></p>";

// Test 3: Check medicines with stock > 0
$CI->db->select('COUNT(*) as total');
$CI->db->from('medicine');
$CI->db->where('hospital_id', $hospital_id);
$CI->db->where('quantity >', 0);
$available_medicines = $CI->db->get()->row()->total;
echo "<p>Medicines with stock > 0: <strong>" . $available_medicines . "</strong></p>";

// Test 4: Get sample medicines
echo "<h3>Sample Medicines:</h3>";
$CI->db->select('id, name, quantity, s_price, company');
$CI->db->from('medicine');
$CI->db->where('hospital_id', $hospital_id);
$CI->db->where('quantity >', 0);
$CI->db->limit(5);
$sample_medicines = $CI->db->get()->result();

if (count($sample_medicines) > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Stock</th><th>Price</th><th>Company</th></tr>";
    foreach ($sample_medicines as $medicine) {
        echo "<tr>";
        echo "<td>" . $medicine->id . "</td>";
        echo "<td>" . $medicine->name . "</td>";
        echo "<td>" . $medicine->quantity . "</td>";
        echo "<td>" . $medicine->s_price . "</td>";
        echo "<td>" . $medicine->company . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ No medicines found with stock > 0</p>";
}

// Test 5: Test the actual method
echo "<h3>Method Test:</h3>";
$response = $CI->medicine_model->getMedicineInfoForPharmacySale('');
echo "<p>Method response count: <strong>" . count($response) . "</strong></p>";
echo "<p>Response data:</p>";
echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";

// Test 6: Test with search term
echo "<h3>Search Test (searching for 'a'):</h3>";
$response_search = $CI->medicine_model->getMedicineInfoForPharmacySale('a');
echo "<p>Search response count: <strong>" . count($response_search) . "</strong></p>";
echo "<p>Search response data:</p>";
echo "<pre>" . json_encode($response_search, JSON_PRETTY_PRINT) . "</pre>";

// Test 7: Test AJAX endpoint directly
echo "<h3>AJAX Endpoint Test:</h3>";
echo "<p>Testing: <code>medicine/getMedicineForPharmacyMedicine</code></p>";

// Simulate POST request
$_POST['searchTerm'] = '';
$CI->load->library('input');
$CI->input->set_post('searchTerm', '');

// Capture output
ob_start();
$CI->load->controller('medicine');
$CI->medicine->getMedicineForPharmacyMedicine();
$ajax_output = ob_get_clean();

echo "<p>AJAX Response:</p>";
echo "<pre>" . $ajax_output . "</pre>";

// Test 8: Test the pharmacy endpoint
echo "<h3>Pharmacy Endpoint Test:</h3>";
echo "<p>Testing: <code>finance/pharmacy/getMedicinesWithBatches</code></p>";

// Simulate POST request for pharmacy endpoint
$_POST['searchTerm'] = '';
$CI->input->set_post('searchTerm', '');

// Capture output
ob_start();
$CI->load->controller('finance/pharmacy');
$CI->pharmacy->getMedicinesWithBatches();
$pharmacy_ajax_output = ob_get_clean();

echo "<p>Pharmacy AJAX Response:</p>";
echo "<pre>" . $pharmacy_ajax_output . "</pre>";

// Test 8: Check if there are any JavaScript errors
echo "<h3>JavaScript Test:</h3>";
echo "<p>To test JavaScript, open browser console (F12) and go to pharmacy add sale page.</p>";
echo "<p>Look for any errors in the console when clicking the medicine dropdown.</p>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
if ($available_medicines == 0) {
    echo "<p style='color: red;'>❌ <strong>No medicines with stock found!</strong> You need to add medicines to the database first.</p>";
    echo "<p>Go to: <a href='medicine'>Medicine Management</a> to add medicines.</p>";
} else {
    echo "<p style='color: green;'>✅ Medicines exist in database. The issue might be JavaScript or AJAX related.</p>";
    echo "<p>Check browser console for errors when using the pharmacy module.</p>";
}
?>
