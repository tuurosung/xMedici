<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Services.php';

    $opd=new Visit();
    $service=new Service();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);

    $admission_id=clean_string($_GET['admission_id']);
    $bed_id=clean_string($_GET['bed_id']);
    $nurse_notes=clean_string($_GET['notes']);
    $nurse_id=$_SESSION['active_user'];


    $services=implode(",",$_GET['services']);
    $services=explode(',',$services);


    $query=$opd->AcceptAdmission($admission_id,$bed_id,$admission_notes,$nurse_id);

    if($query=='save_successful'){
      foreach ($services as $srv) {
        $service->service_id=$srv;
        $service->ServiceInfo();
        $unit_cost=$service->service_cost;

        $table='admission_billing';
        $fields=array("subscriber_id","patient_id","visit_id","admission_id","service_id","unit_cost","days","status");
        $values=array("$active_subscriber","$patient_id","$visit_id","$admission_id","$srv","$unit_cost","1","active");
        $query=insert_data($db,$table,$fields,$values);

      }

      echo 'save_successful';
    }
