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
    $sql = 'SELECT DISTINCT sections.* FROM theory_themes,sections WHERE sections.id = theory_themes.section_id';
    $result = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo "
        <div class='theory'>
        <h3 class='section_text'>".$row['name']."</h3>";
        $sql = 'SELECT theory_themes.* FROM theory_themes WHERE theory_themes.section_id = '.$row['id'];
        $themes = mysqli_query($mysqli, $sql);
        while ($theme = mysqli_fetch_array($themes)) {
            echo "<a class='theme_a' href='theory_theme.php?theme=".urlencode($theme['theme_name'])."'>".$theme['theme_name']."</a>";
        }

        echo "</div>";
    }
?>
</div>


</body>
</html>
