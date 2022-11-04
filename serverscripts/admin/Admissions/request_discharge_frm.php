<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Billing.php';
    require_once '../../Classes/Admissions.php';

    $admission=new Admission();


    $admission_id=clean_string($_GET['admission_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $patient_id=clean_string($_GET['patient_id']);

    $admission_bill_narration=$_GET['admission_bill_narration'];
    $admission_bill=$_GET['admission_bill'];
    $discharge_notes=$_GET['discharge_notes'];
    $time=date('Y-m-d H:i:s');

    reject_empty($admission_bill_narration);
    reject_empty($admission_bill);
    reject_empty($admission_id);

    $query=$admission->RequestDischarge($admission_id,$patient_id,$visit_id,$discharge_notes,$time);

    if($query=='save_successful'){
      $update_admission_status=mysqli_query($db,"UPDATE admissions
                                                                                SET
                                                                                  admission_status='discharge_requested'
                                                                                WHERE
                                                                                  admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'
                                                                      ") or die(mysqli_error($db));
        if($update_admission_status){
          // Bill Patient
          $billing=new Billing();
          $query=$billing->BillPatient($patient_id,$visit_id,'DischargeBilling',$admission_bill,$admission_bill_narration);

          echo $query;
        }else {
          echo 'Discharge Failed';
        }
    }else {
      echo 'Discharge Failed';
    }



 ?>
