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
 
require_once("dbconnect.php");

$sql = 'SELECT date,type, `test_themes_id` FROM `test` 
        UNION SELECT date,type, theme_name FROM theory_themes  
        UNION SELECT date,type, name FROM task  
        ORDER BY date DESC LIMIT 10';

$result = mysqli_query($mysqli, $sql);
while ($row = mysqli_fetch_array($result)) {
    if ($row['type'] == 1) {
        $sql = 'SELECT sections.*, theory_themes.* FROM sections, theory_themes WHERE sections.id = theory_themes.section_id AND theory_themes.date = "'.$row['date'].'"  LIMIT 10';
        $res = mysqli_query($mysqli, $sql);
        while ($arr = mysqli_fetch_array($res)) {
            $str = mb_strimwidth($arr['text'], 0, 100, "...");
            echo "
            <div class='new'>
            <h3>Новий запис у розділі ".$arr['name']."!</h3>
            <label class='date_l'>Дата створення: ".$arr['date']."</label> <br>
            <label class='new_a' >Тема: ".$arr['theme_name']."</label><br>
            <div class='text'>".$str."</div> 
            <form class='textform' action='theory_theme.php' method='get'>
            <button class='read' name='theme' value='".$arr['theme_name']."'>Читати далі</button>
            </form>
            </div>";
        }
    }
     if ($row['type'] == 2) {
        $sql = 'SELECT  test.* FROM  test WHERE test.date = "'.$row['date'].'"  LIMIT 10';
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
            <h3>Нові тести '".$arr['name']."'!</h3>
            <label class='date_l'>Дата створення: ".$arr['date']."</label> <br>
            <label class='new_t'>Теми тестів: ".$themes."</label> <br>
            <form class='testform' action='about_test.php' method='get'>";
            if (isset($_SESSION['login'])) {
                echo "<button class='tests' name='testid' value='".$arr['test_id']."'>Перейти до тесту</button>";
            }
            else {
                echo " <button class='testsd' disabled='disabled' name='".$arr['test_id']."'>Перейти до тесту</button>";
            }
            echo "
            </form>
            </div>";
        }   
    }
     if ($row['type'] == 3) {
        $sql = 'SELECT * FROM task WHERE date = "'.$row['date'].'"  LIMIT 10';
        $res = mysqli_query($mysqli, $sql);
        while ($arr = mysqli_fetch_array($res)) {
        echo "
            <div class='new'>
            <h3>Нова задача '".$arr['name']."'!</h3>
            <label class='date_l'>Дата створення: ".$arr['date']."</label> <br>";
                    $tags = explode(",", $arr['tags']);
                    echo "<div class='tags'>";
                    foreach ($tags as $tag) {
                        echo "
                        <a class='tag' href='search.php?tag=".urlencode($tag)."'>".$tag."</a>";
                    }
                    echo "</div><form class='textform' action='task_info.php' method='get'>";
                    if (isset($_SESSION['login'])) {
                        echo " <button class='taskbutton' name='taskid' value='".$arr['id']."'>Перейти до задачі</button>";
                    }
                    else {
                        echo " <button class='taskbuttond' disabled='disabled'  name='taskb' value='".$arr['name']."'>Перейти до задачі</button>";
                    }

                    
                   
                    echo "</form>
            </div>";
        }
    }
}

?>

</div>

</body>
</html>

