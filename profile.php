<?php
    require_once 'config.php';

    session_start();

    if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
        header("location: login.php");
        exit();
    }


    $booksPerPage = 4;

    $pageNr = 0;
    $pageTotal = 0;
    if(isset($_GET['page'])){
        $pageNr = intval(mysqli_real_escape_string($db, $_GET['page']));
    }
    $rez = mysqli_query($db, "SELECT * FROM books");
    $pageTotal = floor(mysqli_num_rows($rez) / $booksPerPage) + 1;      
    mysqli_free_result($rez);
    if($pageNr < 0)
        $pageNr = 0;

    // get profile desc
    $sql = "SELECT name, description FROM users WHERE id = ?";
    if($stmt = mysqli_prepare($db, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['userId']);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $getUserName, $getUserDesc);
                if(mysqli_stmt_fetch($stmt)){
                    $_SESSION['username'] = $getUserName;
                    $_SESSION['userDesc']= str_replace('\r\n', '<br/>', $getUserDesc);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    if(isset($_POST['newDesc']) && !empty($_POST['newDesc'])){
        echo htmlspecialchars("<span style=\"color:red\">executing desc change!</span>");
        $sql = "UPDATE users SET description = ? WHERE id = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            $newDesc = mysqli_real_escape_string($db, trim($_POST['newDesc']));
            $userID = mysqli_real_escape_string($db, trim($_SESSION['userId']));

            mysqli_stmt_bind_param($stmt, "si", $newDesc, $userID);

            if(mysqli_stmt_execute($stmt)){
                // all ok!
                
                $_SESSION['userDesc']= str_replace('\r\n', '<br/>', $newDesc);
                header("location: profile.php");
            }
            else{
                echo "error executing statement: " + mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }

    // if(isset($_POST['nextPage'])){
    //     $_SESSION['currentPageNr'] ++;
    // }
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>WRITR</title>
        <link rel="stylesheet" href="css/styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <script>
            window.onscroll = function() {scrollFunction()};

            function scrollFunction(){
                if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20){
                    document.getElementById("btnScrollUp").style.display="block";
                } else{
                    document.getElementById("btnScrollUp").style.display="none";
                }
            }

            function scrollToTop(){
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }

            function enableEditProfileDesc(){
                document.getElementById("editProfileDesc").style.display="block";
            }

            function disableEditProfileDesc(){
                document.getElementById("editProfileDesc").style.display="none";
                document.getElementById("descTextArea").value='';
            }

        </script>

        <button id="btnScrollUp" title="Go to the top." onclick="scrollToTop()">Top</button>
        
        <div id="editProfileDesc">
            
            <textarea id="descTextArea" type="text" name="newDesc" placeholder="Enter your new bio." maxlength=500 form="newDescForm"></textarea>
            <form id="newDescForm" method="POST">
                <input type="submit" value="Save" name="btnSaveNewDesc">
            </form>
            <a onclick="disableEditProfileDesc()">Cancel</a>
        </div>

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
                    
                    <br><br><br>
                    <h1>Hello, <?php echo $_SESSION['username'] ?>.</h1>

                    <div class="profile"><img src="/images/chair.png"/></div>

                    <div class="profile"><h4>
                        <?php 
                            if(!empty($_SESSION['userDesc'])){

                                echo $_SESSION['userDesc'];
                            }
                            else{
                                echo "This is a user bio. This is a user bio. This is a user bio. This is a user bio. This is a user bio. This is a user bio. This is a user bio. This is a user bio. This is a user bio. This is a user bio. ";
                            }
                        ?>
                    </h4></div>
                    <div class="profile"><button onclick="enableEditProfileDesc()">EDIT</button></div>
                </div>
            </div>
        </header>

        <!-- favorites -->
        <div class="profileTitle">
            <div class="profileCategory"><h3><a href="">FAVORITES</a></h3></div>
            <div class="profileCategory"><h3><a href="">RECENTLY READ</a></h3></div>
            <div class="profileCategory"><h3><a href="">RECOMMENDED</a></h3></div>
        </div>

        <div class="profileTitle">
            <?php
                if($pageNr + 1 > 1){
                    echo "<a href=\"profile.php?page=0\"><<</a>";
                    echo "<a href=\"profile.php?page=". ($pageNr - 1) ."\"><</a>";
                }
                echo " " . ($pageNr + 1) . " / " . $pageTotal . " ";
                if($pageNr + 1 < $pageTotal){
                    echo "<a href=\"profile.php?page=". ($pageNr + 1) ."\">></a>";
                    echo "<a href=\"profile.php?page=". ($pageTotal-1) ."\">>></a>";
                }
            ?>
            
        </div>

        <div id="favorites" class="profileBooks">
            <ul>
                <?php
                    $sql = "SELECT name, description, author FROM books LIMIT 4 OFFSET ?";
                    if($stmt = mysqli_prepare($db, $sql)){

                        $offsetNr = $pageNr * $booksPerPage;
                        mysqli_stmt_bind_param($stmt, "i", $offsetNr);

                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);

                            if(mysqli_stmt_num_rows($stmt) > 0){
                                $rezName = $rezDesc = $rezAuthor = "";
                                mysqli_stmt_bind_result($stmt, $rezName, $rezDesc, $rezAuthor);
                                while(mysqli_stmt_fetch($stmt)){
                                    echo "<div class=\"profileBook clearfix\">
                                        <li>
                                            <img src=\"images/chair2.png\" alt=\"\"/>
                                            <a href=\"\"><b>" . $rezName . "</b></a>
                                            <h5><a href=\"\">" . $rezAuthor . "</a></h5>
                                            <!-- tags -->
                                            <div class=\"tag\"><a href=\"\">action</a></div>
                                            <div class=\"tag\"><a href=\"\">action</a></div>
                                            <div class=\"tag\"><a href=\"\">action</a></div>
                                            <div class=\"tag\"><a href=\"\">action</a></div>
                                            <div class=\"tag\"><a href=\"\">action</a></div>
                                        </li>
                                    </div>";
                                }
                            }
                            else{
                                echo "<div class=\"profileTitle\">Nothing here!</div>";
                            }
                        }
                        $stmt->close();
                    }
                ?>
            </ul>
        </div>

        <!-- read recently -->
        <div id="recents" class="profileBooks">
            <ul>
            <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
                <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
                <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
                <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
            </ul>
        </div>

         <!-- recommended -->
        <div id="recommends" class="profileBooks">
            <ul>
            <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
                <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
                <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
                <div class="profileBook clearfix">
                    <li>
                        <img src="images/chair2.png" alt=""/>
                        <a href=""><b>Book 1</b></a>
                        <h5><a href="">some guy</a></h5>
                        <!-- tags -->
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                        <div class="tag"><a href="">action</a></div>
                    </li>
                </div>
            </ul>
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