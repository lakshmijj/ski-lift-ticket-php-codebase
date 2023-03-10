<?php

include_once('includes/functions.php');

//start session if not started
session_start();

//get current request method from $_SERVER GLOBAL
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

$members = getMembers();

// sortMyList($members, 'mem_last_name');
function mySort(string $prop)
{
    global $members;
    $key_values = array_column($members, $prop);
    array_multisort($key_values, SORT_ASC, $members);
    // header('Refresh: 0');
}

$selectedIds = array();

if ($request_method == 'POST') {
    deleteSelectedMembers();
    header("refresh: 0");
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="./css/styles.css" rel="stylesheet" />
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5 center border rounded shadow">
        <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="py-4 px-3 needs-validation" novalidate>
            <div class="container mt-3 mb-3 row">
                <div class="col">
                    <h1 class="">Members</h1>
                    <p>Here you can view all existing member data. You can also perform create, update, and delete actions here.</p>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-secondary mx-3"><a href="addmember.php" class="text-white text-decoration-none">Add</a></button>

                    <a href="delete.php"><button class="btn btn-danger">Delete Selected</button></a>
                </div>
            </div>
            <table class="table table-striped mt-5">
                <thead>
                    <tr class="">
                        <th scope="col"><a>Member ID</a></th>
                        <th scope="col"><a>First</a></th>
                        <th scope="col">Last</th>
                        <th scope="col">Member Status</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Last Update</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($members as $member) : ?>
                        <tr>
                            <td> #<?php echo $member['mem_pass_number'] ?></td>
                            <td><?php echo $member['mem_first_name'] ?></td>
                            <td><?php echo $member['mem_last_name'] ?></td>
                            <td class="<?php echo ($member['mem_status'] === 'Active') ? 'text-success' : 'text-danger'; ?>"><?php echo $member['mem_status'] ?></td>
                            <td><?php echo $member['mem_payment_status'] ?></td>
                            <td><?php echo $member['mem_last_updated'] ?></td>
                            <td><a href="editmember.php?mem_pass_number=<?php echo $member['mem_pass_number']; ?>">Edit</a></td>
                            <td class="check"><input class="checkbox" name="checkbox[]" type="checkbox" value="<?php echo $member['mem_pass_number'] ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/validation.js"></script>
</body>

</html>