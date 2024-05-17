<?php
session_start();

// Include config file
require_once "config.php";
include 'navbar2.php';

// Check if the user is logged in, then retrieve their username
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $user_name = $_SESSION["user_name"];
}

// Logout logic
if (isset($_GET["logout"]) && $_GET["logout"] == 1) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to index page after logout
    header("location: index.php");
    exit;
}

// Initialize an empty array to store listings
$listings = [];

// Retrieve listings from the database
$sql = "SELECT listing_id, listing_name, listing_price, listing_image, user_name, status FROM listing";
if ($result = $mysqli->query($sql)) {
    // Fetch result rows as associative array
    while ($row = $result->fetch_assoc()) {
        $listings[] = $row;
    }
    // Free result set
    $result->free();
} else {
    echo "Oops! Something went wrong. Please try again later.";
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-body {
            height: 200px; /* Set a fixed height for the card body */
            overflow: hidden; /* Hide overflow content */
        }

        .card{
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            align-content: center;
        }

        .card-img-top {
            width: 100%;
            height: 250px;
            object-fit: contain;
        }

        .card-img{
            width: 100%;
            height: 350px;
            object-fit: contain;
        }

        body{
            background-color: #218838;
        }

        h1{
            text-align: center;
            font-family: 'Poppins';
            font-size: 40px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 50px;   
        }

        h2{
            text-align: left;
            font-family: 'Poppins';
            font-size: 30px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 50px;   
        }

        .status-available {
            background-color: #28a745;
            color: white;
            padding: 2px 5px;
            border-radius: 5px;
        }

        .status-sold {
            background-color: #dc3545;
            color: white;
            padding: 2px 5px;
            border-radius: 5px;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to the Listing Page</h1>
        <?php if (isset($user_name)) : ?>
            <p style="text-align: right;">Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
            <p style="text-align: right;"><a href="createlisting.php">Create Listing</a></p>
            <p style="text-align: right;"><a href="profile.php">Profile</a></p>
            <p style="text-align: right;"><a href="userlistings.php">Your Listings</a></p>
        <?php else : ?>
            <p style="text-align: right;">Welcome! <a href="login.php">Log in</a> or <a href="register.php">Register</a> to create listings.</p>
        <?php endif; ?>

        <h2>Listings</h2>
        <div class="row">
            <?php foreach ($listings as $listing) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($listing['listing_image']); ?>" class="card-img-top" alt="Listing Image">
                        <div class="card-body">
                            <h5 class="card-title"><a href="listingdetails.php?id=<?php echo $listing['listing_id']; ?>"><?php echo htmlspecialchars($listing['listing_name']); ?></a></h5>
                            <p class="card-text">Price: ₱<?php echo htmlspecialchars($listing['listing_price']); ?></p>
                            <p class="card-text">Uploaded by: <?php echo htmlspecialchars($listing['user_name']); ?></p>
                            <?php if (!empty($listing['status'])) : ?>
                                <p class="<?php echo ($listing['status'] === 'available') ? 'status-available' : 'status-sold'; ?>"><?php echo htmlspecialchars($listing['status']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (isset($user_name)) : ?>
            <p><a href="?logout=1">Sign Out</a></p>
        <?php endif; ?>
    </div>
</body>

</html>
