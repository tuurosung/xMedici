<?php

  require_once '../../dbcon.php';
  require_once '../../Classes/Billing.php';
  require_once '../../Classes/Patient.php';
  require_once '../../Classes/Services.php';

  $bill_id=clean_string($_GET['bill_id']);

  $billing=new Billing();
  $billing->bill_id=$bill_id;
  $billing->BillInfo();

  $patient_id=$billing->patient_id;
  $patient=new Patient();
  $patient->patient_id=$patient_id;
  $patient->PatientInfo();

  $reference=$billing->reference;
  $prefix=substr($reference,0,3);

  $service=new Service();


  if($prefix=='SRQ'){
    $request_info=mysqli_query($db,"SELECT * FROM service_requests WHERE request_id='".$reference."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    $request_info=mysqli_fetch_array($request_info);
    $service_id=$request_info['service_id'];
    $service->service_id=$service_id;
    $service->ServiceInfo();
    $details=$service->description;
  }


 ?>


 <div id="bill_info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-body">
          <h6 class="font-weight-bold montserrat mb-4">Bill Info - <?php echo $service->service_id; ?></h6>


          <div class="mb-3">
            <label for="" class="m-0">Folder Number</label>
            <p class="font-weight-bold m-0"><?php echo $billing->patient_id; ?></p>
          </div>

          <div class="mb-3">
            <label for="" class="m-0">Patient Name</label>
            <p class="font-weight-bold m-0"><?php echo $patient->patient_fullname; ?></p>
          </div>

          <div class="mb-3">
            <label for="" class="m-0">Details</label>
            <p class="font-weight-bold m-0"><?php echo $billing->reference; ?> - <?php echo $details; ?></p>
            <p class="font-weight-bold m-0"><?php echo $billing->narration; ?> </p>
          </div>

       </div>
       <div class="modal-footer">
         ...
       </div>
     </div>
   </div>
 </div>
