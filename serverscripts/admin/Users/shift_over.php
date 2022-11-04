<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Users.php';


  $staff_id=clean_string($_GET['staff_id']);
  $sn=clean_string($_GET['sn']);



  reject_empty($staff_id);

  $user=new User();
  $query=$user->EndShift($staff_id,$sn);
  echo $query;
 ?>
