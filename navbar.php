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
            <div class="logo"> <img src="logo.svg"> </div>

            <ul class="nav-ul" id="nav_ul">
                <li>
                    <a href="#accueil">About</a>
                </li>
                <li>
                    <a href="#cours">Lorem</a>
                </li>
                <li>
                    <a href="#tutos">Ipsum</a>
                </li>
                <li>
                    <a href="#contact">Lorem</a>
                </li>
            </ul>

            <div class="hamburger" id="hamburger">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
        </nav>
    </header>

    <h1>dont touch</h1>

    <footer id="footer">
        <p>lorem</p>
    </footer>

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