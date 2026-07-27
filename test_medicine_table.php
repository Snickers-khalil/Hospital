<?php
// Test script to check medicine table DataTables JSON response
require_once 'index.php';

// Simulate a DataTables request
$_REQUEST['draw'] = 1;
$_REQUEST['start'] = 0;
$_REQUEST['length'] = 10;
$_REQUEST['search']['value'] = '';
$_REQUEST['order'][0]['column'] = 0;
$_REQUEST['order'][0]['dir'] = 'asc';

// Capture output
ob_start();

// Call the getMedicineList function
$CI =& get_instance();
$CI->load->model('medicine_model');
$CI->load->model('settings_model');

// Simulate the controller method
$requestData = $_REQUEST;
$start = $requestData['start'];
$limit = $requestData['length'];
$search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';

$order = isset($_REQUEST["order"]) ? $_REQUEST["order"] : null;
$columns_valid = array(
    "0" => "id",
    "1" => "name",
    "2" => "category",
    "3" => "box",
    "4" => "price",
    "5" => "s_price",
    "6" => "quantity",
    "7" => "generic",
    "8" => "company",
    "9" => "effects",
    "10" => "actions",
);

if ($order) {
    $values = $CI->settings_model->getColumnOrder($order, $columns_valid);
    $dir = $values[0];
    $order = $values[1];
} else {
    $dir = 'asc';
    $order = 'id';
}

if ($limit == -1) {
    if (!empty($search)) {
        $medicines = $CI->medicine_model->getMedicineBysearch($search, $order, $dir);
    } else {
        $medicines = $CI->medicine_model->getMedicineWithoutSearch($order, $dir);
    }
} else {
    if (!empty($search)) {
        $medicines = $CI->medicine_model->getMedicineByLimitBySearch($limit, $start, $search, $order, $dir);
    } else {
        $medicines = $CI->medicine_model->getMedicineByLimit($limit, $start, $order, $dir);
    }
}

$i = 0;
$info = array();
foreach ($medicines as $medicine) {
    $i = $i + 1;
    $settings = $CI->settings_model->getSettings();
    if ($medicine->quantity <= 0) {
        $quan = '<p class="os">Stock Out</p>';
    } else {
        $quan = $medicine->quantity;
    }
    
    $option1 = '<a type="button" class="btn btn-primary btn-sm btn_width editbutton" data-toggle="modal" data-id="' . $medicine->id . '"><i class="fa fa-edit"> </i> Edit</a>';
    $option2 = '<a class="btn btn-danger btn-sm btn_width delete_button" href="medicine/delete?id=' . $medicine->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> Delete</a>';

    $info[] = array(
        $i,
        $medicine->name,
        $medicine->category,
        $medicine->box,
        $settings->currency . $medicine->price,
        $settings->currency . $medicine->s_price,
        $quan,
        $medicine->generic,
        $medicine->company,
        $medicine->effects,
        '<span class="d-flex gap-2">' . $option1 . ' ' . $option2 . '</span>'
    );
}

if (!empty($medicines)) {
    $output = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => count($CI->medicine_model->getMedicine()),
        "recordsFiltered" => count($CI->medicine_model->getMedicine()),
        "data" => $info
    );
} else {
    $output = array(
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    );
}

$json_output = json_encode($output);
$json_error = json_last_error();

echo "JSON Output:\n";
echo $json_output . "\n\n";

echo "JSON Error Code: " . $json_error . "\n";
echo "JSON Error Message: " . json_last_error_msg() . "\n\n";

if ($json_error === JSON_ERROR_NONE) {
    echo "✅ JSON is valid!\n";
} else {
    echo "❌ JSON error detected!\n";
}

echo "\nData structure:\n";
print_r($output);
?>
