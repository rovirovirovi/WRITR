<?php
    define('DB_ADRESS', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_NAME', 'writr');

    $db = mysqli_connect(DB_ADRESS, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if(!$db){
        die("Error connecting to MySQL DB: " . $db->connect_error);
    }

?>