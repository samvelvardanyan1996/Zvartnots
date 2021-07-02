<?php
include_once("./functions/file_func.php");
$source_page = file_get_contents("https://zamc.am/%d6%84%d5%a1%d6%80%d5%bf%d5%a5%d5%a6/");

$base_start = 'background-image: url("';
$base_end = 'background-position: -45px -20px;';

$base_start_length = strlen($base_start);
$base_end_length = strlen($base_end);

$base_start_pos = strpos($source_page, $base_start);

$base_end_pos = strpos($source_page, $base_end);
$base_length = $base_end_pos - $base_start_pos;
// var_dump($base_length);

$subs = substr($source_page, $base_start_pos + $base_start_length, $base_length);

// $subs_index = strpos($subs, 'background-position:');
// $subs = substr($subs, 'background-position:', );

// var_dump($subs);
// $new_dir = date('Y_m', strtotime('-1 month'));
$new_dir = date('Y_m');
$path = "./images/$new_dir/";

if (!is_dir($path)) {
  mkdir($path);
}


$image_name = $path . set_obs_id() . '.png';
base64_to_jpeg($subs, $image_name);

function base64_to_jpeg($base64_string, $output_file) {
  $ifp = fopen( $output_file, 'w' ); 

  $data = explode( 'base64', $base64_string );
  $data = explode( '");', $data[1] );

  // var_dump($data[0]);
  fwrite( $ifp, base64_decode( $data[ 0 ] ) );

  fclose( $ifp );

  return $output_file;
}

?>
<!-- <img src=<? echo $image_name; ?> /> -->