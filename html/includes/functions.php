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

function authenticate(array $data)
{
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

    $sql = "SELECT COUNT(*) FROM app_users WHERE user_name = '" . $data['username'] . "' AND user_pwd = '" . $data['password'] . "'";

    $result = $conn->query($sql)->fetchAll();
    if (isset($result) && $result[0][0] > 0) {
        $_SESSION['logginError'] = FALSE;
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        return header("Location: home.php");
    } else {
        $_SESSION['logginError'] = TRUE;
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