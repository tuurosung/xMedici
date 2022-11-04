<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Services.php';
    require_once '../../Classes/ServiceRequests.php';
    require_once '../../Classes/Billing.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $service_id=clean_string($_GET['service_id']);
    $visit_type=clean_string($_GET['visit_type']);
    $major_complaint=clean_string($_GET['major_complaint']);

    reject_empty($patient_id);
    reject_empty($service_id);
    reject_empty($visit_type);
    reject_empty($major_complaint);

    // Create Visit
    $visit_id=$opd->CreateVisit($patient_id,$visit_type,$major_complaint);

    $s=new Service();
    $s->service_id=$service_id;
    $s->ServiceInfo();
    $unit_cost=$s->service_cost;
    $qty=1;

    $total= (int) $qty * (float)$unit_cost;

    // Record Request
    $srq=new ServiceRequest();
    $reference=$srq->RequestService($patient_id,$visit_id,$service_id,$unit_cost,$qty,$total);

    $bill_amount=$total;


    // Bill Patient
    $billing=new Billing();
    $billing->BillPatient($patient_id,$visit_id,$reference,$bill_amount,"Consultation Fee");

    echo 'save_successful';
