<?php

// time functions
date_default_timezone_set('UTC');

function setMinutes()
{
  $corrected_minutes = '00';
  $minutes = date('i', get_timestamp());
  // if ($minutes > 29 ) { // && $minutes < 45
  //   $corrected_minutes = 30;
  // }
  // return $corrected_minutes;
  return $minutes;
}

function set_request_date()
{
  $minutes = setMinutes(get_timestamp());
  // return date("d.m.y H:$minutes");
  return date("d.m.16 H:$minutes");
  // return date("d.m.y 06:30");
}

function get_timestamp()
{
  return time();
}

function set_date()
{
  // return date('13.01.20 00:30');
  return date('d.m.y 07:00');
}

function set_obs_id()
{
  $minutes = setMinutes(get_timestamp());
  // $hours = date('H') + 4;
  $hours = date('H');
  return date("dmy$hours$minutes") . "Z";
}

// get last image from ../images
function listdirfile_by_date($path)
{
  $dir = opendir($path);
  $list = array();
  while ($file = readdir($dir)) {
    if ($file != '..' && $file != '.') {
      $mtime = filemtime($path . $file) . ',' . $file;
      $list[$mtime] = $file;
    }
  }
  closedir($dir);
  krsort($list);

  foreach ($list as $key => $value) {
    return $list[$key];
  }
  return '';
}

// delete images and last-month folder recursively
function delTree($path)
{ 
    $files = array_diff(scandir($path), array('.', '..')); 

    foreach ($files as $file) { 
        (is_dir("$path/$file")) ? delTree("$path/$file") : unlink("$path/$file"); 
    }

    return rmdir($path); 
}

// file functions
function addLog($log_msg, $log_file) {
  $log_handle = fopen($log_file, 'a+');
  fwrite($log_handle, $log_msg);
  fclose($log_handle);
}

function setTphTempSign($met_temp, $tph_temp) {
  if(strstr($met_temp, "M") && !strstr($tph_temp, "-")) {
    $tph_temp = "-".$tph_temp;
  }
  return $tph_temp;
}

function get_tph_data($tph_row_array)
{
  $tph_data = [
    'tph_temp' => '',
    'tph_press' => '',
    'tph_humidity' => '',
  ];

  if (count($tph_row_array) > 0) {
    $tph_row = array_values($tph_row_array)[0];
    // dumper($tph_row);
    $t_colon = 'T:';
    $h_colon = 'H:';
    $tp_colon = 'Tp:';
    $p_colon = 'P:';
    $qnh_colon = 'QNH:';
    $t_pos = strpos($tph_row, $t_colon);
    $h_pos = strpos($tph_row, $h_colon);
    $tp_pos = strpos($tph_row, $tp_colon);
    $p_pos = strpos($tph_row, $p_colon);
    $qnh_pos = strpos($tph_row, $qnh_colon);
    // echo 't_pos->'. $t_pos . '; h_pos->'. $h_pos . '; p_pos->'. $p_pos . '<br>';
    $t_string = substr($tph_row, $t_pos + strlen($t_colon), $h_pos - ($t_pos + strlen($t_colon)));
    $tph_data['tph_temp'] = trim($t_string);
    // dumper($t_string);
    // dumper($tph_data);

    $h_string = substr($tph_row, $h_pos + strlen($h_colon), $tp_pos - ($h_pos + strlen($h_colon)));
    $tph_data['tph_humidity'] = trim($h_string);
    // dumper($h_string);
    // dumper($tph_data);

    $p_string = substr($tph_row, $p_pos + strlen($p_colon), $qnh_pos - ($p_pos + strlen($p_colon)));
    $p_array = explode(":", trim($p_string));
    // dumper(trim($p_string));
    // dumper($p_array);

    $tph_data['tph_press'] = trim($p_array[1]);
    // dumper($tph_data);
  }
  return $tph_data;
}

function get_met_cloud_data($met_row_array, $cloud_patterns)
{
  $met_cloud = '';
  $cloud_patterns_all = implode("|", $cloud_patterns);
  // $cloud_patterns_static = implode("|", array_slice($cloud_patterns, 0, 4));
  if (count($met_row_array) > 0) {
    $met_row = $met_row_array[0];
    if (preg_match_all("/\b($cloud_patterns_all)/", $met_row, $matched_cloud_array)) {
      // dumper($matched_cloud_array);
      $cloud_substring = substr($met_row, strpos($met_row, $matched_cloud_array[0][0]));
      $cloud_array = explode(" ", $cloud_substring);
      if (count($matched_cloud_array[0]) > 1) {
        $met_cloud = implode(" ", array_slice($cloud_array, 0, 2));
      } else {
        $met_cloud = reset($cloud_array);
      }
    }
  }
  return $met_cloud;
}

function get_met_phenom_data($met_row_array, $phenom_patterns)
{
  $met_phemon = '';
  if (count($met_row_array) > 0) {
    $met_row = $met_row_array[0];
    $phenom_patterns_str = implode("|", $phenom_patterns);
    if (preg_match_all("/\b($phenom_patterns_str)\b/", $met_row, $match_phenom)) {
      // dumper($match_phenom);
      $met_phemon = implode(" ", $match_phenom[0]); // preg_match_all
      // $met_phemon = $match_phenom[0]; // preg_match
    }
  }
  return $met_phemon;
}

function get_wnd_data($wnd_row_array)
{
  $wnd_09_27 = [
    'wnd_09_d' => '',
    'wnd_27_d' => '',
    'wnd_09_f' => '',
    'wnd_27_f' => '',
  ];
  if (count($wnd_row_array) > 0) {
    $wnd_row = array_values($wnd_row_array)[0];
    // dumper($wnd_row);
    $wnd_row_parts = explode("[", $wnd_row);
    // dumper($wnd_row_parts);
    $wnd_09_array = explode(", ", trim($wnd_row_parts[1]));
    // dumper($wnd_09_array);    

    $wnd_09_27['wnd_09_d'] = str_replace(" ", 0, substr($wnd_09_array[0], -3, 3));
    $wnd_09_27['wnd_09_f'] = trim(substr($wnd_09_array[1], -3, 3));

    $wnd_27_array = explode(", ", trim($wnd_row_parts[2]));
    // dumper($wnd_27_array);

    $wnd_09_27['wnd_27_d'] = str_replace(" ", 0, substr($wnd_27_array[0], -3, 3));
    $wnd_09_27['wnd_27_f'] = trim(substr($wnd_27_array[1], -3, 3));
  }
  return $wnd_09_27;
}

function get_vis_data($vis_row_array)
{
  $vis_data = [
    'vis_09' => '',
    'vis_mid' => '',
    'vis_27' => '',
  ];

  if (count($vis_row_array) > 0) {

    $vis_row = array_values($vis_row_array)[0];
    $vis_row_parts = explode("V:", $vis_row);
    $vis_array = array_slice($vis_row_parts, 1);

    $vis_data['vis_09'] = substr($vis_array[0], 0, 4);
    $vis_data['vis_mid'] = substr($vis_array[1], 0, 4);
    $vis_data['vis_27'] = substr($vis_array[2], 0, 4);
    // dumper($vis_data);
  }
  return $vis_data;
}

function get_met_press_data($met_row_array)
{
  $met_pressure = '';

  if (count($met_row_array) > 0) {
    $met_row = $met_row_array[0];

    if (strpos($met_row, 'Q')) {
      $before_pressure = strstr($met_row, 'Q', false);
    }

    $before_pressure_array = explode(" ", $before_pressure);
    $met_pressure = reset($before_pressure_array);
    $met_pressure = trim($met_pressure, 'Q');
    // echo $met_pressure . ' <-- pressure <br>';
  }
  return $met_pressure;
}

function get_met_expected_data($met_row_array)
{
  $met_change_expected = '';

  if (count($met_row_array) > 0) {
    $met_row = $met_row_array[0];

    if (strpos($met_row, 'NOSIG=')) {
      $before_change_expected = strstr($met_row, 'NOSIG=', false);
    } else if (strpos($met_row, 'FG=')) { // remove FG= if no need in 2020
      $before_change_expected = strstr($met_row, 'FG=', false);
    } else {
    }

    $before_change_expected_array = explode(" ", $before_change_expected);
    $met_change_expected = reset($before_change_expected_array);
    $met_change_expected = trim(trim($met_change_expected), '=');
    // echo $met_change_expected . ' <-- change_expected <br>';
  }
  return $met_change_expected;
}

function get_met_visibility($met_row_array)
{
  $met_visibility = '';

  if (count($met_row_array) > 0) {
    $met_row = $met_row_array[0];
    if (preg_match('/\b[0-9]{4}\b/', $met_row, $visibility_matches)) {
      $met_visibility = $visibility_matches[0];
    }
  }
  return $met_visibility;
}

function get_met_temp_data($met_row_array)
{
  $met_temp = [
    'temperature' => '',
    'dewpoint' => '',
  ];

  if (count($met_row_array) > 0) {
    $met_str = '';
    $met_row = $met_row_array[0];
    if (preg_match('/\b[M]?+[0-9]{2}\/[M]?+[0-9]{2}\b/', $met_row, $temp_matches)) {
      $met_str = $temp_matches[0];
      $met_array = explode("/", $met_str);
      $met_temp['temperature'] = $met_array[0];
      $met_temp['dewpoint'] = $met_array[1];
    }
  }
  return $met_temp;
}

function get_met_wind_data($met_row_array)
{
  $met_wind = [
    'w_dir' => '',
    'w_speed' => '',
  ];

  if (count($met_row_array) > 0) {
    $met_kt = '';
    $met_row = $met_row_array[0];


    if (strpos($met_row, 'KT')) {

      $before_kt = strstr($met_row, 'KT', true);
      $before_kt_array = explode(" ", $before_kt);
      $met_kt = end($before_kt_array);

      $met_wind['w_dir'] = substr($met_kt, 0, 3); // VRB -> 'Varying direction wind'
      $met_wind['w_speed'] = substr($met_kt, 3);
      // echo $met_kt . ' <-- met_tk <br>';
    }
  }
  return $met_wind;
}

function dumper($var)
{
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
}

function make_file_name($date_md, $file_prefix)
{
  return  $file_prefix .  $date_md . FILE_EXT;
}

function search_line_by_date_n_time($var)
{
  // $dtt = set_date();
  $dtt = set_request_date();
  // dumper($dtt);
  if (strpos($var,  $dtt) !== false) {
    return true;
  } else {
    return false;
  }
}

function search_met_line_by_keyword($var)
{
  $keyword = make_met_keyword(get_timestamp());

  if (strpos($var,  $keyword) !== false) {
    return true;
  } else {
    return false;
  }
}

function make_met_keyword()
{
  $day = date('d', get_timestamp());
  $hour = date('H', get_timestamp());
  $minutes = '00';
  $current_min = setMinutes(get_timestamp());
  if ($current_min > 29 ) { // && $minutes < 45
    $minutes = 30;
  }

   return $day.$hour.$minutes.'Z';
  // return '13' . '00' . '30' . 'Z';
  // return $day . '06' . '30' . 'Z';
}


function get_file_row_array($file_name, $file_prefix)
{
  $file_row = [];
  if (file_exists(DBC_PATH . $file_name)) {

    $file_list = file(DBC_PATH . $file_name);
    if (
      $file_prefix === CLD_PREFIX ||
      $file_prefix === WND_PREFIX ||
      $file_prefix === VIS_PREFIX ||
      $file_prefix === TPH_PREFIX
    ) {
      $file_row =  array_filter($file_list, 'search_line_by_date_n_time');
      // dumper($file_row);
    } elseif ($file_prefix === MET_PREFIX) {
      $file_rows =  array_filter(array_reverse($file_list, true), 'search_met_line_by_keyword');
      // dumper($file_rows);
      $file_row = array_slice(array_values($file_rows), 0, 1);
    }
  }
  return $file_row;
}

function get_cld_hash($row_array)
{
  $cld_hash = '';

  if (count($row_array) > 0) {
    $cld_row = array_values($row_array)[0];
    $cld_hash_pos = strpos($cld_row, "H");
    $cld_hash = substr($cld_row, $cld_hash_pos + 2, 4);
  }
  return $cld_hash;
}
