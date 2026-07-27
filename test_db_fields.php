<?php
// Database field test script
// Access this via: your-domain/test_db_fields.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Medicine Database Fields Test</h2>";

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

// Test 1: Get table structure
echo "<h3>Medicine Table Structure:</h3>";
$fields = $CI->db->field_data('medicine');
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field Name</th><th>Type</th><th>Max Length</th><th>Primary Key</th></tr>";
foreach ($fields as $field) {
    echo "<tr>";
    echo "<td>" . $field->name . "</td>";
    echo "<td>" . $field->type . "</td>";
    echo "<td>" . $field->max_length . "</td>";
    echo "<td>" . ($field->primary_key ? 'Yes' : 'No') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test 2: Get sample medicine record
echo "<h3>Sample Medicine Record:</h3>";
$CI->db->select('*');
$CI->db->from('medicine');
$CI->db->where('hospital_id', $hospital_id);
$CI->db->limit(1);
$sample = $CI->db->get()->row();

if ($sample) {
    echo "<pre>" . json_encode($sample, JSON_PRETTY_PRINT) . "</pre>";
    
    // Test 3: Check specific fields
    echo "<h3>Field Values:</h3>";
    echo "<ul>";
    echo "<li>ID: " . (isset($sample->id) ? $sample->id : 'NOT SET') . "</li>";
    echo "<li>Name: " . (isset($sample->name) ? $sample->name : 'NOT SET') . "</li>";
    echo "<li>Company: " . (isset($sample->company) ? $sample->company : 'NOT SET') . "</li>";
    echo "<li>S_Price: " . (isset($sample->s_price) ? $sample->s_price : 'NOT SET') . "</li>";
    echo "<li>Quantity: " . (isset($sample->quantity) ? $sample->quantity : 'NOT SET') . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ No medicines found for this hospital</p>";
}

// Test 4: Test the actual method with debugging
echo "<h3>Method Test with Field Debugging:</h3>";
$CI->load->model('medicine_model');

// Override the method temporarily to see raw data
$searchTerm = '';
$hospital_id = $CI->session->userdata('hospital_id');

if (!empty($searchTerm)) {
    $query = $CI->db->select('*')
    ->from('medicine')
    ->where('hospital_id', $hospital_id)
    ->where('quantity >', '0')
    ->group_start()
        ->like('id', $searchTerm)
        ->or_like('name', $searchTerm)
    ->group_end()
    ->get();

$users = $query->result_array();
} else {
    $CI->db->select('*');
    $CI->db->where('hospital_id', $hospital_id);
    $CI->db->where('quantity >', '0');
    $CI->db->limit(3);
    $fetched_records = $CI->db->get('medicine');
    $users = $fetched_records->result_array();
}

echo "<p>Raw database records:</p>";
echo "<pre>" . json_encode($users, JSON_PRETTY_PRINT) . "</pre>";

// Test 5: Test the actual method
echo "<h3>Actual Method Response:</h3>";
$response = $CI->medicine_model->getMedicineInfoForPharmacySale('');
echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p>Check the field names above and compare with the method response.</p>";
echo "<p>If any field shows 'NOT SET', that's likely the cause of 'undefined' values.</p>";
?>
