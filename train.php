<html>
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="css/index.css">
 <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body style="overflow: hidden;">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script>
    <?php
        include('header.php');
        $id;
        if (isset($_POST['code-editor'])) {
            $dir = "users\\".$_SESSION['login']."\\"."task\\".$_POST['attempt'];
            $id = $_POST['attempt'];
            if(!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $fp = fopen($dir."\\task.php", "w");
            fwrite($fp, $_POST['code-editor']);
            fclose($fp);

            $fp = fopen("tasks.php", "w");
            fwrite($fp, $_SESSION['tasks']);
            fclose($fp);

            $task_res;
            try{
                include($dir.'\\task.php');
                
                include('assertEquals.php');
                if (!isset($task_res)) {
                    $task_res = true;
                }
                include('tasks.php');
            }
            catch(Throwable $e){$task_res = "Помилка: ".$e->getMessage();}

        }
        else {
            $id = $_GET['taskid'];
        }
        
        $sql = 'SELECT * FROM task WHERE id = "'.$id.'"';
            $result = mysqli_query($mysqli, $sql);
            while ($row = mysqli_fetch_array($result)) {
            echo "
            <div class='background'>
                <div class='details-info'>
                    <div class='taskn' style='border: none;'>
                        <h3>".$row['name']."</h3>";
                        $tags = explode(",", $row['tags']);
                    echo "<div class='ttags' style='border-bottom: 2px solid black;padding-bottom: 10px;'>";
                    foreach ($tags as $tag) {
                        echo "
                        <a class='tag' href='search.php?tag=".urlencode($tag)."'>".$tag."</a>";
                    }

                   $str = str_replace("\n", "<br>", $row['details']);
                    echo "</div>";

                    if (isset($task_res)) {
                        ?>
                        <div class='result'>
                        <?php
                        if ($task_res === true) {
                            echo '<div class="result-text"><label class="res-text">Всі тести пройдено!</label></div>';
                            $fp = fopen($dir."\\complete.php", "w");
                            fwrite($fp, $_POST['code-editor']);
                            fclose($fp);
                        }
                        else {
                            echo '<div class="exception-text"><label class="res-text" style="font-size:18px;color:red;">Тести не пройдено!</label><br>
                            <label >'.$task_res.'</label></div>';
                        }
                        ?>
                        </div>
                        <?php
                    }

                    echo" <div class='task-text' style='margin-right: 0px;'><p class='pt' style='text-align:center;'><b>Деталі</b></p>".$str."</div>

                   </div> 

                </div>

            <div class='code-task'>";
    ?>
            <form action='train.php' method='post' style='
                width: 100%;text-align:center;
            '>
    <?php 
        if (isset($_POST['code-editor'])){
             echo' <textarea name="code-editor" data-editor="php" data-gutter="1" rows="15" >'.$_POST['code-editor'].'</textarea>
            <br>';
        }
        else{
            echo' <textarea name="code-editor" data-editor="php" data-gutter="1" rows="15" >'.$row['code'].'</textarea>
            <br>';
         }
        echo' <div class="example-task">
        <label class="examples">Приклади тестів:</label>
        <div id="task-editor">'.htmlspecialchars($row['task']).'
        </div>';
        $_SESSION['tasks'] = $row['task'];
        echo '
        </div>
        <button class="attempt" name="attempt" value="'.$row['id'].'" type="submit">Спроба</button>
        </form>
        </div>';
    }
    ?>

<script>
     $(function() {
  $('textarea[data-editor]').each(function() {
    var textarea = $(this);
    var mode = textarea.data('editor');
    var editDiv = $('<div style="width:100%;height:500px">', {
      position: 'absolute',
      'class': textarea.attr('class')
    }).insertBefore(textarea);
    textarea.css('display', 'none');
    var editor = ace.edit(editDiv[0]);
    editor.renderer.setShowGutter(textarea.data('gutter'));
    editor.getSession().setValue(textarea.val());
    editor.getSession().setMode("ace/mode/" + mode);
    editor.setTheme("ace/theme/monokai");

    textarea.closest('form').submit(function() {
      textarea.val(editor.getSession().getValue());
    })
  });
});
 </script>

<script>
    var editor2 = ace.edit("task-editor");
    editor2.setTheme("ace/theme/monokai");
    editor2.getSession().setMode("ace/mode/php");
    editor2.setReadOnly(true);
    document.getElementById("task-editor").style.height = "260px";
  </script>


    </body>
</html>
