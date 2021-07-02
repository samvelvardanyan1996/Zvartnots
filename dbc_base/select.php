<?php
require_once('./connect.php');
require_once('../functions/file_func.php');
$log_file = '../logs/select.txt';

$query = "SELECT * FROM obs_data WHERE 1 ORDER BY id DESC LIMIT 1";
$res = $pdo->query($query);
$json_array = $res->fetch(PDO::FETCH_ASSOC);
$json_array_without_date = array_slice($json_array, 0, -1);

$dir_name = date('Y_m');
$path = "../images/$dir_name/";

$image_name = listdirfile_by_date($path);
// var_dump($image_name);
$json_array_with_image = array_merge(
  $json_array_without_date, 
  ['image_name'=> $image_name],
  ['folder_name' => $dir_name]
);
$json_string_with_image = json_encode($json_array_with_image, JSON_PRETTY_PRINT);


// dumper($json_string_with_image);
echo $json_string_with_image;

$log_select = $json_array['obs_id'] . " - selected on " . date('d.m.Y H:i:s') . "\n";
addLog($log_select, $log_file);
