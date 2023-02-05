<?php

include_once('includes/functions.php');

//get current request method from $_SERVER GLOBAL
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

$registrations = getRegistrations();

$selected = [];

//if post and no errors add Registration
if($request_method === "POST"){
    if(!empty($_POST['selected'])){
       deleteRegistrations($_POST['selected']);
    }
}


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="./css/styles.css" rel="stylesheet" />
</head>

<body>

    <div class="container py-4 px-3 mx-auto">
        <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="py-4 px-3" novalidate>
         <input type="submit" class="btn btn-primary" value="Delete Selected" />
        <table class="table table-striped align-middle">
            <thead>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Program</th>
                <th>Returning?</th>
            </thead>
            <tbody>
               
                <?php foreach ($registrations as $registration) : ?>
                    <tr>
                        <td><input type="checkbox" name='selected[]' value="<?php echo $registration['id']; ?>"></td>
                        <td><?php echo $registration['fname']; ?></td>
                        <td><?php echo $registration['lname']; ?></td>
                        <td><?php echo $registration['email']; ?></td>
                        <td><?php echo $registration['program']; ?></td>
                        <td><?php echo niceBool(intval($registration['returning'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </form>

    </div>

</body>

</html>