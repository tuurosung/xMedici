<?php

      require_once '../../dbcon.php';


      $invoice_id=clean_string($_GET['invoice_id']);
      $visit_id=clean_string($_GET['visit_id']);

      $query=mysqli_query($db,"UPDATE invoices SET visit_id='".$visit_id."' WHERE invoice_id='".$invoice_id."' AND subscriber_id='".$active_subscriber."'")  or die(mysqli_error($db));

      if($query){
        echo 'save_successful';
      }else {
        echo 'failed';
      }



 ?>
