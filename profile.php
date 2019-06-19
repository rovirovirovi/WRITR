<?php
    require_once 'config.php';

    session_start();

    if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
        header("location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
    my page wow!
    <?php echo "name: " . $_SESSION['username'] . " - id: " . $_SESSION['userId'] ?>
</html>