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
							  <div class="col-md-8">
									<div class="card">
										<div class="header">
											<h4 class="title">Purchase History</h4>
											<p class="category">
												Shows all purchases made within the Month in Review
											</p>
										</div>
										<hr>
										<div class="content">
											<table class="table">
											  <thead>
											    <tr>
											      <th>#</th>
											      <th>Invoice ID</th>
											      <th>Supplier</th>
											      <th>Cost</th>
											      <th>Paid</th>
											      <th>Balance</th>
														<th>

														</th>
											    </tr>
											  </thead>
											  <tbody>
													<?php
														require_once '../serverscripts/dbcon.php';
														$select=mysqli_query($db,"SELECT * FROM stores_purchases") or die('failed');
														$i=1;
														while ($rows=mysqli_fetch_array($select)) {
															$get_supplier=mysqli_query($db,"SELECT * FROM suppliers WHERE supplier_id='".$rows['supplier_id']."'") or die('failed');
										          $get_supplier=mysqli_fetch_array($get_supplier);
										          $supplier_name=$get_supplier['supplier_name'];
															?>
															<tr>
													      <td><?php echo $i++; ?></td>
													      <td><?php echo $rows['invoice_id']; ?></td>
													      <td><?php echo $supplier_name; ?></td>
													      <td><?php echo $rows['total_cost'] ?></td>
													      <td><?php echo $rows['amount_paid'] ?></td>
													      <td><?php echo $rows['balance'] ?></td>
																<td class="text-right">
																	<i class="fa fa-credit-card action_buttons view-card" id="<?php echo $rows['purchase_id']; ?>"></i>
																	<i class="fa fa-trash-o action_buttons delete" id="<?php echo $rows['purchase_id']; ?>"></i>
																	<i class="fa fa-print hidden"></i>
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
								<div class="col-md-4">
									<div class="card">
										<div class="header">
											<h4 class="title">Sidebar</h4>
											<p class="category">
												Options
											</p>
										</div>
										<hr>
										<div class="content">
											<a href="purchase_step1.php" class="btn btn-primary wide">
												Create New Purchase
											</a>
											<br>
											<br>

											<button type="button" class="btn btn-info wide">
												View Purchases
											</button>
											<br>

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
<div id="modal_holder">

</div>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/admin_footer.php'; ?>

	<script type="text/javascript">
    	$(document).ready(function(){

				$('.table').DataTable({
					'sort':false
				})

				$('.table tbody').on('click', '.view-card', function(event) {
					event.preventDefault();

					var id=$(this).attr('ID')
					$.ajax({
						url: '../serverscripts/medicalstores/purchase_card.php?purchase_id='+id,
						type: 'GET',
						success:function(msg){

							$('#modal_holder').html(msg)
							$('#card_modal').modal('show')

						}
					})

				});


				$('.table tbody').on('click', '.delete', function(event) {
					event.preventDefault();

					if(confirm("Delete this entry?")===true){
						var purchase_id=$(this).attr('id')
						$.ajax({
							url: '../serverscripts/medicalstores/delete_purchase.php?purchase_id='+purchase_id,
							type: 'GET',
							success:function(msg){
								if(msg==='delete_successful'){
									alert('Purchase Deleted Successfully')
									window.location.reload()
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
