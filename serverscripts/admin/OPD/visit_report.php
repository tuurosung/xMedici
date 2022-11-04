<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Patient.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Drugs.php';
    require_once '../../Classes/Tests.php';


    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);

    $visit=new Visit();
    $visit->VisitInfo($visit_id);


    $patient_id=$visit->patient_id;

    $p=new Patient();
    $p->patient_id=$patient_id;
    $p->PatientInfo();

    $opd=new Visit();

 ?>




<div id="visit_report_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <section class="mb-5">
          <p class="montserrat font-weight-bold">1. Presenting Complains</p>

          <div class="px-4 py-2 mt-4">
            <?php

            $query=mysqli_query($db,"SELECT *
                                                        FROM patient_complains
                                                        WHERE
                                                          subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'
                                              ") or die(mysqli_error($db));

            if(mysqli_num_rows($query)==0){
              echo 'No Complains';
            }else {
              while ($complains=mysqli_fetch_array($query)) {
                ?>
                <p class="mb-2"><?php echo ucfirst($complains['complain']); ?>, <?php echo $complains['complain_duration']; ?></p>

                <?php
              }
            }

             ?>
          </div>
        </section>

        <section class="mb-5">
          <p class="montserrat font-weight-bold mb-3">2. Out - Direct Questioning</p>

          <div class="grey lighten-4 px-4 py-2" id="odq_holder">
            <?php

            $query=mysqli_query($db,"SELECT *
                                                        FROM patient_odq
                                                        WHERE
                                                          subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
                                              ") or die(mysqli_error($db));

            if(mysqli_num_rows($query)==0){
              echo 'No ODQ';
            }else {
              // $hpc_count=1;
              while ($odq=mysqli_fetch_array($query)) {
                ?>
                <p><?php echo $odq['question']; ?> <?php echo $odq['response']; ?></p>

                <div class="mb-3">

                </div>
                <?php
              }
            }

             ?>

          </div>
        </section>


        <section class="mb-5">
          <p class="montserrat font-weight-bold mb-3">3. History Of Presenting Complaints</p>

          <div class="grey lighten-4  px-4 py-2 mt-4">
            <?php

            $query=mysqli_query($db,"SELECT *
                                                        FROM patient_hpc
                                                        WHERE
                                                          subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
                                              ") or die(mysqli_error($db));

            if(mysqli_num_rows($query)==0){
              echo 'No HPC';
            }else {
              $hpc_count=1;
              while ($hpc=mysqli_fetch_array($query)) {
                ?>
                <p><?php echo $hpc['history']; ?></p>

                <div class="mb-3">

                </div>
                <?php
              }
            }

             ?>

          </div>
        </section>

        <section class="mb-5">
          <p class="montserrat font-weight-bold mb-3">4. Clinical Examination</p>
          <div class="grey lighten-4  px-4 py-2 mt-4" id="examination_holder">
            <?php

            $query=mysqli_query($db,"SELECT *
                                                        FROM patient_examination
                                                        WHERE
                                                          subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
                                              ") or die(mysqli_error($db));

            if(mysqli_num_rows($query)==0){
              echo 'No Clinical Examination';
            }else {
              // $hpc_count=1;
              while ($odq=mysqli_fetch_array($query)) {
                ?>
                <p><?php echo $odq['observation']; ?></p>

                <?php
              }
            }

             ?>
          </div>
        </section>

        <section class="mb-5">
          <p class="mb-3 font-weight-bold montserrat">5. Diagnosis</p>

          <div class="grey lighten-4 px-4 py-2">
            <?php

            $query=mysqli_query($db,"SELECT *
                                                        FROM patient_diagnosis
                                                        WHERE
                                                          subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'
                                              ") or die(mysqli_error($db));

            if(mysqli_num_rows($query)==0){
              echo 'No Diagnosis';
            }else {
              while ($diagnosis=mysqli_fetch_array($query)) {
                ?>
                <p class="montserrat font-weight-bold m-0">	<?php echo $diagnosis['diagnosis_id']; ?></p>
                <p class="m-0 montserrat"><?php echo $opd->Diagnosis($diagnosis['diagnosis_id']); ?></p>
                <div class="mb-4">

                </div>
                <?php

              }
            }

             ?>
          </div>
        </section>

          <section>
            <h6 class="montserrat font-weight-bold">Investigations</h6>

            <div class="grey lighten-4 p-3">
              <li class="list-group-item custom-list-item">
                <div class="row">
                  <div class="col-md-2">
                    Request ID
                  </div>
                  <div class="col-md-6">
                    Test Name
                  </div>
                  <div class="col-md-2">
                    Result Status
                  </div>
                  <div class="col-md-2 text-right">
                    Option
                  </div>
                </div>
              </li>
              <?php
                $get_lab_requests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($db));
                while ($requests=mysqli_fetch_array($get_lab_requests)) {
                  $test=new Test();
                  $test->test_id=$requests['test_id'];
                  $test->TestInfo();
                  ?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-md-2">
                        <?php echo $requests['request_id']; ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo $test->description; ?>
                      </div>
                      <div class="col-md-2">
                        <?php echo $requests['results_status']; ?>
                      </div>
                      <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-primary btn-sm view_test_result" data-test_id="<?php echo $requests['test_id']; ?>" data-request_id="<?php echo $requests['request_id'] ?>"><i class="fa fa-file-alt mr-2"></i> Results</button>
                      </div>
                    </div>
                  </li>
                  <?php
                }
               ?>

            </div>
          </section>
      </div>
      <div class="modal-footer">
        ...
      </div>
    </div>
  </div>
</div>
