<?php

  require_once '../../dbcon.php';
  require_once '../../Classes/Invoices.php';
  require_once '../../Classes/Billing.php';

  $invoice_id=clean_string($_GET['invoice_id']);

  $invoice=new Invoice();

  $invoice->invoice_id=$invoice_id;
  $invoice->InvoiceInfo();

  $patient_id=$invoice->patient_id;
  $visit_id=$invoice->visit_id;
  $invoice_total=$invoice->total;

  if(empty($visit_id)){
    echo 'Patient Visit Not Selected';
  }else {
    $update=mysqli_query($db,"UPDATE invoices SET lockstatus='locked' WHERE invoice_id='".$invoice_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

    if($update){
      // Bill Patient
      $billing=new Billing();
      $query=$billing->BillPatient($patient_id,$visit_id,$invoice_id,$invoice_total,'Sum of services and drugs on patient invoice number "'.$invoice_id.'"');
      echo 'save_successful';
    }
  }




 ?>
