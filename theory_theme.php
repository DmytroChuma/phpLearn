<html>
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="css/index.css">
 <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script>

<?php
    include('header.php');
?>
<div class='news'>
<?php
    require_once("dbconnect.php");

    $sql = 'SELECT theory_themes.* FROM theory_themes WHERE theme_name = "'.$_GET['theme'].'"';
    $result = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $sss = "";
        $num = 1;
        $arr = explode("/code", $row['text']);
    for ($i = 0; $i < count($arr); $i++) {
        if ($i == count($arr) - 1) {
            $sss .= $arr[$i];
            break;
        }
        if ($i%2!=0 ) {
            $sss .= htmlspecialchars($arr[$i]);
            $sss .= '" />';
        }
        else {
        $sss .= $arr[$i];
        $sss .= '<pre class="editor" data-name="editor_'.$num.'" id="editor_'.$num.'" style="font-size: 16px;"></pre> 
                <input  name="editor_'.$num.'" type="hidden" value="';
        }
        $num ++;
    }

        echo " 
        <div class='theme'>
        <div class='theme2'>
            <h3 class='theme_text'>".$row['theme_name']."</h3>
            <label class='date'>Дата створення: ".$row['date']."</label> <br>
            </div>
        </div>
        <div class='info'>
            <div class='infoo'>
            ".$sss."     
            </div>
        </div>";
    }
?>

 <script>
$(document).ready(function(){
  
var editor;
var ednum = 0; 
ace_config = {
    maxLines: Infinity,
    enableBasicAutocompletion: true,
    enableSnippets: true,
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
    +_name+".setReadOnly(true);"
    +"var code_"+ednum+" = $('textarea[name='+name+']');"
    +_name+".getSession().setValue($('input[name='+name+']').val());"
    +_name+".getSession().on('change', function(){"
    +"$('input[name='+name+']').val("+_name+".getSession().getValue());"
    +"});";
    eval(code);

});
  }); 
 
</script>


<div>
</body>
</html>
