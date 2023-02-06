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

    if(isset($data['returning'])){
        $data['returning'] = ($data['returning'] === 'Yes') ? 1 : 0;
    }

    if($conn){
        $sql = "INSERT INTO registration (fname, lname, email, program, returning) VALUES (:fname, :lname, :email, :program, :returning)";
        //$conn->prepare($sql)->execute([
        //    'fname'=> $data['fname'],
        //    'lname' => $data['lname'],

        //]);
        $conn->prepare($sql)->execute($data);
    }

    //redirect back to index
    return header("Location: index.php");
}

/*
* return all registrations
* @return array
*/
function getRegistrations(){
    $conn = connectToDB();
    $data = [];
    if($conn){
        $data = $conn->query("SELECT * FROM registration")->fetchAll();
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

?>