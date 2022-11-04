<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php




    $patient=new Patient();
    $doctor=new Doctor();
    $nurse=new Nurse();
    $pharm=new Pharmacist();
    $opd=new Visit();
    $pmt=new Payment();






 ?>





<main class="py-3 ml-lg-5 main" style="">
  <div class="container-fluid mt-2">

    <?php
        if(!empty($user->email) && $user->mail_verify==0 ){
          ?>
          <div class="card primary-color-dark mb-5 white-text">
            <div class="card-body">
              <i class="far fa-bell mr-3" aria-hidden></i> Email verification pending. We've sent you a verification link in your inbox.
            </div>
          </div>
          <?php
        }
     ?>

    <div class="row mb-4">
      <div class="col-md-8">

        <div class="mb-5">
            <h3 class="montserrat mb-2">Welcome, <span class="font-weight-bold"><?php echo $user_fullname; ?></span></h3>
            <p>You have <?php echo $doctor->MyAppointments($active_user); ?> Appoitments Pending.</p>
        </div>


        <div class="">
          <h3 class="font-weight-bold montserrat mb-3">Appointments</h3>
          <?php
            $my_appointments=mysqli_query($db,"SELECT * FROM visits WHERE status='active' AND subscriber_id='".$active_subscriber."' AND doctor_id='".$active_user."'") or die(mysqli_error($db));
            while ($my_appointments=mysqli_fetch_array($my_appointments)) {
              $patient->patient_id=$my_appointments['patient_id'];
              $patient->PatientInfo();
              ?>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-2" style="font-weight:300">
                      <?php echo date('H:i:s',$my_appointments['timestamp']); ?>
                    </div>
                    <div class="col-3" style="font-weight:500">
                      <?php echo $patient->patient_fullname; ?>
                    </div>
                    <div class="col-4">
                      <?php echo $my_appointments['major_complaint']; ?>
                    </div>
                    <div class="col-3 text-right">
                      <div class="dropdown open">
                        <button class="btn btn-primary btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
                          <ul class="list-group">
                            <a href="singlevisit.php?visit_id=<?php echo $rows['visit_id']; ?>" class="list-group-item">
                              <i class="fas fa-arrow-alt-circle-right mr-2" aria-hidden></i>
                              Open Portal
                            </a>
                            <li class="list-group-item discharge" data-patient_id="<?php echo $rows['patient_id']; ?>" data-visit_id="<?php echo $rows['visit_id']; ?>">
                              <i class="fas fa-arrow-alt-circle-left mr-2" aria-hidden></i>
                              Discharge
                            </li>
                            <li class="list-group-item"><i class="fas fa-ban mr-2" aria-hidden></i> Absconded</li>
                            <li  class="list-group-item"><i class="fas fa-bed mr-2" aria-hidden></i> Expired</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }
           ?>
        </div>
      </div>
      <div class="col-md-4">

      </div>
    </div>



    <div class="row mb-4">
      <div class="col-md-8">
        <div class="card primary-color-dark text-white" style="border-radius:8px">
          <div class="card-body">

            <p class="poppins mb-3">Please be sure to check values entered before saving. There is no room for error when it comes to health records. Pay full attention to instructions, notifications and system warnings</p>
            <a href="consulting_room.php" type="button" class="btn btn-white m-0">
              <i class="fas fa-user-md mr-2" aria-hidden></i>
              Consulting Room
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-3">

        <div class="card mb-2">
          <div class="card-body">
            <div class="row poppins">
              <div class="col-6">OPD</div><div class="col-6 font-weight-bold text-right">3</div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="row poppins">
              <div class="col-6">Admissions</div><div class="col-6 font-weight-bold text-right">3</div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="row">
      <div class="col-md-3">

        <div class="card mb-2" style="border-radius:8px">
          <div class="card-body p-2">
            <div class="row">
              <div class="col-3">
                <i class="fas fa-search mr-2 xmedici_icons" aria-hidden></i>
              </div>
              <div class="col-9 poppins d-flex align-items-center font-weight-bold">
                <a href="findpatient.php">
                Find Patient
                </a>
              </div>
            </div>
          </div>
        </div>


        <div class="card mb-2" style="border-radius:8px">
          <div class="card-body p-2">
            <div class="row">
              <div class="col-3">
                <i class="fas fa-sign-in-alt mr-2 xmedici_icons" aria-hidden></i>
              </div>
              <div class="col-9 poppins d-flex align-items-center font-weight-bold">
                <a href="opd.php">
                  Out-Patients
                </a>
              </div>
            </div>
          </div>
        </div>


      </div>
      <div class="col-md-5">
        <div class="card">
          <div class="card-body">

          </div>
        </div>
      </div>
    </div>





    </div>
    <!-- Padding Div -->
















<?php require_once '../navigation/footer.php'; ?>

<?php
    if(empty($user->email)){
      ?>
      <script type="text/javascript">
        $('#email_modal').modal('show')
      </script>
      <?php
    }
 ?>
<script type="text/javascript">


    $('#update_user_email_frm').on('submit', function(event) {
      event.preventDefault();
      $.ajax({
        url: '../serverscripts/admin/Users/update_user_email.php',
        type: 'GET',
        data:$('#update_user_email_frm').serialize(),
        success:function(msg){
          if(msg==='update_successful'){
            bootbox.alert("We've sent a verification link to your email.",function(){
              window.location.reload();
            })
          }else {
            bootbox.alert(msg)
          }
        }
      })
    });


</script>
