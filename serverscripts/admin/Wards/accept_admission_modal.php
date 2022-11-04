<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Services.php';
    require_once '../../Classes/Patient.php';

    $admission_id=clean_string($_GET['admission_id']);
    $get_admission_info=mysqli_query($db,"SELECT * FROM admissions WHERE admission_id='".$admission_id."' AND status='active' AND admission_request_status='PENDING' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    $admission_info=mysqli_fetch_array($get_admission_info);

    $patient_id=$admission_info['patient_id'];
    $visit_id=$admission_info['visit_id'];
    $ward_id=$admission_info['ward_id'];

    $p=new Patient();
    $p->patient_id=$patient_id;
    $p->PatientInfo();
?>



<div id="accept_admission_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="accept_admission_frm">
      <div class="modal-body">

        <h6 class="montserrat" style="font-weight:800; font-size:25px">Admit Patient</h6>
        <hr class="small-hr">

        <div class="row mt-5">
          <div class="col-md-4">

            <?php
              // if($visit->admission_status=='admitted'){
              // 	?>
              <!-- // 	<p class="text-center mb-3 montserrat font-weight-bold">ADMITTED</p>
              // 	<hr> -->
              <?php
              // }
             ?>

            <div class="text-center mb-3">
              <?php
                switch ($p->sex) {
                  case 'male':
                    echo '<img src="../images/dummy_male.png" alt="" class="img-fluid" style="width:100px">';
                    break;
                  case 'female':
                    echo '<img src="../images/dummy_female.png" alt="" class="img-fluid mx-auto" style="width:100px">';
                    break;

                  default:
                    // code...
                    break;
                }
               ?>
            </div>



          </div>
          <div class="col-md-8">

            <p class="font-weight-bold montserrat text-uppercase primary-text" style="font-size:16px"><?php echo $p->patient_fullname; ?></p>

            <div class="row">
              <div class="col-6">
                  <p class="font-weight-bold text-uppercase montserrat"><?php echo $p->sex; ?> | <?php echo $p->age; ?></p>
              </div>
              <div class="col-6">
                <p class="font-weight-bold text-uppercase montserrat"></p>
              </div>
            </div>





          </div>
        </div>

        <hr>

        <div class="row d-none">
          <div class="col-md-6">
              <input type="text" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
          </div>
          <div class="col-md-6">
            <input type="text" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Admission ID</label>
              <input type="text" class="form-control" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Bed Allocation</label>
              <select class="custom-select browser-default" name="bed_id">
                <?php
                  $get_beds=mysqli_query($db,"SELECT * FROM beds WHERE ward_id='".$ward_id."' AND bed_status='EMPTY'") or die(mysqli_error($db));
                  while ($rows=mysqli_fetch_array($get_beds)) {
                    ?>
                    <option value="<?php echo $rows['bed_id']; ?>"><?php echo $rows['description']; ?></option>
                    <?php
                  }
                 ?>
              </select>
            </div>
          </div>
        </div>


        <div class="form-group">
          <label for="">Admitting Nurse's Notes</label>
          <textarea name="notes" class="form-control"></textarea>
        </div>

        <hr>
        <section class="p">
          <div class="row">
          <?php
            $get_charges=mysqli_query($db,"SELECT * FROM services WHERE billing_point='admission' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
            while ($admission_charges=mysqli_fetch_array($get_charges)) {
              ?>
              <div class="col-md-4">
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="services[]"
                    <?php if($admission_charges['billing_type']=='mandatory'){ echo 'checked';} ?>
                    id="<?php echo $admission_charges['service_id']; ?>"
                    value="<?php echo $admission_charges['service_id']; ?>"
                  />
                  <label class="form-check-label" for="<?php echo $admission_charges['service_id']; ?>">
                    <?php echo $admission_charges['description']; ?>
                  </label>
                </div>
              </div>
              <?php
            }
           ?>
         </div>
        </section>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-bed  mr-2" aria-hidden></i> Admit Patient</button>
      </div>
    </form>
    </div>
  </div>
</div>
