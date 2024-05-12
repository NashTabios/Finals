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
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="shortcut icon" href="favicon.png">
</head>

<body>
    <header id="header">
        <div class="progressBar-container">
            <div class="progressBar" id="progressBar"></div>
        </div>
        <nav class="navbar">
            <div class="logo"> <img src="navlogo2.png"> </div>
            
            <?php if (isset($user_name)) : ?>
            <ul class="nav-ul" id="nav_ul">
                <li>
                    <a href="aboutus.php">About</a>
                </li>
                <li>
                    <a href="#contact">Contact</a>
                </li>
                <li>
                    <a href="?logout=1"> Sign Out</a>
                </li>
            </ul>
            <?php else : ?>
            <ul class="nav-ul" id="nav_ul">
                <li>
                    <a href="aboutus.php">About</a>
                </li>
                <li>
                    <a href="#contact">Contact</a>
                </li>
                <li>
                    <a href="login.php">Login</a>
                </li>
            </ul>
            <?php endif; ?>
            

            <div class="hamburger" id="hamburger">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
        </nav>
        <nav class="navbar2"> </nav>
    </header>


    <!-- <footer id="footer">
        <p>lorem</p>
    </footer> -->

    <script>
        hamburger.onclick = () => {
            hamburger.classList.toggle("open");
            nav_ul.classList.toggle("slide");
            document.body.classList.toggle("noScroll");
        };

        onscroll = () => {
            header.classList.add("shadowHeader");
            footer.classList.add("shadowFooter");

            setTimeout(() => {
                header.classList.remove("shadowHeader");
                footer.classList.remove("shadowFooter");
            }, 1000);

            const page = document.documentElement; //element HTML
            let totalHeight = page.scrollHeight; //Height Total of page
            let visibleHeight = page.clientHeight; //Height visible
            let scrolling = page.scrollTop; //size of scroll
            let max = totalHeight - visibleHeight;
            progressBar.style.width = Math.floor(scrolling / max * 100) + "%"; //width in %

            if (progressBar.style.width == "100%")
                progressBar.style.backgroundColor = "green";
            else progressBar.style.backgroundColor = "rgb(192, 127, 6)";
        }
    </script>
</body>

</html>