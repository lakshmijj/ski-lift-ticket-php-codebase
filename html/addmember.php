<?php
session_start();

include_once('includes/functions.php');

//get current request method from $_SERVER GLOBAL
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);


//form errors
$fields = [
    'mem_pass_number',
    'mem_first_name',
    'mem_last_name',
    'mem_status',
    'mem_payment_status',
    'mem_bar_code',
    'mem_last_updated',
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

if($request_method == 'POST') {
    if(empty($errors)) {
        addRegistration($_POST);
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
    <title>Add Member</title>
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
                    <h1>Add Member</h1>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-10 col-sm-8 col-md-6 rounded shadow border">
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="py-4 px-3 needs-validation" novalidate>
                        <div class="mb-3 has-validation">
                            <label for="mem_first_name">First Name <nobr class="text-danger">*</nobr></label>
                            <input type="text" id="mem_first_name" name="mem_first_name" placeholder="John" class="form-control <?php echo isInvalid('mem_first_name'); ?>" value="" required />

                            <div class="invalid-feedback">
                                Please enter a first name
                            </div>
                        </div>
                        <div class="mb-3 has-validation">
                            <label for="mem_last_name">Last Name <nobr class="text-danger">*</nobr></label>
                            <input type="text" id="mem_last_name" name="mem_last_name" placeholder="Wick" class="form-control <?php echo isInvalid('mem_last_name'); ?>" value="" required />

                            <div class="invalid-feedback">
                                Please enter a last name
                            </div>
                        </div>
                        <h5>Member Status<nobr class="text-danger">*</nobr>
                        </h5>
                        <div class="mb-3 has-validation">
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('mem_status'); ?>" type="radio" name="mem_status" id="mem_status-active" value="Active" required checked />
                                <label class="form-check-label" for="program1">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('mem_status'); ?>" type="radio" name="mem_status" id="mem_status-cancelled" value="Cancelled" required />
                                <label class="form-check-label" for="program2">Cancelled</label>

                                <div class="invalid-feedback">
                                    Please select a status
                                </div>
                            </div>
                        </div>
                        <h5>Payment Status <nobr class="text-danger">*</nobr>
                        </h5>

                        <div class="mb-3 has-validation">
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('mem_payment_status'); ?>" type="radio" name="mem_payment_status" id="payment-paid" value="paid" required checked />
                                <label class="form-check-label" for="mem_payment_status-paid">Paid</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('mem_payment_status'); ?>" type="radio" name="mem_payment_status" id="payment-pending" value="pending" required />
                                <label class="form-check-label" for="mem_payment_status-pending">Pending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('mem_payment_status'); ?>" type="radio" name="mem_payment_status" id="payment-refunded" value="refunded" required />
                                <label class="form-check-label" for="mem_payment_status-refunded">Refunded</label>

                                <div class="invalid-feedback">
                                    Please select an option
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mem_last_updated">Date last updated <nobr class="text-danger">*</nobr></label>
                            <input type="datetime-local" id="mem_last_updated" name="mem_last_updated" class="form-control" value="" />
                        </div>

                        <!-- HIDDEN INPUTS FOR BARCODE AND ID -->
                        <input type="text" id="mem_pass_number" name="mem_pass_number" class="form-control" value="10000" required />
                        <input type="hidden" id="mem_bar_code" name="mem_bar_code" class="form-control" value="10612587315464669148831491824678452609529717308215" required />

                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary" value="Register" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/validation.js"></script>
</body>

</html>