<?php

include_once("./functions/file_func.php");
$last_dir = date('Y_m', strtotime('-1 month'));
$path = "./images/$last_dir/";

if(is_dir($path)) {
  echo  $last_dir . '<-- last_dir<br>';
  $k = delTree($path);
  var_dump($k);
}else{
  echo $last_dir . "<-- not exists <br>";
}
