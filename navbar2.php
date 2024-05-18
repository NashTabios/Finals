<?php
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="navbar2.css">
</head>

<body>
  <nav class="navbar">
    <div class="logo">
      <i class="fa-solid fa-font-awesome"></i>
      <!-- <img src="logom.png"> -->
      <a href="#">REPS</a>
    </div>
    <div class="menu">
      <?php if (isset($user_name)) : ?>
        <div class="menu-links">
          <a href="index.php">Home</a>
          <a href="aboutus.php">About</a>
          <a href="listing.php">Listings</a>
          <a href="profile.php">Profile</a>
        </div>
        <a href="?logout=1">
          <button class="log-in">Sign-Out</button>
        </a>
    </div>
  <?php else : ?>
    <div class="menu-links">
      <a href="index.php">Home</a>
      <a href="aboutus.php">About</a>
    </div>
    <a href="login.php">
      <button class="log-in">Log-In</button>
    </a>
    <a href="register.php">
      <button class="log-in">Create an account</button>
    </a>
    </div>
  <?php endif; ?>
  <div class="menu-btn">
    <i class="fa-solid fa-bars"></i>
  </div>
  </nav>
</body>

</html>