<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header id="navbar">
        <nav class="navbar-container container">
            <a href="/" class="home-link">
                <div class="navbar-logo"></div> <!-- Replace with your logo -->
                Website Name
            </a>
            <button type="button" id="navbar-toggle" aria-controls="navbar-menu" aria-label="Toggle menu" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div id="navbar-menu" aria-labelledby="navbar-toggle">
                <ul class="navbar-links">
                    <li class="navbar-item"><a class="navbar-link" href="/about">About</a></li>
                    <li class="navbar-item"><a class="navbar-link" href="/blog">Blog</a></li>
                    <li class="navbar-item"><a class="navbar-link" href="/careers">Careers</a></li>
                    <li class="navbar-item"><a class="navbar-link" href="/contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <script src="index.js"></script> <!-- Add your JavaScript file if needed -->
</body>
</html>
