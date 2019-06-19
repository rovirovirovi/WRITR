<?php
    require_once 'config.php';

    session_start();

    $username = $password = $email = "";
    $err = "";

    if(isset($_POST['regUsername']) && !empty($_POST['regUsername'])){
        // check if username exists
        $sql = "SELECT id FROM users WHERE name = ?";
        $username = mysqli_real_escape_string($db, trim($_POST['regUsername']));

        if($stmt = mysqli_prepare($db, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $username);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    //user exists
                    $err = "Username already exists!";
                }
            }

        }
    }

    if(empty($err) && isset($_POST['regUsername']) && !empty($_POST['regUsername']) && isset($_POST['regPass']) && !empty($_POST['regPass']) && isset($_POST['regPassCheck']) && !empty($_POST['regPassCheck']) && isset($_POST['regEmail']) && !empty($_POST['regEmail'])){
        $sql = "INSERT INTO users (name, password, email) VALUES (?, ?, ?)";
        
        if($stmt = mysqli_prepare($db, $sql)){
            

            $username = mysqli_real_escape_string($db, trim($_POST['regUsername']));
            $password = mysqli_real_escape_string($db, trim($_POST['regPass']));
            $passHash = password_hash($password, PASSWORD_DEFAULT);
            $email = mysqli_real_escape_string($db, trim($_POST['regEmail']));

            mysqli_stmt_bind_param($stmt, "sss", $username, $passHash, $email);

            if(mysqli_stmt_execute($stmt)){
                // account created!
                echo "Account successfully created!";
                $username = $password = $email = "";
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
                    <h2>Register and join countless others who wanted to share their stories with the world!</h2><br>
                    <b>Username</b>:<br>
                    <input type="text" placeholder="Your username" name="regUsername"><br><br>
                    <b>Password</b>:<br>
                    <input type="password" placeholder="Your password" name="regPass"><br><br>
                    <b>Password check</b>:<br>
                    <input type="password" placeholder="Your password" name="regPassCheck"><br><br>
                    <b>Email</b>:<br>
                    <input type="text" placeholder="Your email" name="regEmail"><br>

                    <br>
                    <span style="color: #f26d7d"><?php echo $err?></span><br>
                    <input type="submit" name="register" value="SIGN UP">
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



