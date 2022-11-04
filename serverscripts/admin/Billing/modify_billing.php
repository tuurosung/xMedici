<?php

session_start();
require_once '../../dbcon.php';
require_once '../../Classes/Payments.php';
require_once '../../Classes/OPD.php';

$patient_id=clean_string($_GET['patient_id']);
$visit_id=clean_string($_GET['visit_id']);
$bill_id=clean_string($_GET['bill_id']);

$billing=new Billing();

$billing->bill_id=$bill_id;
$billing->BillInfo();

 ?>



<div id="modify_billing_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="modify_billing_frm">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Edit Billing</h6>
        <hr class="hr">

        <div class="row d-none">
          <div class="col-md-6">
              <label for="">Patient ID</label>
              <input type="text" class="form-control" name="patient_id" value="<?php echo $billing->patient_id; ?>">
          </div>
          <div class="col-md-6">
              <label for="">Visit ID</label>
              <input type="text" class="form-control" name="visit_id" value="<?php echo $billing->visit_id; ?>">
          </div>
          <div class="col-md-6">
              <label for="">Bill ID</label>
              <input type="text" class="form-control" name="bill_id" value="<?php echo $billing->bill_id; ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="">Amount</label>
          <input type="text" class="form-control" name="bill_amount" value="<?php echo $billing->bill_amount; ?>">
        </div>

        <div class="form-group">
          <label for="">Narration</label>
          <textarea class="form-control" name="narration"><?php echo $billing->narration; ?></textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" ><i class="fas fa-check mr-2" aria-hidden></i> Update Billing</button>
      </div>
      </form>
    </div>
  </div>
</div>
