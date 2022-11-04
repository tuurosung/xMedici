<?php
require_once '../dbcon.php';

$drug_id=clean_string($_GET['drug_id']);
$drug_info=drug_info($drug_id);

?>


<div id="add_stock_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="add_stock_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Add Stock Form</h6>
        <hr class="hr">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Unit</label>
              <select class="browser-default custom-select" name="unit">
                <option value="Tab">Tablets</option>
                <option value="Cap">Capsules</option>
                <option value="Vile">Vile</option>
                <option value="Amp">Ampule</option>
                <option value="Btl">Bottle</option>
                <option value="Roll">Roll</option>
                <option value="Tube">Tube</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="">Drug Id</label>
          <input type="text" class="form-control input-sm" name="drug_id" value="<?php echo $drug_id; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Drug Name</label>
          <input type="text" class="form-control input-sm" name="drug_name" value="<?php echo $drug_info['drug_name']; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Batch Code</label>
          <input type="text" class="form-control input-sm" name="batch_code" value="<?php echo time(); ?>" readonly required>
        </div>

        <div class="form-group">
          <label for="">Stocking Date</label>
          <input type="text" class="form-control" name="stocking_date" id="stocking_date" value="<?php echo date('Y-m-d'); ?>" required autocomplete="off">
        </div>



        <div class="row" style="margin-bottom:15px">
          <div class="col-md-6 col-xs-6">
            <label for="">Qty Stocked</label>
            <input type="text" class="form-control input-sm" name="qty_stocked" id="qty_stocked"required autocomplete="off" required>
          </div>
          <div class="col-md-6 col-xs-6">
            <label for="">Expiry Date</label>
            <input type="text" class="form-control input-sm" name="expiry_date" id="expiry_date" required>
          </div>
        </div>

      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          Add Stock
        </button>
      </div>
      </form>
    </div>
  </div>
</div>
