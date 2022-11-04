<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Expenditure.php';

  $expenditure_id=clean_string($_GET['expenditure_id']);
  $expenditure=new Expenditure();
  $expenditure->expenditure_id=$expenditure_id;
  $expenditure->ExpenditureInfo();
 ?>


<div id="edit_expenditure_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="edit_expenditure_frm">
      <div class="modal-body">
        <h6 class="">Edit Expenditure</h6>
        <hr class="hr">
            <div class="form-group d-non">
              <input type="text" class="form-control" id="expenditure_id" name="expenditure_id" value="<?php echo $expenditure->expenditure_id; ?>" placeholder="Expenditure ID" readonly>
            </div>

            <div class="form-group">
              <label for="">Expenditure Header</label>
              <select class="custom-select browser-default" name="expenditure_account" required>
                <?php
                    $get_accounts=mysqli_query($db,"
                    SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                    LEFT JOIN account_headers h on h.sn=a.account_header
                    WHERE h.type=5 && subscriber_id='".$active_subscriber."';
                    ") or die('failed');
                    while ($accounts=mysqli_fetch_array($get_accounts)) {
                      ?>
                      <option value="<?php echo $accounts['account_number']; ?>" <?php if($accounts['account_number']==$expenditure->expenditure_account){ echo 'selected'; } ?>><?php echo $accounts['account_name']; ?></option>
                      <?php
                    }
                 ?>
              </select>
            </div>



            <div class="form-group">
              <label for="">Expenditure Amount</label>
              <input type="text" class="form-control" id="amount" name="amount" value="<?php echo $expenditure->amount; ?>" required>
            </div>


            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Payment Account</label>
                  <select class="custom-select browser-default" name="payment_account" required>
                    <?php
                        $get_accounts=mysqli_query($db,"
                        SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                        LEFT JOIN account_headers h on h.sn=a.account_header
                        WHERE h.sn=1 && subscriber_id='".$active_subscriber."';
                        ") or die('failed');
                        while ($accounts=mysqli_fetch_array($get_accounts)) {
                          ?>
                          <option value="<?php echo $accounts['account_number']; ?>"  <?php if($accounts['account_number']==$e->payment_account){ echo 'selected'; } ?>><?php echo $accounts['account_name']; ?></option>
                          <?php
                        }
                     ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Expenditure Date</label>
                  <input type="text" class="form-control" id="expenditure_date" name="expenditure_date"  value="<?php echo $expenditure->date; ?>" required>
                </div>
              </div>
            </div>


            <div class="form-group">
              <label for="">Narration</label>
              <textarea  class="form-control" id="description" name="description" placeholder=""><?php echo $expenditure->description; ?></textarea>
            </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Expenditure</button>
        </div>
      </form>
    </div>
  </div>
</div>
