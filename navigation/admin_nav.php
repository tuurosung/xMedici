<?php
  // session_start();

  if(!isset($_SESSION['active_user']) || $_SESSION['active_user']==''){
    header('Location: ../index.php');
  }

  if(!isset($_SESSION['active_subscriber']) || $_SESSION['active_subscriber']==''){
      header('Location: ../index.php');
  }

  // elseif ( $_SESSION['access_level'] !='administrator' && $_SESSION['access_level'] !='reception') {
  //   header('Location: ../login.php');
  // }
?>

<?php

  if($_SESSION['access_level']=='administrator'){
    include_once 'administrator_nav.php';
  }elseif ($_SESSION['access_level']=='doctor') {
    include_once 'doctors_nav.php';
  }elseif ($_SESSION['access_level']=='nurse') {
    include_once 'nurse_nav.php';
  }elseif ($_SESSION['access_level']=='pharmacist') {
    include_once 'pharmacist_nav.php';
  }elseif ($_SESSION['access_level']=='labtist') {
    include_once 'labtist_nav.php';
  }elseif ($_SESSION['access_level']=='accountant') {
    include_once 'accountant_nav.php';
  }elseif ($_SESSION['access_level']=='administrator_hr') {
    include_once 'hr_nav.php';
  }


 ?>
