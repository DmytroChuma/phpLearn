<html>
<head>
<link rel="stylesheet" href="css/registration.css">
<link rel="stylesheet" href="css/font-awesome.css">
	</head>
	<body>
		<div class='container'>
			<form action='registration.php' method='post'>
				<?php 
				session_start();
					if (isset($_POST['submit'])){
						$email = $_POST['uname'];
						$login = $_POST['userlogin'];
						$pass = $_POST['password'];
					}
					else {
						$email = "";
						$login = "";
						$pass = "";
					}
				?>
				<p>Email</p>
				<div class='email-input'>
					<?php
					echo '<input type="text" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" placeholder="Enter Email" title="email@example.com" name="uname" value="'.$email.'" required > <br>';
					?>
					 
				</div>
				<p>Логін</p>
				<div class='inputs'>
				<?php
					echo "<input type='text' name='userlogin' value='".$login."' placeholder='Введіть логін' required>";
				?>
			</div>

			<p>Пароль</p>
			<div class='inputs-pass'>
				<input type='password' name='password' placeholder="Введіть пароль" required>
						</div>
					<?php 
						if (isset($_POST['submit'])){
							require_once("dbconnect.php");
							$sql = 'SELECT * FROM users';
							$result = mysqli_query($mysqli, $sql);
							$break = false;
							while ($row = mysqli_fetch_array($result)) {
								if ($row['email']==$_POST['uname']) {
									echo "<label style='color:red;' class='error' >Такий email занятий!</label><br>";
									$break=true;
									break;
								}
								else if ($row['login']==$_POST['userlogin']) {
									echo "<label style='color:red;' class='error' >Такий логін занятий!</label><br>";
									$break=true;
									break;
								}
								
							}
							if(!$break){
								$sql = 'INSERT INTO users (email, login, password, token) VALUES ("'.$_POST['uname'].'","'.$_POST['userlogin'].'","'.$_POST['password'].'", "")';
            					mysqli_query($mysqli, $sql);
            					$_SESSION['login'] = $_POST['userlogin'];
            					mkdir("users\\".$_POST['userlogin'], 0777, true);
            					mkdir("users\\".$_POST['userlogin']."\\task", 0777, true);
            					mkdir("users\\".$_POST['userlogin']."\\test", 0777, true);
            					header("Location: login.php");
  								exit;
            				}
						}
					?>
				<button class='sb' type='submit' name='submit'>Зареєструватись</button>
				<br>
				<a href="login.php">Уже є обліковий запис?</a> <br> <br>
				<a href="index.php">На головну</a>
			</form>
		</div>
	</body>
</html>