<?php

  // include classes
  require_once '../../../dbcon.php';
  require_once '../../../Classes/Billing.php';
  require_once '../../../Classes/Radiology.php';

  $radio=new Radiology();

  // get and clean data

  $patient_type=clean_string($_GET['patient_type']);

  if($patient_type=='walkin_patient'){
    $patient_id='';
    $visit_id='';
    $surname=clean_string($_GET['surname']);
    $othernames=clean_string($_GET['othernames']);
    $age=clean_string($_GET['age']);
    $sex=clean_string($_GET['sex']);
    $address=clean_string($_GET['address']);
    $clinical_history=clean_string($_GET['clinical_history']);
    $service_id=clean_string($_GET['service_id']);
    $service_cost=clean_string($_GET['service_cost']);
    $doctor=clean_string($_GET['doctor']);
    $station_address=clean_string($_GET['station_address']);



    $request_id=$radio->NewRequest($patient_type,$patient_id,$visit_id,$surname,$othernames,$age,$sex,$address,$clinical_history,$service_id,$service_cost,$doctor,$station_address);

    if($request_id != 'false'){
      // Bill Patient
      $reference=$request_id;
      $bill_amount=$service_cost;
      $patient_id='Walkin';
      $visit_id='Walkin';

      $billing=new Billing();
      $billing->BillPatient($patient_id,$visit_id,$reference,$bill_amount,"Cost Of Walkin Radiology Request");

      echo 'save_successful';
    }

  }else {
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $surname='';
    $othernames='';
    $age='';
    $sex='';
    $address='';
    $clinical_history=clean_string($_GET['clinical_history']);
    $service_id=clean_string($_GET['service_id']);
    $service_cost=clean_string($_GET['service_cost']);
    $doctor=clean_string($_GET['doctor']);
    $station_address=clean_string($_GET['station_address']);



    $request_id=$radio->NewRequest($patient_type,$patient_id,$visit_id,$surname,$othernames,$age,$sex,$address,$clinical_history,$service_id,$service_cost,$doctor,$station_address);

    if($request_id != 'false'){
      // Bill Patient
      $reference=$request_id;
      $bill_amount=$service_cost;
      $patient_id='Walkin';
      $visit_id='Walkin';

      $billing=new Billing();
      $billing->BillPatient($patient_id,$visit_id,$reference,$bill_amount,"Cost Of Walkin Radiology Request");

      echo 'save_successful';
    }
  }

 ?>
