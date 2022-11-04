<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);


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
    <div class="card mt-4">
      <div class="card-body">
        <div class="row" style="font-size:11px">
          <div class="col-8">
            <p class="m-0 montserrat font-weight-bold"><?php echo $complains['complain']; ?></p>
            <p class="m-0 montserrat">Duration : <?php echo $complains['complain_duration']; ?></p>
          </div>
          <div class="col-4">
            <a href="#" class="btn btn-danger btn-sm remove_complain" id="<?php echo $complains['sn']; ?>">
              <i class="fas fa-trash-alt mr-2" aria-hidden></i> Remove
            </a>
          </div>
        </div>

      </div>
    </div>



    <div class="mb-3">

    </div>
    <?php
  }
}

 ?>
