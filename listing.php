<?php
session_start();

// Include config file
require_once "config.php";

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
$sql = "SELECT listing_name, listing_price, listing_desc, listing_image, user_name FROM listing";
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
</head>

<body>
    <div class="container">
        <h1>Welcome to the Listing Page</h1>
        <?php if (isset($user_name)) : ?>
            <p>Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
            <p><a href="createlisting.php">Create Listing</a></p>
        <?php else : ?>
            <p>Welcome! <a href="login.php">Log in</a> or <a href="register.php">Register</a> to create listings.</p>
        <?php endif; ?>

        <h2>Listings</h2>
        <div class="row">
            <?php foreach ($listings as $listing) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($listing['listing_image']); ?>" class="card-img-top" alt="Listing Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($listing['listing_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($listing['listing_desc']); ?></p>
                            <p class="card-text">Price: ₱<?php echo htmlspecialchars($listing['listing_price']); ?></p>
                            <p class="card-text">Uploaded by: <?php echo htmlspecialchars($listing['user_name']); ?></p>
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
