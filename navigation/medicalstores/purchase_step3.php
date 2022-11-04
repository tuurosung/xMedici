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
											<h4 class="title">Purchase Step 3</h4>
											<p class="category">
												Payment Details
											</p>
										</div>
										<hr>
										<div class="content">
											<?php
												require_once '../serverscripts/dbcon.php';
												session_start();
												$purchase_id=$_SESSION['active_purchase_id'];

												$select_string=mysqli_query($db,"SELECT * FROM stores_purchases WHERE purchase_id='".$purchase_id."'") or die('failed');
												$purchase_details=mysqli_fetch_array($select_string);

												$number_of_items=mysqli_query($db,"SELECT COUNT(*) AS number_of_items FROM stores_purchaseditems  WHERE purchase_id='".$purchase_id."'") or die('failed');
												$number_of_items=mysqli_fetch_assoc($number_of_items);

												$get_sum=mysqli_query($db,"SELECT SUM(cost) as cost FROM stores_purchaseditems WHERE purchase_id='".$purchase_id."'") or die('failed');
												$get_sum=mysqli_fetch_assoc($get_sum);
											?>

											<form id="purchase_step3_frm">

											<div class="row">
											  <div class="col-md-6">
													<label for="">Purchase Id</label>
													<input type="text" name="purchase_id" id="purchase_id" placeholder="Purchase ID" class="form-control" value="<?php echo $purchase_details['purchase_id']; ?>" readonly required>
											  </div>
											</div>
											<br>
											<div class="row">
											  <div class="col-md-6">
													<label for="">Number of Items</label>
													<input type="text" name="number_of_items" class="form-control" value="<?php echo $number_of_items['number_of_items']; ?>" readonly>
											  </div>
												<div class="col-md-6">
													<label for="">Purchase Cost</label>
													<input type="text" name="cost" id="cost" class="form-control" value="<?php echo $get_sum['cost']; ?>" required readonly>
												</div>
											</div>
											<br>
											<div class="row">
											  <div class="col-md-6">
													<label for="">Amount Paid</label>
													<input type="text" name="amount_paid" id="amount_paid" class="form-control" required >
											  </div>
												<div class="col-md-6">
													<label for="">Balance</label>
													<input type="text" name="balance" id="balance" class="form-control" required readonly>
												</div>
											</div>
											<br>


											<div class="row" style="margin-bottom:50px">
											  <div class="col-md-6">
													<button type="button" class="btn btn-danger wide"><i class="fa fa-trash-o"></i> Cancel</button>
											  </div>
											  <div class="col-md-6">
													<button type="submit" class="btn btn-primary wide">Complete <i class="fa fa-check"></i></button>
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

						// invoice_id_gen();
						//
						// function invoice_id_gen(){
						// 	var text = "";
	          //   var possible = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	          //   for( var i=0; i < 15; i++ )
	          //       text += possible.charAt(Math.floor(Math.random() * possible.length));
						//
	          //   $('#purchase_id').val(text)
						// }

						$('#amount_paid').on('keyup', function(event) {
							event.preventDefault();

							var cost=$('#cost').val()
							var amount_paid=$(this).val()
							var balance=(parseFloat(cost)-parseFloat(amount_paid)).toFixed(2)
							$('#balance').val(balance)
						});



						$('#purchase_step3_frm').on('submit', function(event) {
							event.preventDefault();

							var con=confirm('Do you want to complete purchase?')

							if(con===true){
								$.ajax({
									url: '../serverscripts/medicalstores/purchase_step3_frm.php',
									type: 'GET',
									data:$(this).serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											alert('Purchase Complete!');
											window.location='purchases.php';
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
