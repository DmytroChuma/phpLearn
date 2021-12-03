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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script>
    <div class='test-page' style='height: calc(100% - 60px);display: flex;'>
        <?php
            function getNextNumber($count){
                for($i = $_SESSION["number"] + 1; $i<=$count;$i++){
                    if($_SESSION[$i."test"] !== "" && $i <= $count){
                        continue;
                    }
                    else{
                        return $i;
                    }
                }
                return 0;
            }
            function getPrevNumber($count){
                for($i = $_SESSION["number"] - 1; $i>=1;$i--){
                    if($_SESSION[$i."test"] !== ""){
                        continue;
                    }
                    else{
                        return $i;
                    }
                }
                return 0;
            }
            if(isset($_POST['test-exit'])){
                $_SESSION['ans_count'] = 0;
                header("Location: test_result.php"); 
                exit;
            }

            $id;
  
            if(isset($_POST['test_id'])) {
                 $id = $_POST['test_id'];
            }
            else {
                $id = $_SESSION['testid'];
            }
            
            $sql = 'SELECT * FROM test WHERE test_id = "'.$id.'"';
            $result = mysqli_query($mysqli, $sql);
            $number;
            $prev = false;  
            $next = true;
            
            while ($row = mysqli_fetch_array($result)) {
                $test_count = substr_count($row['test'], '|t');

                if (isset($_POST['testnumber'])) {
                $number = $_POST['testnumber'];
                $_SESSION['number'] = $number;

                 if ( getPrevNumber($test_count) == 0) {
                        $prev = false;
                    }
                    else {
                        $prev = true;
                    }
                    if ( getNextNumber($test_count) == 0) {
                        $next = false;
                    }
                    else{
                        $next = true;
                    }


                }
                else if (isset($_POST['next'])){

                    $number = $_SESSION['number'] = getNextNumber($test_count);

                    if ( getPrevNumber($test_count) == 0) {
                        $prev = false;
                    }
                    else {
                        $prev = true;
                    }


                    if ( getNextNumber($test_count) == 0) {
                        $next = false;

                    }
                    else{
                        $next = true;
                    }


                    
                }
                else if (isset($_POST['prev'])){
                    $number = $_SESSION['number'] = getPrevNumber($test_count);


                     if ( getPrevNumber($test_count) == 0) {
                        $prev = false;
                    }
                    else {
                        $prev = true;
                    }


                    if ( getNextNumber($test_count) == 0) {
                        $next = false;

                    }
                    else{
                        $next = true;
                    }

                }
                else if(isset($_POST['skip'])) {
                    $_SESSION[$_SESSION['number']."test"] = 'skip';
                    if (isset($_SESSION['ans_count'])) {
                        $_SESSION['ans_count']++;
                    }
                    else {
                        $_SESSION['ans_count']= 1;
                    }
                    if (getNextNumber($test_count) == 0) {
                        $number = $_SESSION['number'] = getPrevNumber($test_count);
                    }
                    else{
                        $number = $_SESSION['number'] = getNextNumber($test_count);
                    }

                    if ( getPrevNumber($test_count) == 0) {
                        $prev = false;
                    }
                    else {
                        $prev = true;
                    }
                    if ( getNextNumber($test_count) == 0) {
                        $next = false;
                    }
                    else{
                        $next = true;
                    }
                    if ($_SESSION['ans_count'] == $test_count) {    
                        header("Location: test_result.php"); 
                        exit;
                    }

                }
                 else if (isset($_POST['save'])){
                      if (isset($_POST['variant'])){
                       $_SESSION[$_SESSION['number']."test"] = $_POST['variant']; 
                     }
                     else if (isset($_POST['check'])){
                         $_SESSION[$_SESSION['number']."test"] = $_POST['check']; 
                     }
                        if (getNextNumber($test_count) == 0) {
                        $number = $_SESSION['number'] = getPrevNumber($test_count);
                    }
                    else{
                        $number = $_SESSION['number'] = getNextNumber($test_count);
                    }

                    if ( getPrevNumber($test_count) == 0) {
                        $prev = false;
                    }
                    else {
                        $prev = true;
                    }
                    if ( getNextNumber($test_count) == 0) {
                        $next = false;
                    }
                    else{
                        $next = true;
                    }
                    if (isset($_SESSION['ans_count'])) {
                        $_SESSION['ans_count']++;
                    }
                    else {
                        $_SESSION['ans_count']= 1;
                    }
                    if ($_SESSION['ans_count'] == $test_count) {
                        header("Location: test_result.php"); 
                        exit;
                    }
 
                }

                else {
                     $number = 1;
                    $_SESSION['number'] = $number;
                 }


                $test = explode("|t", $row['test']);
                unset($test[0]);           
                $text="";
                    $strcode = explode("/code", $test[$number]);
                    for ($i = 0; $i<count($strcode); $i++) {
                        if ($i%2 != 0) {
                            $text .= "<textarea style='margin-top:5px;
                            ' class='codetext' readonly >".htmlspecialchars($strcode[$i])."</textarea>";
                        }
                        else{
                            $text .= $strcode[$i];
                        }
                    }
                    $pos = strpos($text, "|");
                    $text=substr($text, 0, $pos);
                        
               $variants = explode("\n", $test[$number]);
               unset($variants[0]);
                echo " 
                <div class='about-test' style='height: 100%;width: 100%;display: flex; flex-direction: column;'>
                <form action='test.php' method='post' style='margin-block-end: 0em;flex: 1 1 30%; display: flex; flex-direction: column;'>
                    <div class='test-text'>";
                    echo "<div style='margin-right:20px;margin-left:20px; margin-top:20px;font-size:18px;'>".$number.". ".$text."</div>";
 
                echo "</div>
                    <div class='test-variants'>
                    <div style='font-size:18px;margin-left:20px; margin-top:20px'>Оберіть варіант/варіанти відповіді</div>
                     ";  
                    $rnum=1;
                    $c="";
                    $check=false;
                    foreach ($variants as $value) {
                        if(stristr($value, "|r")){
                            $str = str_replace("|r","",$value);
                            $str = str_replace("\n","",$str);
                            $r= str_replace("|r","<div style='margin-right:20px;margin-left:20px; margin-top:20px'>
                                <input type='radio' id='variant".$rnum."' name='variant' value='".$str."' required> 
                                <label for='variant".$rnum."'>",$value)."
                           </label></div>";
                           $r = trim($r);
                           $check = false;
                           echo str_replace("\n","",$r);
                        }
                        else if(stristr($value, "|c")){
                            $str = str_replace("|c","",$value);
                            $str = str_replace("\n","",$str);
                            $check=true;
                            $c .= str_replace("|c", "<div style='margin-right:20px;margin-left:40px; margin-top:20px'>
                            <label class='checkcontainer'>", $value)."<input class='required-checkbox' type='checkbox' name='check[]' value='".$str."'>
                            <span class='checkmark'></span>
                        </label></div>";


                        }
                        $rnum++;
                    }
                    if($check){
                        $c = str_replace("\n","",$c);
                        echo "<input id='radio-for-checkboxes' type='radio' name='radio-for-required-checkboxes' required/>".$c;
                    }
 

                    echo "</div>
                    <button type='submit' class='save-answear' name='save'>Зберегти відповідь</button>
                       </form>
                        <form style='    margin-block-end: 0em;' action='test.php' method='post'>
                    <div class='nav-buttons'>";

                    echo "
                        
                        <div style='display: flex; margin-left: 20px;'>";
                        if($prev){
                            echo"<button type='submit' class='nav-test-button' name='prev'>Попереднє</button>";
                        }
                        else {
                            echo"<button class='nav-test-button-d' disabled='disabled' name='prev'>Попереднє</button>";   
                        }
                        
                        echo"<button type='submit' class='nav-test-button' name='skip'>Пропустити</button>";
                        if ($next) {
                            echo "<button type='submit' class='nav-test-button' name='next'>Наступне</button>";
                        }else{
                            echo "<button class='nav-test-button-d' disabled='disabled' name='next'>Наступне</button>";
                        }
                        echo "
                        </div>
                        
                    </div>
                   </form> 
                </div>
                <div class='test-number' style='    
                width: 300px;
                background: #F2F2F2;
                float: right;
                display: flex;
                flex-direction: column;
                flex: 0 1 30%;
                max-width: 400px;
                min-width: 400px;
                border-left:2px solid black;'>"; 
                if($test_count > 50){
                    echo"<div class='numbers' style='margin-left:23px;height: 100%; overflow: auto;'><form method='POST'>";
                }else{
                    echo"<div class='numbers' style='margin-left:25px;height: 100%; overflow: auto;'><form method='POST'>";
                }
                echo "<input type='hidden' name='test_id' value='".$id."' >";
                for ($i = 1; $i <= $test_count; $i++ ) {
                    $classname="test-button";
                    if(isset($_SESSION[$i.'test'])){
                        if($_SESSION[$i.'test'] != ''){ //'skip'
                            $classname="test-button-d";
                        }
                    }
                    if ($test_count > 50) {
                        if ($classname=="test-button-d") {
                            echo "<button style='margin-right: 23px;' disabled='disabled' class='".$classname."' type='submit' name='testnumber' value='".$i."'>".$i."</button>";
                        }
                        else{
                            echo "<button style='margin-right: 23px;'  class='".$classname."' type='submit' name='testnumber' value='".$i."'>".$i."</button>";
                        }
                        
                    }
                    else{
                        if ($classname=="test-button-d") {
                            echo "<button class='".$classname."' disabled='disabled' type='submit' name='testnumber' value='".$i."'>".$i."</button>";
                        }
                        else{
                            echo "<button class='".$classname."' type='submit' name='testnumber' value='".$i."'>".$i."</button>";
                        }
                       
                    }
                }  
                echo "</form></div>
                <div style='flex: 1 1 30%;
                max-height: 70px;
                min-height: 70px;
                    padding-top: 20px;
                        border-top: 2px solid black;
                text-align:center;'>
                <form action='test.php' method='post'><button class='test-exit' name='test-exit' type='submit'>Завершити тест</button></form></div>
                </div>";
                
            }
        ?>
    </div>
 <script>
$(document).ready(function(){
  
var editor;
var ednum = 0; 
ace_config = {
    maxLines: Infinity,
    enableBasicAutocompletion: true,
    enableSnippets: false,
    enableLiveAutocompletion: false

};

$('.editor').each(function( index ) {
    ednum++; 
    _name = "editor_"+ ednum;
    code = "var name = $(this).data('name');"
    +_name+" = ace.edit(this);"
    +_name+".setTheme('ace/theme/monokai');"
    +_name+".getSession().setMode('ace/mode/php');"

    +_name+".setOptions(ace_config);"
    +_name+".session.setOptions('useWorker', false);"
    +_name+".setReadOnly(true);"
    +"var code_"+ednum+" = $('textarea[name='+name+']');"
    +_name+".getSession().setValue($('input[name='+name+']').val());"
    +_name+".getSession().on('change', function(){"
    +"$('input[name='+name+']').val("+_name+".getSession().getValue());"
    +"});";
    eval(code);
    document.querySelector('.editor').classList.toggle('monochrome');
});
  }); 
</script>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>
            function bindItemsInput() {
    var inputs = document.querySelectorAll('[name="check[]"]')
    var radioForCheckboxes = document.getElementById('radio-for-checkboxes')
    function checkCheckboxes () {
        var isAtLeastOneServiceSelected = false;
        for(var i = inputs.length-1; i >= 0; --i) {
            if (inputs[i].checked) isAtLeastOneCheckboxSelected = true;
        }
        radioForCheckboxes.checked = isAtLeastOneCheckboxSelected
    }
    for(var i = inputs.length-1; i >= 0; --i) {
        inputs[i].addEventListener('change', checkCheckboxes)
    }
}
bindItemsInput()
        </script>

    </body>
</html>
