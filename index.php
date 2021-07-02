<?php
date_default_timezone_set('UTC');
// constants: file-prefixes
define('CLD_PREFIX', 'cld');
define('MET_PREFIX', 'met');
define('WND_PREFIX', 'wnd');
define('VIS_PREFIX', 'vis');
define('TPH_PREFIX', 'tph');
define('FILE_EXT', '.tel');
define('DBC_PATH', './dbc/');
/* 
CLOUD_PATTERNS static = ['SKC', 'NSC', 'CAVOK', 'VV']
cloud_types = ['CB', 'TCU']
*/
define('CLOUD_PATTERNS', ['NSC', 'CAVOK', 'VV', 'FEW', 'SCT', 'BKN', 'OVC']);

define(
  'PHENOM_PATTERNS',
  [
    //rainfall - обложные осадки
    'RA',
    'SN',
    'RASN',
    'DZ',
    'SG',
    'IC',

    // undercooling - переохлаждение воздуха
    'FZDZ',
    'FZRA',
    'FZFG',

    // high humidity - повышенная влажность
    'BR',
    'FG',
    'MIFG',
    'BCFG',
    'PR',

    // solid particles - твердые частицы
    'FU',
    'HZ',
    'SA',
    'DU',
    'VA',

    // wind related - связанные с ветром
    'DS',
    'SS',
    'PO',
    'FC',
    'SQ',

    // shower
    'SH',
    'GR',
    'GS',

    // storm
    'TS',
    'TSRA',
    'TSGS',
  ]
);

require_once('./functions/file_func.php');
/**
 * list of functions that need to time configuration
 * 1) use set_request_date() instead of set_date() in
 *    search_line_by_date_n_time()
 * 
 * 1.1) set_request_date() 
 *    return date("d.m.y H:$minutes") instead of date("d.m.16 H:$minutes")
 * 
 * 2) make_met_keyword() 
 *      must return $day.$hour.$minutes.'Z'; // bot not hardcodding
 * 
 * 3) indert.php:
 *     insert only when ($check === false) // no dublicate row in DB
 * 
 * 4) system works by bat-files:
 *    cronbat.bat - for insex.php (inserting data to DB)
 *    imgbat.bat  - for get_image.php (fetching image to ../images folder)
 */

$date_md = date('md');
// $date_md = date('0113');
$request_date = set_request_date();

echo $request_date . ' -- request_date <br>';
echo date('T') . ' -- TimeZone <br>';
echo set_date() . ' -- current date <br>';
echo set_obs_id() . ' -- obs_id <br><br>';

$cld_file_name = make_file_name($date_md, CLD_PREFIX);
echo $cld_file_name . '<-- cld_file_name <br>';
$cld_row_array = get_file_row_array($cld_file_name, CLD_PREFIX);
// dumper($cld_row_array);
$cld_hash = get_cld_hash($cld_row_array);
echo $cld_hash . ' <-- $cld_hash <br><br>';


$met_file_name = make_file_name($date_md, MET_PREFIX);
echo $met_file_name . '<-- met_file_name <br>';
$met_row_array = get_file_row_array($met_file_name, MET_PREFIX);
dumper($met_row_array);

$met_wind_data = get_met_wind_data($met_row_array);
dumper($met_wind_data);

// met_ no need Start
$met_temp_data = get_met_temp_data($met_row_array);
dumper($met_temp_data);
// $met_visibility_data = get_met_visibility($met_row_array);
// echo $met_visibility_data . '<-- met_visibility_data <br>';
// $met_press_data = get_met_press_data($met_row_array);
// echo $met_press_data . '<-- met_press_data <br>';
// $met_expected_data = get_met_expected_data($met_row_array);
// echo $met_expected_data . '<-- met_change_expected <br>';
// met_ no need End

$met_phenom_data = get_met_phenom_data($met_row_array, PHENOM_PATTERNS);
echo $met_phenom_data . '<-- met_phenom <br>';
$met_cloud_data = get_met_cloud_data($met_row_array, CLOUD_PATTERNS);
echo $met_cloud_data . '<-- met_cloud_data <br><br>';

// vis
$vis_file_name = make_file_name($date_md, VIS_PREFIX);
echo $vis_file_name . '<-- vis_file_name <br>';
$vis_row_array = get_file_row_array($vis_file_name, VIS_PREFIX);
// dumper($vis_row_array);

$vis_data = get_vis_data($vis_row_array);
dumper($vis_data);

// wnd
$wnd_file_name = make_file_name($date_md, WND_PREFIX);
echo $wnd_file_name . '<-- wnd_file_name <br>';
$wnd_row_array = get_file_row_array($wnd_file_name, WND_PREFIX);
// dumper($wnd_row_array);

$wnd_data = get_wnd_data($wnd_row_array);
dumper($wnd_data);

// tph
$tph_file_name = make_file_name($date_md, TPH_PREFIX);
echo $tph_file_name . '<-- tph_file_name <br>';
$tph_row_array = get_file_row_array($tph_file_name, TPH_PREFIX);
dumper($tph_row_array);
$tph_data = get_tph_data($tph_row_array);

$tph_data["tph_temp"] = setTphTempSign($met_temp_data["temperature"], $tph_data["tph_temp"]);
dumper($tph_data);

require_once('./dbc_base/insert.php');



// preg for temperatures
// $k = preg_match_all('/\b[M]?+[0-9]{2}\/[M]?+[0-9]{2}\b/', $str,$temp, PREG_PATTERN_ORDER);
