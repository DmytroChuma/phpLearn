<?php
function reverseWords($str) {
 $str = strrev($str);
  $arr = explode(" ", $str);
  $arr = array_reverse($arr);
  $str = implode(" ", $arr);
  return $str;
}
?>