<?php
require_once '../dbcon.php';

$item_code=$_GET['item_code'];

$get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$item_code."'") or die('failed');
$get_item_info=mysqli_fetch_array($get_item_info);

$item_code=$get_item_info['drug_id'];
$item_name=$get_item_info['drug_name'];
?>

<div class="header">
  <h4 class="title">Stock Reduction Form</h4>
  <p class="category">
    Reduce Stock Quantity
  </p>
</div>
<hr>
<div class="content" style="font-family:roboto">
  <form id="stockout_frm">

  <div class="row" style="margin-bottom:12px">
    <div class="col-md-6 col-xs-6">
      <label for="">Item Code</label>
      <input type="text" class="form-control input-sm" name="item_code" value="<?php echo $item_code; ?>" readonly>
    </div>
    <div class="col-md-6 col-xs-6">
      <label for="">Item Name</label>
      <input type="text" class="form-control input-sm" name="item_name"  value="<?php echo $item_name; ?>" readonly>
    </div>
  </div>

  <div class="row" style="margin-bottom:12px">
    <div class="col-md-6 col-xs-6">
      <label for="">Batch Code</label>
      <select class="form-control" name="batch_code" id="batch_code">
        <?php
        $select_batches=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$item_code."' && qty_rem >0") or die('failed');
        while ($batch_rows=mysqli_fetch_array($select_batches)) {
          ?>

          <option value="<?php echo $batch_rows['batch_code']; ?>"><?php echo $batch_rows['batch_code']; ?></option>
          <?php
        }

        ?>
      </select>
    </div>
    <div class="col-md-6 col-xs-6">
      <label for="">Stocking Date</label>
      <input type="text" class="form-control input-sm" name="stockout_date" id="stockout_date"  required="required">
    </div>
  </div>

  <div class="row" style="margin-bottom:15px">
    <div class="col-md-6 col-xs-6">
      <label for="">Stockout Purpose</label>
      <input type="text" class="form-control input-sm" name="stockout_purpose" id="stockout_purpose" required>
    </div>
    <div class="col-md-6 col-xs-6">
      <label for="">Qty Withdrawn</label>
      <input type="text" class="form-control input-sm" name="stockout_qty" id="stockout_qty" required>
    </div>
  </div>


  <div class="row" style="margin-bottom:12px">
    <div class="col-md-6 col-xs-6">
      <button type="submit" class="btn btn-success wide">
        <i class="fa fa-save"></i>
        Withdraw Stock
      </button>
    </div>
    <div class="col-md-6 col-xs-6">
      <button  type="button" class="btn btn-primary wide return">
        <i class="fa fa-undo"></i>
        Return To Items
      </button>
    </div>
  </div>


</form>

</div>
