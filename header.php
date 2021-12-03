
<?php
 session_start();
 
    require_once("dbconnect.php");
    if(isset($_COOKIE["password_cookie_token"]) && !empty($_COOKIE["password_cookie_token"])){
        $res = $mysqli->query("SELECT login, password, type,email FROM `users` WHERE token = '".$_COOKIE["password_cookie_token"]."'");
        if(!$res){
            echo "<p class='mesage_error' >Помилка</p>".$mysqli->error();
        }else{
            $arr = $res->fetch_array(MYSQLI_ASSOC);
            if($arr){  
                $_SESSION['login'] = $arr["login"];
                $_SESSION['password'] = $arr["password"];
                $_SESSION['type'] = $arr["type"];
                $_SESSION['email'] = $arr["email"];
            } 
        }

    }
?>

<meta charset="utf-8">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/font-awesome.css">
<div class='header-form'>
    <div class='header-border'></div>
<nav class="header">
	<div class='inner-header'>
  <ul>
    <li class=''><a href="index.php" class='menu' >Головна</a></li>
    <li class=''><a href="theory.php" class='menu' >Теорія</a></li>
    <li class=''><a href="tests.php" class='menu'>Тести</a></li>
    <li class=''><a href="practice.php" class='menu'>Практика</a></li>
  </ul>
<div class='forms'>
<form class='search' action='search.php' method='get'>
  	<input class='stext' type='text' name='search' placeholder="Пошук" />
    <div class='sb'>
     <button type="submit" class='search-button' ></button>
   </div>
  </form>
  <?php
if (isset($_SESSION['login'])) {
echo "<div class='dropdown'>
  <button class='dropbtn'>".$_SESSION['login']."</button>
  <div class='dropdown-content'>";
  if($_SESSION['type'] == 0){
    echo "<a href='test_res.php'>Результати тестів</a>
    <a href='task_res.php'>Результати задач</a>";
  }
    else {
       echo "  <a href='edit.php?type=0'>Редактор розділів</a>
       <a href='edit.php?type=1'>Редактор теорії</a>
       <a href='edit.php?type=2'>Редактор тестів</a>
    <a href='edit.php?type=3'>Редактор задач</a>";
    }
    echo"<a class='exitdown' href='logout.php'>Вихід</a>
  </div>
</div>"; 
}
else {
  echo "<div class='lb'>
  <a href='login.php' class='login-button' >Вхід</a>
      </div>";
}
?>

 
  </div>
</div>
</nav>
 
 <div class='header-border'></div>
 

</div>


