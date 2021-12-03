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
    <div class='task'>
    	<?php 
    		$sql = 'SELECT * FROM task WHERE id = "'.$_GET['taskid'].'"';
            $result = mysqli_query($mysqli, $sql);
            while ($row = mysqli_fetch_array($result)) {
            	 echo "
                    <div class='taskName'>
                    <div class='taskn'>
                    <h3>".$row['name']."</h3>
                    <label class='date'>Дата створення: ".$row['date']."</label> <br>";
                    $tags = explode(",", $row['tags']);
                    echo "<div class='ttags'>";
                    foreach ($tags as $tag) {
                        echo "
                        <a class='tag' href='search.php?tag=".urlencode($tag)."'>".$tag."</a>";
                    }
                    
                    $str = str_replace("\n", "<br>", $row['details']);
                    echo "</div></div>
                    <div class='task-text'><p class='pt'><b>Деталі</b></p>".$str."</div>
                    <form class='taskform' action='train.php' method='get'>
                    <button class='starttask' name='taskid' value='".$row['id']."'>Тренуватись</button>
                    </form>
                    </div>";
            }
    	?>
    </div>

</body>
</html>
