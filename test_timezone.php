<?php
// Test script to check timezone functionality
require_once 'index.php';

// Get CI instance
$CI =& get_instance();

echo "<h2>Timezone Test</h2>";

// Test if the controller method exists
if (method_exists($CI->home, 'updateTimezone')) {
    echo "<p style='color: green;'>✅ updateTimezone method exists</p>";
} else {
    echo "<p style='color: red;'>❌ updateTimezone method does not exist</p>";
}

// Test if user is logged in
if ($CI->ion_auth->logged_in()) {
    echo "<p style='color: green;'>✅ User is logged in</p>";
    $user = $CI->ion_auth->user()->row();
    echo "<p>User ID: " . $user->id . "</p>";
    echo "<p>Username: " . $user->username . "</p>";
} else {
    echo "<p style='color: red;'>❌ User is not logged in</p>";
}

// Test hospital_id
$hospital_id = $CI->session->userdata('hospital_id');
if ($hospital_id) {
    echo "<p style='color: green;'>✅ Hospital ID: " . $hospital_id . "</p>";
} else {
    echo "<p style='color: red;'>❌ No hospital_id in session</p>";
}

// Test settings table
$CI->load->model('settings_model');
$settings = $CI->settings_model->getSettings();
if ($settings) {
    echo "<p style='color: green;'>✅ Settings loaded</p>";
    echo "<p>Current timezone: " . ($settings->timezone ?? 'Not set') . "</p>";
} else {
    echo "<p style='color: red;'>❌ Could not load settings</p>";
}

// Test database connection
if ($CI->db->simple_query('SELECT 1')) {
    echo "<p style='color: green;'>✅ Database connection working</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed</p>";
}

echo "<h3>Test AJAX URL</h3>";
echo "<p>URL: " . base_url() . "home/updateTimezone</p>";

echo "<h3>Test Form</h3>";
echo '<form id="testForm">
    <select name="timezone">
        <option value="UTC">UTC</option>
        <option value="America/New_York">America/New_York</option>
        <option value="Europe/London">Europe/London</option>
    </select>
    <button type="button" onclick="testTimezone()">Test Timezone Update</button>
</form>';

echo '<div id="result"></div>';

echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
echo '<script>
function testTimezone() {
    const timezone = document.querySelector("select[name=timezone]").value;
    
    $.ajax({
        url: "' . base_url() . 'home/updateTimezone",
        method: "POST",
        data: { timezone: timezone },
        dataType: "json",
        success: function(response) {
            console.log("Response:", response);
            document.getElementById("result").innerHTML = "<p style=\"color: green;\">Success: " + JSON.stringify(response) + "</p>";
        },
        error: function(xhr, status, error) {
            console.error("Error:", xhr, status, error);
            console.error("Response Text:", xhr.responseText);
            document.getElementById("result").innerHTML = "<p style=\"color: red;\">Error: " + error + "<br>Response: " + xhr.responseText + "</p>";
        }
    });
}
</script>';
?>
