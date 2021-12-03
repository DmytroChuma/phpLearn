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

    <?php 
        if (isset($_SESSION['login'])){
             ?>
             <div class='tests-name'>
            <?php
                $sql = 'SELECT test.* FROM  test ORDER BY test.name ASC';
                $res = mysqli_query($mysqli, $sql);

                while ($arr = mysqli_fetch_array($res)) {
                    $str = explode(",", $arr['test_themes_id']);
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
                    <h3>Тести '".$arr['name']."'</h3>
                    <label class='date_l'>Дата створення: ".$arr['date']."</label> <br>
                    <label class='new_t'>Теми тестів: ".$themes."</label> <br>
                    <form class='testform' action='about_test.php' method='get'>";
                    echo "<button class='tests' name='testid' value='".$arr['test_id']."'>Перейти до тесту</button>
                    </form>
                    </div>";
                    }
            ?>
            </div>
            <?php
        }
        else {
            ?>
            <div class='needLog'><p class='needT'>Щоб проходити тести потрібно авторизуватись!</p>
                <form action='login.php'><button class='tb' type='submit'>Авторизація</button></form>
                <p class='needT'>Якщо у вас немає облікового запису, зареєструйтесь.</p>
                <form action='registration.php'><button class='tb' type='submit'>Реєстрація</button></form>
            </div>
      <?php  }

    ?>
    
</body>
</html>
