<?php
session_start();

// Check if the user is not logged in, then redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

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

$user_name = $_SESSION["user_name"];
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
    <h1>Welcome to the Listing Page</h1>
    <p>Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
    <p>This is the listing page content.</p>
    <p><a href="?logout=1">Sign Out</a></p>
</body>

</html>