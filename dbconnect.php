<?php
    
    header('Content-Type: text/html; charset=utf-8');

    $server = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $database = "php_learn";
 

    $mysqli = new mysqli($server, $username, $password, $database);


    if (mysqli_connect_errno()) { 
        echo "<p><strong>Помилка підключення до БД</strong>. ".mysqli_connect_error()."</p>";
        exit(); 
    }

    $mysqli->set_charset('utf8');

?>