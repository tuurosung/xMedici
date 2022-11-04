<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';
    require_once '../../Classes/Patient.php';

    $status=clean_string($_GET['status']);

 ?>



<table class="table datatables table-condensed">
  <thead class="grey lighten-4">
    <tr>
      <th>#</th>
      <th>Date/Time</th>
      <th>Patient Name</th>
      <th>Age</th>
      <th class="text-center">Sex</th>
      <th>Test Requested</th>
      <th>Sample</th>
      <th>Doctor</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // $s=new Service();
    $get_tests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE status='active' AND results_status='".$status."' AND subscriber_id='".$active_subscriber."' ORDER BY timestamp desc")  or die(mysqli_error($db));
    $i=1;
    while ($rows=mysqli_fetch_array($get_tests)) {
      $test=new Test();
      $test->test_id=$rows['test_id'];
      $test->TestInfo();

      $patient=new Patient();
      $patient->patient_id=$rows['patient_id'];
      $patient->PatientInfo();

      if($rows['patient_id']==''){
        $request_id=$rows['request_id'];
        $getinfo=mysqli_query($db,"SELECT * FROM lab_requests WHERE request_id='".$request_id."' AND subscriber_id='".$active_subscriber."'") or die(msyqli_error($db));
        $info=mysqli_fetch_array($getinfo);
        $fullname=$info['patient_name'];
        $age=$info['age'].'years';
        $sex=$info['sex'];
      }else {
        $fullname=$patient->patient_fullname;
        $age=$patient->age;
        $sex=$patient->sex_description;
      }
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo date('d-M-Y | H:i:s',$rows['timestamp']); ?></td>
        <td><?php echo $fullname; ?></td>
        <td><?php echo $age; ?></td>
        <td class="text-center"><?php echo $sex; ?></td>
        <td><?php echo $test->description; ?></td>
        <td><?php echo $test->specimen; ?></td>
        <td><?php echo $rows['results_status']; ?> <?php if($rows['results_status']=='complete'){ echo '<i class="fas fa-check"></i>'; } ?></td>
        <td class="text-right">

          <?php
              if($rows['results_status']=='complete'){
                ?>
                <div class="dropdown open">
                  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Options
                  </button>
                  <div class="dropdown-menu p-0 m-0 b-0" aria-labelledby="dropdownMenu1">
                    <ul class="list-group">
                      <li class="list-group-item print_results" data-request_id="<?php echo $rows['request_id']; ?>" data-test_id="<?php echo $rows['test_id']; ?>">Print Results</li>
                      <li class="list-group-item edit_test_btn" id="<?php echo $rows['test_id']; ?>">Edit Entry</li>
                    </ul>
                  </div>
                </div>
                <?php
              }else {
                ?>
                <a href="test_results_entry.php?test_id=<?php echo $rows['test_id']; ?>&request_id=<?php echo $rows['request_id']; ?>" type="button" class="btn btn-primary btn-sm"><i class="fas fa-file-alt mr-1" aria-hidden></i> Enter Results</a>
                <?php
              }
           ?>

        </td>
      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
