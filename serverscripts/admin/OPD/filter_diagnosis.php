<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Patient.php';

    $search_term=clean_string($_GET['search_term']);
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);


    $query=mysqli_query($db,"SELECT *
                                               FROM
                                                  sys_diagnosis
                                                 WHERE
                                                diagnosis_id LIKE '%".$search_term."%' OR description LIKE '%".$search_term."%'
                                              LIMIT 50
                                    ") or die(mysqli_error($db));
                      while ($diagnosis=mysqli_fetch_array($query)) {
                        ?>
                        <li class="list-group py-1">
                          <div class="row">
                            <div class="col-md-2">
                              <?php echo $diagnosis['diagnosis_id']; ?>
                            </div>
                            <div class="col-md-7">
                                <?php echo $diagnosis['description']; ?>
                            </div>
                            <div class="col-md-2">
                              <button type="button" class="btn btn-primary btn-sm diagnose_btn" data-diagnosis_id="<?php echo $diagnosis['diagnosis_id']; ?>" data-patient_id="<?php echo $patient_id; ?>" data-visit_id="<?php echo $visit_id; ?>">Diagnose</button>
                            </div>
                          </div>
                        </li>
                        <?php
                      }
