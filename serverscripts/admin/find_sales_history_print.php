<?php
require_once '../dbcon.php';
$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];
$attendant=$_GET['attendant'];


if($attendant=='all'){
  $get_total=mysqli_query($db,"SELECT SUM(total_cost) AS total_transaction_cost FROM sales WHERE date BETWEEN '".$start_date."' AND '".$end_date."'  AND status='SOLD' ") or die('failed1');
  $total_transaction_cost=mysqli_fetch_assoc($get_total);
  $total_transaction_cost=$total_transaction_cost['total_transaction_cost'];

}
else{
  $get_total=mysqli_query($db,"SELECT SUM(total_cost) AS total_transaction_cost FROM sales WHERE attendant='".$attendant."' AND date BETWEEN '".$start_date."' AND '".$end_date."'  AND status='SOLD' ") or die('failed1');
  $total_transaction_cost=mysqli_fetch_assoc($get_total);
  $total_transaction_cost=$total_transaction_cost['total_transaction_cost'];

}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.css" media="print" title="no title">
  </head>


  <body>

    <div class="" style=" padding:10px">
        <div class="" style="font-family:roboto; border-top-style:solid; border-top-width:1px; border-bottom-style:solid; border-bottom-width:1px; margin-bottom:10px">
          <div class="row" style="padding:10px">
            <div class="text-center">
          		<span style="font-size:20px; font-weight:bold">DARTAH PHARMACY LIMITED</span>	<br>
          		<span style="font-size:16px; ">DOKPONG BRANCH</span> <br>
          		<span style="font-size:16px; ">WA-UPPER WEST REGION</span> <br>
          		<span style="font-size:16px; ">NEW REGIONAL HOSPITAL MAIN ROAD</span> <br>
          		<span style="font-size:16px; "><i class="fa fa-phone"></i>: 0208238834 / 0244444706 / 0207856467</span>
          	</div>
            <br>
            <div class="text-center" style="font-size:15px; font-weight:bold">
                SALES HISTORY BETWEEN <?php echo $start_date; ?> AND <?php echo $end_date; ?>
            </div>
          </div>
        </div>

<hr>

<table class="table datatables" style="font-family:roboto">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Code</th>
      <th>Item Name</th>
      <th>Unit Price</th>
      <th>Qty</th>
      <th class="text-right">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // if($attendant=='all'){
    //   $get_transactions=mysqli_query($db,"SELECT * FROM cart_transactions WHERE date BETWEEN '".$start_date."' AND '".$end_date."'") or die('failed');
    // }
    // else {
    //   $get_transactions=mysqli_query($db,"SELECT * FROM cart_transactions WHERE date BETWEEN '".$start_date."' AND '".$end_date."' && attendant='".$attendant."'") or die('failed');
    // }


    if($attendant=='all'){
      $get_items=mysqli_query($db,"SELECT DISTINCT(item_code),selling_price  FROM sales WHERE date BETWEEN '".$start_date."' AND '".$end_date."'") or die('failed1');
    }
    else {
      $get_items=mysqli_query($db,"SELECT DISTINCT(item_code),selling_price FROM sales WHERE date BETWEEN '".$start_date."' AND '".$end_date."' && attendant='".$attendant."'") or die('failed1');
    }


    $i=1;


    while ($item_rows=mysqli_fetch_array($get_items)) {

        $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$item_rows['item_code']."'") or die('failed1');
        $get_item_info=mysqli_fetch_array($get_item_info);

        $qty=mysqli_query($db,"SELECT SUM(qty) AS qty FROM sales WHERE item_code='".$item_rows['item_code']."' && date BETWEEN '".$start_date."' AND '".$end_date."'") or die('failed2');
        $qty=mysqli_fetch_assoc($qty);



        ?>

        <tr id="">
          <td><?php echo $i++; ?></td>
          <td><?php echo $item_rows['item_code']; ?></td>
          <td><?php echo $get_item_info['drug_name']; ?></td>
          <td><?php echo $item_rows['selling_price']; ?></td>
          <td><?php echo $qty['qty']; ?></td>
          <td class="text-right"><?php echo number_format($item_rows['selling_price'] * $qty['qty'],2); ?></td>

        </tr>


        <?php




    }
    ?>

  </tbody>
</table>

  <div class="text-center" style="font-family:roboto; font-size:15px; font-weight:bold">
    TOTAL AMOUNT SOLD = <?php echo $total_transaction_cost; ?>
  </div>


  <div class="text-right" style="font-family:roboto; font-size:15px; border-top-style:solid; border-bottom-style:solid; border-top-width:1px; border-bottom-width:1px; padding:10px; bottom:10px; position:inherit; width:100%; margin-top:50px">
    Powered By: Kindred GH. Technologies 0206982464
  </div>

  </body>
  <script type="text/javascript">
   print()
  </script>

</html>
