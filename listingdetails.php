<?php
session_start();

// Include config file
require_once "config.php";
include 'navbar2.php';

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if listing ID is provided in the URL
if (!isset($_GET['id']) || empty(trim($_GET["id"]))) {
    echo "Invalid request.";
    exit;
}

// Initialize variables
$listing_name = $listing_price = $listing_desc = $listing_image = $user_name = $status = "";
$email_address = $first_name = $last_name = "";
$profile_picture = $user_add = "";
$contact_num = "";

// Prepare a select statement to retrieve listing details
$sql_listing = "SELECT listing_name, listing_price, listing_desc, listing_image, user_name, status FROM listing WHERE listing_id = ?";

if ($stmt_listing = $mysqli->prepare($sql_listing)) {
    // Bind variables to the prepared statement as parameters
    $stmt_listing->bind_param("i", $param_id);

    // Set parameters
    $param_id = trim($_GET["id"]);

    // Attempt to execute the prepared statement
    if ($stmt_listing->execute()) {
        // Store result
        $stmt_listing->store_result();

        // Check if listing exists
        if ($stmt_listing->num_rows == 1) {
            // Bind result variables
            $stmt_listing->bind_result($listing_name, $listing_price, $listing_desc, $listing_image, $user_name, $status);
            $stmt_listing->fetch();
        } else {
            echo "Listing not found.";
            exit;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_listing->close();
}

// Prepare a select statement to retrieve user details
$sql_user = "SELECT email_address, first_name, last_name, profile_picture, user_add, contact_num FROM users WHERE user_name = ?";

if ($stmt_user = $mysqli->prepare($sql_user)) {
    // Bind variables to the prepared statement as parameters
    $stmt_user->bind_param("s", $param_user_name);

    // Set parameters
    $param_user_name = $user_name;

    // Attempt to execute the prepared statement
    if ($stmt_user->execute()) {
        // Store result
        $stmt_user->store_result();

        // Check if user exists
        if ($stmt_user->num_rows == 1) {
            // Bind result variables
            $stmt_user->bind_result($email_address, $first_name, $last_name, $profile_picture, $user_add, $contact_num);
            $stmt_user->fetch();
        } else {
            echo "User not found.";
            exit;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt_user->close();
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing Details</title>
    <link rel="stylesheet" href="listingdetails.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<main>
    <div class="nero">
      <div class="nero__heading">
        <span class="nero__bold">Product </span>Details
      </div>
      <p class="nero__text">
      </p>
    </div>
  </main>
    <div class="container">
            <img src="<?php echo htmlspecialchars($listing_image); ?>" alt="Listing Image" class="listing-image">
                <div class="box"><!-- box Starts -->
                <h2 class="text-center"><?php echo htmlspecialchars($listing_name); ?></h2>
                <br>
                <p><?php echo htmlspecialchars($listing_desc); ?></p>
                <p>Price: ₱<?php echo htmlspecialchars($listing_price); ?></p>
                <p>Status: <?php echo htmlspecialchars($status); ?></p>
                <p>Uploaded by: <?php echo htmlspecialchars($user_name); ?></p>
                </div>
        <div class="box same-height"><!-- box same-height Starts -->
        <h3 class="text-center"> Seller information </h3>
            <?php if (!empty($profile_picture)) : ?>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
            <?php endif; ?>
            <p>User Name: <?php echo htmlspecialchars($user_name); ?></p>
            <p>Email Address: <?php echo htmlspecialchars($email_address); ?></p>
            <p>Contact Number: <?php echo htmlspecialchars($contact_num); ?></p>
            <p>First Name: <?php echo htmlspecialchars($first_name); ?></p>
            <p>Last Name: <?php echo htmlspecialchars($last_name); ?></p>
            <p>User Address: <?php echo htmlspecialchars($user_add); ?></p>
        </div>
        </div>
    </div>
</body>

</html>
