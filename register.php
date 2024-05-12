<?php
// Include config file
require_once "config.php";
include 'navbar.php';

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
    <link rel="stylesheet" href="register.css">


</head>

<body>
    <div class="container">
        <div class="form-container">
                <h1>Sign up</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" name="email_address" class="form-control <?php echo (!empty($email_address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email_address; ?>">
                    <span class="invalid-feedback"><?php echo $email_address_err; ?></span>
                </div>
                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" name="user_name" class="form-control <?php echo (!empty($user_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $user_name; ?>">
                    <span class="invalid-feedback"><?php echo $user_name_err; ?></span>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                    <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                    <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Birthday</label>
                    <input type="date" name="dob" class="form-control <?php echo (!empty($dob_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dob; ?>">
                    <span class="invalid-feedback"><?php echo $dob_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="pass" class="form-control <?php echo (!empty($pass_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pass; ?>">
                    <span class="invalid-feedback"><?php echo $pass_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
</body>

</html>
