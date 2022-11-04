<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Drugs.php';
    require_once '../../Classes/Billing.php';

    $total_bill=clean_string($_GET['total_bill']);
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);


    reject_empty($total_bill);
    reject_empty($patient_id);
    reject_empty($visit_id);

    $update_prescriptions=mysqli_query($db,"UPDATE prescriptions SET dispensary_status='CHECKOUT' WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    $update_cart=mysqli_query($db,"UPDATE pharm_cart SET status='CHECKOUT' WHERE  patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

    // Bill Patient
    $billing=new Billing();
    $billing->BillPatient($patient_id,$visit_id,'Pharmacy-Billing',$total_bill,"Cost of drugs sold at pharmacy");

    echo 'checkout_successful';
 ?>
