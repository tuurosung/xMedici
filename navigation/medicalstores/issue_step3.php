<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<?php require_once '../navigation/admin_header.php' ?>

	<style media="screen">
	  .card-table > tbody > tr > td{
	    font-family: tillana;
	  }
	  .card-table  thead {
	    font-family: tillana;
	    background-color: #fff;
	    color: #000;
	  }
	  .card-table >thead > tr >th{
	    color:#000;
	  }
	</style>
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
										<div class="header " style="">
											<h4 class="title " style="text-align:center; font-family:tillana">DARTAH PHARMACY LIMITED</h4>
											<br>
											<p class="category text-center" style="font-weight:bold">
												INVENTORY CONTROL CARD
											</p>
										</div>
										<hr>
										<div class="content">
											<?php
												require_once '../serverscripts/dbcon.php';
												session_start();
												$issue_id=$_SESSION['active_issue_id'];

												$select_string=mysqli_query($db,"SELECT * FROM stores_issueditems WHERE issue_id='".$issue_id."'") or die('failed');
												$select_details=mysqli_query($db,"SELECT * FROM stores_issues WHERE issue_id='".$issue_id."'") or die('failed');
												$issue_details=mysqli_fetch_array($select_details);
												//
												// $number_of_items=mysqli_query($db,"SELECT COUNT(*) AS number_of_items FROM stores_purchaseditems  WHERE purchase_id='".$purchase_id."'") or die('failed');
												// $number_of_items=mysqli_fetch_assoc($number_of_items);
												//
												// $get_sum=mysqli_query($db,"SELECT SUM(cost) as cost FROM stores_purchaseditems WHERE purchase_id='".$purchase_id."'") or die('failed');
												// $get_sum=mysqli_fetch_assoc($get_sum);
											?>
											<div class="row" style="width:90%; margin:0px auto; font-family:tillana" >
												<div class="col-md-6" style="border-style:solid; border-width:thin;border-right:none; padding:10px">
													To Whom Issued: <?php echo $issue_details['receiver']; ?>
												</div>
												<div class="col-md-6" style="border-style:solid; border-width:thin; padding:10px">
													Issue Date: <?php echo $issue_details['date']; ?>
												</div>
											</div>

											<table class="table card-table" style="width:90%; margin:0px auto">
											  <thead>
											    <tr>
											      <th>#</th>
											      <th>Drug ID</th>
											      <th>Drug Name</th>
											      <th>Qty Issed</th>
											    </tr>
											  </thead>
											  <tbody>
													<?php
													$i=1;
													while ($rows=mysqli_fetch_array($select_string)) {
														?>
														<tr>
												      <td><?php echo $i++; ?></td>
												      <td><?php echo $rows['drug_id']; ?></td>
												      <td><?php echo $rows['drug_name']; ?></td>
												      <td><?php echo $rows['qty']; ?></td>
												      <td></td>
												    </tr>
														<?php
													}

													 ?>

											  </tbody>
											</table>
											<hr style="width:90%">

											<button id="complete" type="button" class="btn custom_button_blue wide center-block" style="width:90%; margin:0px auto">
												Complete Issue
											</button>
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


						$('#complete').on('click', function(event) {
							event.preventDefault();

							var con=confirm('Done with the stock issue?')

							if(con===true){
								$.ajax({
									url: '../serverscripts/medicalstores/update_quantities.php',
									type: 'GET',
									success:function(msg){
										if(msg==='update_complete'){
											alert('Stock issue complete')
											window.location='supplies.php';
										}
										else {
											alert('Something went wrong')
										}
									}
								})


							} //end if
						});

    	});
	</script>

</html>
