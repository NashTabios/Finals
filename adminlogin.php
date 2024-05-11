<?php
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$admin_email = $admin_pass = "";
$admin_email_err = $admin_pass_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email address
    $input_admin_email = trim($_POST["admin_email"]);
    if (empty($input_admin_email)) {
        $admin_email_err = "Please enter an email address.";
    } else {
        $admin_email = $input_admin_email;
    }

    // Validate password
    $input_admin_pass = trim($_POST["admin_pass"]);
    if (empty($input_admin_pass)) {
        $admin_pass_err = "Please enter your password.";
    } else {
        $admin_pass = $input_admin_pass;
    }

    // Check input errors before checking credentials
    if (empty($admin_email_err) && empty($admin_pass_err)) {
        // Prepare a select statement
        $sql = "SELECT admin_id, admin_email, admin_name, admin_pass FROM admins WHERE admin_email = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_admin_email);

            // Set parameters
            $param_admin_email = $admin_email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email address exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($admin_id, $admin_email, $admin_name, $hashed_pass);
                    if ($stmt->fetch()) {
                        if ($admin_pass === $hashed_pass) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["admin_id"] = $admin_id;
                            $_SESSION["admin_email"] = $admin_email;
                            $_SESSION["admin_name"] = $admin_name;

                            // Redirect user to admin dashboard page
                            header("location: admindashboard.php");
                            exit;
                        } else {
                            // Display an error message if password is not valid
                            $admin_pass_err = "The password you entered is not valid.";
                        }
                    }
                } else {
                    // Display an error message if email address doesn't exist
                    $admin_email_err = "No account found with that email address.";
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
    <title>REPS - Admin Page Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <h1>REPS - Admin</h1>
    <div class="login-container">
        <h2>Sign In</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="admin_email">Email Address</label>
                <input type="text" id="admin_email" name="admin_email" class="form-control <?php echo (!empty($admin_email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $admin_email; ?>">
                <span class="invalid-feedback"><?php echo $admin_email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" id="admin_pass" name="admin_pass" class="form-control <?php echo (!empty($admin_pass_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $admin_pass_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</body>

</html>
