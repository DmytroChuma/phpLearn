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
    <div class='news'>
        <?php 
            $dir = "users\\".$_SESSION['login']."\\test";
            $user_compete_test = scandir($dir);
            array_shift($user_compete_test);
            array_shift($user_compete_test);
            
            $dir = 'users';
            $users = scandir($dir);
            array_shift($users);
            array_shift($users);
            foreach ($user_compete_test as $test_id) {
                $sql = 'SELECT * FROM test WHERE test_id = "'.$test_id.'"';
                $result = mysqli_query($mysqli, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $name = $row['name'];
                }
                $user_results = array();
                $user_res = file_get_contents("users\\".$_SESSION['login']."\\test\\".$test_id."\\res.txt");
                echo "<div style='display:flex;flex-direction:row;' class='new'><div style='font-size:18px;'><h3 style='width:400px;'>".$name."</h3><br>Ваш результат: ".(int)$user_res."%</div><div style='margin-top:10px;font-size:18px;margin-left:60px;'>Топ 5 користувачів<div>";
                foreach ($users as $user_login) {
                    $dir = "users\\".$user_login."\\test\\".$test_id;
                    if(is_dir($dir)) {
                        $dir = "users\\".$user_login."\\test\\".$test_id."\\res.txt";
                        if (file_exists($dir)) {
                            $res = file_get_contents($dir);
                            if (count($user_results) <= 5) {
                                $user_results[$user_login]=$res;
                            }
                            else {
                                if (min($user_result) < $res) {
                                    $key=array_search(min($user_result), $user_results);
                                    if (false !== $key) {
                                        unset($user_results[ $key ]);
                                        $user_results[$user_login]=$res;
                                    }
                                }
                            }
                        }
                        
                    }

                }
                arsort($user_results);
                foreach($user_results as $key => $value){
                  echo "<div>Користувач $key виконав тест на ".(int)$value."%</div>";  
                }
                
                echo "</div></div></div>";
            }
        
        ?>
    </div>

</body>
</html>