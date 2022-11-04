<?php

    require_once '../../dbcon.php';
    require_once '../../Classes/Invoices.php';
    require_once '../../Classes/OPD.php';

    $patient_id=clean_string($_GET['patient_id']);
    $ref=clean_string($_GET['ref']);
    $due_date=clean_string($_GET['due_date']);
    $footer_notes=clean_string($_GET['footer_notes']);

    $visit=new Visit();


      if($visit->CheckForVisit($patient_id)=='no_visit'){
        echo 'Patient does not have an active visit';
      }elseif ($visit->CheckForVisit($patient_id)=='active_visit') {

        $invoice=new Invoice();
        $query=$invoice->CreateInvoice($invoice_type,$patient_id,$ref,$due_date,$created_by,$footer_notes);
        echo $query;
        
      }




 ?>
