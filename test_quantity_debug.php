<?php
// Test script to debug medicine quantity calculation
require_once 'index.php';

// Get CI instance
$CI =& get_instance();
$CI->load->model('medicine_model');

// Set a test hospital_id (you may need to adjust this)
$CI->session->set_userdata('hospital_id', 1); // Adjust to your hospital_id

echo "<h2>Medicine Quantity Debug</h2>";

// Get all medicines
$medicines = $CI->medicine_model->getMedicine();
echo "<h3>All Medicines:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Name</th><th>Current Quantity</th><th>Calculated Quantity</th></tr>";

foreach ($medicines as $medicine) {
    $calculated_qty = $CI->medicine_model->calculateCurrentQuantity($medicine->id);
    echo "<tr>";
    echo "<td>{$medicine->id}</td>";
    echo "<td>{$medicine->name}</td>";
    echo "<td>{$medicine->quantity}</td>";
    echo "<td>{$calculated_qty}</td>";
    echo "</tr>";
}
echo "</table>";

// Check purchase items for a specific medicine
echo "<h3>Purchase Items Debug:</h3>";
$CI->db->select('mpi.*, mp.purchase_status, mp.hospital_id');
$CI->db->from('medicine_purchase_items mpi');
$CI->db->join('medicine_purchases mp', 'mpi.purchase_id = mp.id', 'left');
$CI->db->where('mp.hospital_id', 1); // Adjust to your hospital_id
$purchase_items = $CI->db->get()->result();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Medicine ID</th><th>Purchase Status</th><th>Received Status</th><th>Quantity Ordered</th><th>Quantity Received</th></tr>";

foreach ($purchase_items as $item) {
    echo "<tr>";
    echo "<td>{$item->medicine_id}</td>";
    echo "<td>{$item->purchase_status}</td>";
    echo "<td>{$item->received_status}</td>";
    echo "<td>{$item->quantity_ordered}</td>";
    echo "<td>{$item->quantity_received}</td>";
    echo "</tr>";
}
echo "</table>";

// Check pharmacy sales
echo "<h3>Pharmacy Sales Debug:</h3>";
$CI->db->where('hospital_id', 1); // Adjust to your hospital_id
$pharmacy_payments = $CI->db->get('pharmacy_payment')->result();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Payment ID</th><th>Category Name</th><th>Amount</th></tr>";

foreach ($pharmacy_payments as $payment) {
    echo "<tr>";
    echo "<td>{$payment->id}</td>";
    echo "<td>{$payment->category_name}</td>";
    echo "<td>{$payment->amount}</td>";
    echo "</tr>";
}
echo "</table>";
?>
