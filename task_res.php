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
        $sql = 'SELECT MAX(id) FROM task';
        $result = mysqli_query($mysqli, $sql);
        $tasknum = 0;
        while ($row = mysqli_fetch_array($result)) {
            $num = 1;
            for($i = $row['MAX(id)']; $i >= 1; $i--){
                $dir = "users\\".$_SESSION['login']."\\"."task\\".$i;
                if(is_dir($dir)) {
                    $tasknum ++;
                    echo "<div style='border-bottom:2px solid black;'><div style='margin-top:20px;margin-left:20px;margin-right:20px;'>";
                    $sql = 'SELECT * FROM task WHERE id ="'.$i.'"';
                    $res = mysqli_query($mysqli, $sql);
                    while ($arr = mysqli_fetch_array($res)) {
                        echo '<div style="font-size:20px;"><b><a href="task_info.php?taskid='.urlencode($i).'">'.$arr["name"].'</a></b></div>';
                    }
                    $complete = file_get_contents($dir."\\complete.php");
                    echo '<pre class="editor" data-name="editor_'.$num.'" id="editor_'.$num.'" style="font-size: 16px;"></pre> 
                    <input  name="editor_'.$num.'" type="hidden" value="'.htmlspecialchars($complete).'" /></div></div>';
                    $num++;
                }
            }
        }
        if ($tasknum == 0) {
            echo"<div style='text-align:center;padding: 20px 0px;'><label style='font-size:26px;'>Ви ще не проходили жодної задачі!</label></div>";
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
    
</body>
</html>