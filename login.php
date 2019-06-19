<?php
    require_once 'config.php';

    session_start();

    $username = $password = $email = "";
    $err = "";

    if(isset($_POST['logUsername']) && !empty($_POST['logUsername']) && isset($_POST['logPass']) && !empty($_POST['logPass'])){
        $sql = "SELECT name, password, email, id FROM users WHERE name = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            $username = mysqli_real_escape_string($db, trim($_POST['logUsername']));
            $password = mysqli_real_escape_string($db, trim($_POST['logPass']));

            mysqli_stmt_bind_param($stmt, "s", $username);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){ // found the a matching username
                    mysqli_stmt_bind_result($stmt, $username, $hashedPass, $email, $id);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashedPass)){
                            //log in ok
                            session_start();
                            $_SESSION['username'] = $username;
                            $_SESSION['userId'] = $id;
                            header("location: index.php");
                            exit();
                        }
                        else{
                            $err = "Login information invalid!";
                        }
                    }
                }
               
            }
            else{
                $err = "Illegal character!";
            }

            mysqli_stmt_close($stmt);
        }
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
                        <ul class="nav">
                            <li><a href="#">WRITE</a></li>
                            <li><a href="#">READ</a></li>
                            <li><a href="#">USER</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="register">
                <form method="POST">
                    <h2>Log in and continue reading and writing!</h2><br>
                    <b>Username</b>:<br>
                    <input type="text" placeholder="Your username" name="logUsername"><br><br>
                    <b>Password</b>:<br>
                    <input type="password" placeholder="Your password" name="logPass"><br><br>

                    <br><br>
                    <input type="submit" name="login" value="LOG IN">
                </form>
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



