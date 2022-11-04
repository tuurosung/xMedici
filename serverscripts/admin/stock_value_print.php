<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" media="print"/>
    <link rel="stylesheet" href="../../fontawesome/css/font-awesome.css" media="print" title="no title">

  </head>


  <body style="font-size:10px">

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
              STOCK VALUE AS AT <?php echo date('d-m-Y  H:i:s'); ?>
          </div>
        </div>
      </div>



<table class="table datatables" style="font-size:15px; font-family:roboto" >
  <thead>
    <tr>
      <th>#</th>
      <th>Drug Name</th>
      <th>Avail. Qty</th>
      <th>Price</th>
      <th class="text-right">Actual</th>
      <th class="text-right">Expected</th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_items=mysqli_query($db,"SELECT drug_id,drug_name,restock_level,selling_price,cost_price FROM inventory ORDER BY drug_name ASC")  or die('failed');
    $i=1;
    $overall_total=0;
    while ($rows=mysqli_fetch_array($get_items)) {

      $get_qty=mysqli_query($db,  "SELECT SUM(qty_rem) as total_qty FROM stock WHERE drug_id='".$rows['drug_id']."'") or die('failed');
      $get_qty=mysqli_fetch_assoc($get_qty);

      $total_qty=$get_qty['total_qty'];

      if($total_qty==0){
        continue;
      }

      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php  echo (int)$total_qty; ?></td>
        <td><?php echo $rows['selling_price']; ?></td>
        <td class="text-right"><?php  echo number_format($rows['cost_price']*$total_qty,2); ?></td>
        <td class="text-right"><?php  echo number_format($rows['selling_price']*$total_qty,2); ?></td>



      </tr>
      <?php $actual_stock +=$rows['cost_price']*$total_qty; ?>
      <?php $expected_stock +=$rows['selling_price']*$total_qty; ?>

      <?php
    }
    ?>


  </tbody>
</table>
<hr>
<div class="text-center" style="font-weight: bold; font-size:15px; font-family:roboto">
  Actual Stock Value = GH&cent; <?php echo number_format($actual_stock,2); ?>
  <br>
  <br>
  Expected Stock Value = GH&cent; <?php echo number_format($expected_stock,2); ?>
  <br>
  <br>
  Expected Profit = GH&cent; <?php echo number_format($expected_stock-$actual_stock,2); ?>
</div>

<div class="text-right" style="font-family:roboto; font-size:15px; border-top-style:solid; border-bottom-style:solid; border-top-width:1px; border-bottom-width:1px; padding:10px; bottom:10px; position:inherit; width:100%; margin-top:50px">
  Powered By: Kindred GH. Technologies 0206982464
</div>



</body>
</html>
