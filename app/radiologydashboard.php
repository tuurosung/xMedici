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

        <div class="row mb-5">
          <div class="col-8">

          </div>
          <div class="col-4 d-flex flex-row-reverse">

            <div class="mr-2">
              <p><i class="far fa-clock-alt mr-2" aria-hidden></i> <?php echo date('H:i:s'); ?></p>
            </div>
            <div class="mr-2">
              <p><i class="far fa-calendar-alt mr-2" aria-hidden></i> Today, <?php echo date('d M'); ?></p>
            </div>



          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="card" style="border-radius:10px">
              <div class="card-body py-4">
                <div class="row">
                  <div class="col-9">
                    <h3 class="montserrat">Welcome, <span class="font-weight-bold">Dr. Modesta</span> </h3>
                    <p>Here's a summary of all that's happening in your Lab</p>
                  </div>
                  <div class="col-3">
                    <button type="button" class="btn btn-primary wide">
                      <i class="fas fa-plus mr-2" aria-hidden></i> New Order
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="row my-5">
    				  <div class="col-md-4">
    						<div class="card" style="border-radius:10px">
    							<div class="card-body">
    								<div class="row">
    								  <div class="col-3">
    										<div class="icon-box primary-color d-flex justify-content-center align-items-center">
    											<i class="fas fa-archive" aria-hidden></i>
    										</div>
    								  </div>
    								  <div class="col-9">
    										<p class="big-text montserrat"><?php //echo $test->count_all_tests; ?></p>
    									  Tests
    								  </div>
    								</div>
    							</div>
    						</div>
    				  </div>
    				  <div class="col-md-4">
    						<div class="card" style="border-radius:10px">
    							<div class="card-body">
    								<div class="row">
    								  <div class="col-3">
    										<div class="icon-box primary-color d-flex justify-content-center align-items-center">
    											<i class="far fa-clock" aria-hidden></i>
    										</div>
    								  </div>
    								  <div class="col-9">
    										<p class="big-text montserrat"><?php //echo $test->count_pending_tests; ?></p>
    										Pending
    								  </div>
    								</div>
    							</div>
    						</div>
    				  </div>
    				  <div class="col-md-4">
    						<div class="card" style="border-radius:10px">
    							<div class="card-body">
    								<div class="row">
    								  <div class="col-3">
    										<div class="icon-box primary-color d-flex justify-content-center align-items-center">
    											<i class="fas fa-check" aria-hidden></i>
    										</div>
    								  </div>
    								  <div class="col-9">
    										<p class="big-text montserrat"><?php// echo $test->count_complete_tests; ?></p>
    										Complete
    								  </div>
    								</div>
    							</div>
    						</div>
    				  </div>
    				</div>

            <div class="card" style="border-radius:10px">
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
                      <th>Test Requested</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // $s=new Service();
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
                        <td><?php echo $test->description; ?></td>
                        <td class="<?php if($rows['results_status']=='complete'){ echo 'text-primary'; } ?>"><?php echo $rows['results_status']; ?> </td>


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
          <div class="col-4">
            <div class="card" style="height:90vh">
              <div class="card-body">

              </div>
            </div>
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
		$('#radiologydashboard_li').addClass('font-weight-bold')


		$('#new_test_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Create new test?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Laboratory/new_test_frm.php',
						type: 'GET',
						data: $('#new_test_frm').serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Test Created Successfully",function(){
									window.location.reload()
								})
							}
							else {
								bootbox.alert(msg,function(){
									window.location.reload()
								})
							}
						}
					})//end ajax
				}
			})
		});//end submit


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


		$('.delete_test_btn').on('click', function(event) {
			event.preventDefault();
			var test_id=$(this).attr('ID')
			bootbox.confirm("Permanently delete this test?",function(r){
				if(r===true){
					$.get('../serverscripts/admin/Laboratory/delete_test.php?test_id='+test_id,function(msg){
						if(msg==='delete_successful'){
							bootbox.alert('Test deleted successfully',function(){
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


    $('.table tbody').on('click','.delete', function(event) {
      event.preventDefault();

      var drug_id=$(this).attr('ID')
      bootbox.confirm("Are you sure you want to delete this item?",function(r){
        if(r===true){
          $.ajax({
            url: '../serverscripts/admin/inventory_delete.php?drug_id='+drug_id,
            type: 'GET',
            success: function(msg){
              if(msg==='delete_successful'){
                bootbox.alert("Drug deleted successfully",function(){
                  window.location.reload()
                })
              }
              else {
                bootbox.alert(msg)
              }
            }
          })
        }
      })
    });//end delete




	</script>

</html>
