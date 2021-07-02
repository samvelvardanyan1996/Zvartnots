<?php
require_once('./dbc_base/connect.php');

$log_file = './logs/insert.txt';
$obs_id = set_obs_id();

$query = "SELECT obs_id FROM `obs_data` WHERE obs_id = '$obs_id'";
$res = $pdo->query($query);

$check = $res->fetch(PDO::FETCH_ASSOC);

// if($check !== false){
if ($check === false) {
  $met_w_dir = $met_wind_data['w_dir'];
  $met_w_speed = $met_wind_data['w_speed'];

  // $met_temp = $met_temp_data['temperature'];
  // $met_dewpoint = $met_temp_data['dewpoint'];
  // $met_visibility = $met_visibility_data;
  // $met_press = $met_press_data;
  // $met_expected = $met_expected_data;

  $met_phenom = $met_phenom_data;
  $met_cloud = $met_cloud_data;

  $vis_09 = $vis_data['vis_09'];
  $vis_mid = $vis_data['vis_mid'];
  $vis_27 = $vis_data['vis_27'];

  $wnd_09_d = $wnd_data['wnd_09_d'];
  $wnd_27_d = $wnd_data['wnd_27_d'];
  $wnd_09_f = $wnd_data['wnd_09_f'];
  $wnd_27_f = $wnd_data['wnd_27_f'];

  // $wnd_09_fmax = $wnd_data['wnd_09_fmax'];
  // $wnd_27_fmax = $wnd_data['wnd_27_fmax'];

  $tph_temp = $tph_data['tph_temp'];
  $tph_press = $tph_data['tph_press'];
  $tph_humidity = $tph_data['tph_humidity'];

  // $query = "INSERT INTO `obs_data`(
  //   `obs_id`, `cld_hash`, `met_w_dir`, `met_w_speed`, `met_temp`, `met_dewpoint`, `met_visibility`,
  //   `met_press`, `met_expected`, `met_phenom`, `met_cloud`, `vis_09`, `vis_0`, `vis_27`,
  //   `wnd_09_d`, `wnd_27_d`,`wnd_09_f`, `wnd_27_f`, `wnd_09_fmax`, `wnd_27_fmax`, `tph_temp`, `tph_press`, `tph_humidity`)
  //   VALUES ('$obs_id', '$cld_hash', '$met_w_dir', '$met_w_speed', '$met_temp', '$met_dewpoint', '$met_visibility',
  //   '$met_press', '$met_expected', '$met_phenom', '$met_cloud', '$vis_09', '$vis_0', '$vis_27',
  //   '$wnd_09_d', '$wnd_27_d', '$wnd_09_f', '$wnd_27_f', '$wnd_09_fmax', '$wnd_27_fmax', '$tph_temp', '$tph_press', '$tph_humidity')";
  $query = "INSERT INTO `obs_data`(
      `obs_id`, `cld_hash`, `met_w_dir`, `met_w_speed`, `met_phenom`, `met_cloud`,
      `vis_09`, `vis_mid`, `vis_27`, `wnd_09_d`, `wnd_27_d`,`wnd_09_f`, `wnd_27_f`,
      `tph_temp`, `tph_press`, `tph_humidity`)
      VALUES ('$obs_id', '$cld_hash', '$met_w_dir', '$met_w_speed', '$met_phenom', '$met_cloud',
      '$vis_09', '$vis_mid', '$vis_27', '$wnd_09_d', '$wnd_27_d', '$wnd_09_f', '$wnd_27_f',
      '$tph_temp', '$tph_press', '$tph_humidity')";
  // dumper($query);

  if (
    strlen($cld_hash) && strlen($met_w_dir) && strlen($vis_09) &&
    strlen($wnd_09_d) && strlen($tph_press)
  ) {
    $pdo->query($query);
    // dumper($pdo);
    $log_success = $obs_id . " - Success on insert data! " . date('d.m.Y H:i:s') . "\n";
    addLog($log_success, $log_file);
    echo $log_success . '<br>';
  } else {
    $log_error = $obs_id . " - Error on insert: data is empty! " . date('d.m.Y H:i:s') . "\n";
    addLog($log_error, $log_file);
    echo $log_error . '<br>';
  }
} else {
  $log_warning = $obs_id . " - Warning: olready exists! " . date('d.m.Y H:i:s') . "\n";
  addLog($log_warning, $log_file);
  echo $log_warning . '<br>';
}
