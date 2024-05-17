<?php 
session_start(); // Start the session

// Check if the user is logged in
$loggedin = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;

include 'navbar2.php'; // Include the navbar

?>

<html>
<head>
    <title>About Us - R.E.P.S</title>
    <meta charset="utf-8">
    <meta name="viewport" ccontent="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type ="text/css" href="aboutus.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" >
</head>
<body>
    <section>
        <div class="about-us">
            <h1>About Us</h1>
            <div class="wrapper">
                <div class="content">
                    <h2>Vision</h2>
                    <p>At REPS, we envision a future where sustainability is not just a buzzword, 
                        but a way of life ingrained in every aspect of society. Our vision extends 
                        beyond mere recycling; it encompasses a holistic approach to consumption, where every item,
                         from the smallest trinket to the largest appliance, is viewed as a valuable resource deserving 
                         of a second chance. We imagine a world where waste is minimized, resources are maximized, 
                         nd communities thrive in harmony with the planet. Through our platform, we seek to catalyze 
                         this transformation, inspiring individuals worldwide to join us on a journey towards a greener, 
                         cleaner, and more sustainable tomorrow.
                    </p>
                    <h2>Mission</h2>
                    <p>REPS is on a mission to revolutionize the way we perceive and interact with our material possessions.
                         Our platform serves as a catalyst for change, empowering users to participate actively in the circular 
                         economy by seamlessly exchanging, recycling, and reselling items that would otherwise end up in landfills.
                          We are committed to providing a user-friendly marketplace that not only facilitates transactions but 
                          also educates and inspires individuals to make informed choices that benefit both the environment and 
                          society as a whole. Through strategic partnerships, technological innovation, and community engagement,
                           we strive to create a global network of conscientious consumers who share our passion for sustainability 
                           and are dedicated to making a positive impact on the planet. Together, we can turn the tide on waste and 
                           pave the way for a brighter, more sustainable future for generations to come.
                    </p>
                </div>
                <div class="image-section">
                    <img src="logo.svg">
                </div>
            </div>
        </div>
    </section>
</body>
</html>
