<ul class="list-group">
<?php
  session_start();
  require_once '../../dbcon.php';
  require_once '../../Classes/Patient.php';

  $p=new Patient();

  $search_term=clean_string($_GET['search_term']);

  $query=mysqli_query($db," SELECT *
                                              FROM patients
                                              WHERE
                                                (patient_id LIKE '%".$search_term."%' OR surname LIKE '%".$search_term."%' OR othernames LIKE '%".$search_term."%' OR phone_number LIKE '%".$search_term."%' )
                                                AND
                                                subscriber_id='".$active_subscriber."'
                                                AND
                                                status!='deleted'
                                  ") or die(mysqli_error($db));
      ?>

      <?php
      // $get_patients=mysqli_query($db,"SELECT * FROM patients WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY surname asc")  or die('failed');
      $i=1;
      if(mysqli_num_rows($query)==0){
        ?>
        <h3 class="p-5 m-5 text-center poppins">No Patients Found</h3>
        <?php
      }
      else {
      while ($rows=mysqli_fetch_array($query)) {
        $p->patient_id=$rows['patient_id'];
        $p->PatientInfo();
        $othernames=$rows['othernames'];
        ?>
        <div class="card mb-3">
          <div class="card-body">
            <div class="row" style="font-size:12px">
              <div class="col-md-1">
                <p class="m-0 " style="font-weight:500">
                  <a  href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>" class="font-weight-bold">
                    <?php echo $p->patient_id; ?>
                  </a>
                  </p>

              </div>
              <div class="col-md-4">
                <p><?php echo $p->patient_fullname; ?></p>
              </div>
              <div class="col-md-2">
                <p><?php echo $p->age; ?></p>
              </div>
              <div class="col-md-2">
                <p><?php echo ucfirst($p->sex); ?></p>
              </div>
              <div class="col-md-2">
                <p><?php echo $p->last_visit; ?></p>
              </div>
              <div class="col-md-1 text-right">
                <a href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>">
                  <i class="fas fa-chevron-right text-primary fa-2x" aria-hidden></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }
  ?>
</div>



 </ul>
