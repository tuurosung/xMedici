<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<style media="screen">
  body{
    background-color: #fff;
  }
</style>
<?php

  $test=new Test();


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
    										<p class="big-text montserrat"><?php echo $test->count_all_tests; ?></p>
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
    										<p class="big-text montserrat"><?php echo $test->count_pending_tests; ?></p>
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
    										<p class="big-text montserrat"><?php echo $test->count_complete_tests; ?></p>
    										Complete
    								  </div>
    								</div>
    							</div>
    						</div>
    				  </div>
    				</div>

            <div class="card" style="border-radius:10px">
              <div class="card-body py-3">

                <h5 class="font-weight-bold mb-4">Recent Orders</h5>

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
                    $get_tests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE status='active' AND subscriber_id='".$active_subscriber."' ORDER BY timestamp desc LIMIT 15 ")  or die(mysqli_error($db));
                    $i=1;
                    while ($rows=mysqli_fetch_array($get_tests)) {
                      $test=new Test();
                      $test->test_id=$rows['test_id'];
                      $test->TestInfo();

                      $patient=new Patient();
                      $patient->patient_id=$rows['patient_id'];
                      $patient->PatientInfo();

                      if($rows['patient_id']==''){
                        $request_id=$rows['request_id'];
                        $getinfo=mysqli_query($db,"SELECT * FROM lab_requests WHERE request_id='".$request_id."' AND subscriber_id='".$active_subscriber."'") or die(msyqli_error($db));
                        $info=mysqli_fetch_array($getinfo);
                        $fullname=$info['patient_name'];
                        $age=$info['age'].'years';
                        $sex=$info['sex'];
                      }else {
                        $fullname=$patient->patient_fullname;
                        $age=$patient->age;
                        $sex=$patient->sex_description;
                      }
                      ?>
                      <tr style="cursor:pointer"  class="test">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo date('d-M-Y | H:i:s',$rows['timestamp']); ?></td>
                        <td style="text-decoration:underline">
                          <a href="test_results_entry.php?test_id=<?php echo $rows['test_id']; ?>&request_id=<?php echo $rows['request_id']; ?>">
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




<div id="new_service_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="new_test_frm" autocomplete="off">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">New Lab Test</h5>
					<hr class="hr mb-3">


						<div class="row poppins">
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Category</label>
									<select class="custom-select browser-default" name="test_category">
										<?php
												$get_categories=mysqli_query($db,"SELECT * FROM lab_test_categories") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_categories)) {
													?>
													<option value="<?php echo $rows['category_id']; ?>"><?php echo $rows['description']; ?></option>
													<?php
												}
										 ?>

									</select>
						    </div>
						  </div>
						  <div class="col-md-6">

						  </div>
						</div>

						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Description</label>
									<input type="text" class="form-control" name="description" id="description" placeholder="Enter the name of the test">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Cost</label>
									<input type="text" class="form-control" name="test_cost" id="test_cost"  placeholder="Enter the cost of the test">
						    </div>
						  </div>
						</div>


						<div class="form-group">
							<label for="">Specimen</label>
							<select class="custom-select browser-default" name="specimen">
								<option value="Blood">Blood</option>
								<option value="Urine">Urine</option>
								<option value="Stool">Stool</option>
								<option value="Seminal Fluid">Seminal Fluid</option>
							</select>
						</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Create Test</button>
      </div>
			</form>
    </div>
  </div>
</div>


<div id="new_lab_request_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="request_labs_frm">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Walk-In Lab Request</h6>
        <hr class="hr">

        <div class="spacer">	</div>
        <div class="spacer">	</div>

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
          $get_tests=mysqli_query($db,"SELECT * FROM lab_tests WHERE subscriber_id='".$active_subscriber."' AND status='active' ORDER BY description") or die(mysqli_error($db));
          while ($tests=mysqli_fetch_array($get_tests)) {
            ?>

            <div class="col-6">
              <li class="list-group-item">
                <div class="form-check">
                    <input	class="form-check-input tests"	type="checkbox" name="test_id[]" 	value="<?php echo $tests['test_id']; ?>" id="<?php echo $tests['test_id']; ?>"/>
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

<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#lab_nav').addClass('active')
		$('#lab_submenu').addClass('show')
		$('#queued_tests_li').addClass('font-weight-bold')


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
