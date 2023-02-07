<?php
session_start();

$errors = [];
$saved = [];

if (isset($_SESSION['errors'])) {
    //save form errors session array to var
    $errors = $_SESSION['errors'];

    //unset session
    unset($_SESSION['errors']);
}

if (isset($_SESSION['saved'])) {
    //save form errors session array to var
    $saved = $_SESSION['saved'];

    var_dump($saved);
    var_dump(in_array($saved[$name], $saved));

    //unset session
    unset($_SESSION['saved']);
}

/**
 * returns css class for invalid input
 * @param string $name
 * @return mixed string or null
 */
function isInvalid(string $name)
{
    global $errors;
    return (in_array($name, $errors)) ? 'is-invalid' : null;
}

/**
 * 
 */
function isSaved(string $name)
{
    global $saved;
    if (isset($saved[$name])) {
        return (in_array($saved[$name], $saved)) ? $saved[$name] : null;
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
</head>

<body>
    <div class="min-vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h1>Add Member</h1>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-10 col-sm-8 col-md-6 rounded shadow border">
                    <form method="post" action="register-post.php" class="py-4 px-3 needs-validation" novalidate>
                        <div class="mb-3 has-validation">
                            <label for="fname">First Name <nobr class="text-danger">*</nobr></label>
                            <input type="text" id="fname" name="fname" placeholder="John" class="form-control <?php echo isInvalid('fname'); ?>" value="<?php echo isSaved('fname'); ?>" required />

                            <div class="invalid-feedback">
                                Please enter a first name
                            </div>
                        </div>
                        <div class="mb-3 has-validation">
                            <label for="lname">Last Name <nobr class="text-danger">*</nobr></label>
                            <input type="text" id="lname" name="lname" placeholder="Wick" class="form-control <?php echo isInvalid('lname'); ?>" value="<?php echo isSaved('lname'); ?>" required />

                            <div class="invalid-feedback">
                                Please enter a last name
                            </div>
                        </div>
                        <h5>Member Status<nobr class="text-danger">*</nobr>
                        </h5>
                        <div class="mb-3 has-validation">
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('program'); ?>" type="radio" name="program" id="program1" value="IT Web Programming" required checked />
                                <label class="form-check-label" for="program1">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('program'); ?>" type="radio" name="program" id="program2" value="IT Systems and Security" required />
                                <label class="form-check-label" for="program2">Cancelled</label>

                                <div class="invalid-feedback">
                                    Please select a program
                                </div>
                            </div>
                        </div>
                        <h5>Are you a returning student? <nobr class="text-danger">*</nobr>
                        </h5>
                        
                        <div class="mb-3 has-validation">
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('returnng'); ?>" type="radio" name="payment-status" id="payment-paid" value="paid" required checked />
                                <label class="form-check-label" for="returning-yes">Paid</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('returning'); ?>" type="radio" name="payment-status" id="payment-pending" value="pending" required />
                                <label class="form-check-label" for="returning-yes">Pending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?php echo isInvalid('returning'); ?>" type="radio" name="payment-status" id="payment-refunded" value="refunded" required />
                                <label class="form-check-label" for="returning-no">Refunded</label>

                                <div class="invalid-feedback">
                                    Please select an option
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="fname">Date last updated <nobr class="text-danger">*</nobr></label>
                            <input type="date" id="fname" name="fname" class="form-control" required />
                        </div>
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