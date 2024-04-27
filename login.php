<?php
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email_address = $pass = "";
$email_address_err = $pass_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email address
    $input_email_address = trim($_POST["email_address"]);
    if (empty($input_email_address)) {
        $email_address_err = "Please enter an email address.";
    } else {
        $email_address = $input_email_address;
    }

    // Validate password
    $input_pass = trim($_POST["pass"]);
    if (empty($input_pass)) {
        $pass_err = "Please enter your password.";
    } else {
        $pass = $input_pass;
    }

    // Check input errors before checking credentials
    if (empty($email_address_err) && empty($pass_err)) {
        // Prepare a select statement
        $sql = "SELECT id, email_address, pass FROM users WHERE email_address = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email_address);

            // Set parameters
            $param_email_address = $email_address;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email address exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $email_address, $hashed_pass);
                    if ($stmt->fetch()) {
                        if (password_verify($pass, $hashed_pass)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email_address"] = $email_address;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else {
                            // Display an error message if password is not valid
                            $pass_err = "The password you entered is not valid.";
                        }
                    }
                } else {
                    // Display an error message if email address doesn't exist
                    $email_address_err = "No account found with that email address.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>REPS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Email Address</label>
            <input type="text" name="email_address" class="form-control <?php echo (!empty($email_address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email_address; ?>">
            <span class="invalid-feedback"><?php echo $email_address_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="pass" class="form-control <?php echo (!empty($pass_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $pass_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>

</body>

</html>