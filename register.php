<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email_address = $user_name = $first_name = $last_name = $dob = $pass = "";
$email_address_err = $user_name_err = $first_name_err = $last_name_err = $dob_err = $pass_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email address
    $input_email_address = trim($_POST["email_address"]);
    if (empty($input_email_address)) {
        $email_address_err = "Please enter an email address.";
    } else {
        $email_address = $input_email_address;
    }

    // Validate other fields
    $user_name = trim($_POST["user_name"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $dob = trim($_POST["dob"]);
    $pass = trim($_POST["pass"]);

    // Check if all required fields are not empty
    if (empty($email_address_err) && empty($user_name_err) && empty($first_name_err) && empty($last_name_err) && empty($dob_err) && empty($pass_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (email_address, user_name, first_name, last_name, dob, pass) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $email_address, $user_name, $first_name, $last_name, $dob, $pass);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("location: listing.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPS - Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


</head>

<body>
<div class="card">
    <h1>Register</h1>
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="flex-column">
                <label>Email</label>
            </div>
            <div class="inputForm">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 32 32" height="20">
                    <g data-name="Layer 3" id="Layer_3">
                        <path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path>
                    </g>
                </svg>
                <input placeholder="Enter your Email" class="input" type="text" name="email_address" value="<?php echo $email_address; ?>">
                <span class="invalid-feedback"><?php echo $email_address_err; ?></span>
            </div>
        <div class="flex-column">
            <label>User Name</label>
        </div>
        <div class="inputForm">
            <input placeholder="Enter your User Name" class="input" type="text" name="user_name" value="<?php echo $user_name; ?>">
            <span class="invalid-feedback"><?php echo $user_name_err; ?></span>
        </div>
        <div class="flex-column">
            <label>First Name</label>
        </div>
        <div class="inputForm">
            <input placeholder="Enter your First Name" class="input" type="text" name="first_name" value="<?php echo $first_name; ?>">
            <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
        </div>
        <div class="flex-column">
            <label>Last Name</label>
        </div>
        <div class="inputForm">
            <input placeholder="Enter your Last Name" class="input" type="text" name="last_name" value="<?php echo $last_name; ?>">
            <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
        </div>
        <div class="flex-column">
            <label>Birthday</label>
        </div>
        <div class="inputForm">
            <input class="input" type="date" name="dob" value="<?php echo $dob; ?>">
            <span class="invalid-feedback"><?php echo $dob_err; ?></span>
        </div>
        <div class="flex-column">
            <label>Password</label>
        </div>
        <div class="inputForm">
            <input placeholder="Enter your Password" class="input" type="password" name="pass" value="<?php echo $pass; ?>">
            <span class="invalid-feedback"><?php echo $pass_err; ?></span>
        </div>
        <div class="flex-row">
                <input type="submit" class="button-submit" value="Submit">
                <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
            </div>
    </form>
</body>

</html>
