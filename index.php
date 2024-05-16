<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - R.E.P.S</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<header id="header">
<div class="centered-logo">
    <img src="indexlogo.png" alt="Logo">
</div>
    <div class="progressBar-container">
        <div class="progressBar" id="progressBar"></div>
    </div>
    <nav class="navbar">
        <?php if (isset($user_name)) : ?>
        <ul class="nav-ul" id="nav_ul">
            <li><a href="index.php">Home</a></li>
            <li><a href="aboutus.php">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="?logout=1">Sign Out</a></li>
        </ul>
        <?php else : ?>
        <ul class="nav-ul" id="nav_ul">
            <li><a href="index.php">Home</a></li>
            <li><a href="aboutus.php">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="login.php">Sign In</a></li>
        </ul>
        <?php endif; ?>
        <div class="hamburger" id="hamburger">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>
    </nav>
</header>
<div class="cardContainer">
    <div class="login-card">
        <a href="login.php" class="normal-signin">Login</a>
        <div class="instruction-text">Don't have an Account?</div>
        <a href="register.php" class="create-account">Create Account</a>
        <p><a href="aboutus.php">about us</a></p>
        <p><a href="adminlogin.php">ADMIN</a></p>
    </div>
</div>
<script src="index.js"></script>
</body>
</html>
