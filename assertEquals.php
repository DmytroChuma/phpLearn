<?php 

	function assertEquals($a, $b){
		if ($a != $b) {
			throw new Exception("Значення $b не відповідає значенню $a.<br>
				Очікується: $a<br>
				Отримано: $b");
		}
	}

?>
 