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
$user_name = $email_address = $first_name = $last_name = $dob = $profile_picture = $user_add = "";
$user_name_err = $email_address_err = $first_name_err = $last_name_err = $dob_err = $profile_picture_err = $user_add_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate email address
    if (empty(trim($_POST["email_address"]))) {
        $email_address_err = "Please enter your email address.";
    } else {
        $email_address = trim($_POST["email_address"]);
    }

    // Validate date of birth
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter your date of birth.";
    } else {
        $dob = trim($_POST["dob"]);
    }

    // Validate user address
    if (empty(trim($_POST["user_add"]))) {
        $user_add_err = "Please enter your address.";
    } else {
        $user_add = trim($_POST["user_add"]);
    }

    // Check if a file was uploaded
    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $profile_picture_err = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            $profile_picture_err = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $profile_picture_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $profile_picture_err = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file;
            } else {
                $profile_picture_err = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Check input errors before updating the database
    if (empty($first_name_err) && empty($last_name_err) && empty($email_address_err) && empty($dob_err) && empty($user_add_err) && empty($profile_picture_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email_address = ?, dob = ?, user_add = ?, profile_picture = ? WHERE user_name = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("sssssss", $param_first_name, $param_last_name, $param_email_address, $param_dob, $param_user_add, $param_profile_picture, $param_user_name);

            // Set parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email_address = $email_address;
            $param_dob = $dob;
            $param_user_add = $user_add;
            $param_profile_picture = $profile_picture;
            $param_user_name = $_SESSION["user_name"];

            // Attempt to execute the statement
            if ($stmt->execute()) {
                // Redirect to profile page after successful update
                header("location: profile.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }
}

// Retrieve user information from the database
$sql = "SELECT email_address, first_name, last_name, dob, profile_picture, user_add FROM users WHERE user_name = ?";
if ($stmt = $mysqli->prepare($sql)) {
    // Bind parameters
    $stmt->bind_param("s", $param_user_name);
    $param_user_name = $_SESSION["user_name"];

    // Attempt to execute the statement
    if ($stmt->execute()) {
        // Store result
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($email_address, $first_name, $last_name, $dob, $profile_picture, $user_add);
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
            document.getElementById("profilePicture").classList.toggle("d-none");
        }
    </script>
    <style>
        .container {
            max-width: 1000px;
            margin-top: 75px;
            margin-bottom:75px;
            background: aliceblue;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            
        }

        .row {
            display: flex;
            justify-content: center;
        }

        .user-content {
            display: flex;
            justify-content: center;
        }

        .user-img {
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

        .user-info {
            border-radius: 20px;
            background-color: aliceblue;
            padding: 30px;
            width: 550px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="user-content mb-3">
                <div class="user-img">
                    <img class="rounded-circle" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                </div>

                <div class="user-info">
                    <h2 class="title text-center">User Profile</h2>
                    <br>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                        <div class="form-group">
                            <b><label for="userAddress">Address:</label></b>
                            <input type="text" name="user_add" class="form-control editable" value="<?php echo htmlspecialchars($user_add); ?>" readonly>
                            <span class="text-danger"><?php echo $user_add_err; ?></span>
                        </div>
                        <div class="form-group d-none" id="profilePicture">
                            <b><label for="profilePicture">Profile Picture:</label></b><br>
                            <input type="file" name="profile_picture" accept="image/*">
                            <span class="text-danger"><?php echo $profile_picture_err; ?></span>
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
