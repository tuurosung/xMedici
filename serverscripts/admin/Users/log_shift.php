<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Staff.php';


  $staff_id=clean_string($_GET['staff_id']);
  $shift_type=clean_string($_GET['shift_type']);
  $notes=clean_string($_GET['notes']);


  reject_empty($staff_id);
  reject_empty($shift_type);

  $user=new Staff();
  $query=$user->LogShift($staff_id,$shift_type,$notes);
  echo $query;
 ?>
