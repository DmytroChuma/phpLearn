<html>
<head>
	<title>Авторизація</title>
<link rel="stylesheet" href="css/login.css">
<link rel="stylesheet" href="css/font-awesome.css">
	</head>
	<body>
		<div class='container'>
			<form action='login.php' method='POST'>
				<p>Логін</p>
				<div class='inputs'>
					<?php
					session_start();
						if(isset($_SESSION['login'])){
							echo"<input type='text' name='userlogin' placeholder='Введіть логін' value='".$_SESSION['login']."' required>";
						}
						else {
							echo "<input type='text' name='userlogin' placeholder='Введіть логін' required>";
						}
					?>
				
			</div>
			<p>Пароль</p>
			<div class='inputs-pass'>
				<input type='password' name='password' placeholder="Введіть пароль" required>
						</div>

						<label class="checkcontainer">Запам'ятати мене
  							<input type="checkbox" name='check'>
  							<span class="checkmark"></span>
						</label>	

						 <br>
						 <?php
						 
						 	if (isset($_POST['submit'])) {


								require_once("dbconnect.php");

						
						   		$sql = 'SELECT email, login, password, type FROM users';
						   		$result = mysqli_query($mysqli, $sql);
								while ($row = mysqli_fetch_array($result)) {
									if ($_POST['userlogin'] == $row['login'] && $_POST['password'] == $row['password']) {
										$_SESSION['login'] = $_POST['userlogin'];
										$_SESSION['password'] = $_POST['password'];
										 $_SESSION['type'] = $row["type"];
										 $_SESSION['email'] = $rpw["email"];
										
										if ($_POST['check']) {
											 $password_cookie_token = md5($_POST['password'].time());
											 $update_password_cookie_token = $mysqli->query("UPDATE users SET token='".$password_cookie_token."' WHERE login = '".$_POST['userlogin']."'");
											 if(!$update_password_cookie_token){
        										$_SESSION["error_messages"] = "<p class='mesage_error' >Помилка'</p>";
        										header("HTTP/1.1 301 Moved Permanently");
        										header("Location: login.php");
        										exit();
    										}
    										setcookie("password_cookie_token", $password_cookie_token, time() + (1000 * 60 * 60 * 24 * 30)); //30 днів
										}
										else{
											if(isset($_COOKIE["password_cookie_token"])){
        										$update_password_cookie_token = $mysqli->query("UPDATE users SET token = '' WHERE login = '".$_POST['userlogin']."'");
        										setcookie("password_cookie_token", "", time() - 3600);
   											}
										}
										header("Location: index.php");
  										exit;
										return;
									}
								}
						 			echo "<label class='error' >Неправильний логін або пароль!</label>";
						 	}
						 ?>
						 <br>

				<button class='sb' type='submit' name='submit'>Ввійти</button>
				<br>
				<a href="registration.php">Зареєструватись</a> <br> <br>
				<a href="index.php">На головну</a>
			</form>
		</div>
	</body>
</html>