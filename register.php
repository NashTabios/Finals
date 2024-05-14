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
        <div class="formcontainert">
            <h1></h1>
            <p></p>
        </div>
        <div class="form-container">
            <h1>Sign up</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="userdetails">
                        <div class="form-group">
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                            <div class="label">First name</div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                            <div class="label">Last name</div>
                        </div>
                        <div class="bdform">
                            <input type="date" name="dob" class="form-control <?php echo (!empty($dob_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dob; ?>">
                            <div class="label">Birthdate</div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="email_address" class="form-control <?php echo (!empty($email_address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email_address; ?>">
                            <div class="label">Email address</div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="user_name" class="form-control <?php echo (!empty($user__name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $user_name; ?>">
                            <div class="label">Username</div>
                        </div>
                        <div class="form-group">
                            <input type="password" name="pass" class="form-control <?php echo (!empty($pass_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pass; ?>">
                            <div class="label">New password</div>
                        </div>
                    </div>
                    <div class="btn">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </form>
        </div>
    </div>
    
</body>

</html>
