<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php

    switch ($access_level) {
      case 'doctor':
        echo "<script>window.location='doctorsdashboard.php'</script>";
        break;
      case 'pharmacist':
        echo "<script>window.location='prescriptions.php'</script>";
        break;
      case 'nurse':
        echo "<script>window.location='nursesdashboard.php'</script>";
        break;

      default:
        // code...
        break;
    }


    if($_SESSION['access_level']=='administrator'){

    }elseif ($_SESSION['access_level']=='doctor') {
      header("location: doctorsdashboard.php");
    }elseif ($_SESSION['access_level']=='nurse') {
      header("location: nursesdashboard.php");
    }elseif ($_SESSION['access_level']=='pharmacist') {
      header("location: prescriptions.php");
    }elseif ($_SESSION['access_level']=='labtist') {
      header("location: laboratorydashboard.php");
    }elseif ($_SESSION['access_level']=='accountant') {
      header("location: accountantdashboard.php");
    }elseif ($_SESSION['access_level']=='administrator_hr') {
      header("location: hrdashboard.php");
    }


    // $account=new Account();
    $patient=new Patient();
    // $p=new Patient();
    $admission=new Admission();
    $opd=new Visit();
    
    $pmt=new Payment();
    $expenditure=new Expenditure();
    // $account=new Account();
    $service=new Service();
    // $staff=new Staff();

    $today=date('Y-m-d');
    $this_month=date('m');



 ?>





<main class="pb-3 ml-lg-5 main">
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



    <div class="row mb-5">
      <div class="col-md-8">

        <section>
          <!-- Top Summaries -->
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-8">
                      <p class="montserrat font-weight-bold text-primary" style="font-size:16px"> GHS <?php echo number_format($pmt->todays_revenue,2); ?></p>
                      <p>Today's Payments</p>
                    </div>
                    <div class="col-4">
                      <div class="icon-box d-flex justify-content-center align-items-center primary-color-dark">
                        <i class="fas fa-credit-card float-right" aria-hidden></i>
                      </div>
                    </div>
                  </div>


                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-8">
                      <p class="montserrat font-weight-bold text-danger" style="font-size:16px"> GHS <?php echo number_format($expenditure->ExpenditurePeriod($today,$today),2); ?></p>
                      <p>Today's Expenditure   </p>
                    </div>
                    <div class="col-4">
                      <div class="icon-box d-flex justify-content-center align-items-center primary-color-dark">
                        <i class="fas fa-chart-area float-right" aria-hidden></i>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-8">
                      <p class="montserrat font-weight-bold text-success" style="font-size:16px"> GHS <?php echo number_format($pmt->todays_revenue - $expenditure->ExpenditurePeriod($today,$today),2); ?></p>
                      <p class="">Closing Balance </p>
                    </div>
                    <div class="col-4">
                      <div class="icon-box d-flex justify-content-center align-items-center primary-color-dark">
                        <i class="fas fa-book float-right" aria-hidden></i>
                      </div>
                    </div>
                  </div>


                </div>
              </div>
            </div>
          </div>
          <!-- top summaries end here -->
        </section>


        <section class="my-5">
          <!-- Financial summary graph -->
            <div class="card">
                <div class="card-body">
                  <h6 class="montserrat font-weight-bold mb-4">Financial Performance Summary</h6>
                  <canvas id="payments_graph" width="300" style="height:300px !important"></canvas>
                </div>
            </div>
            <!-- Graph ends here -->
        </section>

        <section class="my-5">
          <div class="card">
            <div class="card-body">
              <h6 class="montserrat font-weight-bold">Income Breakdown</h6>

              <?php
                $get_service_request_breakdown=mysqli_query($db,"SELECT service_id,sum(total) as total_income FROM service_requests WHERE date BETWEEN '".$month_start."' AND '".$month_end."' AND subscriber_id='".$active_subscriber."' AND status='active' GROUP BY service_id") or die(mysqli_error($db));
                while ($rows=mysqli_fetch_array($get_service_request_breakdown)) {
                  $service->service_id=$rows['service_id'];
                  $service->ServiceInfo();
                  ?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-6"><?php echo $service->description; ?></div>
                      <div class="col-6 text-right"><?php echo number_format($rows['total_income'],2); ?></div>
                    </div>
                  </li>
                  <?php
                }
               ?>



              <?php
                $get_payments=mysqli_query($db,"SELECT income_account,SUM(amount_paid) as total_income FROM payments WHERE date BETWEEN '".$month_start."' AND '".$month_end."' AND subscriber_id='".$active_subscriber."' AND status='active' GROUP BY income_account") or die(mysqli_error($db));
                while ($rows=mysqli_fetch_array($get_payments)) {
                  $account->account_number=$rows['income_account'];
                  $account->AccountInfo();
                  ?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-6"><?php echo $account->account_name; ?></div>
                      <div class="col-6 text-right"><?php echo number_format($rows['total_income'],2); ?></div>
                    </div>
                  </li>
                  <?php
                }
               ?>

            </div>
          </div>
        </section>

        <section class="my-5">
          <div class="card">
            <div class="card-body">
              <h6 class="montserrat font-weight-bold">Today's Visits</h6>


                  <?php
                    $i=1;

                    $get_patients_today=mysqli_query($db,"SELECT *
                                                                                      FROM visits
                                                                                      WHERE
                                                                                        subscriber_id='".$active_subscriber."' AND
                                                                                        status='active' AND
                                                                                        subscriber_id='".$active_subscriber."' AND
                                                                                        visit_date='".$today."'
                                                                            ") or die(mysqli_error($db));
                    while ($patients=mysqli_fetch_array($get_patients_today)) {
                      $patient->patient_id=$patients['patient_id'];
                      $patient->PatientInfo();
                      $doctor->doctor_id=$patients['doctor_id'];
                      $doctor->DoctorInfo();
                      ?>
                      <li class="list-group-item" style="border-left:0px !important; border-right:0px !important; ">
                        <div class="row">
                          <div class="col-1">
                            <?php echo $i++; ?>
                          </div>
                          <div class="col-6">
                            <?php echo ucfirst(mb_strtolower($patient->patient_fullname)); ?>
                          </div>
                          <div class="col-5">
                            <?php echo $doctor->doctor_fullname;  ?>
                          </div>
                        </div>
                      </li>

                      <?php
                    }
                   ?>

            </div>
          </div>
        </section>




      </div>
      <div class="col-4">

        <div class="card mb-5">
          <div class="card-body p-0">
            <ul class="list-group">
              <li class="list-group-item">Total Patients <span class="float-right"><?php echo $patient->total_patient_count; ?></span> </li>
              <li class="list-group-item">Total Visits <span class=" float-right"><?php echo $opd->total_visit_count; ?></span> </li>
              <li class="list-group-item">Current OPD <span class=" float-right"><?php echo $opd->active_opd_cases; ?></span></li>
              <a href="inpatients.php" class="list-group-item">On Admission <span class=" float-right"><?php echo $opd->active_admissions; ?></span></a>
            </ul>
          </div>
        </div>

        <!-- Payment summary card -->
        <div class="card mb-3">
          <div class="card-body p-0">
            <h6 class="p-3 montserrat font-weight-bold">Payment Summary</h6>
            <?php
                $get_payments=mysqli_query($db,"SELECT payment_account, SUM(amount_paid) as total FROM payments WHERE subscriber_id='".$active_subscriber."' AND status='active' AND date='".$today."' GROUP BY payment_account") or die(mysqli_error($db));
                while ($rows=mysqli_fetch_array($get_payments)) {
                  $account->account_number=$rows['payment_account'];
                  $account->AccountInfo();
                  ?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-md-6"><?php echo $account->account_name; ?></div>
                      <div class="col-md-6 text-right"><?php echo$rows['total']; ?></div>
                    </div>
                  </li>

                  <?php
                }
             ?>
             <li class="list-group-item grey lighten-3">
               <div class="row">
                 <div class="col-md-6">Total</div>
                 <div class="col-md-6 text-right font-weight-bold">GHS <?php echo number_format($pmt->todays_revenue,2); ?></div>
               </div>
             </li>
          </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
              <p class="big-text" style="font-family:'Roboto'"><span class="montserrat">GHS</span> <?php echo number_format($pmt->monthly_revenue,2); ?></p>
              <span style="font-size:10px">Payments Received this month</span>
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
                $get_staff_list=mysqli_query($db,"SELECT * FROM staff WHERE status='active' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

                while ($staff_list=mysqli_fetch_array($get_staff_list)) {
                  ?>

                  <option value="<?php echo $staff_list['staff_id']; ?>"><?php echo $staff_list['surname'].' '.$staff_list['othernames']; ?> </option>

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

PaymentGraph();
// ExpenditureGraph()
function PaymentGraph(){
      {
          $.post("../serverscripts/admin/Billing/payment_graph.php",
          function (data){
              console.log(data);
              data=$.parseJSON(data)

               var date = [];
              var payment = [];

              for (var i in data) {
                  date.push(data[i].dates);
                  payment.push(data[i].total_payments);
                  // alert(i)
              }
              // alert(sales)

              var chartdata = {
                  labels: date,
                  datasets: [
                      {
                          label: 'Daily Payments',
                          borderColor: 'rgb(0, 13, 126)',
                          pointBackgroundColor: 'rgb(0, 13, 126)',
                          backgroundColor: 'rgba(13, 71,161, 1)',
                          borderRadius: 20,
                          data: payment
                      }
                  ]
              };

              var graphTarget = $("#payments_graph");

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
