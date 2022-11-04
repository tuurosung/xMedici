<?php
  require_once '../../dbcon.php';
  $sn=clean_string($_GET['sn']);
  reject_empty($sn);

  $delete_query=mysqli_query($db,"UPDATE admission_billing SET status='deleted' WHERE sn='".$sn."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  if($delete_query){
    echo 'delete_successful';
  }else {
    echo 'Unable to delete bill';
  }

 ?>
