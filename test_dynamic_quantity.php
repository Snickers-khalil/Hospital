<?php
// Dynamic Medicine Quantity Calculation Test Script
// Access this via: your-domain/test_dynamic_quantity.php

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Dynamic Medicine Quantity Calculation Test</h2>";

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

// Get medicines to test
$medicines = $CI->medicine_model->getMedicine();
echo "<h3>Testing Dynamic Quantity Calculation</h3>";

if (empty($medicines)) {
    echo "<p style='color: orange;'>⚠️ No medicines found. Please add some medicines first.</p>";
    echo "<p>Go to: <a href='medicine/addMedicineView'>Add Medicine</a></p>";
    exit;
}

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Medicine ID</th>";
echo "<th>Medicine Name</th>";
echo "<th>Current Quantity (DB)</th>";
echo "<th>Calculated Quantity</th>";
echo "<th>Total Purchased</th>";
echo "<th>Total Sold</th>";
echo "<th>Status</th>";
echo "<th>Actions</th>";
echo "</tr>";

foreach ($medicines as $medicine) {
    $calculated_quantity = $CI->medicine_model->calculateCurrentQuantity($medicine->id);
    $current_quantity = $medicine->quantity;
    $is_accurate = ($calculated_quantity == $current_quantity);
    
    // Get detailed breakdown
    $hospital_id = $CI->session->userdata('hospital_id');
    
    // Calculate total purchased
    $CI->db->select('SUM(quantity_ordered) as total_purchased');
    $CI->db->from('medicine_purchase_items mpi');
    $CI->db->join('medicine_purchases mp', 'mpi.purchase_id = mp.id', 'left');
    $CI->db->where('mpi.medicine_id', $medicine->id);
    $CI->db->where('mp.hospital_id', $hospital_id);
    $CI->db->where('mp.purchase_status', 'received');
    $purchased = $CI->db->get()->row();
    $total_purchased = $purchased ? $purchased->total_purchased : 0;
    
    // Calculate total sold
    $CI->db->where('hospital_id', $hospital_id);
    $pharmacy_payments = $CI->db->get('pharmacy_payment')->result();
    $total_sold = 0;
    
    foreach ($pharmacy_payments as $payment) {
        if (!empty($payment->category_name)) {
            $items = explode(',', $payment->category_name);
            foreach ($items as $item) {
                $parts = explode('*', $item);
                if (count($parts) >= 4 && $parts[0] == $medicine->id) {
                    $total_sold += floatval($parts[2]);
                }
            }
        }
    }
    
    echo "<tr>";
    echo "<td>" . $medicine->id . "</td>";
    echo "<td><strong>" . $medicine->name . "</strong></td>";
    echo "<td>" . $current_quantity . "</td>";
    echo "<td style='color: " . ($is_accurate ? 'green' : 'red') . ";'><strong>" . $calculated_quantity . "</strong></td>";
    echo "<td>" . $total_purchased . "</td>";
    echo "<td>" . $total_sold . "</td>";
    echo "<td style='color: " . ($is_accurate ? 'green' : 'red') . ";'>" . ($is_accurate ? '✅ Accurate' : '❌ Needs Update') . "</td>";
    echo "<td><a href='medicine/updateMedicineQuantity?id=" . $medicine->id . "' class='btn btn-sm btn-primary'>Update</a></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>How Dynamic Quantity Calculation Works:</h3>";
echo "<div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h4>Formula:</h4>";
echo "<p><strong>Current Quantity = Total Purchases - Total Sales</strong></p>";
echo "<h4>Sources:</h4>";
echo "<ul>";
echo "<li><strong>Total Purchases:</strong> Sum of 'quantity_ordered' from medicine_purchase_items where purchase_status = 'received'</li>";
echo "<li><strong>Total Sales:</strong> Sum of quantities from pharmacy_payment table (pharmacy sales)</li>";
echo "</ul>";
echo "</div>";

echo "<h3>Benefits:</h3>";
echo "<ul>";
echo "<li>✅ <strong>Accurate Stock:</strong> Always reflects actual inventory</li>";
echo "<li>✅ <strong>No Manual Entry:</strong> Eliminates human error</li>";
echo "<li>✅ <strong>Real-time Updates:</strong> Automatically updates with purchases/sales</li>";
echo "<li>✅ <strong>Data Integrity:</strong> Maintains consistency across modules</li>";
echo "<li>✅ <strong>Audit Trail:</strong> Complete transaction history</li>";
echo "</ul>";

echo "<h3>Test the System:</h3>";
echo "<ol>";
echo "<li>Go to <a href='medicine/addMedicineView'>Add New Medicine</a> - Notice no quantity field</li>";
echo "<li>Add a medicine (quantity will be set to 0 initially)</li>";
echo "<li>Go to <a href='medicine/addPurchaseView'>Add Purchase Order</a></li>";
echo "<li>Purchase some quantity of the medicine</li>";
echo "<li>Receive the purchase (mark as received)</li>";
echo "<li>Check medicine list - quantity should update automatically</li>";
echo "<li>Make a pharmacy sale</li>";
echo "<li>Check medicine list again - quantity should decrease</li>";
echo "</ol>";

echo "<h3>API Endpoints:</h3>";
echo "<ul>";
echo "<li><code>GET medicine/calculateCurrentQuantity?id={medicine_id}</code> - Calculate quantity for specific medicine</li>";
echo "<li><code>GET medicine/updateAllMedicineQuantities</code> - Update all medicine quantities</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<p>After implementing this system:</p>";
echo "<ul>";
echo "<li>All new medicines will have quantity = 0 initially</li>";
echo "<li>Quantities will be calculated from purchase/sale transactions</li>";
echo "<li>Manual quantity entry is no longer needed</li>";
echo "<li>Stock levels will always be accurate</li>";
echo "</ul>";
?>
