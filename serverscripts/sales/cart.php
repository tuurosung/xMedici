<?php
require_once '../dbcon.php';
session_start();

$tracksales_transid=$_SESSION['tracksales_transid'];
$select_string=mysqli_query($db,"SELECT * FROM sales WHERE status='PENDING' && transid='".$tracksales_transid."'") OR die('failed');

$num_rows=mysqli_num_rows($select_string);

if($num_rows==0){
  echo '<p>
  Cart Empty
  </p>';
  die();
}
else {

}


?>

<table class="table  datatables" style="font-size:11px">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Qty</th>
      <th>Sub-Total</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i=1;
    while ($rows=mysqli_fetch_array($select_string)) {

      $drug_id=$rows['item_code'];
      $drug_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$drug_id."'") or die('failed');
      $drug_info=mysqli_fetch_array($drug_info);
      $drug_name=$drug_info['drug_name'];

      $total+=$rows['total_cost'];
      ?>
      <tr>
        <td style="width:1%"><?php echo $i++; ?></td>
        <td style="width:50%"><?php echo $drug_name; ?></td>
        <td style="width:5%"><?php echo $rows['qty']; ?></td>
        <td style="width:40%"><?php echo $rows['total_cost']; ?></td>
        <td>
          <button type="button" class="btn btn-danger btn-sm delete" id="<?php echo $rows['sn']; ?>">Delete</button>
        </td>
      </tr>

      <?php
    }
    ?>

    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td>
        <?php echo number_format($total,2); ?>
      </td>
      <td>


      </td>
    </tr>

  </tbody>
</table>
<br>
<form id="cart_frm">

<h4 class="page-header"style="font-family:myoswald-Regular; font-size:12px">Billing</h4>
<div class="row" style="margin-top:30px;">
  <div class="col-md-6 text-right">
    <div style="font-size:15px; margin-top:10px">Amount Payable:</div>
  </div>
  <div class="col-md-6">
    <input type="text" name="amount_payable" id="amount_payable" class="form-control input-sm text-right" value="<?php echo number_format($total,2); ?>" style="font-size:15px" readonly>
  </div>
</div>
<div class="row" style="margin-top:10px">
  <div class="col-md-6 text-right">
    <div style="font-size:15px; margin-top:10px">Amount Paid:</div>
  </div>
  <div class="col-md-6">
    <input type="text" name="amount_paid" id="amount_paid" class="form-control input-sm text-right" style="font-size:15px" autocomplete="off"  required="required">
  </div>
</div>
<div class="row" style="margin-top:10px">
  <div class="col-md-6 text-right">
    <div style="font-size:15px; margin-top:10px">Balance Remaining:</div>
  </div>
  <div class="col-md-6">
    <input type="text" name="balance_remaining" id="balance_remaining" class="form-control input-sm text-right" style="font-size:15px" readonly>
  </div>
</div>

<br>
<div class="row" style="margin-top:10px">
  <div class="col-md-6">
    <button type="button" class="btn btn-danger wide" id="destroy_cart">
      <i class="fa fa-trash-o"></i>
      Destroy Cart
    </button>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-success wide">
      Checkout
      <i class="fa fa-arrow-right"></i>
    </button>

  </div>
</div>
</form>
