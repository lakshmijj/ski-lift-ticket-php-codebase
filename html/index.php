<?php
//start session if not started
session_start();

include_once('includes/functions.php');

//get current request method from $_SERVER GLOBAL
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);


// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if($request_method === "POST"){
    if ( !isset($_POST['username'], $_POST['password']) ) {
        // Could not get the data that should have been sent.
        exit('Please fill both the username and password fields!');
    }else{

        authenticate($_POST);
    }
}


//form errors
$fields = [
    'fname',
    'lname',
    'email',
    'program',
    'returning',
];
$optional = [];
$values = [];
$errors = [];

// loop through fields
foreach ($fields as $field) {

    //if post, check for empty fields
    if ($request_method === 'POST') {
        //if field was submitted and is empty, add to errors array
        if (empty($_POST[$field]) && !in_array($field, $optional)) {
            $errors[] = $field;
        }
    }

    // if field was submitted save to $values array
    if (isset($_POST[$field])) {
        $values[$field] = $_POST[$field];
    } else {
        // field was not submitted so set to null
        $values[$field] = null;
    }
}

/**
 * returns field is-invalid css class
 * @param string $field
 * @param array $errors
 * @return mixed string or null
 */
function isInvalid(string $field)
{
    global $errors;
    return (in_array($field, $errors)) ? 'is-invalid' : null;
}

//if post and no errors add Registration
if($request_method === "POST"){
    if(empty($errors)){
        addRegistration($_POST);
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="./css/styles.css" rel="stylesheet" />
</head>

<body>
    <div class="login">
			<h1>Login</h1>
			<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/validation.js"></script>
</body>

</html>