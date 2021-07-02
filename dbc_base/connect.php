<?php
$user = 'zv_dbc_user';
$pass = 'zv_dbc_2020$';
try {
  $pdo = new PDO('mysql:host=localhost;dbname=zv_dbc', $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Error run inquiry!: ";
}
