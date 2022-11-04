<?php require_once '../navigation/header_lite.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<style media="screen">
  body{
    background-color: #fff;
  }
</style>
<?php
  require_once '../serverscripts/Classes/Radiology.php';
  require_once '../serverscripts/Classes/Patient.php';
  require_once '../serverscripts/Classes/Services.php';

  $radio=new Radiology();
  $patient=new Patient();
  $service=new Service();


 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:; min-height:100vh">
			<div class="container-fluid mt-2">

        <div class="row mb-4">
          <div class="col-md-7">
              <h4 class="titles mb-4">Radiology Requests</h4>
          </div>
          <div class="col-md-5 text-right">
            <!-- <button type="button" class="btn primary-color-dark m-0 br-1" data-toggle="modal" data-target='#attendance_modal'>
              <i class="fas fa-user-md mr-2" aria-hidden></i>
              Log Attendance
            </button> -->
          </div>
        </div>

        <div class="card" style="border-radius:10px; min-height:700px">
          <div class="card-body py-3">

            <h5 class="font-weight-bold mb-4">Radiology Requests</h5>

            <table class="table datatables table-condensed">
              <thead class="grey lighten-4">
                <tr>
                  <th>#</th>
                  <th>Time</th>
                  <th>Reference</th>
                  <th>Patient Name</th>
                  <th>Age</th>
                  <th class="text-center">Sex</th>
                  <th>Request Type</th>
                  <th>Doctor</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php

                // $get_tests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE status='active' AND subscriber_id='".$active_subscriber."' ORDER BY timestamp desc LIMIT 15 ")  or die(mysqli_error($db));
                $result=$radio->All();
                $i=1;
                foreach ($result as $rows) {

                  $patient->patient_id=$rows['patient_id'];
                  $patient->PatientInfo();

                  if($rows['patient_type']=='walkin_patient'){
                    $request_id=$rows['request_id'];
                    $fullname=$rows['surname'] .' '.$rows['othernames'];
                    $age=$rows['age'];
                    $sex=$rows['sex'];
                  }else {
                    $fullname=$patient->patient_fullname;
                    $age=$patient->age;
                    $sex=$patient->sex_description;
                  }

                  $service->service_id=$rows['service_id'];
                  $service->ServiceInfo();
                  ?>
                  <tr style="cursor:pointer"  class="test">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $rows['timestamp']; ?></td>
                    <td style="text-decoration:underline">
                      <a href="radio_resultsentry.php?request_id=<?php echo $rows['request_id']; ?>">
                      <?php echo $rows['request_id']; ?>
                    </a>
                    </td>
                    <td><?php echo $fullname; ?></td>
                    <td><?php echo $age; ?></td>
                    <td class="text-center"><?php echo $sex; ?></td>
                    <td><?php echo $service ->description; ?></td>
                    <td><?php echo $rows['doctor']; ?></td>
                    <td><?php echo $rows['request_status']; ?></td>
                    <td class="text-right">
                      <div class="dropdown open">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Options
                        </button>
                        <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
                          <ul class="list-group">
                            <li class="list-group-item edit"  data-request_id="<?php echo $rows['request_id']; ?>"><i class="fas fa-pencil-alt mr-3" aria-hidden></i>Edit</li>
                            <li class="list-group-item delete" data-request_id="<?php echo $rows['request_id']; ?>"><i class="far fa-trash-alt mr-3" aria-hidden></i> Delete</li>
                          </ul>
                        </div>
                      </div>
                    </td>

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
		</main>





<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#radio_nav').addClass('active')
		$('#radio_submenu').addClass('show')
		$('#radiology_requests_li').addClass('font-weight-bold')




		$('.edit_test_btn').on('click', function(event) {
			event.preventDefault();
			var test_id=$(this).attr('ID')
			$.get('../serverscripts/admin/Laboratory/edit_test_modal.php?test_id='+test_id,function(msg){
				$("#modal_holder").html(msg)
				$('#edit_test_modal').modal('show')

				$('#edit_test_frm').on('submit', function(event) {
					event.preventDefault();
					bootbox.confirm('Update test info?',function(r){
						if(r===true){
							$.ajax({
								url: '../serverscripts/admin/Laboratory/edit_test_frm.php',
								type: 'GET',
								data:$('#edit_test_frm').serialize(),
								success:function(msg){
									if(msg==='update_successful'){
										bootbox.alert("Test info updated successfully",function(){
											window.location.reload()
										})
									}else {
										bootbox.alert(msg)
									}
								}
							})
						}
					})
				});
			})
		});


		$('.table tbody').on('click', '.delete', function(event) {
			event.preventDefault();
			var request_id=$(this).data('request_id')
			bootbox.confirm("Permanently delete this request?",function(r){
				if(r===true){
					$.get('../serverscripts/admin/OPD/Radiology/delete.php?request_id='+request_id,function(msg){
						if(msg==='delete_successful'){
							bootbox.alert('Request deleted successfully',function(){
								window.location.reload()
							})
						}else {
							bootbox.alert(msg)
						}
					})
				}
			})
		});



    $('.table tbody').on('click','.print_results', function(event) {
      event.preventDefault();
       var request_id=$(this).data('request_id')
       var test_id=$(this).data('test_id')
       window.open('print_labresults.php?test_id='+test_id+'&request_id='+request_id,"_blank","width=1200")
    });//end print function





	</script>

</html>
