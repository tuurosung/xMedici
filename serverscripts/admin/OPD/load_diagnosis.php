<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);

    $query=mysqli_query($db,"SELECT *
                                                FROM patient_diagnosis
                                                WHERE
                                                  subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
                                      ") or die(mysqli_error($db));

    if(mysqli_num_rows($query)==0){
      echo 'No Diagnosis';
    }else {
      while ($diagnosis=mysqli_fetch_array($query)) {
        ?>
        <li class="list-group-item grey lighten-5">
          <div class="row">
            <div class="col-md-4">
              <?php echo $diagnosis['diagnosis_id']; ?>
            </div>
            <div class="col-md-6">
              <?php echo $opd->Diagnosis($diagnosis['diagnosis_id']); ?>
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-danger btn-sm">Delete</button>
            </div>
          </div>
        </li>
        <?php

      }
    }
?>
