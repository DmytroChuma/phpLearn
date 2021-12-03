<html>
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="css/index.css">
 <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body>

    <?php
        include('header.php');   
    ?>
    <div class='test-page'>
    	<?php 
    		unset($_SESSION['ans_count']);
    	 	$sql = 'SELECT * FROM test WHERE test_id = "'.$_GET['testid'].'"';
    	 	$_SESSION['testid'] = $_GET['testid'];
    		$result = mysqli_query($mysqli, $sql);
            while ($row = mysqli_fetch_array($result)) {
            	$str = explode(",", $row['test_themes_id']);
            	$themes = "";
            	for ($i = 0; $i < count($str); $i++) {
                	$sql = 'SELECT theme_id, theme_name FROM theory_themes WHERE theme_id = '.$str[$i];
                	$t = mysqli_query($mysqli, $sql);
                	while ($a = mysqli_fetch_array($t)){
                    $themes .= $a['theme_name'].", ";
                }
            	}
	            $themes = substr($themes,0,-2);
	            echo "
	            <div class='new'>
	            <h3>Тести '".$row['name']."'</h3>
	            <label class='date_l'>Дата створення: ".$row['date']."</label> <br>
	            <label class='new_t'>Теми тестів: ".$themes."</label> <br>
	            </div>
	            <div style='font-size: 18px; margin-left: 20px; margin-top:10px;'>
	            <label ><p class='pt'><b>Деталі</b></p>".$row['details']."</label>";
	            $str = substr_count($row['test'], '|t');
	            for ($i = 1; $i <= $str; $i++) {
	            	$_SESSION[$i.'test'] = "";
	            }
	            echo "
	            <p>Кількість питань: ".$str."</p>";
	            if (file_exists("users\\".$_SESSION['login']."\\"."test\\".$_GET['testid'].'\\res.txt')) {
	            	$fd = fopen('users\\'.$_SESSION['login'].'\\'.'test\\'.$_GET['testid'].'\\res.txt', 'r') or die("Помилка");
					while(!feof($fd))
					{
						$str = htmlentities(fgets($fd));
					}   
	            	echo "<p>Ваш кращий результат: ".(int)$str."%</p>";
	            }
	            else {
	            	echo "<p>Ваш результат: 0%</p>";
	        }
	            echo"
	            <p style='text-align:justify;margin-right:20px;'><b>Увага!</b> Натискаючи кнопку \"Пропустити\" ви не зможете повернутись на тест який пропустили. Якщо ви хочете перейти до наступного або попереднього тесту натисніть кнопку \"Наступний\" або \"Попередній\". Натискаючи кнопку \"Завершити тест\", тест завершиться навіть якщо ви відповіли не на всі запитання. Тест завершиться автоматично тоді, коли будуть дані відповіді на всі питаня.</p>
	            <div style='text-align:center; '><form action='test.php' method='get'><button class='starttask' type='submit' name='testid' value='".$row['test_id']."'>Почати тестування</button></div>
	            </div>";
	            
	            }
    	?>

    </div>

</body>
</html>
