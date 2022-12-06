<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php

    require_once '../serverscripts/Classes/Reports.php';



    // $account=new Account();
    // $patient=new Patient();
    // $p=new Patient();
    // $doctor=new Doctor();
    // $nurse=new Nurse();
    // $pharm=new Pharmacist();
    $opd=new Visit();
    // $pmt=new Payment();
    // $expenditure=new Expenditure();
    // $account=new Account();
    // $service=new Service();
    // $staff=new Staff();
    $reports=new Report();

    $today=date('Y-m-d');
    $this_month=date('m');



 ?>





<main class="pb-3 ml-lg-5 main" style="">
  <div class="container-fluid mt-0 pt-5">

    <div class="row mb-4">
      <div class="col-md-7">
          <h4 class="titles mb-4">Patients Reports</h4>
      </div>
      <div class="col-md-5 text-right">
        <button type="button" class="btn primary-color-dark m-0 br-1" data-toggle="modal" data-target='#attendance_modal'>
          <i class="fas fa-user-md mr-2" aria-hidden></i>
          Print
        </button>
      </div>
    </div>


  <div class="card mb-5">
    <div class="card-body">
      <div class="row">
        <div class="col-3">
          <div class="form-group">
            <label for="">Start Date</label>
            <input type="text" class="form-control" name="start_date" id="start_date" value="">
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="">End Date</label>
            <input type="text" class="form-control" name="start_date" id="start_date" value="">
          </div>
        </div>
      </div>
    </div>
  </div>


    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6>DIAGNOSIS REPORT</h6>
          </div>
          <div class="card-body">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Diagnosis</th>
                  <th>Count</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i=1;
                  $diagReport=$reports->DiagnosisReport();
                  foreach ($diagReport as $rows ) {
                    ?>
                    <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $opd->Diagnosis($rows['primary_diagnosis']); ?></td>
                      <td><?php echo $rows['count']; ?></td>
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
