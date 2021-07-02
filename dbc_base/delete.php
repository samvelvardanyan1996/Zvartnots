<?php

date_default_timezone_set('UTC');
require_once('./connect.php');

$date = date('Y-m-d', strtotime('-1 month'));
var_dump($date);

$query = "DELETE FROM obs_data WHERE date <= '$date'";
$res = $pdo->query($query);