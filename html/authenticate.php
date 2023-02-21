<?php
session_start();

include_once('includes/functions.php');

//get current request method from $_SERVER GLOBAL
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

//form errors
$fields = [
    'mem_pass_number',
];

$optional = [];
$values = [];
$errors = [];
$id;
$member;


if (isset($_POST['mem_pass_number'])) {
    $id = $_POST['mem_pass_number'];
    $member = getMember($id)[0];
}

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

if ($request_method == 'POST') {
    if (empty($errors)) {

    } else {
        var_dump($errors);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="./css/styles.css" rel="stylesheet" />
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="min-vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h1>Verify and Sign in Member</h1>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-10 col-sm-8 col-md-6 rounded shadow border">
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="py-4 px-3 needs-validation" novalidate>
                        <div class="mb-3 has-validation">
                            <label for="mem_pass_number">Pass Number </label>
                            <input type="text" id="mem_pass_number" name="mem_pass_number" class="form-control <?php echo isInvalid('mem_pass_number'); ?>" required />

                            <div class="invalid-feedback">
                                Please enter a pass number
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="submit" id="btnSubmit" class="btn btn-primary" value="Verify Membership" />
                        </div>



                    </form>
                    <div class="mb-3">
                        <?php
                            if (isset($_POST['mem_pass_number'])) {
                                if (memberValid($id)) {
                                    echo '<h2 class="text-success">VALID</h2>';
                                    echo '<h4>Pass #: ' . $member['mem_pass_number'] . '</h4>';
                                    echo '<h4>First Name: ' . $member['mem_first_name'] . '</h4>';
                                    echo '<h4>Last Name: ' . $member['mem_last_name'] . '</h4>';
                                    echo '<h4>Membership Status: ' . $member['mem_status'] . '</h4>';
                                    echo '<h4>Payment Status: ' . $member['mem_payment_status'] . '</h4>';

                                } else {
                                    echo '<h2 class="text-danger">INVALID</h2>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/validation.js"></script>
</body>

</html>