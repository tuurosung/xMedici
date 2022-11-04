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
							  <div class="col-md-8 col-md-offset-2">
									<div class="card">
										<div class="header">
											<h4 class="title">Purchase Step 1</h4>
											<p class="category">
												Basic Details
											</p>
										</div>
										<hr>
										<div class="content">
											<form id="purchase_step1_frm">

											<div class="row">
											  <div class="col-md-6">
													<input type="text" name="purchase_id" id="purchase_id" placeholder="Purchase ID" class="form-control" readonly required>
											  </div>
											</div>
											<br>
											<div class="row">
											  <div class="col-md-6">
													<select class="form-control" name="supplier_id" id="supplier_id"  required>
														<option value="">Select Supplier</option>


											    <?php
														require_once '../serverscripts/dbcon.php';
														$get_suppliers=mysqli_query($db,"SELECT * FROM suppliers") or die('failed');
														while ($rows=mysqli_fetch_array($get_suppliers)) {
															?>
															<option value="<?php echo $rows['supplier_id']; ?>"> <?php echo $rows['supplier_name']; ?></option>
															<?php
														}

													 ?>
													 </select>
											  </div>
												<div class="col-md-6">
													<input type="text" name="invoice_id" class="form-control" placeholder="Invoice ID" required>
												</div>
											</div>
											<br>
											<div class="row" style="margin-bottom:50px">
											  <div class="col-md-6">
													<button type="button" class="btn btn-danger wide"><i class="fa fa-trash-o"></i> Cancel</button>
											  </div>
											  <div class="col-md-6">
													<button type="submit" class="btn btn-primary wide">Proceed Next <i class="fa fa-chevron-right"></i></button>
											  </div>
											</div>

											</form>
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

						$('#purchase_step1_frm').on('submit', function(event) {
							event.preventDefault();

							var con=confirm('Do you want to proceed to step 2?')

							if(con===true){
								$.ajax({
									url: '../serverscripts/medicalstores/purchase_step1_frm.php',
									type: 'GET',
									data:$(this).serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											window.location='purchase_step2.php';
										}
										else {
											alert(msg)
										}
									}
								})


							}
						});

    	});
	</script>

</html>
