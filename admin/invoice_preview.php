<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
$patient=new Patient();
$invoice=new Invoice();

$invoice_id=clean_string($_GET['invoice_id']);
$invoice->invoice_id=$invoice_id;
$invoice->InvoiceInfo();



  // $invoice_info=invoice_info($invoice_id);
  $patient->patient_id=$invoice->patient_id;
  $patient->PatientInfo();
 ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <div class="row mb-5">
      <div class="col-md-6">
          <h4 class="titles montserrat">Invoice Preview - <?php echo $invoice_id; ?></h4>

      </div>
      <div class="col-md-6 text-right">
          <a href="invoice_prepare.php?invoice_id=<?php echo $invoice_id; ?>">
          <button type="button" class="btn btn-primary btn-rounded"> <i class="fas fa-arrow-left mr-3"></i>Return</button>
        </a>



      </div>
    </div>

    <?php
        if($invoice_info['payment_status']=='paid'){
          ?>
          <span class="badge badge-success px-3 montserrat mb-4" style="font-size:20px">PAID <i class="fas fa-check ml-3"></i></span>
          <?php
        }
     ?>


    <?php
        if($invoice_info['lockstatus']=='locked'){
          ?>
          <div class="card mb-5">
            <div class="card-body">
              <h6 class="font-weight-bold mb-3">Payment</h6>

              <div class="row">
                <div class="col-md-4 pt-3" style="font-size:16px;">
                  Expected Amount : GHS <?php echo $invoice_info['total']; ?>
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4 text-right">
                  <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_payment_modal"><i class="fas fa-credit-card mr-3"></i>Record Payment</button>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
     ?>


    <div class="card" style="min-height:297mm">
      <div class="card-body">
          <div class="row mt-3">
            <div class="col-md-6">
              <h5 class="montserrat font-weight-bold"><?php echo $hospital->hospital_name; ?></h5>
              <h6 class="montserrat font-weight-bold" style="font-size:12px"><?php echo $hospital->hospital_address; ?></h6>
              <h6 class="montserrat font-weight-bold" style="font-size:12px"><?php echo $hospital->phone_numbers; ?></h6>
            </div>
            <div class="col-md-6 text-right">
              <h1 class="montserrat mb-3">INVOICE</h1>
            </div>
          </div>
          <hr class="hr">

          <div class="row mb-5">
            <div class="col-md-6">
              <h6 class="montserrat font-weight-bold" style="font-size:14px"><?php echo $patient->patient_fullname; ?></h6>
              <h6 class="" style="font-size:14px"><?php echo $patient->address; ?></h6>
              <h6 class="" style="font-size:14px"><?php echo $patient->phone_number; ?></h6>
            </div>
            <div class="col-md-6 ">
              <div class="row">
                <div class="col-md-8 text-right">
                    Invoice #
                </div>
                <div class="col-md-4 font-weight-bold">
                  <?php echo $invoice->invoice_id ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 text-right">
                    Invoice Date
                </div>
                <div class="col-md-4 font-weight-bold">
                  <?php echo $invoice->date_created; ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 text-right">
                    Due Date
                </div>
                <div class="col-md-4 font-weight-bold">
                  <?php echo $invoice->due_date; ?>
                </div>
              </div>
            </div>
          </div>

          <table class="table table-condensed">
            <thead>
              <tr>
                <th>#</th>
                <th>Description</th>
                <th>Unit Cost</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                $get_invoice_items=mysqli_query($db,"SELECT * FROM invoice_items WHERE subscriber_id='".$active_subscriber."' && invoice_id='".$invoice_id."' && status='active'") or die(mysqli_error($db));
                while ($invoice_items=mysqli_fetch_array($get_invoice_items)) {
                  // $drug_info=drug_info($invoice_items['drug_id']);
                  ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $invoice_items['description']; ?></td>
                    <td><?php echo $invoice_items['unit_cost'] ?></td>
                    <td class="text-center"><?php echo $invoice_items['qty'] ?></td>
                    <td class="text-right"><?php echo $invoice_items['total'] ?></td>
                  </tr>
                  <?php
                }
               ?>
               <tr>
                 <td colspan="4" class="text-right">Sub - Total (GHS)</td>
                 <td class="text-right"><?php echo $invoice->sub_total; ?></td>
               </tr>
               <tr>
                 <td colspan="4" class="text-right">VAT (GHS)</td>
                 <td class="text-right"><?php echo $invoice->vat_amount; ?></td>
               </tr>
               <tr class="font-weight-bold">
                 <td colspan="4" class="text-right">Total (GHS)</td>
                 <td class="text-right"><?php echo $invoice->total; ?></td>
               </tr>
            </tbody>
          </table>

      </div>
    </div>





</div>
<div id="modal_holder"></div>

</div>
</main>



<div id="new_payment_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="invoice_payment_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Record Payment For This Invoice</h6>
        <hr class="hr">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Customer ID</label>
              <input type="text" class="form-control input-sm" name="customer_id" value="<?php echo $invoice_info['customer_id']; ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Invoice ID</label>
              <input type="text" class="form-control input-sm" name="invoice_id" value="<?php echo $invoice_id; ?>" readonly>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="">Amount Paid</label>
          <input type="text" class="form-control input-sm" name="amount_paid" id="amount_paid" value="<?php echo $invoice_info['balance_remaining']; ?>" required>
        </div>

        <div class="form-group">
          <label for="">Payment Date</label>
          <input type="text" class="form-control input-sm" name="payment_date" id="payment_date" value="<?php echo $today; ?>" required>
        </div>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Payment Account</label>
              <select class="custom-select browser-default" name="payment_account" id="payment_account" required>
                <?php
                    $get_accounts=mysqli_query($db,"
                    SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                    LEFT JOIN account_headers h on h.sn=a.account_header
                    WHERE h.sn=1 && subscriber_id='".$active_subscriber."';
                    ") or die('failed');
                    while ($accounts=mysqli_fetch_array($get_accounts)) {
                      ?>
                      <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                      <?php
                    }
                 ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Receivables Account</label>
              <select class="custom-select browser-default" name="receivables_account" id="receivables_account" required>
                <?php
                    $get_accounts=mysqli_query($db,"
                    SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                    LEFT JOIN account_headers h on h.sn=a.account_header
                    WHERE h.sn=3 && subscriber_id='".$active_subscriber."';
                    ") or die('failed');
                    while ($accounts=mysqli_fetch_array($get_accounts)) {
                      ?>
                      <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                      <?php
                    }
                 ?>
              </select>
            </div>
          </div>
        </div>



      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          Record Payment
        </button>
      </div>
      </form>
    </div>
  </div>
</div>









</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

      $('#payment_date').datepicker()







      $('#invoice_payment_frm').one('submit', function(event) {
        event.preventDefault();
        bootbox.confirm("Proceed with payment?",function(r){
          if(r===true){

            $.ajax({
              url: '../serverscripts/admin/invoice_payment_frm.php',
              type: 'GET',
              data:$('#invoice_payment_frm').serialize(),
              success: function(msg){
                if(msg==='save_successful'){
                  bootbox.alert("Payment Successful",function(){
                    window.location.reload()
                  })
                }
                else {
                  bootbox.alert(msg)
                }//end if
              }//end success
            }) //end ajax
          }
        })

      }); //end submit




	</script>

</html>
