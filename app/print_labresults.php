<?php require_once '../navigation/print_header_a4.php'; ?>

<?php
    require_once '../serverscripts/dbcon.php';
    require_once '../serverscripts/Classes/Tests.php';
    require_once '../serverscripts/Classes/Patient.php';
    require_once '../serverscripts/Classes/OPD.php';

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


  <h4 class="font-weight-bold montserrat" style="font-weight:bold; font-size:16px">LABORATORY RESULTS</h4>

  <div class="row poppins" style="margin-top:30px">
    <div class="col-3">Request ID</div>
    <div class="col-9 font-weight-bold"><?php echo $request_id; ?></div>
  </div>
  <div class="row poppins">
    <div class="col-3">Patient Name</div>
    <div class="col-9 font-weight-bold"><?php echo $patient->patient_fullname; ?></div>
  </div>
  <div class="row poppins">
    <div class="col-3">Date</div>
    <div class="col-9"><?php echo $opd->requested_test_date; ?></div>
  </div>


  <div class="spacer"></div>

  <h6 class="montserrat font-weight-bold"><?php echo $test->description; ?></h6>


  <table class="table table-condensed">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Value</th>
        <th>Normal Range</th>
        <th>Flag</th>
        <th>Unit</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $load_parameters=mysqli_query($db,"SELECT * FROM lab_requests_results WHERE request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
        while ($rows=mysqli_fetch_array($load_parameters)) {
          $parameter_id=$rows['parameter_id'];
          $test->ParameterInfo($parameter_id);
          ?>
          <tr>
            <td><?php echo $test->parameter_name; ?></td>
            <td><?php echo $rows['result']; ?></td>
            <td><?php echo $test->parameter_general_min; ?> - <?php echo $test->parameter_general_max; ?></td>
            <td>
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
           </td>

            <td><?php echo $test->parameter_unit; ?></td>
          </tr>
          <?php
        }
       ?>

    </tbody>
  </table>

  <div class="" style="margin-top:50px">
      <h6>Comments</h6>
    <?php
      $getcomments=mysqli_query($db,"SELECT * FROM lab_test_results_comments WHERE test_id='".$test_id."' AND request_id='".$request_id."' AND status='active'") or die(mysqli_error($db));
      while ($comments=mysqli_fetch_array($getcomments)) {
        ?>
        <p><span class="font-weight-bold">Scientist: </span><?php echo $comments['scientist']; ?></p>
        <p><?php echo $comments['comments']; ?></p>
        <div class="spacer">

        </div>
        <?php
      }
     ?>
  </div>

  <div class="" style="position:absolute; width:90%; bottom:20px">

    <div class="">
      <p class="m-0 font-weight-bold poppins">Legend</p>
      <p class="m-0">*L -- Low</p>
      <p class="m-0">*H -- High</p>
    </div>



    <div class="float-right">
      <p>.....................................................</p>
      (Supervising Scientist)
    </div>
    <div class="row">
      <div class="col-md-6">

      </div>
      <div class="col-md-6 text-center">

      </div>
    </div>
  </div>

  <script type="text/javascript">
    print();
  </script>
