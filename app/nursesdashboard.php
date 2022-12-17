<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
  require_once '../serverscripts/Classes/Admissions.php';
?>
<?php


$patient = new Patient();
$opd = new Visit();
$pmt = new Payment();
$service = new Service();

?>

<?php

$p = new Patient();
$ward = new Ward();
$visit = new Visit();
$admission = new Admission();
// $s=new Service();

$active_user = $_SESSION['active_user'];
?>


<?php
if (!empty($user->email) && $user->mail_verify == 0) {
?>
  <div class="card primary-color-dark mb-5 white-text">
    <div class="card-body">
      <i class="far fa-bell mr-3" aria-hidden></i> Email verification pending. We've sent you a verification link in your inbox.
    </div>
  </div>
<?php
}
?>




<div class="row mb-5">
  <div class="col-5">
    <h4 class="titles">Nurses Dashboard</h4>
  </div>
  <div class="col-7 text-right d-flex flex-row-reverse">

    <div class="dropdown open">
      <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Request Walk-in
      </button>
      <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
        <ul class="list-group">
          <li class="list-group-item" data-toggle="modal" data-target="#new_lab_request_modal">Lab Request</li>
          <li class="list-group-item">Pharmacy</li>
          <li class="list-group-item" data-toggle="modal" data-target="#new_ultrasound_modal"><i class="fas fa-search mr-3" aria-hidden></i> Radiology</li>
        </ul>
      </div>
    </div>
    <a href="findpatient.php" type="button" class="btn btn-primary btn-rounded"><i class="fas fa-search mr-2 " aria-hidden></i> Find Patient</a>
  </div>
</div>

<div class="row mb-5">
  <div class="col-6">
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h6>New Patients</h6>
            <div class="row">
              <div class="col-6">
                <h3 class="montserrat text-success" style="font-weight:600">0</h3>
              </div>
              <div class="col-6 text-right"><img src="../images/outpatient.svg" alt="" style="height:30px"></div>
            </div>

            <div class="progress mt-2" style="height: 10px;">
              <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h6>My Patients</h6>
            <div class="row">
              <div class="col-6">
                <h3 class="montserrat text-success" style="font-weight:600">0</h3>
              </div>
              <div class="col-6 text-right"><img src="../images/mypatients.svg" alt="" style="height:30px"></div>
            </div>
            <div class="progress mt-2" style="height: 10px;">
              <div class="progress-bar bg-dark" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h6>In - Patients</h6>
            <div class="row">
              <div class="col-6">
                <h3 class="montserrat text-success" style="font-weight:600"><?php echo $admission->active_admissions; ?></h3>
              </div>
              <div class="col-6 text-right"><img src="../images/outpatient.svg" alt="" style="height:30px"></div>
            </div>
            <div class="progress mt-2" style="height: 10px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h6>Out Patients</h6>
            <div class="row">
              <div class="col-6">
                <h3 class="montserrat text-success" style="font-weight:600"><?php echo $visit->active_opd_cases; ?></h3>
              </div>
              <div class="col-6 text-right"><img src="../images/mypatients.svg" alt="" style="height:30px"></div>
            </div>
            <div class="progress mt-2" style="height: 10px;">
              <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="col-6">
    <div class="card">
      <div class="card-body" style="min-height:280px; overflow:auto">
        <h6 class="montserrat mb-5" style="font-weight:600">Pending Admission Requests</h6>

        <table class="table table-condensed">
          <thead style="font-weight:400;">
            <tr style="border-bottom:solid 1px #4285f4 !important">
              <th>Date</th>
              <th>Full Name</th>
              <th>Ward</th>
              <th>Doctor</th>
              <th></th>
            </tr>
          </thead>
          <tbody>


            <?php
            $get_patients = mysqli_query($db, "SELECT *
                                                                  FROM admissions
                                                                  WHERE
                                                                    admission_request_status='PENDING' AND
                                                                    status='active' AND
                                                                    subscriber_id='" . $active_subscriber . "'
                                              ")  or die(mysqli_error($db));
            $i = 1;
            while ($rows = mysqli_fetch_array($get_patients)) {
              $p->patient_id = $rows['patient_id'];
              $p->PatientInfo();
              $othernames = $p->othernames;

              $ward->ward_id = $rows['ward_id'];
              $ward->WardInfo();

              $doctor->doctor_id = $rows['admission_requested_by'];
              $doctor->DoctorInfo();
            ?>
              <tr>
                <td><?php echo date('d-m-Y', $rows['request_timestamp']); ?></td>
                <td><?php echo $p->patient_fullname; ?></td>
                <td><?php echo $ward->description; ?></td>
                <td><?php echo $doctor->doctor_fullname; ?></td>
                <td class="text-right">
                  <div class="dropdown open">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Options
                    </button>
                    <div class="dropdown-menu minioptions b-0 p-0" aria-labelledby="dropdownMenu1">
                      <ul class="list-group">
                        <li class="list-group-item accept_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-check-circle text-success mr-2" aria-hidden></i> Accept</li>
                        <li class="list-group-item reject_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-times-circle text-danger mr-2" aria-hidden></i> Reject</li>
                      </ul>
                    </div>
                  </div>
                </td>
              </tr>
            <?php
            }
            ?>


          </tbody>
        </table>


      </div>
    </div>
  </div>
</div>

<div class="row">

  <div class="col-md-6 mb-4 mb-md-0">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title"> Recent OPD Cases</h6>
        <hr>
        <div class="mb-5"></div>
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>#</th>
              <th>Folder #</th>
              <th>Visit ID</th>
              <th>Patient Name</th>
              <th>Major Complaint</th>
            </tr>
          </thead>
          <tbody>

            <div class="row">

              <?php

              // $list = $visit->AllVisits();
              $sql="SELECT * FROM visits ORDER BY timestamp desc LIMIT 10";
              $r=$mysqli->query($sql);
              $i = 1;
              while ($rows=$r->fetch_array()) {
                $p->patient_id = $rows['patient_id'];
                $p->PatientInfo();

                $patient_id = $rows['patient_id'];
                $visit_id = $rows['visit_id'];

                // $visit->VisitBalance()
              ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                 
                  <td><?php echo $rows['patient_id']; ?></td>
                  <td>
                    <u>
                      <a href="singlevisit.php?visit_id=<?php echo $rows['visit_id']; ?>">
                        <?php echo $rows['visit_id']; ?>
                      </a>
                    </u>
                  </td>
                  <td><?php echo $p->patient_fullname; ?></td>
                  <td><?php echo $rows['major_complaint']; ?></td>
                  
                </tr>
              <?php
              }
              ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-4 mb-md-0">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Discharge Requests</h6>
        <hr>
      </div>
    </div>
  </div>

</div>



<div id="new_lab_request_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width:1200px !important">
    <div class="modal-content">
      <form id="walkin_lab_request_frm">
        <div class="modal-body">
          <h6 class="montserrat font-weight-bold">Walk-In Lab Request</h6>
          <hr class="hr">

          <div class="spacer"> </div>
          <div class="spacer"> </div>

          <div class="form-group">
            <label for="">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" value="" required>
          </div>

          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Age</label>
                <input type="text" name="age" class="form-control" value="" required>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Sex</label>
                <select class="custom-select browser-default" name="sex">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="">Doctor's Name</label>
            <input type="text" name="doctors_name" class="form-control" value="">
          </div>



          <div class="form-group d-none">
            <input type="text" class="form-control" id="" name="request_type" value="WALKIN" readonly>
          </div>

          <ul class="list-group">

            <div class="row">




              <?php
              $get_tests = mysqli_query($db, "SELECT * FROM lab_tests WHERE subscriber_id='" . $active_subscriber . "' AND status='active' ORDER BY description") or die(mysqli_error($db));
              while ($tests = mysqli_fetch_array($get_tests)) {
              ?>
                <div class="col-4">
                  <li class="list-group-item">
                    <div class="form-check">
                      <input class="form-check-input tests" type="checkbox" name="test_id[]" value="<?php echo $tests['test_id']; ?>" id="<?php echo $tests['test_id']; ?>" />
                      <label class="form-check-label" for="<?php echo $tests['test_id']; ?>">
                        <?php echo $tests['description']; ?>
                      </label>
                    </div>
                  </li>
                </div>
              <?php
              }
              ?>
            </div>
          </ul>
          <div class="spacer">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2" aria-hidden></i>Submit Request</button>
        </div>
      </form>
    </div>
  </div>
</div>




<div id="new_ultrasound_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="radiology_request_frm" autocomplete="">
        <div class="modal-body px-4 pb-5">


          <h5 class="poppins" style="font-weight:500">New Radiology Request</h5>
          <hr class="hr">

          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Surname</label>
                <input type="text" class="form-control" name="surname" id="surname" value="">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Othername</label>
                <input type="text" class="form-control" name="othernames" id="othernames" value="">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Age</label>
                <input type="text" class="form-control" name="age" id="age" value="">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Sex</label>
                <select class="browser-default custom-select" name="sex" id="sex" value="">
                  <option value="">--select--</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="">Address / Ward</label>
            <input type="text" class="form-control" name="address" id="address" value="">
          </div>

          <div class="form-group">
            <label for="">Clinical History</label>
            <textarea name="clinical_history" class="form-control"></textarea>
          </div>


          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Investigation Requested</label>
                <select class="custom-select browser-default" name="service_id" id="ultrasound_service_id">
                  <option value="">--------</option>
                  <?php
                  $rows = $service->servicesFilter('radiology');
                  foreach ($rows as $row) {
                  ?>
                    <option value="<?php echo $row['service_id']; ?>" data-service_cost="<?php echo $row['service_cost']; ?>"><?php echo $row['description']; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Service Cost.</label>
                <input type="text" class="form-control" name="service_cost" id="ultrasound_service_cost" value="" readonly>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">Medical Officer / Dr.</label>
                <input type="text" class="form-control" name="doctor" id="doctor" value="">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Station Address</label>
                <input type="text" class="form-control" name="station_address" id="station_address" value="">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="">X-Ray Serial Number</label>
                <input type="text" class="form-control" name="serial_number" id="serial_number" value="">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="">Station Address</label>
                <input type="text" class="form-control" name="previous_serial_number" id="previous_serial_number" value="">
              </div>
            </div>
          </div>

          <div class="d-block float-right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary mr-0"><i class="fas fa-check mr-3" aria-hidden></i> Submit Request</button>
          </div>

          <div class="mb-4">

          </div>

        </div>
      </form>
    </div>
  </div>
</div>





<div id="modal_holder">

</div>


<?php require_once '../navigation/footer.php'; ?>
<?php
if (empty($user->email) && $_SESSION['noticeshown'] == 0) {
?>
  <script type="text/javascript">
    $('#email_modal').modal('show')
  </script>
<?php
  $_SESSION['noticeshown'] = 1;
}
?>
<script type="text/javascript">
  $('#update_user_email_frm').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: '../serverscripts/admin/Users/update_user_email.php',
      type: 'GET',
      data: $('#update_user_email_frm').serialize(),
      success: function(msg) {
        if (msg === 'update_successful') {
          bootbox.alert("We've sent a verification link to your email.", function() {
            window.location.reload();
          })
        } else {
          bootbox.alert(msg)
        }
      }
    })
  });



  $('.accept_admission').on('click', function(event) {
    event.preventDefault();
    var admission_id = $(this).data('admission_id');
    $.get('../serverscripts/admin/Wards/accept_admission_modal.php?admission_id=' + admission_id, function(msg) {
      $('#modal_holder').html(msg)
      $('#accept_admission_modal').modal('show')

      $('#accept_admission_frm').on('submit', function(event) {
        event.preventDefault();
        bootbox.confirm("Confirm Patient Admission?", function(r) {
          if (r === true) {
            $.ajax({
              url: '../serverscripts/admin/Wards/accept_admission_frm.php',
              type: 'GET',
              data: $('#accept_admission_frm').serialize(),
              success: function(msg) {
                if (msg === 'save_successful') {
                  bootbox.alert('Patient admission successful', function() {
                    window.location.reload();
                  })
                } else {
                  bootbox.alert(msg)
                }
              }
            }) //end ajax
          } //end if
        })
      });
    })
  });


  $('#walkin_lab_request_frm').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: '../serverscripts/admin/OPD/request_labs_frm.php',
      type: 'GET',
      data: $('#walkin_lab_request_frm').serialize(),
      success: function(msg) {
        if (msg === 'save_successful') {
          bootbox.alert('Success. Walkin Laboratory Request Queued.', function() {
            window.location.reload()
          })
        } else {
          bootbox.alert(msg)
        }
      }
    })
  });


  // Functions for handling ultrasound form

  $('#ultrasound_service_id').on('change', function(event) {
    event.preventDefault();
    let service_cost = $(this).find(":selected").data('service_cost');
    $('#ultrasound_service_cost').val(service_cost)
  });

  $('#radiology_request_frm').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: '../serverscripts/admin/OPD/Radiology/new_request.php?patient_type=walkin_patient',
      type: 'GET',
      data: $('#radiology_request_frm').serialize(),
      success: function(msg) {
        if (msg === 'save_successful') {
          bootbox.alert("Request successful", function() {
            window.location.reload()
          })
        } else {
          bootbox.alert(msg)
        }
      }
    })

  });
</script>