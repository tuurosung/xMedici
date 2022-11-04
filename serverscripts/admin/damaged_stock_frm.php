<?php
require_once '../dbcon.php';

$item_code=$_GET['item_code'];

$get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_name='".$item_code."'") or die('failed');
$get_item_info=mysqli_fetch_array($get_item_info);

$item_code=$get_item_info['drug_id'];
$item_name=$get_item_info['drug_name'];
?>


<div class="">
  <form id="damaged_stock_frm">

  <h5 class="page-header">Damaged Stock Form</h5>
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
        $select_batches=mysqli_query($db,"SELECT * FROM stock WHERE item_code='".$item_code."' && qty_rem !=0") or die('failed');
        while ($batch_rows=mysqli_fetch_array($select_batches)) {
          ?>

          <option value="<?php echo $batch_rows['batch_code']; ?>"><?php echo $batch_rows['batch_code']; ?></option>
          <?php
        }

        ?>
      </select>
    </div>
    <div class="col-md-6 col-xs-6">
      <label for="">Damage Date</label>
      <input type="text" class="form-control input-sm" name="damage_date" id="damage_date"  required="required">
    </div>
  </div>

  <div class="row" style="margin-bottom:15px">
    <div class="col-md-6 col-xs-6">
      <label for="">Damage Details</label>
      <input type="text" class="form-control input-sm" name="damage_details" id="damage_details" required>
    </div>
    <div class="col-md-6 col-xs-6">
      <label for="">Qty Damaged</label>
      <input type="text" class="form-control input-sm" name="qty_damaged" id="qty_damaged" required>
    </div>
  </div>


  <div class="row" style="margin-bottom:12px">
    <div class="col-md-6 col-xs-6">
      <button type="submit" class="btn btn-success custom_button_green wide">
        <i class="fa fa-save"></i>
        Record Damages
      </button>
    </div>
    <div class="col-md-6 col-xs-6">
      <button  type="button" class="btn btn-danger wide return">
        <i class="fa fa-undo"></i>
        Return To Items
      </button>
    </div>
  </div>


</form>

</div>
