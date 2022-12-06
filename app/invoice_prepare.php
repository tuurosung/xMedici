<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
  $invoice_id=clean_string($_GET['invoice_id']);
  $invoice_info=invoice_info($invoice_id);

  $invoice=new Invoice();
  $invoice->invoice_id=$invoice_id;
  $invoice->InvoiceInfo();
  $patient_id=$invoice->patient_id;

  $patient=new Patient();
  $patient->patient_id=$invoice->patient_id;
  $patient->PatientInfo();

  $visit=new Visit();


 ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <?php
      if($invoice_info['lockstatus']=='locked'){
        ?>
        <div class="row">
          <div class="col-md-8">
              <h4 class="titles montserrat">Sorry, Invoice is locked for editing</h4>
          </div>
          <div class="col-md-4 text-right">
            <a href="invoices.php">
              <button type="button" class="btn btn-primary btn-rounded"> <i class="fas fa-arrow-left mr-3"></i>Return</button>
            </a>
          </div>
        </div>


        <?php
      }
      else {
        ?>



    <div class="row mb-3">
      <div class="col-md-6">
          <h4 class="titles montserrat">New Invoice </h4>
          <h6><?php echo $customer_info['customer_name']; ?></h6>

          <div class="p-2"  style="border-left: solid 2px ">
            <p><?php echo $invoice_id; ?></p>
            <p class="montserrat font-weight-bold"><?php echo $patient->patient_fullname; ?></p>
          </div>
      </div>
      <div class="col-md-6 text-right">


        <div class="dropdown open">
          <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Options
          </button>
          <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
            <ul class="list-group">
              <a class="list-group-item" href="invoice_preview.php?invoice_id=<?php echo $invoice_id; ?>"><i class="fas fa-eye mr-2" aria-hidden></i> Preview</a>
              <li class="list-group-item save_invoice"  id="<?php echo $invoice_id; ?>"><i class="fas fa-shopping-basket mr-2" aria-hidden></i> Save & Checkout</li>
              <li class="list-group-item" id="delete_invoice"><i class="far fa-trash-alt mr-2" aria-hidden></i> Delete Invoice</li>
            </ul>
          </div>
        </div>

      </div>
    </div>





    <div class="row">
      <div class="col-md-6">

      </div>
      <div class="col-md-3">
        <label for=""></label>
        <div class="form-group">
          <label for="">Active Visit</label>
          <select class="custom-select browser-default" name="visit_id" id="visit_id" required>
            <option value="">Select Visit</option>
            <?php
                $get_visits=mysqli_query($db,"SELECT * FROM visits WHERE patient_id='".$patient_id."' AND status='active'") or die(mysqli_error($db));

                while ($visits=mysqli_fetch_array($get_visits)) {
                  // $visit->visit_id=;
                  $visit->VisitInfo($visits['visit_id']);
                  ?>
                  <option value="<?php echo $visits['visit_id']; ?>" <?php if($invoice->visit_id==$visits['visit_id']){ echo 'selected'; } ?>><?php echo $visits['visit_date']; ?> - <?php echo $visit->major_complaint; ?></option>
                  <?php
                }
             ?>
          </select>
        </div>

      </div>
      <div class="col-md-3">
        <label for=""></label>
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


    <div class="card mb-5">
      <div class="card-body">
        <h6 class="font-weight-bold montserrat mb-3">Add Invoice Item</h6>
        <form id="invoice_item_frm" autocomplete="off">
          <input type="text" class="d-none" name="invoice_id"  value="<?php echo $invoice_id; ?>">
          <div class="row">
          <div class="col-md-3">
            <label for="">Description</label>
            <select class="custom-select browser-default" name="description" id="description">

              </select>
          </div>
          <div class="col-md-2">
            <label for="">Unit Cost</label>
            <input type="text" class="form-control" name="unit_cost" id="unit_cost" value="">
          </div>
          <div class="col-md-2">
            <label for="">Qty</label>
            <input type="text" class="form-control" name="qty" id="qty"  value="1" required>
          </div>
          <div class="col-md-2">
            <label for="">Total</label>
            <input type="text" class="form-control" name="total" id="total" value="" readonly>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary wide" style="margin-top:21px"><i class="fas fa-shopping-basket mr-2" aria-hidden></i> Add To Invoice</button>
          </div>
        </div>
        </form>
      </div>
    </div>


    <div class="card mb-5">
      <div class="card-body pt-5">
        <table class="table table-condensed">
          <thead class="grey lighten-4">
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Unit Cost</th>
              <th>Qty</th>
              <th class="text-right">Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $i=1;
              $get_invoice_items=mysqli_query($db,"SELECT * FROM invoice_items WHERE subscriber_id='".$active_subscriber."' && invoice_id='".$invoice_id."' && status='active'") or die(mysqli_error($db));
              while ($invoice_items=mysqli_fetch_array($get_invoice_items)) {

                ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $invoice_items['description']; ?></td>
                  <td><?php echo $invoice_items['unit_cost'] ?></td>
                  <td><?php echo $invoice_items['qty'] ?></td>
                  <td class="text-right"><?php echo $invoice_items['total'] ?></td>
                  <td class="text-right">
                    <button type="button" class="btn btn-white btn-sm remove" id="<?php echo $invoice_items['sn']; ?>" data-invoice_id="<?php echo $invoice_items['invoice_id']; ?>">Remove</button>
                  </td>
                </tr>
                <?php
              }
             ?>
             <tr>
               <td colspan="4" class="text-right">Sub - Total (GHS)</td>
               <td class="text-right"><?php echo $invoice_info['sub_total']; ?></td>
               <td class="text-right">

               </td>
             </tr>
             <tr>
               <td colspan="4" class="text-right">VAT (GHS)</td>
               <td class="text-right"><?php echo $invoice_info['vat_amount']; ?></td>
               <td class="text-right">

               </td>
             </tr>
             <tr class="font-weight-bold">
               <td colspan="4" class="text-right">Total (GHS)</td>
               <td class="text-right"><?php echo $invoice_info['total']; ?></td>
               <td class="text-right">

               </td>
             </tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php
  }
 ?>


</div>
<div id="modal_holder"></div>

</div>
</main>



<div id="new_invoice_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="new_invoice_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Create New Invoice</h6>
        <hr class="hr">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Customer ID</label>
              <input type="text" class="form-control input-sm" name="customer_id" value="<?php echo $customer_id; ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Invoice ID</label>
              <input type="text" class="form-control input-sm" name="invoice_id" value="<?php echo invoice_idgen(); ?>" readonly>
            </div>
          </div>
        </div>




        <div class="form-group">
          <label for="">Purchase Order #</label>
          <input type="text" class="form-control input-sm" name="purchase_order_number" value="" >
        </div>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Date Issued</label>
              <input type="text" class="form-control input-sm" name="date_issued" id="date_issued" value="<?php echo $today; ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Payment Due Date</label>
              <input type="text" class="form-control input-sm" name="due_date" id="due_date" value="<?php echo $today; ?>" required>
            </div>
          </div>
        </div>



        <div class="row">
          <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="">VAT</label>
              <select class="custom-select browser-default" name="vat_status" id="vat_status">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="">Tax Percentage</label>
              <input type="text" class="form-control input-sm" name="vat_percent" id="vat_percent" value="17.5" required>
            </div>
          </div>
        </div>


        <!-- <div class="form-group">
          <label for="">Receivables Account</label>
          <select class="custom-select browser-default" name="account_id" required>
            <?php
                // $get_accounts=mysqli_query($db,"
                // SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                // LEFT JOIN account_headers h on h.sn=a.account_header
                // WHERE h.sn=3 && subscriber_id='".$active_subscriber."';
                // ") or die('failed');
                // while ($accounts=mysqli_fetch_array($get_accounts)) {
                  ?>
                  <option value="<?php //echo $accounts['account_number']; ?>"><?php //echo $accounts['account_name']; ?></option>
                  <?php
                //}
             ?>
          </select>
        </div> -->




      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          Create Invoice
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

  $('#description').select2({
        placeholder: 'Select an item',
        ajax: {
            url: '../serverscripts/admin/Invoices/filter_services_and_drugs_select.php',
            dataType: 'json',
            delay: 100,
            data: function (data) {
                return {
                    searchTerm: data.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results:response
                };
            },
            cache: true
        }
    });

    $('#unit_cost').on('keyup', function(event) {
      event.preventDefault();
      var qty=$('#qty').val()
      if(qty==='' || qty===0){

      }else {
        var unit_cost=$('#unit_cost').val()
        var qty=$('#qty').val()
        $('#total').val((unit_cost*qty).toFixed(2))
      }
    });



      $('#qty').on('keyup', function(event) {
        event.preventDefault();
        var unit_cost=$('#unit_cost').val()
        if(unit_cost==='' || unit_cost===0){
          bootbox.alert("Unit cost cannot be empty",function(){
            $('#unit_cost').focus()
          })
        }else {
          var unit_cost=$('#unit_cost').val()
          var qty=$('#qty').val()
          $('#total').val((unit_cost*qty).toFixed(2))
        }
      });



      $('#vat_status').on('change', function(event) {
        event.preventDefault();
        var vat_status=$('#vat_status').val()
        if(vat_status=='no'){
          $('#vat_percent').prop('readonly', true)
          $('#vat_percent').prop('value', 'N/A')
        }
      });

      $('#invoice_item_frm').one('submit', function(event) {
        event.preventDefault();
        $.ajax({
          url: '../serverscripts/admin/Invoices/invoice_item_frm.php',
          type: 'GET',
          data:$('#invoice_item_frm').serialize(),
          success: function(msg){
            if(msg==='save_successful'){
              bootbox.alert("Invoice Item Added",function(){
                window.location.reload()
              })
            }
            else {
              bootbox.alert(msg)
            }//end if
          }//end success
        }) //end ajax
      }); //end submit

      $('.save_invoice').on('click', function(event) {
        event.preventDefault();
        var invoice_id=$(this).attr('ID');
        var income_account=$('#income_account').val()
        var receivables_account=$('#receivables_account').val()
        bootbox.confirm("Are you done creating this invoice?",function(r){
          if(r===true){
            $.get("../serverscripts/admin/Invoices/save_invoice.php?invoice_id="+invoice_id+"&income_account="+income_account+"&receivables_account="+receivables_account,function(msg){
              if(msg==='save_successful'){
                bootbox.alert("Invoice created successfully",function(){
                  window.location='invoices.php'
                })
              }
              else {
                bootbox.alert(msg)
              }
            })
          }
        })
      });


			$('#stock_value_print').on('click', function(event) {
				window.open("../serverscripts/admin/stock_value_print.php","_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")
			})

      $('.table tbody').on('click', '.remove', function(event) {
        event.preventDefault();
        var sn=$(this).attr('ID');
        var invoice_id=$(this).data('invoice_id');
        bootbox.confirm("Remove this from invoice?",function(r){
          if(r===true){
            $.get('../serverscripts/admin/Invoices/remove_invoice_item.php?sn='+sn+'&invoice_id='+invoice_id,function(msg){
              if(msg==='delete_successful'){
                window.location.reload()
              }else {
                bootbox.alert(msg)
              }
            })
          }
        })
      });


      $('#visit_id').on('change', function(event) {
        event.preventDefault();
        var visit_id=$(this).val();
        var invoice_id='<?php echo $invoice_id; ?>';
        $.get('../serverscripts/admin/Invoices/update_visit_id.php?visit_id='+visit_id+'&invoice_id='+invoice_id,function(msg){
          if(msg==='save_successful'){
            bootbox.alert('Visit updated',function(){
              window.location.reload()
            })
          }else {
            alert(msg)
          }
        })
      });




      $('#delete_invoice').on('click', function(event) {
        event.preventDefault();
        var invoice_id='<?php echo $invoice_id; ?>'
        bootbox.confirm("Proceed to delete this invoice?",function(r){
          if(r===true){
            $.get('../serverscripts/admin/Invoices/delete_invoice.php?invoice_id='+invoice_id,function(msg){
              if(msg==='delete_successful'){
                bootbox.alert('Invoice deleted successfully',function(){
                  window.location='all_invoices.php';
                })
              }else {
                bootbox.alert(msg)
              }
            })
          }
        })
      });



	</script>

</html>
