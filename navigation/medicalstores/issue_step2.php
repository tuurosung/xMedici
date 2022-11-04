<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<?php require_once '../navigation/admin_header.php' ?>

</head>
<body>

<div class="wrapper">

    <?php require_once '../navigation/stores_nav.php'; ?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div>

                <div class="collapse navbar-collapse">


                    <ul class="nav navbar-nav navbar-right">


                        <li class="logout">
                            <a href="#">
															<i class="fa fa-lock"></i>
                                Log out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">

							<div class="row">
							  <div class="col-md-12">
									<div class="card">
										<div class="header">
											<h4 class="title">Drug Issue Step 2</h4>
											<p class="category">
												Basic Details
											</p>
										</div>
										<hr>
										<div class="content">
											<div class="row">
											  <div class="col-md-4 table-responsive">
													<h5 class="custom_header">Item Catalogue</h5>
													<hr>
													<table class="table table-responsive">
													  <thead>
													    <tr>
													      <th>#</th>
													      <th>Item Name</th>
													      <th></th>
													    </tr>
													  </thead>
													  <tbody>
															<?php
															 require_once '../serverscripts/dbcon.php';
															 $get_items=mysqli_query($db,"SELECT * FROM inventory") or die('failed');
															 $i=1;
															 while ($rows=mysqli_fetch_array($get_items)) {
															 	?>
																	<tr style="font-size:11px">
															      <td><?php echo $i++; ?></td>
															      <td><?php echo $rows['drug_name']; ?></td>
																		<td class="text-right">
																			<button type="button" class="btn btn-sm" id="<?php echo $rows['drug_id']; ?>" name="<?php echo $rows['drug_name']; ?>">
																				Add
																			</button>
																		</td>
															    </tr>
																<?php
															 }
															?>

													  </tbody>
													</table>
											  </div>

												<div class="col-md-8">
													<h5>Counter</h5>
													<hr>
													<form id="counter_frm">

													<div class="row">
													  <div class="col-md-6">
															<input type="text" name="drug_id" id="drug_id" class="form-control" placeholder="Drug ID" required readonly>
													  </div>
													  <div class="col-md-6">

													  </div>
													</div>
													<br>
													<div class="row">
													  <div class="col-md-6">
															<input type="text" name="drug_name" id="drug_name" class="form-control" placeholder="Drug Name" required readonly>
													  </div>
													  <div class="col-md-6">
															<input type="text" name="qty" id="qty" class="form-control" placeholder="Qty Issued" required >
													  </div>
													</div>
													<br>
													<div class="row" style="margin-bottom:30px">

													  <div class="col-md-12">
															<button type="submit" class="btn custom_button_blue wide">
																Add to List
															</button>
													  </div>
													</div>

													<div id="listholder">

													</div>
													<div class="col-md-12">
														<button id="proceed"   type="button" class="btn custom_button_purple wide">
															Proceed to step 3
															<i class="fa fa-chevron-right"></i>
														</button>
													</div>

												</form>
												</div>

											</div>
										</div>
									</div>
							  </div>

							</div>


            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">

                <p class="copyright pull-right">
                    &copy; 2016 <a href="#">Powered by Kindred GH. Technologies
                </p>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/admin_footer.php'; ?>

	<script type="text/javascript">
    	$(document).ready(function(){

						invoice_id_gen();

						function invoice_id_gen(){
							var text = "";
	            var possible = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	            for( var i=0; i < 15; i++ )
	                text += possible.charAt(Math.floor(Math.random() * possible.length));

	            $('#purchase_id').val(text)
						}

						// $('#purchase_step1_frm').on('submit', function(event) {
						// 	event.preventDefault();
						//
						// 	var con=confirm('Do you want to proceed to step 2?')
						//
						// 	if(con===true){
						// 		$.ajax({
						// 			url: '../serverscripts/medicalstores/purchase_step1_frm.php',
						// 			type: 'GET',
						// 			success: function(msg){
						// 				if(msg==='save_successful'){
						// 					window.location='purchase_step2.php';
						// 				}
						// 				else {
						// 					alert(msg)
						// 				}
						// 			}
						// 		})
						//
						//
						// 	}
						// });

						$('.table').DataTable({
							'sort':false
						})

						$('.table tbody').on('click', '.btn-sm', function(event) {
							event.preventDefault();

							var drug_id=$(this).attr('ID')
							var drug_name=$(this).attr('name');

							$('#drug_id').val(drug_id)
							$('#drug_name').val(drug_name)
							$('#qty').focus()

							$('#qty').on('keyup', function(event) {
								var supply_price=$('#supply_price').val()
								var qty=$(this).val()
								var cost=(parseFloat(supply_price) * parseFloat(qty)).toFixed(2)
								$('#cost').val(cost)
							});
						});

						$('#counter_frm').on('submit', function(event) {
							event.preventDefault();
							var con=confirm('Add to list?')

							if(con===true){
								$.ajax({
									url: '../serverscripts/medicalstores/issue_addtolist.php',
									type: 'GET',
									data:$(this).serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											alert('Added to list')
											$('#tables_search').focus()
											issued_items()
											$('#counter_frm')[0].reset()
										}
										else {
											alert('failed');
										}
									}
								})

							}
						});



						issued_items()
						function issued_items(){
							$.ajax({
								url: '../serverscripts/medicalstores/issueditems_list_tmp.php',
								type: 'GET',
								success: function(msg){
									$('#listholder').html(msg)
								}
							})

						}

						$('#proceed').on('click', function(event) {
							event.preventDefault();
							var con=confirm('Proceed to step 3?')

							if(con===true){
								window.location='issue_step3.php'
							}
						});

    	});
	</script>

</html>
