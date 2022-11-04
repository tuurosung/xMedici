<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Billing.php';

    $opd=new Visit();

    $request_type=clean_string($_GET['request_type']);
    $tests =$_GET['test_id'];

    // Create Request ID
    $request_id=$opd->LabRequestIdGen();


    if($request_type !='WALKIN'){

        $patient_id=clean_string($_GET['patient_id']);
        $visit_id=clean_string($_GET['visit_id']);

        reject_empty($patient_id);
        reject_empty($visit_id);
        reject_empty($tests);

        // Queue Tests
        foreach ($tests as $test_id) {
          $opd->QueueTest($patient_id,$visit_id,$request_id,$test_id);
        }

        // Create Billing
        $bill_amount=$opd->RequestCost($request_id,$patient_id,$visit_id);

        // Create Request
        $opd->RequestTests($patient_id,$visit_id,$request_id,$bill_amount,$patient_name,$request_type,$age,$sex,$requested_by,$doctors_name);

          $reference=$request_id;

        // Bill Patient
        $billing=new Billing();
        $billing->BillPatient($patient_id,$visit_id,$reference,$bill_amount,"Cost Of Laboratory Tests");

        echo 'save_successful';

    }else {

        $patient_id=rand(10000,99999);
        $visit_id=rand(1000,9999);

        $patient_name=clean_string($_GET['patient_name']);
        $age=clean_string($_GET['age']);
        $sex =$_GET['sex'];
        $doctors_name =$_GET['doctors_name'];


        reject_empty($patient_name);
        reject_empty($age);
        reject_empty($sex);
        reject_empty($doctors_name);

        // Queue Tests
        foreach ($tests as $test_id) {
          $opd->QueueTest($patient_id,$visit_id,$request_id,$test_id);
        }

        // Create Billing
        $bill_amount=$opd->RequestCost($request_id,$patient_id,$visit_id);

        // Create Request
        $opd->RequestTests($patient_id,$visit_id,$request_id,$bill_amount,$patient_name,$request_type,$age,$sex,$requested_by,$doctors_name);

          $reference=$request_id;

        // Bill Patient
        $billing=new Billing();
        $billing->BillPatient($patient_id,$visit_id,$reference,$bill_amount,"Cost Of Laboratory Tests");

        echo 'save_successful';


    }
