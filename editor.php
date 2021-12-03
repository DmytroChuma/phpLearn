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
    function checkEditType() {
        if (isset($_POST['editbutton'])) {
            $eb = $_POST['editbutton'];
        }
        else {
            $eb = $_GET['editbutton'];
        }
        return $eb;
    }
    function str_replace_once($search, $replace, $text) 
    { 
        $pos = strpos($text, $search); 
        return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
    }
    function error0($error, $type) {
        $_SESSION['name'] = $_POST['name'];
        if($_POST['save-button']!='add'){
            $_SESSION['id'] = $_POST['id'];
        }
        header("Location: editor.php?error=".$error."&type=".$type."&editbutton=".checkEditType()); 
        exit;
    }
    function error1($error, $type) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['section'] = $_POST['section'];
        $_SESSION['content'] = $_POST['content'];
        if($_POST['save-button']!='add'){
            $_SESSION['id'] = $_POST['id'];
        } 
        header("Location: editor.php?error=".$error."&type=".$type."&editbutton=".checkEditType()); 
        exit;
    }
    function error2($error, $type){
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['themes'] = $_POST['check'];
        $_SESSION['content'] = $_POST['content'];
        $_SESSION['answears'] = $_POST['answears'];
        $_SESSION['details'] = $_POST['details'];
        $_SESSION['explain'] = $_POST['explain'];
        if($_POST['save-button']!='add'){
            $_SESSION['id'] = $_POST['id'];
        } 
        header("Location: editor.php?error=".$error."&type=".$type."&editbutton=".checkEditType()); 
        exit;
    }
    function error3($error, $type){
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['tag'] = $_POST['tag'];
        $_SESSION['content'] = $_POST['content'];
        $_SESSION['task'] = $_POST['task'];
        $_SESSION['code'] = $_POST['code'];
        if($_POST['save-button']!='add'){
            $_SESSION['id'] = $_POST['id'];
        } 
        header("Location: editor.php?error=".$error."&type=".$type."&editbutton=".checkEditType()); 
        exit;
    }

    function createText($text){
        $text = explode("/code", $text);
        $str="";
        for ($i =0; $i < count($text);$i++){    
            if($i%2!=0){
                $text[$i] = str_replace('<br>','\n',$text[$i]);
                $text[$i] = str_replace('"','\"',$text[$i]);
                $text[$i] = str_replace("</p>","\n",$text[$i]);
                $text[$i] = str_replace("<p>","",$text[$i]);
                $text[$i] = str_replace("</div>","\n",$text[$i]);
                $str .= "/code".str_replace("<div>", "", $text[$i])."/code";
            }
            else{
                $text[$i] = str_replace("</div>","</p>",$text[$i]);
                $str .= str_replace("<div>","<p>",$text[$i]);
                }
        }
        $str = str_replace("&lt;","<",$str);
        $str = str_replace("&gt;",">",$str);
        $str = str_replace("&nbsp;","",$str);

        return $str;
    }
     include('header.php');
        if(isset($_GET['edit-type'])){
            if ($_GET['edit-type'] == 0) {
                if($_POST['save-button']=='add'){
                    $sql='INSERT INTO sections (name) VALUES ("'.$_POST['name'].'")';
                    if (!mysqli_query($mysqli, $sql)){
                        error0(1, $_GET['edit-type']);
                    }
                    else{
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
                else{
                    $sql='UPDATE sections SET name = "'.$_POST['name'].'" WHERE id ='.$_POST['id'];
                    if (!mysqli_query($mysqli, $sql)){
                        error0(1, $_GET['edit-type']);
                    }
                    else{
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
            }
            else if ($_GET['edit-type'] == 1) {
                if ($_POST['section'] == "Розділ") {
                    error1(1, $_GET['edit-type']);
                }
                if (trim($_POST['content'])==""){
                    error1(2, $_GET['edit-type']);
                }
                $sql = 'SELECT * FROM sections WHERE name ="'.$_POST['section'].'"';
                $res = mysqli_query($mysqli, $sql);
                while ($arr = mysqli_fetch_array($res)) {
                    $section = $arr['id'];
                }
                $str = createText($_POST['content']);
                  
                if($_POST['save-button']=='add'){
                    $sql='INSERT INTO theory_themes (section_id, theme_name, date, text) VALUES ("'.$section.'", "'.$_POST['name'].'", "'.date("Y-m-d H:i:s").'", "'.$str.'")';
                    if (!mysqli_query($mysqli, $sql)){
                        error1(3, $_GET['edit-type']);
                    }
                    else{
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
                else {
                    $sql='UPDATE theory_themes SET section_id = "'.$section.'", theme_name = "'.$_POST['name'].'", text = "'.$str.'" WHERE theme_id = "'.$_POST['id'].'"';
                    if (!mysqli_query($mysqli, $sql)){
                        error1(3, $_GET['edit-type']);
                    }
                    else {
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
            }
            else if ($_GET['edit-type'] == 2) {
                if (!isset($_POST['check'])) {
                    error2(1, $_GET['edit-type']);
                }
                $themes = implode(",",$_POST['check']);
                if($_POST['save-button']=='add'){
                    $sql='INSERT INTO test (test_themes_id, date, name, details, test, answear, explain_test) VALUES ("'.$themes.'", "'.date("Y-m-d H:i:s").'", "'.$_POST['name'].'", "'.$_POST['details'].'", "'.$_POST['content'].'", "'.$_POST['answears'].'", "'.$_POST['explain'].'")';
                    if (!mysqli_query($mysqli, $sql)){
                         error2(2, $_GET['edit-type']);
                    }
                    else{
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
                else {
                    $sql='UPDATE test SET test_themes_id = "'.$themes.'", name = "'.$_POST['name'].'", details = "'.$_POST['details'].'", test = "'.$_POST['content'].'", answear = "'.$_POST['answears'].'", explain_test = "'.$_POST['explain'].'" WHERE test_id = "'.$_POST['id'].'"';
                    if (!mysqli_query($mysqli, $sql)){
                         error2(2, $_GET['edit-type']);
                    }
                    else {
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
            }
            else {
                if (trim($_POST['content'])==""){
                    header("Location: editor.php?error=1&type=".$_GET['edit-type']."&editbutton=".checkEditType()); 
                    exit;
                }
               

                $tag = explode(",",$_POST['tag']);
                $tag = array_map("trim",$tag);
                $tag = implode(",",$tag);

                $task = $_POST['task'];
                if (substr( $task, 0, 5 ) !== "<?php") {
                    $task = "<?php\n".$task;
                }
                if (substr( $task, strlen($task) - 2) !== "?>") {
                    $task = $task."\n?>";
                }

                $code = $_POST['code'];
                if (substr( $code, 0, 5 ) !== "<?php") {
                    $code = "<?php\n".$code;
                }
                if (substr( $code,strlen($code) - 2) !== "?>") {
                    $code = $code."\n?>";
                }

                if($_POST['save-button']=='add'){
                    $sql='INSERT INTO task (name, tags, date, details, task, code) VALUES ("'.$_POST['name'].'", "'.$tag.'", "'.date("Y-m-d H:i:s").'", "'.$_POST['content'].'", "'.$task.'", "'.$code.'")';
                    if (!mysqli_query($mysqli, $sql)){
                        error3(2, $_GET['edit-type']);
                    }
                    else{
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }
                else {
                    $sql='UPDATE task SET name = "'.$_POST['name'].'", tags = "'.$tag.'", details ="'.$_POST['content'].'", task = "'.$task.'", code = "'.$code.'" WHERE id="'.$_POST['id'].'"';
                    if (!mysqli_query($mysqli, $sql)){
                        error3(3, $_GET['edit-type']);
                    }
                    else {
                        header("Location: edit.php?type=".$_GET['edit-type']); 
                        exit;
                    }
                }

            }
        }
        $name = "";
        $text = "";
        $section = "";
        $id = "";
        $task = "";
        $tag = "";
        $code = "";
        $themes = "";
        $answear = "";
        $explain = "";
        $details = "";
        if (!isset($_GET['error'])){
                if (checkEditType() == '2') {
                    if ($_GET['type'] == 0) {
                        $sql = 'SELECT * FROM sections WHERE name = "'.$_POST['select'].'"';
                        $result = mysqli_query($mysqli, $sql);
                        while ($res = mysqli_fetch_array($result)) {
                            $name = $res['name'];
                            $id = $res['id'];
                        }
                    }
                    else if ($_GET['type'] == 1 ) {
                        $sql = 'SELECT * FROM theory_themes WHERE theme_name = "'.$_POST['select'].'"';
                        $result = mysqli_query($mysqli, $sql);
                        while ($res = mysqli_fetch_array($result)) {
                            $name = $res['theme_name'];
                            $section = $res['section_id'];
                            $text = $res['text'];
                            $id = $res['theme_id'];
                            $sql = 'SELECT * FROM sections WHERE id = "'.$section.'"';
                            $res = mysqli_query($mysqli, $sql);
                            while ($arr = mysqli_fetch_array($res)) {
                                $section = $arr['name'];
                            }
                        }
                    }
                    else if ($_GET['type'] == 2) {
                        $sql = 'SELECT * FROM test WHERE name = "'.$_POST['select'].'"';
                        $result = mysqli_query($mysqli, $sql);
                        while ($res = mysqli_fetch_array($result)) {
                            $name = $res['name'];
                            $text = $res['test'];
                            $themes = $res['test_themes_id'];
                            $id = $res['test_id'];
                            $details = $res['details'];
                            $answear = $res['answear'];
                            $explain = $res['explain_test'];
                        }
                    }
                    else {
                        $sql = 'SELECT * FROM task WHERE name = "'.$_POST['select'].'"';
                        $result = mysqli_query($mysqli, $sql);
                        while ($res = mysqli_fetch_array($result)) {
                            $name = $res['name'];
                            $text = $res['details'];
                            $tag = $res['tags'];
                            $id = $res['id'];
                            $task = $res['task'];
                            $code = $res['code'];
                        }
                    }

                }
                if ( checkEditType() == '3') {
                    if ($_GET['type'] == 0) {
                        $sql = 'SELECT * FROM sections WHERE name = "'.$_POST['select'].'"';
                        $result = mysqli_query($mysqli, $sql);
                        while ($res = mysqli_fetch_array($result)) {
                            $sql = 'DELETE FROM theory_themes WHERE section_id ="'.$res['id'].'"';
                            mysqli_query($mysqli, $sql);
                        }
                        $sql = 'DELETE FROM sections WHERE name ="'.$_POST['select'].'"';
                    }
                    else if ($_GET['type'] == 1 ) {
                        $sql = 'DELETE FROM theory_themes WHERE theme_name ="'.$_POST['select'].'"';
                    }
                    else if ($_GET['type'] == 2 ) {
                        $sql = 'DELETE FROM test WHERE name ="'.$_POST['select'].'"';
                    }
                    else {
                        $sql = 'DELETE FROM task WHERE name ="'.$_POST['select'].'"';
                    }
                    mysqli_query($mysqli, $sql);
                    header("Location: edit.php?type=".$_GET['type']); 
                    exit;
                }
            if ($_POST['select'] == "Оберіть" && $_POST['editbutton'] != '1') {
                header("Location: edit.php?choose=true&type=".$_GET['type']); 
                exit;
            }
            if ($_SESSION['type']!=1){
                goHome();
            }
            if (!isset($_GET['type'])) {
                goHome();
            }
        }
        else {

            if (checkEditType() == '1' || checkEditType() == '2') {
                if ($_GET['type'] == 0) {
                    $name = $_SESSION['name'];
                    $id = $_SESSION['id'];
                }
                else if ($_GET['type'] == 1 ) {
                    $name = $_SESSION['name'];
                    $section = $_SESSION['section'];
                    $text = $_SESSION['content'];
                    $id = $_SESSION['id'];
                }
                else if ($_GET['type'] == 2) {
                    $name = $_SESSION['name'];
                    if (is_array($_SESSION['themes'])) {
                        $themes = implode(",",$_SESSION['themes']);
                    }
                    
                    $text = $_SESSION['content'];
                    $details = $_SESSION['details'];
                    $answear = $_SESSION['answears'];
                    $explain = $_SESSION['explain'];
                    $id = $_SESSION['id'];
                }
                else {
                    $name = $_SESSION['name'];
                    $tag = $_SESSION['tag'];
                    $text = $_SESSION['content'];
                    $id = $_SESSION['id'];
                    $task = $_SESSION['task'];
                    $code = $_SESSION['code'];
                }
            }
        }
    ?>
    <div class='news'>
        <?php 
            if ($_GET['type'] == 0) {
                ?>
                <?php
                    if(isset($_GET['error'])){
                        if($_GET['error']==1){
                            $str="Такий розділ вже існує!";
                        }
                        echo "<label style='display:block;color:red;text-align:center;width:100%;' class='edit'>".$str."</label>"; 
                    }
                ?>
                <div class='editor-body'>
                <form action='editor.php?edit-type=0' method='post' onsubmit="return save()">
                <label class='edit'>Назва розділу</label>
                        <br>
                        <input name='name' class='edit-name' type='text' placeholder="Введіть назву розділу" required value='<?php echo $name; ?>'>
                <?php 
                    $value = 'add';
                    if ((isset($_POST['editbutton']) || isset($_GET['editbutton'])) && checkEditType() != 1) {
                        echo '<input type="hidden" id="id" name="id" value="'.$id.'">';
                        $value = 'edit';
                    }
                    echo '<input type="hidden"  name="editbutton" value="'.checkEditType().'">';
                    echo "<button class='edit-save' type='submit' name='save-button' value='".$value."' >Зберегти</buton>";
                    ?>
                </form>
                </div>
                    <?php 
            }
            else if ($_GET['type'] == 1 ) {
                ?>
                <div class='editor-body'>
                    <form action='editor.php?edit-type=1' method='post' onsubmit="return save()">
                     

                        <?php
                            if(isset($_GET['error'])){
                                if($_GET['error']==1){
                                    $str="Потрібно обрати розділ!";
                                }
                                else if($_GET['error']==2){
                                    $str="Напишіть текст!";
                                }
                                else{
                                    $str ="Така тема вже існує!";
                                }
                                echo "<label style='display:block;color:red;text-align:center;width:100%;' class='edit'>".$str."</label>";
                            }
                        ?>
                        <label class='edit'>Розділ</label>
                        <br>
                        <select name='section' class='edit'>
                            <option>Розділ</option>
                            <?php
                                $sql = 'SELECT name FROM  sections';
                                $result = mysqli_query($mysqli, $sql);
                                while ($res = mysqli_fetch_array($result)) {
                                    if ($res['name'] == $section) {
                                        echo "<option selected='selected'>".$res['name']."</option>";
                                    }
                                    else {
                                    echo "<option>".$res['name']."</option>";
                                    }   
                                }
                            ?>  
                        </select>
                        <br>
                        <label class='edit'>Назва теми</label>
                        <br>
                        <input name='name' class='edit-name' type='text' placeholder="Введіть назву теми" required value='<?php echo $name; ?>'>
                        <div style='height:605px'>
                            <?php 
                                $str = str_replace("<?php","&lt;?php", $text);
                                $str = str_replace("?>","?&gt;", $str);
                                $str = str_replace("<?=","&lt;?=", $str);
                                $str = str_replace("<?","&lt;?", $str);

                                echo '<input type="hidden" id="content" name="content" value="'.htmlspecialchars($str).'" />';
                                
                            ?>
                           
                            <script src='textedit.js'></script>
                        </div>

                        <?php 
                            $value = 'add';
                            if ((isset($_POST['editbutton']) || isset($_GET['editbutton'])) && checkEditType() != 1) {
                                echo '<input type="hidden" id="id" name="id" value="'.$id.'">';
                                $value = 'edit';
                            }
                                echo '<input type="hidden"  name="editbutton" value="'.checkEditType().'">';
                                echo "<button class='edit-save' type='submit' name='save-button' value='".$value."' >Зберегти</buton>";
                        ?>
                        
                    </form>
                </div>
                <?php 
            }
            else if ($_GET['type'] == 2) {
                ?>
                 <div class='editor-body'>
                    <form action='editor.php?edit-type=2' method='post' onsubmit="return save()">
                        <?php
                            if(isset($_GET['error'])){
                                if($_GET['error']==1){
                                    $str="Потрібно обрати хочаб 1 тему!";
                                }
                                else {
                                    $str="Такий тест уже існує!";
                                }
                                echo "<label style='display:block;color:red;text-align:center;width:100%;' class='edit'>".$str."</label>";
                            }

                        ?>

                        <label class='edit'>Назва тесту</label>
                        <input class='edit-name' type='text' placeholder="Введіть назву тесту" name='name' required  value='<?php echo $name; ?>'>
                        <br>
                        <label class='edit'>Оберіть теми</label>
                        <div style='display:flex;flex-direction: column;border: 2px solid black; height: 150px;overflow: auto; margin-bottom:20px'>
                            <?php 
                                $sql = 'SELECT theme_id,theme_name FROM  theory_themes';
                                $result = mysqli_query($mysqli, $sql);
                                
                                $theme = explode(",",$themes);
                                while ($res = mysqli_fetch_array($result)) {
                                    $check = "";
                                    if (in_array($res['theme_id'], $theme)) {
                                        $check = "checked='checked'";
                                    }
                                    echo "<div style='margin-right:20px;margin-left:40px; margin-bottom:10px;margin-top:10px;'><label class='checkcontainer'>".$res['theme_name']."<input class='required-checkbox' type='checkbox' ".$check." name='check[]' value='".$res['theme_id']."'><span class='checkmark'></span>
                                        </label></div>";
                                }
                            ?>
                        </div>
                         <label class='edit'>Додайте короткий опис тесту.</label>
                        <div style='height:150px; margin-bottom:20px;'>
                            <?php 
                                 echo "<textarea name='details' class='edit' required>".$details."</textarea>";
                            ?>
                            
                        </div>
                        <label class='edit'>Щоб створити питання напишіть на його початку тег '|t'. Вказувати номер запитання не потрібно. Щоб додати варіант відповіді, як radioButton напишіть тег '|r', щоб додати checkBox напишіть '|c'. Щоб додати приклад помістіть його між тегів '/code' та '/code'.
                        </label>
                        <div style='height:300px; margin-bottom:20px;'>
                            <?php 
                                 echo "<textarea name='content' class='edit' required >".$text."</textarea>";
                            ?>
                        </div>
                         <label class='edit'>Щоб створити відповідь для тесту напишіть тег '|a'.</label>
                        <div style='height:300px; margin-bottom:20px;'>
                            <?php 
                                 echo "<textarea name='answears' class='edit' required>".$answear."</textarea>";
                            ?>
                            
                        </div>
                         <label class='edit'>Щоб додати пояснення для тесту напишіть тег '|e'.</label>
                        <div style='height:300px; '>
                            <?php 
                                 echo "<textarea name='explain' class='edit' required>".$explain."</textarea>";
                            ?>
                            
                        </div>
                        <?php 
                           $value = 'add';
                            if ((isset($_POST['editbutton']) || isset($_GET['editbutton'])) && checkEditType() != 1) {
                                echo '<input type="hidden" id="id" name="id" value="'.$id.'">';
                                $value = 'edit';
                            }
                                echo '<input type="hidden"  name="editbutton" value="'.checkEditType().'">';
                                echo "<button class='edit-save' type='submit' name='save-button' value='".$value."' >Зберегти</buton>";
                        ?>
                    </form>
                </div>
                <?php 
            }
            else {
                ?>
                 <div class='editor-body'>
                    <form action='editor.php?edit-type=3' method='post' onsubmit="return save()">
                        <?php 
                            if(isset($_GET['error'])){
                                if($_GET['error']==1){
                                    $str="Додайте опис задачі!";
                                }
                                else {
                                    $str="Така задача вже існує!";
                                }
                                echo "<label style='display:block;color:red;text-align:center;width:100%;' class='edit'>".$str."</label>";
                            }
                        ?>
                        <label class='edit'>Назва задачі</label>
                        <input class='edit-name' type='text' placeholder="Введіть назву задачі" name='name' required value='<?php echo $name; ?>'>
                        <br>
                        <label class='edit'>Теги задачі</label>
                        <br>
                        <label class='edit'>Запишіть теги для задачі через кому. Наприклад "тег1,тег2,тег3...".</label>
                        <input class='edit-name' type='text' placeholder="Введіть теги" name='tag' required value='<?php echo $tag; ?>'>
                        <br>
                        <label class='edit'>Додайте короткий опис задачі. Щоб додати приклад помістіть його між тегів '&lt;code&gt;' та '&lt;/code&gt;'</label>
                        <div style='height:300px; margin-bottom:20px;'>
                            <?php 
                                 echo "<textarea name='content' class='edit' required >".$text."</textarea>";
                            ?>
                        </div>
                        <label class='edit'>Напишіть функцію.</label>
                        <div style='height:150px; margin-bottom:20px; '>
                            <?php 
                                echo "<textarea name='code' class='edit' required >".$code."</textarea>";
                            ?>
                        </div>
                        <label class='edit'>Напишіть завдання до задачі.</label>
                        <div style='height:300px; '>
                            <?php 
                                echo "<textarea name='task' class='edit' required >".$task."</textarea>";
                            ?>
                        </div>
                        <?php 
                            $value = 'add';
                            if ((isset($_POST['editbutton']) || isset($_GET['editbutton'])) && checkEditType() != 1) {
                                echo '<input type="hidden" id="id" name="id" value="'.$id.'">';
                                $value = 'edit';
                            }
                                echo '<input type="hidden"  name="editbutton" value="'.checkEditType().'">';
                                echo "<button class='edit-save' type='submit' name='save-button' value='".$value."' >Зберегти</buton>";
                        ?>
                    </form>
                </div>
                <?php 
            }
        ?>
    </div>
    
</body>
</html>