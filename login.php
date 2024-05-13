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
        $sql = "SELECT id, email_address, user_name, pass FROM users WHERE email_address = ?";

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
                    $stmt->bind_result($id, $email_address, $username, $hashed_pass);
                    if ($stmt->fetch()) {
                        if ($pass == $hashed_pass) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email_address"] = $email_address;
                            $_SESSION["user_name"] = $username;

                            // Redirect user to listing page
                            header("location: listing.php");
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
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="login-container">
        <h2>Sign In</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email_address">Email Address</label>
                <input type="text" id="email_address" name="email_address" class="form-control
                <?php echo (!empty($email_address_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $email_address; ?>" placeholder="Enter your Email">
                <span class="invalid-feedback"><?php echo $email_address_err; ?></span>
                
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" class="form-control
                <?php echo (!empty($pass_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter your Password">
                <span class="invalid-feedback"><?php echo $pass_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>or</p>
            <div class="flex-row">
                <button class="btn google">
                <svg
                    xml:space="preserve"
                    style="enable-background:new 0 0 512 512;"
                    viewBox="0 0 512 512"
                    y="0px"
                    x="0px"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns="http://www.w3.org/2000/svg"
                    id="Layer_1"
                    width="20"
                    version="1.1"

                        ><path
                        d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256
                    c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456
                    C103.821,274.792,107.225,292.797,113.47,309.408z"
                        style="fill:#FBBB00;"
                        ></path>
                        <path
                        d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451
                    c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535
                    c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"
                        style="fill:#518EF8;"
                        ></path>
                        <path
                        d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512
                    c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771
                    c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"
                        style="fill:#28B446;"
                        ></path>
                        <path
                        d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012
                    c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0
                    C318.115,0,375.068,22.126,419.404,58.936z"
                        style="fill:#F14336;"
                        ></path>
                    </svg>
                    Google
                </button>
                
                <button class="btn facebook">
                <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="#1877F2"
                width="20"
                height="20"
            >
                <path
                    fill-rule="evenodd"
                    d="M20.5 2H3.5C2.121 2 1 3.121 1 4.5v15c0 1.379 1.121 2.5 2.5 2.5h9.428v-7.745h-2.607v-3.024h2.607V9.834c0-2.584 1.577-3.993 3.882-3.993 1.1 0 2.048.082 2.318.118v2.7h-1.59c-1.248 0-1.493.593-1.493 1.467v1.927h2.987l-.39 3.024h-2.597V22H20.5c1.379 0 2.5-1.121 2.5-2.5v-15C23 3.121 21.879 2 20.5 2z"
                    clip-rule="evenodd"
                />
            </svg>
                Facebook
            </button>

            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>

</html>

