<?php
//start session if not started
session_start();
/*include_once('includes/functions.php');*/
$host     = 'localhost';
$db       = 'shop';
$user     = 'bobin';
$password = 'root';

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

} catch (PDOException $e) {
    echo $e->getMessage();
}


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


$searchArray = array();

// Search
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = " AND (mv.mem_pass_number LIKE :mem_pass_number OR 
           ah.hill_name LIKE :hill_name OR
           mv.visited_on LIKE :visited_on 
            ) ";
    $searchArray = array(
        'mem_pass_number'=>"%$searchValue%",
        'hill_name'=>"%$searchValue%",
        'visited_on'=>"%$searchValue%"
    );
}

// Total number of records without filtering
$sql = "SELECT mv.mem_pass_number, mv.hill_id, mv.visited_on , ah.hill_name  FROM mem_visits mv LEFT JOIN app_hills ah ON ah.hill_id=mv.hill_id";
$sqlAllcount = "SELECT COUNT(*) AS allcount  FROM mem_visits mv JOIN app_hills ah ON ah.hill_id=mv.hill_id ";
if($fromDate!='' && $toDate!=''){
    $sql .= " WHERE mv.visited_on >= DATE '$fromDate' AND mv.visited_on <= DATE '$toDate'";
    $sqlAllcount .= " WHERE mv.visited_on >= DATE '$fromDate' AND mv.visited_on <= DATE '$toDate'";
}
if(!empty($hillsArr)){
    $hillIds = implode("," , $hillsArr);
    $sql .= " AND mv.hill_id IN ($hillIds)";
    $sqlAllcount .= " AND mv.hill_id IN ($hillIds) ";
}

$stmt = $conn->prepare($sqlAllcount);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering

$stmt = $conn->prepare($sqlAllcount.$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$stmt = $conn->prepare($sql.$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach ($searchArray as $key=>$search) {
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

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
