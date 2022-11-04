<?php
ob_start();
require_once '../serverscripts/dbcon.php';
session_start();

echo $_SESSION['access_level'];

if(!isset($_SESSION['access_level'])){
  header('Location: ../login.php');
}
else {
header('Location: ../admin/index.php');
}





 ?>
