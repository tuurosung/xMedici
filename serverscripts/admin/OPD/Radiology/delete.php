<?php

  // include classes
  require_once '../../../dbcon.php';
  require_once '../../../Classes/Billing.php';
  require_once '../../../Classes/Radiology.php';

  $radio=new Radiology();
  $billing=new Billing();



  $request_id=clean_string($_GET['request_id']);
  $radio->request_id=$request_id;

  $query=$radio->Delete();
  if($query=='delete_successful'){
    echo 'delete_successful';
  }else {
    echo $query;
  }

 ?>
