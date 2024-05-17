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
          <p>REPS</p>
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
      <h1>second part</h1>
      <p>hindi ko alam ilalagay</p>
    </section>

    <section class="section">
      <h1>third section</h1>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="index2.js"></script>
  </body>
</html>