<?php

include_once('config.php');

/*
* returns a connection to the database
* @return mixed
*/

function connectToDB()
{
    $db = config('db');
    $servername = $db['servername'];
    $username = $db['username'];
    $password = $db['password'];
    $dbName = $db['dbName'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection Failed: ' . $e->getMessage();
    }
}

/*
* authenticate the user and navigate to dashboard page
* @param array $data
* @return void
*/

function authenticate(array $data){
    $conn = connectToDB();
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    //$data = $conn->query("SELECT * FROM app_users")->fetchAll();
    //$sql = "SELECT user_id FROM app_users WHERE user_name = :username AND user_pwd = :password";
    //$data = $conn->prepare($sql);
    //var_dump($data['username']);

    // $statement = $conn->prepare("SELECT COUNT(*) FROM app_users WHERE `user_name` = :username AND `user_pwd` = :password;");    
    // $statement->bindParam(":username", $data['username']);
    // $statement->bindParam(":password", $data['password']);
    // $statement->execute();

    $sql = "SELECT COUNT(*) FROM app_users WHERE user_name = '".$data['username'] ."' AND user_pwd = '".$data['password']."'";
    $result = $conn->query($sql)->fetchAll();
    if(isset($result) && $result[0][0]>0){
        unset($_SESSION['logginError']);
        unset($_SESSION['logginErrorMessage']);
        unset($_SESSION['userMessage']);
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start'] + (600 * 60); //
        return header("Location: existingmembers.php");
    }else{
        unset($_SESSION['name']);
        unset($_SESSION['expire']);
        $_SESSION['logginError'] = TRUE;
        $_SESSION['logginErrorMessage'] = 'Invalid login, please contact administrator for the support';
        return header("Location: index.php");
    }
}

/*
* save new registration
* @param array $data
* @return void
*/
function addMember(array $data)
{
    $conn = connectToDB();

    if ($conn) {
        $sql = "INSERT INTO app_members (mem_pass_number, mem_first_name, mem_last_name, mem_status, mem_payment_status, mem_bar_code, mem_last_updated) VALUES (:mem_pass_number, :mem_first_name, :mem_last_name, :mem_status, :mem_payment_status, :mem_bar_code, :mem_last_updated)";

        $conn->prepare($sql)->execute($data);
    }

    //redirect back to index
    return header("Location: existingmembers.php");
}

/*
* update existing member
* @param array $data
* @param int $id
* @return void
*/
function updateMember(array $data, int $id)
{
    $conn = connectToDB();

    if ($conn) {
        $sql = "UPDATE app_members 
        -- SET (mem_first_name, mem_last_name, mem_status, mem_payment_status, mem_bar_code, mem_last_updated)
        SET mem_first_name=:mem_first_name, mem_last_name=:mem_last_name, mem_status=:mem_status, mem_payment_status=:mem_payment_status, mem_bar_code=:mem_bar_code, mem_last_updated=:mem_last_updated
        -- VALUES (:mem_first_name, :mem_last_name, :mem_status, :mem_payment_status, :mem_bar_code, :mem_last_updated)
        WHERE mem_pass_number = '$id'";

        $conn->prepare($sql)->execute($data);
    }

    //redirect back to members table
    return header("Location: existingmembers.php");
}

/*
* return all members
* @return array
*/
function getMembers()
{
    $conn = connectToDB();
    $data = [];
    if ($conn) {
        $data = $conn->query("SELECT * FROM app_members")->fetchAll();
    }
    return $data;
}

/*
* return member based on pass number
* @return object
*/
function getMember($id)
{
    $conn = connectToDB();
    // $data;

    if ($conn) {
        $data = $conn->query("SELECT * FROM app_members WHERE mem_pass_number = $id")->fetchAll();
    }

    return $data;
}

/*
* validate member status based on pass number
* @param int $id
* @return boolean $valid
*/
function memberValid($id)
{
    $conn = connectToDB();
    $member = getMember($id)[0];
    $valid = false;

    if ($conn) {
        $data = $conn->query("SELECT * FROM app_members WHERE mem_pass_number = $id")->fetchAll();
    }

    if ($data) {
        if ($member['mem_status'] === 'Active') {
            $valid = true;
        }
    }

    return $valid;
}

/*
* Add visit record to mem_visits table
* @param array $data
* @return void
*/
function logVisit(array $data) {
    $conn = connectToDB();

    if ($conn) {
        $sql = "INSERT INTO mem_visits (mem_pass_number, hill_id, visited_on) 
        VALUES (:mem_pass_number, :hill_id, CURRENT_TIMESTAMP)";

        $conn->prepare($sql)->execute($data);
    }
}

/*
* Delete selected members
* @param array $keys
* @return void
*/
function deleteSelectedMembers()
{
    $conn = connectToDB();
    $delSql = [];

    if (!empty($_POST["checkbox"])) {
        foreach ($_POST["checkbox"] as $id) {
            // var_dump($id);
            $delSql[] = intval($id);
        }
        $list = implode(', ', $delSql);

        $conn->query("DELETE FROM app_members WHERE mem_pass_number IN ($list)");
    }

    return header("Location: existingmembers.php");
}

function niceBool(int $value)
{
    if ($value === 1) {
        return 'Yes';
    }
    return 'No';
}

function sortMyList(array $array, string $property)
{
    $key_values = array_column($array, $property);
    array_multisort($key_values, SORT_ASC, $array);
}


/*
* Checking session
* Checking the session state and expiry
* @return void
*/
function commonCheck(){
    userSessionState();
    userSessionOut();
}

/*
* Checking session, if session is expired, navigate to index* 
* @return void
*/
function userSessionState(){
    // If the user is not logged in redirect to the login page...
     if (!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
     }
}

/*
* Logout 
* @param null 
* @return void
*/

function userSessionOut(){
    $now = time(); // Checking the time now when home page starts.
    if ($now > $_SESSION['expire']) {
        unset($_SESSION["loggedin"]);
        unset($_SESSION["name"]);
        $_SESSION['logginError'] = TRUE;
        $_SESSION['logginErrorMessage'] = 'Your session has expired! Please login!';
        header('Location: index.php');
    }
}

function getHills(){
    $conn = connectToDB();
    $sql = "SELECT * FROM app_hills";
    return $conn->query($sql)->fetchAll();

}


function reportDownload($fromDate,$toDate,$hillsArr){
    $conn = connectToDB();

    $sql = "SELECT mv.mem_pass_number, mv.hill_id, mv.visited_on , ah.hill_name  FROM mem_visits mv LEFT JOIN app_hills ah ON ah.hill_id=mv.hill_id";
    if($fromDate!='' && $toDate!=''){
        $sql .= " WHERE mv.visited_on >= DATE '$fromDate' AND mv.visited_on <= DATE '$toDate'";
    }
    if(!empty($hillsArr)){
        $hillIds = implode("," , $hillsArr);
        $sql .= " AND mv.hill_id IN ($hillIds)";
    }
    return $conn->query($sql)->fetchAll();

}

function reportSearch($fromDate,$toDate,$hillsArr,$searchValue,$columnName,$columnSortOrder,$row,$rowperpage){
    $conn = connectToDB();
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
    $retArr['totalRecords'] = $records['allcount'];

// Total number of records with filtering

    $stmt = $conn->prepare($sqlAllcount.$searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $retArr['totalRecordwithFilter'] = $records['allcount'];

// Fetch records
    $stmt = $conn->prepare($sql.$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
    foreach ($searchArray as $key=>$search) {
        $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $retArr['empRecords'] = $stmt->fetchAll();
    return $retArr;

}