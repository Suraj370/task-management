<?php
    
    define("DB_SERVER", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD", "xerxes");
    define("DB_NAME", "tasklist");

   $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
    if ( !$connection ) {
        die("Database connection failed: " . mysqli_error());
    }

    $db_select = mysqli_select_db($connection, DB_NAME);
    if ( !$db_select ) {
        die("Database selection failed: " . mysqli_error($connection));
    }
 ?>    