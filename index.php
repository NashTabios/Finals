<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home - Index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="index2.css" />
    <link rel="stylesheet" href="footer.css" />
    <link rel="stylesheet" href="cards.css" />
  </head>
  <body>
    <aside class="menu">
      <div class="menu-header">
        <p>Where to?</p>
      </div>

      <div class="menu-items">
        <a href="index.php" class="menu-item">Home</a>
        <a href="aboutus.php" class="menu-item">About Us</a>
        <a href="login.php" class="menu-item">Sign-In</a>
        <a href="register.php" class="menu-item">Sign-Up</a>
      </div>
    </aside>
    <button class="menu-btn">
      <i class="bi bi-list"></i>
    </button>

    <div class="container">
      <nav class="navbar">
        <div class="nav-logo">
          <p>RECYCLING EXCHANGE PLATFORM FOR SUSTAINABILITY</p>
        </div>
        <div class="nav-items">
         <a href="index.php" class="menu-item">Home</a>
         <a href="aboutus.php" class="menu-item">About Us</a>
         <a href="login.php" class="menu-item">Sign-In</a>
         <a href="register.php" class="menu-item">Sign-Up</a>
        </div>
      </nav>

      <section class="landing">
        <h1>first part</h1>
        <p>lalagay info</p>
      </section>
    </div>

    <section class="section">
      <h1>Meet the Developers!</h1>
      <p>Future graduates of UST</p>

    <br>
    <br>
    <br>
        <div class="card-container-wrapper">
        <div class="card-container">
        <div class="card">
        <div class="img-content">
        <img src="./imgs/imag1.png">
        </div>
        <div class="content">
            <p class="heading">Kenn</p>
            <p class="desc">
            CHIEF EXECUTIVE OFFICER
            </p>
        </div>
        </div>
        </div>  
        <div class="card-container-wrapper">
        <div class="card-container">
        <div class="card">
        <div class="img-content">
        <img src="./imgs/imag3.png">
        </div>
        <div class="content">
            <p class="heading">Nash</p>
            <p class="desc">
            CHIEF OPERATING OFFICER
            </p>
        </div>
        </div>
        </div>
        <div class="card-container-wrapper">
        <div class="card-container">
        <div class="card">
        <div class="img-content">
        <img src="./imgs/imag2.png">
        </div>
        <div class="content">
            <p class="heading">Shawn</p>
            <p class="desc">
            CHIEF TECHNOLOGY OFFICER
            </p>
        </div>
        </div>
        </div>
        <div class="card-container-wrapper">
        <div class="card-container">
        <div class="card">
        <div class="img-content">
        <img src="./imgs/imag4.png">
        </div>
        <div class="content">
            <p class="heading">Paul</p>
            <p class="desc">
            CHAIRMAN
            </p>
        </div>
        </div>
        </div>
        <div class="card-container-wrapper">
        <div class="card-container">
        <div class="card">
        <div class="img-content">
        <img src="./imgs/imag5.png">
        </div>
        <div class="content">
            <p class="heading">Billy</p>
            <p class="desc">
            CHIEF FINANCIAL OFFICER
            </p>
        </div>
        </div>
        </div>

    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="index2.js"></script>
  </body>
</html>