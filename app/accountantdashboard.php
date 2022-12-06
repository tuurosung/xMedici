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
    $billing=new Billing();
    $today=date('Y-m-d');

 ?>





<main class="pb-3 ml-lg-5 main" style="">
  <div class="container-fluid pt-4">

      <h4 class="titles mb-4">Dashboard</h4>

      <div class="row mb-5">
        <div class="col-6">
          <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <p>Today's Payments</p>
                    <div class="row">
                      <div class="col-9"><p class="montserrat font-weight-bold" style="font-weight:700; font-size:1.8em">GHS <?php echo number_format($pmt->todays_revenue,2); ?></p></div>
                      <!-- <div class="col-3 text-right"><img src="../images/outpatient.svg" alt="" style="height:30px"></div> -->
                    </div>

                    <!-- <div class="progress mt-2" style="height: 10px;">
                      <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> -->
                  </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <p>Today's Expenditure</p>
                    <div class="row">
                      <div class="col-9"><p class="montserrat font-weight-bold" style="font-weight:700; font-size:1.8em">GHS <?php echo number_format($expenditure->ExpenditurePeriod($today,$today),2); ?></p></div>
                      <!-- <div class="col-3 text-right"><img src="../images/outpatient.svg" alt="" style="height:30px"></div> -->
                    </div>

                    <!-- <div class="progress mt-2" style="height: 10px;">
                      <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> -->
                  </div>
                </div>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <p>Closing Balance</p>
                    <div class="row">
                      <div class="col-9"><p class="montserrat font-weight-bold" style="font-weight:700; font-size:1.8em">GHS <?php echo number_format($pmt->todays_revenue - $expenditure->ExpenditurePeriod($today,$today),2); ?></p></div>
                      <!-- <div class="col-3 text-right"><img src="../images/outpatient.svg" alt="" style="height:30px"></div> -->
                    </div>

                    <!-- <div class="progress mt-2" style="height: 10px;">
                      <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> -->
                  </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <p>Overall Expenditure</p>
                    <div class="row">
                      <div class="col-9"><p class="montserrat font-weight-bold" style="font-weight:700; font-size:1.8em">GHS <?php echo number_format($expenditure->ExpenditurePeriod($today,$today),2); ?></p></div>
                      <!-- <div class="col-3 text-right"><img src="../images/outpatient.svg" alt="" style="height:30px"></div> -->
                    </div>

                    <!-- <div class="progress mt-2" style="height: 10px;">
                      <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> -->
                  </div>
                </div>
            </div>
          </div>


        </div>
        <div class="col-6">
          <div class="card">
            <div class="card-body" style="min-height:212px">
              <h6 class="montserrat mb-5" style="font-weight:600">Revenue Graph</h6>

            </div>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body" style="min-height:400px">
              <h6 class="montserrat font-weight-bold mb-5">Recent Payments</h6>

              <table class="table table-condensed datatable">
                <thead class="grey lighten-3 font-weight-bold">
                  <tr>
                    <th>#</th>
                    <th>Bill#</th>
                    <th>Name</th>
                    <th>Narration</th>
                    <th class="text-right">Amount Paid</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                    $i=1;
                    $get_payments=mysqli_query($db,"SELECT *
                                                                            FROM payments
                                                                            WHERE
                                                                              subscriber_id='".$active_subscriber."'  AND
                                                                              status='active' AND
                                                                              date='".$today."'
                                                                            ORDER BY sn DESC
                                                                          LIMIT 5
                                                                ") or die(mysqli_error($db));

                    while ($rows=mysqli_fetch_array($get_payments)) {
                      $p->patient_id=$rows['patient_id'];
                      $p->PatientInfo();
                      $othernames=$p->othernames;


                      $billing->bill_id=$rows['bill_id'];
                      $billing->BillInfo();

                      // if($billing->payment_status=='PAID'){
                      // 	continue;
                      // }
                      ?>
                      <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $rows['bill_id']; ?></td>
                        <td class="text-capitalize"><?php echo ucfirst(mb_strtolower($p->patient_fullname)); ?></td>
                        <td><?php echo $billing->narration; ?></td>
                        <td class="text-right"><?php echo $rows['amount_paid']; ?></td>

                      </tr>

                      <?php
                      $amount_paid+=$rows['amount_paid'];
                      $balance+=$rows['balance'];
                    }
                   ?>



              </tbody>
            </table>


            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body" style="min-height:400px">
              <h6 class="montserrat font-weight-bold mb-5">Pending Bills</h6>

              <table class="table table-condensed datatables">
  							<thead class="grey lighten-3 font-weight-bold">
  								<tr>
  									<th>#</th>
  									<th>Date</th>
  									<th>Bill #</th>
  									<th>Patient ID</th>
  									<th>Name</th>
  									<th>Narration</th>
  									<th class="text-right">Bill Amount</th>
  									<th class="text-right">Paid</th>
  									<th class="text-right">Balance</th>
  									<th></th>
  								</tr>
  							</thead>
  							<tbody>


            </div>
          </div>
        </div>
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
                $get_staff_list=mysqli_query($db,"
                  SELECT doctor_id as staff_id, surname as surname ,othernames as othernames FROM doctors  WHERE subscriber_id='".$active_subscriber."' AND status='active'

                  UNION

                  SELECT nurse_id as staff_id, surname as surname, othernames as othernames FROM nurses  WHERE subscriber_id='".$active_subscriber."' AND status='active'

                  UNION

                  SELECT pharm_id as staff_id, surname as surname, othernames as othernames FROM pharmacists WHERE subscriber_id='".$active_subscriber."' AND status='active'

                  UNION

                  SELECT labtist_id as staff_id, surname as surname, othernames as othernames FROM labtists WHERE subscriber_id='".$active_subscriber."' AND status='active'

                  UNION

                  SELECT accountant_id as staff_id, surname as surname, othernames as othernames FROM accountants WHERE subscriber_id='".$active_subscriber."' AND status='active'
                ") or die(mysqli_error($db));

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
