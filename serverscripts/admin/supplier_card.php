<?php
require_once '../dbcon.php';
$supplier_id=$_GET['supplier_id'];

$select_string=mysqli_query($db,"SELECT * FROM suppliers WHERE supplier_id='".$supplier_id."'") or die('failed');

$supplier_info=mysqli_fetch_row($select_string);

$count_supplies=mysqli_query($db,"SELECT COUNT(*) AS supplies FROM invoices WHERE supplier_id='".$supplier_id."'") or die('failed');
$count_supplies=mysqli_fetch_assoc($count_supplies);


$get_debt=mysqli_query($db,"SELECT SUM(balance_remaining) as debt FROM invoices  WHERE supplier_id='".$supplier_id."' && balance_remaining > 0") or die('failed');
$get_debt=mysqli_fetch_assoc($get_debt);
?>
<div style="border-style:solid; border-width:1px; border-left-width:5px; border-left-color:#df1a6d; border-color:#df1a6d; border-radius:5px; width:80%; margin:0px auto; padding:5px">


<div class="row">
  <div class="col-md-5">
    <i class="fa fa-truck fa-5x "></i>
  </div>
  <div class="col-md-7">
    <span style="font-size:23px"><?php echo $supplier_info[2]; ?></span><br>
    <span style="font-size:15px"><i class="fa fa-map-marker"></i><?php echo $supplier_info[4]; ?></span><br>
    <span style="font-size:12px"><i class="fa fa-phone"></i><?php echo $supplier_info[3]; ?></span><br>
    <br><br>
    <span style="font-size:15px"><i class="fa fa-list-alt"></i> Supply History: <?php echo $count_supplies['supplies']; ?> supplies</span><br>
    <span style="font-size:15px"><i class="fa fa-balance-scale"></i> Debt Value: GH&cent; <?php echo number_format($get_debt['debt'],2); ?></span>
  </div>
</div>




</div>
<br><br>
<div class="text-right" style="margin:0px auto; width:80%">
  <button type="button" class="btn btn-danger " >
    <i class="fa fa-times"></i>
    Close Supplier Card
  </button>

</div>
