<?php
session_start();
require_once("dbconnect.php");
 
if(isset($_SESSION["login"])){
  
    $update_password_cookie_token = $mysqli->query("UPDATE users SET token = '' WHERE login = '".$_SESSION["login"]."'");
     
    if(!$update_password_cookie_token){
        echo "Помилка ".$mysqli->error();
    }else{
        setcookie("password_cookie_token", "", time() - 3600);
    }
    session_destroy();
    header("Location: index.php");
    exit();
    return;
}
 
?>