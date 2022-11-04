<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Drugs.php';

    $opd=new Visit();
    $drug=new Drug();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);

    $query=mysqli_query($db,"SELECT *
                                                FROM prescriptions
                                                WHERE
                                                  subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
                                      ") or die(mysqli_error($db));

    if(mysqli_num_rows($query)==0){
      echo 'No Prescriptions';
    }else {
      while ($drugs=mysqli_fetch_array($query)) {
        $drug->drug_id=$drugs['drug_id'];
        $drug->DrugInfo();
        ?>
        <li class="list-group-item">
          <div class="row">
            <div class="col-md-4">
              <?php echo $drug->drug_name; ?>
            </div>
            <div class="col-md-2">
              <?php echo $drugs['strength'].' '.$drugs['strength_unit']; ?>
            </div>
            <div class="col-md-2">
              <?php echo $drugs['route']; ?>
            </div>
          </div>
        </li>
        <?php

      }
    }
?>
