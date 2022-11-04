<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Staff.php';

  $staff_id=clean_string($_GET['staff_id']);

  $staff=new Staff();

  $staff->staff_id=$staff_id;
  echo $staff->DeleteStaff();
?>
