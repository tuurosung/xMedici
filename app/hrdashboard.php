<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php




    $patient=new Patient();
    $p=new Patient();
    $doctor=new Doctor();
    $nurse=new Nurse();
    $pharm=new Pharmacist();
    $opd=new Visit();
    $pmt=new Payment();
    $expenditure=new Expenditure();
    $account=new Account();
    $staff=new Staff();

    $today=date('Y-m-d');




 ?>





 <main class="pb-3 ml-lg-5 main" style="">
   <div class="container-fluid mt-0 pt-5">

    <div class="row mb-4">
      <div class="col-md-7">
          <h4 class="titles mb-4">Dashboard</h4>
      </div>
      <div class="col-md-5 text-right">
        <button type="button" class="btn primary-color-dark m-0 br-1" data-toggle="modal" data-target='#attendance_modal'>
          <i class="fas fa-user-md mr-2" aria-hidden></i>
          Log Attendance
        </button>
      </div>
    </div>

  <div class="row">

    <div class="col-md-8 pt-5">





      <section class="">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <p>Today's Payments <i class="fas fa-credit-card float-right" aria-hidden></i>  </p>
                <p class="montserrat font-weight-bold text-primary" style="font-size:16px"> GHS <?php echo number_format($pmt->todays_revenue,2); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <p>Today's Expenditure <i class="fas fa-chart-area float-right" aria-hidden></i>  </p>
                <p class="montserrat font-weight-bold text-danger" style="font-size:16px"> GHS <?php echo number_format($expenditure->ExpenditurePeriod($today,$today),2); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <p class="">Closing Balance <i class="fas fa-chart-area float-right" aria-hidden></i>  </p>
                <p class="montserrat font-weight-bold text-success" style="font-size:16px"> GHS <?php echo number_format($pmt->todays_revenue - $expenditure->ExpenditurePeriod($today,$today),2); ?></p>
              </div>
            </div>
          </div>
        </div>
      </section>




      <section class="my-5">
        <div class="card">
            <div class="card-body">
              <h6 class="montserrat font-weight-bold mb-4">Daily Visits Graph</h6>
              <canvas id="visit_graph" style="height:300px !important"></canvas>
            </div>
        </div>

      </section>


      <section class="my-5">
        <div class="card">
          <div class="card-body">
            <h6 class="montserrat font-weight-bold">Today's Visits</h6>

            <table class="table table-condensed datatable">
              <thead class="grey lighten-4">
                <tr>
                  <th>#</th>
                  <th>Patient</th>
                  <th>Physician</th>
                </tr>
              </thead>
              <tbody>

                <?php
                  $i=1;

                  $get_patients_today=mysqli_query($db,"SELECT *
                                                                                    FROM visits
                                                                                    WHERE
                                                                                      subscriber_id='".$active_subscriber."' AND
                                                                                      status='active' AND
                                                                                      visit_date='".$today."'
                                                                          ") or die(mysqli_query($db));
                  while ($patients=mysqli_fetch_array($get_patients_today)) {
                    $patient->patient_id=$patients['patient_id'];
                    $patient->PatientInfo();
                    $doctor->doctor_id=$patients['doctor_id'];
                    $doctor->DoctorInfo();
                    ?>
                    <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $patient->patient_fullname; ?></td>
                      <td><?php echo $doctor->doctor_fullname;  ?></td>
                    </tr>

                    <?php
                  }
                 ?>



            <?php
                $i=1;
                $get_todays_visit=mysqli_query($db,"SELECT * FROM visits WHERE visit_date='".$today."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
                while ($visits=mysqli_fetch_array($get_todays_visit)) {
                  ?>

                  <?php
                }
             ?>

               </tbody>
             </table>
          </div>
        </div>
      </section>



    </div>

    <div class="col-md-4 grey lighten-4 pt-5">

      <div class="card mb-5">
        <div class="card-body p-0">
          <ul class="list-group">
            <li class="list-group-item">Total Patients <span class="float-right"><?php echo $patient->total_patient_count; ?></span> </li>
            <li class="list-group-item">Total Visits <span class=" float-right"><?php echo $opd->total_visit_count; ?></span> </li>
            <li class="list-group-item">Current OPD <span class=" float-right"><?php echo $opd->active_opd_cases; ?></span></li>
            <li class="list-group-item">On Admission <span class=" float-right"><?php echo $opd->active_admissions; ?></span></li>
          </ul>
        </div>
      </div>




      <div class="card mb-5">
          <div class="card-body">

            <h6 class="montserrat font-weight-bold">Staff On Duty</h6>
            <hr class="hr">

            <?php
                $i=1;
                $shifts=$staff->ShiftManifest();
                foreach ($shifts as $rows) {
                  $staff_id=$rows['staff_id'];
                  $staff->staff_id=$staff_id;
                  $staff->StaffInfo();


                  ?>
                  <li class="list-group-item b-0 pl-0" style="border:none !important">
                    <div class="">
                        <p class="m-0"><?php echo $i++; ?>. <?php echo $staff->full_name; ?> <span class="float-right"><?php echo date('H:i:s',strtotime($rows['time_in'])); ?></span> </p>
                    </div>
                  </li>
                  <?php
                }

             ?>


          </div>
      </div>
      <!-- End Card -->

    </div>
  </div>




    </div>
    <!-- Padding Div -->











<div id="attendance_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="attendance_frm" autocomplete="off">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Attendance Log</h6>
        <hr class="hr">

        <div class="form-group">
          <label for="">Staff Identification</label>
          <select class="form-control" name="staff_id" id="staff_id">
            <?php
                $get_staff_list=mysqli_query($db,"SELECT * FROM staff WHERE subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));

                while ($staff_list=mysqli_fetch_array($get_staff_list)) {
                  ?>

                  <option value="<?php echo $staff_list['staff_id']; ?>"><?php echo $staff_list['surname'].' '.$staff_list['othernames']; ?></option>

                  <?php
                }
             ?>
          </select>
        </div>


        <div class="form-group">
          <label for="">Shift Type</label>
          <select class="form-control" name="shift_type">
            <option value="Morning Shift">Morning Shift</option>
            <option value="Afternoon Shift">Afternoon Shift</option>
            <option value="Night Shift">Night Shift</option>
            <option value="24 Hour Shift">24 Hour Shift</option>
            <option value="Call Duty">Call Duty</option>
            <option value="Surgery Duty">Surgery Duty</option>
          </select>
        </div>

        <div class="form-group">
          <label for="">Notes (Optional)</label>
          <textarea name="notes" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Log Attendance</button>
      </div>
      </form>
    </div>
  </div>
</div>





<?php require_once '../navigation/footer.php'; ?>
<script type="text/javascript">

VisitGraph();
// ExpenditureGraph()
function VisitGraph(){
      {
          $.post("../serverscripts/admin/OPD/visit_graph.php",
          function (data){
              console.log(data);
              data=$.parseJSON(data)

               var date = [];
              var visits = [];

              for (var i in data) {
                  date.push(data[i].dates);
                  visits.push(data[i].total_visits);
                  // alert(i)
              }
              // alert(sales)

              var chartdata = {
                  labels: date,
                  datasets: [
                      {
                          label: 'Daily Visits',
                          borderColor: 'rgb(0, 13, 126)',
                          pointBackgroundColor: 'rgb(0, 13, 126)',
                          backgroundColor: 'rgba(13, 71,161, 1)',
                          borderRadius: 20,
                          data: visits
                      }
                  ]
              };

              var graphTarget = $("#visit_graph");

              var barGraph = new Chart(graphTarget, {
                  type: 'bar',
                  responsive: true,
                  data: chartdata
              });
          });
      }
  }


$('.visit').on('click', function(event) {
  event.preventDefault();
  var visit_id=$(this).attr('ID')
  window.location='singlevisit.php?visit_id='+visit_id
});

$('#attendance_modal').on('shown.bs.modal', function(event) {
  event.preventDefault();
  $('#staff_id').select2({
    dropdownParent: $('#attendance_modal')
  })
});

$('#attendance_frm').on('submit', function(event) {
  event.preventDefault();
  $.ajax({
    url: '../serverscripts/admin/Users/log_shift.php',
    type: 'GET',
    data:$('#attendance_frm').serialize(),
    success:function(msg){
      if(msg=='save_successful'){
        bootbox.alert("Shift recorded successfully",function(){
          window.location.reload()
        })
      }else {
        bootbox.alert(msg)
      }
    }
  })
});

</script>
