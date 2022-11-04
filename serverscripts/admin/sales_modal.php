<?php
require_once '../dbcon.php';

$drug_id=clean_string($_GET['drug_id']);
$drug_info=drug_info($drug_id);

?>


<div id="sales_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="sales_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Retail Sales Form</h6>
        <hr class="hr">

        <div class="form-group d-none">
          <label for="">Transaction Id</label>
          <input type="text" class="form-control input-sm" name="transaction_id" value="<?php echo transaction_idgen(); ?>" readonly>
        </div>

        <div class="form-group d-none">
          <label for="">Drug Id</label>
          <input type="text" class="form-control input-sm" name="drug_id" value="<?php echo $drug_id; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Drug Name</label>
          <input type="text" class="form-control input-sm" name="drug_name" value="<?php echo $drug_info['drug_name']; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Batch Code</label>
          <select class="custom-select browser-default" name="batch_code" id="batch_code" required>
            <?php
              $get_batches=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$drug_id."' && status='active' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
              while ($batches=mysqli_fetch_array($get_batches)) {
                ?>
                <option data-qty_rem="<?php echo $batches['qty_rem']; ?>" value="<?php echo $batches['batch_code']; ?>"> Qty Rem. <?php echo $batches['qty_rem']; ?>  / Expiring <?php echo $batches['expiry_date']; ?></option>
                <?php
              }
             ?>
          </select>
        </div>




        <div class="row" style="margin-bottom:15px">
          <div class="col-md-6 col-xs-6">
            <label for="">Selling Price</label>
            <input type="text" class="form-control input-sm" name="selling_price" id="selling_price" value="<?php echo $drug_info['retail_price']; ?>" autocomplete="off" required readonly>
          </div>
          <div class="col-md-6 col-xs-6">
            <label for="">Qty</label>
            <input type="text" class="form-control input-sm" name="qty" id="qty" value="1" required autocomplete="off">
          </div>
        </div>

        <div class="form-group">
          <label for="">Total</label>
          <input type="text" class="form-control input-sm" name="total" id="total" value="<?php echo $drug_info['retail_price']; ?>" required readonly>
        </div>


      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          Add To Cart
        </button>
      </div>
      </form>
    </div>
  </div>
</div>
