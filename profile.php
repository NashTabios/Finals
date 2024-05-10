<?php
session_start();

// Include config file
require_once "config.php";

// Check if the user is not logged in, then redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

// Define variables and initialize with empty values
$user_name = $email_address = $first_name = $last_name = $dob = "";
$user_name_err = $email_address_err = $first_name_err = $last_name_err = $dob_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email address
    $input_email_address = trim($_POST["email_address"]);
    if (empty($input_email_address)) {
        $email_address_err = "Please enter an email address.";
    } else {
        $email_address = $input_email_address;
    }

    // Validate first name
    $input_first_name = trim($_POST["first_name"]);
    if (empty($input_first_name)) {
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = $input_first_name;
    }

    // Validate last name
    $input_last_name = trim($_POST["last_name"]);
    if (empty($input_last_name)) {
        $last_name_err = "Please enter your last name.";
    } else {
        $last_name = $input_last_name;
    }

    // Validate date of birth
    $input_dob = trim($_POST["dob"]);
    if (empty($input_dob)) {
        $dob_err = "Please enter your date of birth.";
    } else {
        $dob = $input_dob;
    }

    // Check input errors before updating the database
    if (empty($email_address_err) && empty($first_name_err) && empty($last_name_err) && empty($dob_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET email_address=?, first_name=?, last_name=?, dob=? WHERE user_name=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_email_address, $param_first_name, $param_last_name, $param_dob, $param_user_name);

            // Set parameters
            $param_email_address = $email_address;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_dob = $dob;
            $param_user_name = $_SESSION["user_name"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to profile page after updating
                header("location: profile.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
} else {
    // Retrieve user information from the database
    $sql = "SELECT email_address, first_name, last_name, dob FROM users WHERE user_name = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the user's username as a parameter
        $stmt->bind_param("s", $param_user_name);

        // Set parameters
        $param_user_name = $_SESSION["user_name"];

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if user exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($email_address, $first_name, $last_name, $dob);
                $stmt->fetch();
                $user_name = $_SESSION["user_name"];
            } else {
                echo "User not found.";
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            exit;
        }

        // Close statement
        $stmt->close();
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
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function toggleEdit() {
            var elements = document.getElementsByClassName("editable");
            for (var i = 0; i < elements.length; i++) {
                elements[i].readOnly = !elements[i].readOnly;
                elements[i].classList.toggle("form-control-plaintext");
                elements[i].classList.toggle("form-control");
            }
            document.getElementById("editButton").classList.toggle("d-none");
            document.getElementById("saveButton").classList.toggle("d-none");
        }
    </script>
    <style>
    .row {
            margin-right: 100px;
        }
    </style>
</head>



<div class="container"> 
    <div class="row">
            <div class="jumbotron p-3 mb-3" style="display: flex;justify-content: center;width: 28%;border-radius: 50px;margin: 0 auto;">
                <div class="user-info">
                    <img class="rounded-circle mb-3 bg-dark" src="profilePic.jpg" style="width:215px;height:215px;padding:1px;">
                    <ul class="meta list list-unstyled" style="text-align:center;">
                        <li class="username my-2">@<?php echo htmlspecialchars($user_name); ?></a></li>
                        <li class="name"><?php echo $first_name_err." ".$last_name_err; ?>
                            <label class="label label-info">User</label>
                        </li>
                        <li class="email"><?php echo $email_address_err ?></li>
                        <li class="my-2"><a href="partials/_logout.php"><button class="btn btn-secondary" style="font-size: 15px;padding: 3px 8px;">Logout</button></a></li>
                    </ul>
                </div>
            </div>
            <div class="content-panel mb-3" style="display: flex;justify-content: center;">
                <div class="border p-3" style="border: 2px solid rgba(0, 0, 0, 0.1);border-radius: 1.1rem;background-color: aliceblue;">
                    <h2 class="title text-center">My Profile</h2>
                
                    <form action="partials/_manageProfile.php" method="post">
                        <div class="form-group">
                            <b><label for="username">Username:</label></b>
                            <input class="form-control" id="username" name="username" type="text" disabled value="<?php echo htmlspecialchars($user_name); ?>">
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-6">
                            <b><label for="firstName">First Name:</label></b>
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required value="<?php echo $firstName ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <b><label for="lastName">Last Name:</label></b>
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last name" required value="<?php echo $lastName ?>">
                        </div>
                        </div>
                        <div class="form-group">
                            <b><label for="email">Email:</label></b>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required value="<?php echo $email ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group  col-md-6">
                                <b><label for="phone">Phone No:</label></b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon">+63</span>
                                    </div>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Your Phone Number" required pattern="[0-9]{10}" maxlength="10" value="<?php echo $phone ?>">
                                </div>
                            </div>
                            <div class="form-group  col-md-6">
                                <b><label for="password">Password:</label></b>    
                                <input class="form-control" id="password" name="password" placeholder="Enter Password" type="password" required minlength="6" maxlength="21" data-toggle="password">
                            </div>
                        </div>
                        <button type="submit" name="updateProfileDetail" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<div class="container">
        <h1>User Profile</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="user_name" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email_address" class="form-control-plaintext editable" value="<?php echo htmlspecialchars($email_address); ?>" readonly>
                <span class="text-danger"><?php echo $email_address_err; ?></span>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control-plaintext editable" value="<?php echo htmlspecialchars($first_name); ?>" readonly>
                <span class="text-danger"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control-plaintext editable" value="<?php echo htmlspecialchars($last_name); ?>" readonly>
                <span class="text-danger"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control-plaintext editable" value="<?php echo htmlspecialchars($dob); ?>" readonly>
                <span class="text-danger"><?php echo $dob_err; ?></span>
            </div>
            <div class="form-group">
                <button type="button" id="editButton" class="btn btn-primary" onclick="toggleEdit()">Edit</button>
                <button type="submit" id="saveButton" class="btn btn-success d-none">Save Changes</button>
                <a href="listing.php" class="btn btn-secondary">Back to Listings</a>
            </div>
        </form>
    </div>