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
                elements[i].classList.toggle("form-control");
                elements[i].classList.toggle("form-control");
            }
            document.getElementById("editButton").classList.toggle("d-none");
            document.getElementById("saveButton").classList.toggle("d-none");
        }
    </script>
    <style>
    .container {
        max-width: 1000px;
        margin-top: 75px;
        background: aliceblue;
        padding: 40px;
        border-radius: 20px; /* Adjust the value to control the curve */
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2); /* Add box shadow */
    }
    .row {
        display: flex; 
        justify-content: center;     
    }

    .user-content{
        display: flex;
        justify-content: center;
        
    }
    .user-img{
        margin-right: 50px;
        display: flex;
        align-items: center;
    }
    .rounded-circle {
        border-radius: 50%; 
        margin-bottom: 1rem; 
        
        width: 250px;
        height: 250px;
        padding: 1px;
    }
    .user-info{
        border-radius: 20px;
        background-color: aliceblue;
        padding: 30px;
        width: 550px; /* Adjust the width if needed */
    }
    </style>
</head>

<body>
<div class="container"> 
    <div class="row">
            <div class="user-content mb-3">
                <div class="user-img">
                    <img class="rounded-circle" src="profilePic.jpg" alt="Profile Picture">
                </div>
               
                <div class="user-info">
                    <h2 class="title text-center">User Profile</h2>
                
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <b><label for="username">Username:</label></b>
                            <input type="text" name="user_name" class="form-control" disabled value="<?php echo htmlspecialchars($user_name); ?>" readonly>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-6">
                            <b><label for="firstName">First Name:</label></b>
                            <input type="text" name="first_name" class="form-control editable" value="<?php echo htmlspecialchars($first_name); ?>" readonly>
                            <span class="text-danger"><?php echo $first_name_err; ?></span>
                        </div>
                        <div class="form-group col-md-6">
                            <b><label for="lastName">Last Name:</label></b>
                            <input type="text" name="last_name" class="form-control editable" value="<?php echo htmlspecialchars($last_name); ?>" readonly>
                            <span class="text-danger"><?php echo $last_name_err; ?></span>
                        </div>
                        </div>
                        <div class="form-group">
                            <b><label for="email">Email:</label></b>
                            <input type="email" name="email_address" class="form-control editable" value="<?php echo htmlspecialchars($email_address); ?>" readonly>
                            <span class="text-danger"><?php echo $email_address_err; ?></span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <b><label>Date of Birth</label></b>
                                <input type="date" name="dob" class="form-control editable" value="<?php echo htmlspecialchars($dob); ?>" readonly>
                                <span class="text-danger"><?php echo $dob_err; ?></span>
                            </div>
                        </div>
                            <button type="button" id="editButton" class="btn btn-primary" onclick="toggleEdit()">Edit</button>
                            <button type="submit" id="saveButton" class="btn btn-success d-none">Save Changes</button>
                            <a href="listing.php" class="btn btn-secondary">Back to Listings</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
