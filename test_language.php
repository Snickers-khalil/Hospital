<?php
// Language test script
// Access this via: your-domain/test_language.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Language Test</h2>";

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

// Test language functions
echo "<h3>Language Function Tests:</h3>";
echo "<ul>";
echo "<li>select_item: " . lang('select_item') . "</li>";
echo "<li>selected_items: " . lang('selected_items') . "</li>";
echo "<li>sub_total: " . lang('sub_total') . "</li>";
echo "<li>discount: " . lang('discount') . "</li>";
echo "<li>total: " . lang('total') . "</li>";
echo "<li>amount_received: " . lang('amount_received') . "</li>";
echo "<li>medicine: " . lang('medicine') . "</li>";
echo "<li>price: " . lang('price') . "</li>";
echo "<li>quantity: " . lang('quantity') . "</li>";
echo "<li>company: " . lang('company') . "</li>";
echo "<li>generic_name: " . lang('generic_name') . "</li>";
echo "</ul>";

// Check if language file exists
$language_file = 'application/language/' . $current_language . '/system_syntax_lang.php';
echo "<h3>Language File Check:</h3>";
echo "<p>Language file: <code>" . $language_file . "</code></p>";

if (file_exists($language_file)) {
    echo "<p style='color: green;'>✅ Language file exists</p>";
    
    // Check for missing entries
    $file_content = file_get_contents($language_file);
    $missing_entries = array();
    
    $required_entries = array(
        'selected_items',
        'select_item',
        'sub_total',
        'discount',
        'total',
        'amount_received',
        'medicine',
        'price',
        'quantity',
        'company',
        'generic_name'
    );
    
    foreach ($required_entries as $entry) {
        if (strpos($file_content, "'" . $entry . "'") === false) {
            $missing_entries[] = $entry;
        }
    }
    
    if (empty($missing_entries)) {
        echo "<p style='color: green;'>✅ All required language entries exist</p>";
    } else {
        echo "<p style='color: red;'>❌ Missing language entries:</p>";
        echo "<ul>";
        foreach ($missing_entries as $entry) {
            echo "<li>" . $entry . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color: red;'>❌ Language file does not exist</p>";
}

// Test specific pharmacy language entries
echo "<h3>Pharmacy Specific Language Tests:</h3>";
$pharmacy_entries = array(
    'poss' => 'Pharmacy POS',
    'invoice_id' => 'Invoice ID',
    'gross_total' => 'Gross Total'
);

echo "<ul>";
foreach ($pharmacy_entries as $key => $expected) {
    $value = lang($key);
    $status = $value ? '✅' : '❌';
    echo "<li>" . $key . ": " . $status . " " . $value . "</li>";
}
echo "</ul>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<p>If any language entries are missing or showing empty, they need to be added to the language file.</p>";
echo "<p>Current language: <strong>" . $current_language . "</strong></p>";
?>
