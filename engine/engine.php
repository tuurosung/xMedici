<?php
ob_start();
session_start();
require_once '../serverscripts/dbcon.php';


echo $_SESSION['access_level'];

// if(!isset($_SESSION['access_level'])){
//   header('Location: ../login.php');
// }
// else {
header('Location: ../admin/index.php');
// }





 ?>
