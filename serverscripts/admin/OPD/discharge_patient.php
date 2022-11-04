<?php

  require_once '../../dbcon.php';
  require_once '../../Classes/OPD.php';

  $visit_id=clean_string($_GET['visit_id']);
  $patient_id=clean_string($_GET['patient_id']);

  $opd=new Visit();

  $outstanding_bill=$opd->VisitBalance($patient_id,$visit_id);

  if($outstanding_bill >0){
    echo "Outstanding Bills Not Cleared";
  }else {
    $discharge_query=mysqli_query($db,"UPDATE visits
                                                                SET status='discharged'
                                                                WHERE
                                                                  visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND subscriber_id='".$active_subscriber."'
                                                      ") or die(mysqli_error($db));
    if($discharge_query){
      echo 'discharge_successful';
    }else {
      echo 'failed';
    }
  }





 ?>
