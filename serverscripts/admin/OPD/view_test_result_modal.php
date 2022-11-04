<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';
    require_once '../../Classes/Patient.php';
    require_once '../../Classes/OPD.php';

    $request_id=clean_string($_GET['request_id']);
    $test_id=clean_string($_GET['test_id']);

    $test=new Test();
    $test->test_id=$test_id;
    $test->TestInfo();

    $opd=new Visit();
    $opd->TestRequestInfo($request_id);
    $patient_id=$opd->requested_test_patient_id;

    $patient=new Patient();
    $patient->patient_id=$patient_id;
    $patient->PatientInfo();


 ?>
<div id="view_test_result_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">



        <div class="row poppins">
          <div class="col-md-3">Request ID</div>
          <div class="col-md-9 font-weight-bold"><?php echo $request_id; ?></div>
        </div>
        <div class="row poppins">
          <div class="col-md-3">Patient Name</div>
          <div class="col-md-9 font-weight-bold"><?php echo $patient->patient_fullname; ?></div>
        </div>
        <div class="row poppins">
          <div class="col-md-3">Date</div>
          <div class="col-md-9"><?php echo $opd->requested_test_date; ?></div>
        </div>

        <hr class="hr">

        <div class="spacer">

        </div>
        <h6 class="montserrat font-weight-bold"><?php echo $test->description; ?></h6>

        <li class="list-group-item custom-list-item" style="border-left:none !important; border-right:none !important">
          <div class="row">
            <div class="col-md-4">
              Parameter
            </div>
            <div class="col-md-2">
              Value
            </div>
            <div class="col-md-1">

            </div>
            <div class="col-md-3">
              Normal Range
            </div>
            <div class="col-md-2">
              Unit
            </div>
          </div>
        </li>
        <?php
          $load_parameters=mysqli_query($db,"SELECT * FROM lab_requests_results WHERE request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
          while ($rows=mysqli_fetch_array($load_parameters)) {
            $parameter_id=$rows['parameter_id'];
            $test->ParameterInfo($parameter_id);
            ?>
            <li class="list-group-item" style="border-left:none !important; border-right:none !important">
              <div class="row">
                <div class="col-md-4">
                  <?php echo $test->parameter_name; ?>
                </div>
                <div class="col-md-2">
                  <?php echo $rows['result']; ?>
                </div>
                <div class="col-md-1">
                  <?php
                    if($rows['result'] > $test->parameter_general_max){
                      ?>
                      <p class="m-0 font-weight-bold">H</p>
                      <?php
                    }elseif ($rows['result'] < $test->parameter_general_min) {
                      ?>
                      <p class="m-0 font-weight-bold">L</p>
                      <?php
                    }
                   ?>
                </div>
                <div class="col-md-3">
                  <?php echo $test->parameter_general_min; ?> - <?php echo $test->parameter_general_max; ?>
                </div>
                <div class="col-md-2">
                  <?php echo $test->parameter_unit; ?>
                </div>
              </div>
            </li>
            <?php
          }
         ?>

         <div class="mt-5">
           <div class="row">
             <div class="col-md-6">
               <p class="m-0 font-weight-bold poppins">Legend</p>

               <p class="m-0">*L -- Low</p>
               <p class="m-0">*H -- High</p>
             </div>
             <div class="col-md-6 text-center">
               <p>.....................................................</p>
               (Supervising Scientist)
             </div>
           </div>
         </div>


      </div>
      <div class="modal-footer">
        ...
      </div>
    </div>
  </div>
</div>
