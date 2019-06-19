<?php
    require_once 'config.php';

    session_start();

    if(isset($_POST['account'])){

    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>WRITR</title>
        <link rel="stylesheet" href="css/styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <header>
            <div class="gray-container">
                <div class="container">
                <a href="index.php" alt = "home"><img src="images/logo.png" alt="WRITR" class="logo"></a>

                    <nav>
                        <form method="POST">
                            <ul class="nav">
                                <li><a href="#">WRITE</a></li>
                                <li><a href="#">READ</a></li>
                                <li><a href="profile.php">PROFILE</a></li>
                            </ul>
                        </form>
                        
                    </nav>
                    
                    
                    <h1>Letting you <b>write</b> and <b>share</b><br/> your stories with  the world</h1> 

                    <div class="center">
                        <div class="join">
                            <a href="register.php">
                                <h3>JOIN THE COMMUNITY</h3>
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="login">
                    or <a href="login.php">LOG IN</a> if you're already part of it!
                    </div> 
                </div>
            </div>
        </header>

        <div class="blue-container">
            <div class="container un-skew">
                <h1>POPULAR</h1>
                <ul class="books">
                    <li class="book"><a href="#">book 1</a></li>
                    <li class="book"><a href="#">book 2</a></li>
                    <li class="book"><a href="#">book 3</a></li>
                    <li class="book"><a href="#">book 4</a></li>
                </ul>
            </div>
        </div>

        <div class="yellow-container">
            <div class="container un-skew">
                <h1>NEW</h1>
                <ul class="books">
                    <li class="book"><a href="#">book 1</a></li>
                    <li class="book"><a href="#">book 2</a></li>
                    <li class="book"><a href="#">book 3</a></li>
                    <li class="book"><a href="#">book 4</a></li>
                </ul>
            </div>
        </div>

        <div class="red-container">
            <div class="container un-skew">
                <h1>FOR YOU</h1>
                <ul class="books">
                    <li class="book"><a href="#">book 1</a></li>
                    <li class="book"><a href="#">book 2</a></li>
                    <li class="book"><a href="#">book 3</a></li>
                    <li class="book"><a href="#">book 4</a></li>
                    <li class="book"><a href="#">book 5</a></li>
                    <li class="book"><a href="#">book 6</a></li>
                    <li class="book"><a href="#">book 7</a></li>
                    <li class="book"><a href="#">book 8</a></li>
                </ul>
            </div>
        </div>

        <footer>
            <div class="container">
                <h3>CONTACT US</h3>
                <div class="twitter">
                    <a href="https://twitter.com/rovi_rovi_rovi"><img src="images/twitter_img.png" alt="Our twitter"></a>
                    <p>@rovi_rovi_rovi</p>
                </div>
                <p>some_email@gmail.com</p>
            </div>
        </footer>

    </body>
</html>