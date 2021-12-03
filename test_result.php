<html>
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="css/index.css">
 <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body>

    <?php
        include('header.php');   
        if (isset($_SESSION['ans_count']) || isset($_POST['test-exit'])) {

        }
        else{
        	header("Location: index.php"); 
            exit;
        }
        $dir = "users\\".$_SESSION['login']."\\"."test\\".$_SESSION['testid'];
         if(!is_dir($dir)) {
         	 mkdir($dir, 0777, true);
         }


    ?>
    <div class='test-result'>
    	<div class='user-result'>
    		<?php 
    			$sql = 'SELECT * FROM test WHERE test_id = "'.$_SESSION['testid'].'"';
            	$result = mysqli_query($mysqli, $sql);
            	while ($row = mysqli_fetch_array($result)) {
            		$test_count = substr_count($row['test'], '|t');
            		$answears = explode("|a", $row['answear']);
            		$ac = 0;
                	unset($answears[0]);
                	$user_answear = "";
                	$n = "\n";
            		for ($i = 1; $i <= substr_count($row['answear'], '|a'); $i++) {
            			if (is_array($_SESSION[$i."test"])) {
            				$user_answear.="|a".implode(",",array_map('trim', $_SESSION[$i."test"]));
            				if ($i != substr_count($row['answear'], '|a')) {
            					$user_answear.=$n;
            				}
						}
            			else{
            				$user_answear.="|a".trim($_SESSION[$i."test"]);
            				if ($i != substr_count($row['answear'], '|a')) {
            					$user_answear.=$n;
            				}
            			}

            			if (is_array($_SESSION[$i."test"])) {
            				$arr  = array_map('trim', $_SESSION[$i."test"]);
            				sort($arr);
            				$answears[$i] = explode(",",trim($answears[$i]));
            				sort($answears[$i]);
	 						if ($arr == $answears[$i]) {
	 							$ac++;
	 						}

            			}
            			else if (trim($_SESSION[$i."test"])==trim($answears[$i])) {
            				$ac++;
            			}
            		}
            		$pr = ($ac * 100) / substr_count($row['answear'], '|a');
                    if (file_exists($dir.'\\res.txt')) {
                        $fd = fopen($dir.'\\res.txt', 'r') or die("Помилка");
                        while(!feof($fd))
                        {
                            $str = htmlentities(fgets($fd));
                        }
                        if($pr > $str){
                            $fp = fopen($dir."\\res.txt", "w");
                            fwrite($fp, "$pr");
                            fclose($fp);
                        }   
                    }
                    else {
                       $fp = fopen($dir."\\res.txt", "w");
                       fwrite($fp, "$pr");
                        fclose($fp); 
                    }
            		$fp = fopen($dir."\\answears.txt", "w");
            		fwrite($fp, "$user_answear");
            		fclose($fp);

            		echo "Ви відповіли правильно на $ac з ".substr_count($row['answear'], '|a')." питань!<br>Ваш результат: ".(int)$pr."%";

            	}
    		?>
    	</div>
    	<div class='test-statictic'>
    		<?php 
    			$result = mysqli_query($mysqli, $sql);
    			while ($row = mysqli_fetch_array($result)) {
            		$test_count = substr_count($row['test'], '|t');

            		$test = explode("|t", $row['test']);
                	unset($test[0]);

            		$answears = explode("|a", $row['answear']);
                	unset($answears[0]);

                	$explains = explode("|e", $row['explain_test']);
                	unset($explains[0]);

                	$rnum=1;

            		for ($i = 1; $i <= $test_count; $i++) {
                        $text="";
 
                    $strcode = explode("/code", $test[$i]);
                    for ($q = 0; $q<count($strcode); $q++) {
                        if ($q%2 != 0) {
                            $text .= "<textarea style='margin-top:5px;
                            ' class='codetext' readonly >".htmlspecialchars($strcode[$q])."</textarea>";
                        }
                        else{
                            $text .= $strcode[$q];
                        }
                    }
                    $pos = strpos($text, "|");
                    $text=substr($text, 0, $pos);
                            echo "
                            <div class='test-stat' style='padding-bottom: 20px;margin-top:20px; border-bottom:2px solid black;'>
                            <div style='margin-left:20px;margin-right:20px;font-size:18px;'>".$i.". ".$text."</div>
                            <div>";

               				 	$variants = explode("\n", $test[$i]);
               					unset($variants[0]);
               					$is_true = false;
               					foreach ($variants as $value) {
               					if (stristr($value, "|r")) {
               						$str = str_replace("|r","",$value);
                            		$str = trim($str);
                            		$check = "";
                            		$style = "";
                            		 if ($_SESSION[$i."test"] != "skip" && $_SESSION[$i."test"] != "") {
                            			$skip=false;
                            			
                            			if (trim($_SESSION[$i.'test']) == $str) {
                            				$check = "checked='checked'";
                            			}
                            		 
                            			if (trim($_SESSION[$i.'test']) == trim($answears[$i]) && trim($_SESSION[$i.'test']) == $str){
                            				$style = "background: #48c948;"; //green
                            				$is_true = true;
                            			}
                            			else if (trim($_SESSION[$i.'test']) != trim($answears[$i]) && trim($_SESSION[$i.'test']) == $str) {
                            				$style = "background: #ff5151;"; //red
                            			}
                            			if (trim($answears[$i]) == $str) {
                            				$style = "background: #48c948;"; //green
                            			}
                            		}
                            		else{
                            			$skip=true;
                            		}
                            		$r= str_replace("|r","<div style='".$style."margin-right:20px;margin-left:20px; margin-top:20px;margin-bottom:20px;padding:10px 0px;border-radius:10px;padding-left:10px;'>
                                		<input type='radio' disabled='disabled' id='variant".$rnum."' name='variant".$i."' value='".$str."' ".$check."> 
                                		<label style='cursor: default;' for='variant".$rnum."'>",trim($value))."
                           				</label></div>";
                           			$r = trim($r);
                           			echo str_replace("\n","",$r);
                           			$rnum++;
               					}
               					if (stristr($value, "|c")) {
               						$str = str_replace("|c","",$value);
                            		$str = trim($str);
                            		$check = "";
                            		$skip;
                            		$style = "";
                            		$answears_arr = explode(",",trim($answears[$i]));
            						sort($answears_arr);
                            		if ($_SESSION[$i."test"] != "skip" && $_SESSION[$i."test"] != "") {
                            			$skip=false;
                            			$arr  = array_map('trim', $_SESSION[$i."test"]);
            							sort($arr);
            							
            							$userAns = array_intersect($arr, $answears_arr);
            							$userAns2 = array_diff($arr, $answears_arr);


            							if (isset($userAns)) {
            								foreach($userAns as $value2){
            									if ($value2 == $str) {
            										$check = "checked='checked'";
            										$style = "background: #48c948;"; //green
            									}
            								}
            							}
            							if (isset($userAns2)) {
            								foreach($userAns2 as $value2){
            									if ($value2 == $str) {
													$style = "background: #ff5151;"; //red
													$check = "checked='checked'";
            									}
            								}
            							}
            							if ($userAns != $answears_arr) {
            								foreach($answears_arr as $value2) {
            									if ($value2 == $str){
            										$style = "background: #48c948;"; //green
            									}
            								}
            							}
            							if ($userAns == $answears_arr) {
            								$is_true = true;
            							}
            						}
            						else {
            							$skip=true;
            						}

                            		$c = str_replace("|c", "<div style='".$style." margin-right:20px;margin-left:20px; margin-top:20px;margin-bottom:20px;padding:10px 0px;border-radius:10px;padding-left:10px;'>
                            		<label style='margin-left: 30px;cursor: default;' class='checkcontainer'>", trim($value))."<input class='required-checkbox' disabled='disabled' type='checkbox' ".$check." name='check[]' value='".$str."'>
                            		<span style='margin-left: 5px;' class='checkmark1'></span>
                        			</label>";
                        			
                        			
               						$c.="</div>";
									echo $c;
               					}

               				}

							if ($skip){
               					echo"<div class='padding-bottom:20px;'><label class='test-res' style='color:red;'>Ви пропустили цей тест!</label></div>";
               				}
               				else if ($is_true){
               					echo"<div class='padding-bottom:20px;'><label class='test-res' style='color:green;'>Ви правильно відповіли на цей тест!</label></div>";
               				}
               				else if (!$is_true){
               					echo"<div class='padding-bottom:20px;' class'><label class='test-res' style='color:red;'>Ви не правильно відповіли на цей тест!</label></div><br>";
               					echo"<div class='padding-bottom:20px;'><label class='test-res'>".$explains[$i]."</label></label></div>";
               				}	
               				echo"</div>
               			</div>";
            		}

            	}
    		?>
    	</div>
    </div>

  </body>
  </html>