<?php

define("DB_SERVER", "localhost"); 
// This need to be whatever your username is to login to phpMyAdmin
define("DB_USER", "your_username");
// This needs to be whatever your password is
define("DB_PASS", "your_password");
// Use the name of the database you want to connect to here
define("DB_NAME", "your_database");

function db_connect () {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    
    if (mysqli_connect_errno()) {
        echo "Failed to connect to mySQL: " .mysqli_connect_errno();
    } else {
        return $connection;
    }
}


function db_disconnect($connection) {
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

?>
