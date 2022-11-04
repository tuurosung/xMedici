<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Services.php';

    $opd=new Visit();
    $service=new Service();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $ward_id=clean_string($_GET['ward_id']);
    
    $admission_notes=clean_string($_GET['admission_notes']);
    $doctor_id=$_SESSION['active_user'];
    // $services=implode(",",$_GET['services']);
    // $services=explode(',',$services);


    reject_empty($patient_id);
    reject_empty($visit_id);
    reject_empty($ward_id);
    reject_empty($admission_notes);

    // echo $services;

    $query=$opd->RequestAdmission($patient_id,$visit_id,$ward_id,$admission_notes,$doctor_id);

    // foreach ($services as $srv) {
    //   $service->service_id=$srv;
    //   $service->ServiceInfo();
    //   $unit_cost=$service->service_cost;
    //
    //
    //
    //   $table='admission_billing';
    //   $fields=array("subscriber_id","patient_id","visit_id","admission_id","service_id","unit_cost","days","status");
    //   $values=array("$active_subscriber","$patient_id","$visit_id","$admission_id","$srv","$unit_cost","1","active");
    //   $query=insert_data($db,$table,$fields,$values);
    //
    // }

    echo $query;

 ?>
