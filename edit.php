<html>
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="css/index.css">
 <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body>

    <?php
    function goHome(){
        header("Location: index.php"); 
        exit;
    }
    function loadNames($query){
        global $mysqli;
        $str="";
        $result = mysqli_query($mysqli, $query);
        while ($row = mysqli_fetch_array($result)) {
            $str.="<option value='".$row['name']."'>".$row['name']."</option>";
        }
        return $str;
    }
        include('header.php');
        if ($_SESSION['type']!=1){
            goHome();
        }
        if (!isset($_GET['type'])) {
            goHome();
        }
    ?>
    <div class='news'>
                <?php
                $_SESSION['name'] = "";
                $_SESSION['section'] = "";
                $_SESSION['content'] = "";
                $_SESSION['id'] = "";
                echo "<form style='font-size: 20px;display: flex;flex-direction: column; margin: 0px 20px; margin-bottom: 20px;' method='post' action='editor.php?type=".$_GET['type']."'>";
                    if ($_GET['type'] == 0) {
                        echo '<p>Оберіть розділ зі списку щоб редагувати. <b>Увага!</b> при видаленні розділу будуть видалені всі його теми.</p>
                        <select name="select"><option value="Оберіть">Оберіть розділ</option>';
                        echo loadNames('SELECT name FROM sections')."</select>";
                    }
                    else if ($_GET['type'] == 1) {
                        echo '<p>Оберіть тему зі списку або натисніть кнопку "Додати" щоб створити нову</p>
                        <select name="select"><option value="Оберіть">Оберіть тему</option>';
                        echo loadNames('SELECT theme_name as name FROM theory_themes')."</select>";
                    }
                    else if ($_GET['type'] == 2) {
                        echo '<p>Оберіть назву тесту зі списку або натисніть кнопку "Додати" щоб створити новий</p>
                        <select name="select"><option value="Оберіть">Оберіть тест</option>';
                        echo loadNames('SELECT name FROM test')."</select>";
                    }
                    else {
                        echo '<p>Оберіть назву задачі зі списку або натисніть кнопку "Додати" щоб створити нову</p>
                        <select name="select"><option value="Оберіть">Оберіть задачу</option>';
                        echo loadNames('SELECT name FROM task')."</select>";
                    }
                    if (isset($_GET['choose'])) {
                        echo '<p style="color:red;">Щоб редагувати або видалити спочатку оберіть назву зі списку!</p>';
                    }
                

                ?>
                <button type='submit' class='editbutton' name='editbutton' value="1">Додати</button>
                <button type='submit' class='editbutton' name='editbutton' value="2">Редагувати</button>
                <button type='submit' class='editbutton' name='editbutton' value="3">Видалити</button>
        </form>
    </div>


</body>
</html>