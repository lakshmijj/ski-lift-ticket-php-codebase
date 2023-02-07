<?php
include_once('config.php');

/*
* returns a connection to the database
* @return mixed
*/

function connectToDB(){
    $db = config('db');
    $servername = $db['servername'];
    $username = $db['username'];
    $password = $db['password'];
    $dbName = $db['dbName'];

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;

    }catch(PDOException $e){
        echo 'Connection Failed: '.$e->getMessage();
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
        $_SESSION['logginError'] = FALSE;
        $_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
        return header("Location: home.php");
    }else{
        $_SESSION['logginError'] = TRUE;
        return header("Location: index.php");
    }
   
}

/*
* save new registration
* @param array $data
* @return void
*/
function addRegistration(array $data){
    $conn = connectToDB();
    // if(isset($data['returning'])){
    //     $data['returning'] = ($data['returning'] === 'Yes') ? 1 : 0;
    // }

    if($conn){
        $sql = "INSERT INTO app_members (mem_pass_number, mem_first_name, mem_last_name, mem_status, mem_payment_status, mem_bar_code, mem_last_updated) VALUES (:mem_pass_number, :mem_first_name, :mem_last_name, :mem_status, :mem_payment_status, :mem_bar_code, :mem_last_updated)";

        $conn->prepare($sql)->execute($data);
    }

    //redirect back to index
    return header("Location: existingmembers.php");
}

/*
* return all members
* @return array
*/
function getMembers(){
    $conn = connectToDB();
    $data = [];
    if($conn){
        $data = $conn->query("SELECT * FROM app_members")->fetchAll();
    }
    return $data;
}

/*
* Delete registrations
* @param array $keys
* @return void
*/
function deleteRegistrations($keys){
    $conn = connectToDB();
    $ids = implode("','", $keys);
    // sql to delete a record
    $sql = "DELETE FROM registration WHERE id IN ('".$ids."')";
    $conn->query($sql);
    return header("Location: registrations.php");
}

function niceBool(int $value){
    if($value === 1){
        return 'Yes';
    }
    return 'No';
}

function sortMyList(array $array, string $property) {
    $key_values = array_column($array, $property);
    array_multisort($key_values, SORT_ASC, $array);
}