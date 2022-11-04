<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Patients.php';
    require_once '../../Classes/Payments.php';


    $payment_id=clean_string($_GET['payment_id']);

    $payment=new Payment();

    $payment->payment_id=$payment_id;
    $payment->PaymentInfo();
 ?>



<div id="edit_payment_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="payment_frm" autocomplete="off">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Edit Payment</h6>
        <hr class="hr">

        <div class="row d-none">
          <div class="col-md-6">
              <label for="">Patient ID</label>
              <input type="text" class="form-control" name="patient_id" value="<?php echo $payment->patient_id; ?>">
          </div>
          <div class="col-md-6">
              <label for="">Visit ID</label>
              <input type="text" class="form-control" name="visit_id" value="<?php echo $payment->visit_id; ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="">Bill ID</label>
          <input type="text" class="form-control" name="bill_id" value="<?php echo $payment->bill_id; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Amount Payable</label>
          <input type="text" class="form-control" name="amount_payable" id="amount_payable" value="<?php echo $payment->amount_payable; ?>" readonly>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Amount Paid</label>
              <input type="text" class="form-control" name="amount_paid" id="amount_paid" value="<?php echo $payment->amount_paid; ?>"required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Balance</label>
              <input type="text" class="form-control" name="balance" id="balance" value="<?php echo $payment->balance; ?>" readonly>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Income Header</label>
              <select class="custom-select browser-default" name="income_account" required>
                <?php
                    $get_accounts=mysqli_query($db,"
                    SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                    LEFT JOIN account_headers h on h.sn=a.account_header
                    WHERE h.type=4 && subscriber_id='".$active_subscriber."';
                    ") or die('failed');
                    while ($accounts=mysqli_fetch_array($get_accounts)) {
                      ?>
                      <option value="<?php echo $accounts['account_number']; ?>" <?php if($payment->income_account==$accounts['account_number']){echo 'selected';} ?>><?php echo $accounts['account_name']; ?></option>
                      <?php
                    }
                 ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Payment Account</label>
              <select class="custom-select browser-default" name="payment_account" required>
                <?php
                    $get_accounts=mysqli_query($db,"
                    SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                    LEFT JOIN account_headers h on h.sn=a.account_header
                    WHERE h.type=1 && subscriber_id='".$active_subscriber."';
                    ") or die('failed');
                    while ($accounts=mysqli_fetch_array($get_accounts)) {
                      ?>
                      <option value="<?php echo $accounts['account_number']; ?>" <?php if($payment->payment_account==$accounts['account_number']){echo 'selected';} ?>><?php echo $accounts['account_name']; ?></option>
                      <?php
                    }
                 ?>
              </select>
            </div>
          </div>
        </div>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" ><i class="fas fa-credit-card mr-2" aria-hidden></i> Update Payment</button>
      </div>
      </form>
    </div>
  </div>
</div>
