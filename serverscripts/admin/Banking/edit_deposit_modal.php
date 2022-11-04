<?php

    require '../../dbcon.php';
    require '../../Classes/Banking.php';

    $deposit_id=clean_string($_GET['deposit_id']);


    $banking=new Banking();

    $banking->deposit_id=$deposit_id;
    $banking->BankingInfo();


 ?>


 <div id="edit_deposit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-side modal-top-right">
     <div class="modal-content">

       <form id="new_deposit_frm">
       <div class="modal-body">
         <h6 class="font-weight-bold montserrat">Edit Deposit Modal</h6>
         <hr class="hr">

          <div class="form-group">
            <input type="text" name="deposit_id" class="form-control" value="<?php  echo $deposit_id; ?>">
          </div>

           <div class="form-group">
             <label for="">Source Account</label>
             <select class="custom-select browser-default" name="source_account" id="source_account" required>
               <?php
                   $get_accounts=mysqli_query($db,"
                   SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                   LEFT JOIN account_headers h on h.sn=a.account_header
                   WHERE h.type=1 && subscriber_id='".$active_subscriber."';
                   ") or die('failed');
                   while ($accounts=mysqli_fetch_array($get_accounts)) {
                     ?>
                     <option value="<?php echo $accounts['account_number']; ?>" <?php if($banking->source_account==$accounts['account_number']){ echo 'selected';} ?>><?php echo $accounts['account_name']; ?></option>
                     <?php
                   }
                ?>
             </select>
           </div>



           <div class="form-group">
             <label for="">Amount Deposited</label>
             <input type="text" class="form-control" id="amount" name="amount" placeholder="" value="<?php echo $banking->amount; ?>" required>
           </div>


           <div class="row">
             <div class="col-md-6">
               <div class="form-group">
                 <label for="">Deposit Account</label>
                 <select class="custom-select browser-default" name="deposit_account" id="deposit_account" required>
                   <?php
                       $get_accounts=mysqli_query($db,"
                       SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                       LEFT JOIN account_headers h on h.sn=a.account_header
                       WHERE h.sn=1 && subscriber_id='".$active_subscriber."';
                       ") or die('failed');
                       while ($accounts=mysqli_fetch_array($get_accounts)) {
                         ?>
                         <option value="<?php echo $accounts['account_number']; ?>" <?php if($banking->deposit_account==$accounts['account_number']){ echo 'selected'; } ?>><?php echo $accounts['account_name']; ?></option>
                         <?php
                       }
                    ?>
                 </select>
               </div>
             </div>
             <div class="col-md-6">
               <div class="form-group">
                 <label for="">Deposit Date</label>
                 <input type="text" class="form-control" id="edit_deposit_date" name="deposit_date" placeholder="" value="<?php echo $banking->date; ?>" required>
               </div>
             </div>
           </div>


           <div class="form-group">
             <label for="">Narration</label>
             <textarea  class="form-control" id="narration" name="narration" placeholder=""><?php echo $banking->narration; ?></textarea>
           </div>



       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Update Deposit Info</button>
       </div>
     </form>
     </div>
   </div>
 </div>
