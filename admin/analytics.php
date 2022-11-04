<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<style media="screen">
		.panel-body{
			font-family: roboto;
		}
	</style>

	<?php require_once '../navigation/admin_header.php' ?>

</head>
<body>

<div class="wrapper">

    <?php require_once '../navigation/admin_nav.php'; ?>
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

                    <div class="col-md-6">
                        <div class="card">
                            <div class="header" style="background-color:#0057e7; ">
                                <h4 class="title" style="color:#fff">Daily Sales History For The Month </h4>
																<p class="category" style="color:#fff">Shows daily summaries of sales made each day of the month</p>
                            </div>
                            <div class="content" style="height:400px">

															<table class="table">
															  <thead>
															    <tr>
															      <th>#</th>
															      <th>Date</th>
															      <th class="text-right">Total Sale</th>
															    </tr>
															  </thead>
															  <tbody>
																	<?php
																		require_once '../serverscripts/dbcon.php';
																		$this_month=date('m');
																		$i=1;
																		$get_dates=mysqli_query($db,"SELECT DISTINCT date FROM sales WHERE MONTH(date)='".$this_month."'") or die('failed');

																		while ($dates=mysqli_fetch_array($get_dates)) {
																			$get_total_sale=mysqli_query($db,"SELECT SUM(total_cost) as total_cost FROM sales WHERE date='".$dates['date']."'") or die('failed');
																			$get_total_sale=mysqli_fetch_assoc($get_total_sale);

																			$sum+=$get_total_sale['total_cost'];

																			?>

																			<tr>
																	      <td><?php 	echo $i++; ?></td>
																	      <td><?php echo $dates['date']; ?></td>
																	      <td class="text-right"><?php echo number_format($get_total_sale['total_cost'],2); ?></td>
																	    </tr>

																			<?php
																		}
																	?>



															  </tbody>
															</table>

															<div class="row" style="font-size:11px">
															  <div class="col-md-6">
																	<div class="panel panel-blue">
																		<div class="panel-heading">
																			Total Sale
																		</div>
																		<div class="panel-body">
																			GH&cent; <?php echo number_format($sum,2); ?>
																		</div>
																	</div>
															  </div>
															  <div class="col-md-6">
																	<div class="panel panel-blue">
																		<div class="panel-heading">
																			Average Monthly Purchase
																		</div>
																		<div class="panel-body">
																			GH&cent; <?php echo number_format(($sum)/($i-1),2); ?>

																		</div>
																	</div>
															  </div>
															</div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header" style="background-color:#6eb6b1; ">
                                <h4 class="title" style="color:#fff">Purchases History </h4>
																<p class="category" style="color:#fff">Summary of all purchases made within the month</p>
                            </div>
                            <div class="content" style="height:400px">

															<table class="table datatables">
															  <thead>
															    <tr>
															      <th>#</th>
															      <th>Supplier Inv. ID</th>
															      <th>Supplier</th>
															      <th>Date</th>
															      <th class="text-right">Cost</th>
															    </tr>
															  </thead>
															  <tbody>
															    <?php
															    //require_once '../dbcon.php';
															    $get_items=mysqli_query($db,"SELECT * FROM invoices")  or die('failed');
															    $i=1;
															    while ($rows=mysqli_fetch_array($get_items)) {
															      $get_supplier=mysqli_query($db,"SELECT * FROM suppliers WHERE supplier_id='".$rows['supplier_id']."'") or die('failed');
															      $get_supplier=mysqli_fetch_array($get_supplier);
															      $supplier_name=$get_supplier['supplier_name'];
																		$purchase_sum+=$rows['purchase_cost'];
															      ?>

															      <tr>
															        <td><?php echo $i++; ?></td>
															        <td><?php echo $rows['supplier_invoice_id']; ?></td>
															        <td><?php echo $supplier_name; ?></td>
															        <td><?php echo $rows['purchase_date']; ?></td>
																			<td class="text-right">
																				<?php echo $rows['purchase_cost']; ?>
																			</td>


															      </tr>

															      <?php
															    }
															    ?>

															  </tbody>
															</table>

															<div class="row" style="font-size:11px">
															  <div class="col-md-6">
																	<div class="panel panel-blue">
																		<div class="panel-heading">
																			Total Purchases
																		</div>
																		<div class="panel-body">
																			GH&cent; <?php echo number_format($purchase_sum,2); ?>
																		</div>
																	</div>
															  </div>
															  <div class="col-md-6">
																	<div class="panel panel-blue">
																		<div class="panel-heading">
																			Average Monthly Sale
																		</div>
																		<div class="panel-body">
																			GH&cent; <?php echo number_format(($purchase_sum)/($i-1),2); ?>

																		</div>
																	</div>
															  </div>
															</div>



                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-6">
                        <div class="card">
                            <div class="header" style="background-color:#d62d20; ">
                                <h4 class="title" style="color:#fff">Monthly Expenditure Summary </h4>
																<p class="category" style="color:#fff">Displays a detailed summary of all expenditure made for the month</p>
                            </div>
                            <div class="content" style="height:400px">
															<table class="table">
															  <thead>
															    <tr>
															      <th>#</th>
															      <th>Date</th>
															      <th>Purpose</th>
															      <th>Amount</th>
															    </tr>
															  </thead>
															  <tbody>
																	<?php
																		//require_once '../serverscripts/dbcon.php';

																		$this_month=date('m');
																		$get_expenditure=mysqli_query($db,"SELECT * FROM expenditure WHERE MONTH(date)='".$this_month."'") or die('failed');
																		$i=1;
																		while ($expenditure_rows=mysqli_fetch_array($get_expenditure)) {
																			$expenditure_sum+=$expenditure_rows['expenditure_amount'];
																			?>

																			<tr>
																	      <td><?php 	echo $i++; ?></td>
																	      <td><?php echo $expenditure_rows['date']; ?></td>
																	      <td><?php echo $expenditure_rows['expenditure_purpose']; ?></td>
																	      <td><?php echo $expenditure_rows['expenditure_amount']; ?></td>
																	    </tr>

																			<?php
																		}
																	?>



															  </tbody>
															</table>

															<div class="row" style="font-size:11px">
															  <div class="col-md-6">
																	<div class="panel panel-blue">
																		<div class="panel-heading">
																			Total Expenditure
																		</div>
																		<div class="panel-body">
																			GH&cent; <?php echo number_format($expenditure_sum,2); ?>
																		</div>
																	</div>
															  </div>
															  <div class="col-md-6">
																	<div class="panel panel-blue">
																		<div class="panel-heading">
																			Average Monthly Sale
																		</div>
																		<div class="panel-body">
																			GH&cent; <?php echo number_format(($expenditure_sum)/($i-1),2); ?>

																		</div>
																	</div>
															  </div>
															</div>




                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header" style="background-color:#008744; ">
                                <h4 class="title" style="color:#fff">Balance Sheet For The Month </h4>
																<p class="category" style="color:#fff">Strikes Income against Expenditure and Purchases to give profit for the month</p>
                            </div>
                            <div class="content" style="height:400px">

															<table class="table table-user-info">
															  <tbody >
															    <tr style="font-size:20px">
															      <td>Total Monthly Income</td>
															      <td class="text-right"><strong><?php echo number_format($sum,2); ?></strong></td>
															    </tr>
															    <tr style="font-size:20px">
															      <td>Total Monthly Purchase</td>
															      <td class="text-right"><strong><?php echo number_format($purchase_sum,2); ?></strong></td>
															    </tr>
															    <tr style="font-size:20px">
															      <td>Total Monthly Expenditure</td>
															      <td class="text-right"><strong><?php echo number_format($expenditure_sum,2); ?></strong></td>
															    </tr>
															    <tr style="font-size:20px">
															      <td>Total Net Profit</td>
															      <td class="text-right"><strong><u><?php echo number_format($sum-($expenditure_sum+$purchase_sum),2); ?></u></strong></td>
															    </tr>
															  </tbody>
															</table>

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

				$('#sales_history_li').addClass('active')

				$('#start_date, #end_date').datepicker({
					dateFormat: 'yy-mm-dd',
					changeMonth:true,
					changeYear:true
				})




				$('#search_frm').on('submit', function(event) {
					event.preventDefault();

					var data=$(this).serialize()
					$.ajax({
						url: '../serverscripts/admin/analytics.php',
						type: 'GET',
						data: data,
						success: function(msg){
							$('#sales_history_holder').html(msg)
							$('.datatables').DataTable({
								'sort':false,
								'paging':false
							})
						}
					})

				});

				$('#print').on('click', function(event) {
						var con=confirm('Do you want to print out this sales report?')
						var start_date=$('#start_date').val()
						var end_date=$('#end_date').val()
						var attendant=$('#attendant').val()
						if(con==true){
							window.open("../serverscripts/admin/analytics_print.php?start_date="+start_date+"&end_date="+end_date,"_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")
						}
				})


    	});
	</script>

</html>
