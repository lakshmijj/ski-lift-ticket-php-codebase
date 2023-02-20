<?php

include_once('includes/functions.php');

//start session if not started
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
// include_once('includes/config.php');


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$hillsArr = $_POST['hillsArr'];

$retArr = reportSearch($fromDate,$toDate,$hillsArr,$searchValue,$columnName,$columnSortOrder,$row,$rowperpage);
$empRecords = $retArr['empRecords'];
$totalRecords = $retArr['totalRecords'];
$totalRecordwithFilter = $retArr['totalRecordwithFilter'];

$data = array();

foreach ($empRecords as $row) {
    $data[] = array(
        "mem_pass_number"=>$row['mem_pass_number'],
        "hill_name"=>$row['hill_name'],
        "visited_on"=>$row['visited_on']
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
exit;


?>
