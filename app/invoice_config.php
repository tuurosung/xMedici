<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>


<?php
		$inv=new Invoice();
		$patient=new Patient();
 ?>
<?php //require_once '../serverscripts/Classes/Invoices.php'; ?>

<?php
    require_once '../serverscripts/Classes/Invoices.php';

    $inv=new Invoice();

    $inv->InvoiceConfigData('1234567890','12.5','2.5','2.5','GHS','Thank you for doing business with us');

    $inv->InvoiceConfigInfo();
 ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <div class="row mb-5">
      <div class="col-md-6">
          <h4 class="titles montserrat">Configure Invoice</h4>

      </div>
      <div class="col-md-6 text-right">
        <a href="invoices.php">
          <button type="button" class="btn btn-primary btn-rounded"> <i class="fas fa-arrow-left mr-3"></i>Return</button>
        </a>



      </div>
    </div>



    <section>
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <form id="update_config_frm">
            <div class="card-body">
              <h6>Configuration</h6>
              <hr class="hr">

              <div class="form-group">
                  <label for="">TIN Number</label>
                  <input type="text" class="form-control" name="tin_number" id="tin_number" value="<?php echo $inv->tin_number; ?>">
              </div>

              <div class="form-group">
                  <label for="">VAT Percent</label>
                  <input type="text" class="form-control" name="vat_percent" id="vat_percent" value="<?php echo $inv->config_vat_percent; ?>">
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="">NHIL Percent</label>
                      <input type="text" class="form-control" name="nhil_percent" id="nhil_percent" value="<?php echo $inv->config_nhil_percent; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="">GETFUND Percent</label>
                      <input type="text" class="form-control" name="getfund_percent" id="getfund_percent" value="<?php echo $inv->config_getfund_percent; ?>">
                  </div>
                </div>
              </div>


              <div class="form-group">
                  <label for="">Default Currency</label>
                  <select class="custom-select browser-default" name="currency" id="currency">
                      <option value="GHS" <?php if($inv->currency=='GHS'){echo 'selected'; } ?>>Ghana Cedi</option>
                      <option value="USD" <?php if($inv->currency=='USD'){echo 'selected'; } ?>>United States Dollar</option>
                  </select>
              </div>

              <button type="submit" class="btn btn-primary wide m-0">Update Config</button>

            </div>
            </form>
          </div>

          <div class="card mt-5">
            <div class="card-body">
              <h6  class="montserrat font-weight-bold">
                Terms & Conditions
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#new_term_modal"><i class="fas fa-plus mr-3"></i>Add</button>
              </h6>
              <hr class="hr">

              <?php
                $k=1;
                $get_terms=mysqli_query($db,"SELECT * FROM invoice_terms WHERE subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
                while ($terms=mysqli_fetch_array($get_terms)) {
                  ?>
                  <p>
                    <?php echo$k++.'. '. $terms['description']; ?>
                    <!-- <i class="fas fa-pen text-primary mr-2 cursor"></i> -->
                    <i class="fas fa-trash text-danger mr-2 cursor delete_term" id="<?php echo $terms['sn']; ?>"></i>
                  </p>
                  <?php
                }
               ?>
            </div>
          </div>


        </div>
        <div class="col-md-8">
          <div class="card" style="min-height:297mm">

            <div class="card-body">
                <div class="row mt-3">
                  <div class="col-md-6">
                    <h5 class="montserrat font-weight-bold"><?php echo $hospital->hospital_name; ?></h5>
                    <h6 class="montserrat font-weight-bold" style="font-size:12px"><?php echo $hospital->hospital_address; ?></h6>
                    <h6 class="montserrat font-weight-bold" style="font-size:12px"><?php echo $hospital->phone_number; ?></h6>

                  </div>
                  <div class="col-md-6 text-right">
                    <h3 class="montserrat mb-3">INVOICE</h3>



                  </div>
                </div>
                <p class="montserrat">Tax Identification Number (TIN): <span class="font-weight-bold"><?php echo $inv->tin_number; ?></span> </p>
                <hr class="hr">

                <div class="row mb-5">
                  <div class="col-md-6">
                    <h6 class="montserrat font-weight-bold" style="font-size:14px">SAMPLE CUSTOMER</h6>
                    <h6 class="montserrat" style="font-size:14px">BLK1234, AREA, CITY, GH</h6>
                    <h6 class="" style="font-size:14px"><i class="fas fa-mobile mr-3"></i>0123456789</h6>
                  </div>
                  <div class="col-md-6 ">
                    <div class="row">
                      <div class="col-md-8 text-right">
                          Invoice #
                      </div>
                      <div class="col-md-4 font-weight-bold">
                        INV-1234-21
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8 text-right">
                          Invoice Date
                      </div>
                      <div class="col-md-4 font-weight-bold">
                        01-03-2021
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8 text-right">
                          Due Date
                      </div>
                      <div class="col-md-4 font-weight-bold">
                        30-03-2021
                      </div>
                    </div>
                  </div>
                </div>

                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Qty</th>
                      <th>Description</th>
                      <th>Unit Price</th>
                      <th class="text-right">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    for ($i=1; $i <7 ; $i++) {
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td>Test Description</td>
                        <td class="text-right">2.00</td>
                        <td class="text-right"><?php echo number_format(2*$i,2); ?> </td>
                      </tr>
                      <?php
                      $sub_total+=(2*$i);
                    }

                     ?>
                     <tr>
                       <td colspan="3" class="text-right b-0"></td>
                       <td class="text-right"></td>
                     </tr>


                  </tbody>
                </table>

                <div class="row mb-5">
                  <div class="col-md-7">
                    <div class="p-2" style="border:1px solid #000; min-height:150px">
                      <h6 class="font-weight-bold montserrat m-0" style="font-size:11px">Terms & Conditions</h6>
                      <hr class="hr">
                      <?php
                          $j=1;
                          $get_terms=mysqli_query($db,"SELECT * FROM invoice_terms WHERE subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
                          while ($terms=mysqli_fetch_array($get_terms)) {
                            ?>
                              <p style="font-size:11px"><?php echo $j++. '. '. $terms['description']; ?></p>
                            <?php
                          }
                       ?>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <!-- Subtotal -->
                    <div class="row" style="font-size:11px">
                      <div class="col-md-6 text-right">
                          Sub - Total (<?php echo $inv->currency; ?>)
                      </div>
                      <div class="col-md-6 font-weight-bold text-right">
                        <?php echo number_format($sub_total,2); ?>
                      </div>
                    </div>

                    <!-- Taxes -->
                    <div class="row">
                      <div class="col-md-6 text-right">
                        VAT (<?php echo $inv->currency; ?>)
                      </div>
                      <div class="col-md-6 text-right">
                        0.00
                      </div>
                    </div>

                    <!-- Total -->
                    <div class="row">
                      <div class="col-md-6 font-weight-bold text-right">
                        TOTAL (<?php echo $inv->currency; ?>)
                      </div>
                      <div class="col-md-6 text-right font-weight-bold">
                        <?php echo number_format($sub_total,2); ?>
                      </div>
                    </div>

                    <section class="" style="margin-top:7rem">
                      <hr class="mt-5" style="border-top:dashed 1px #000; width:50%">
                      <p class="m-0 font-weight-bold text-uppercase text-center" style="font-size:11px">Created By</p>
                    </section>
                  </div>
                </div>


                <p class="text-italic text-center font-weight-bold montserrat" style="font-size:13px"><?php echo $inv->tagline; ?></p>

                <hr style="border-top:dashed 1px #000; width:50%" class="mt-4" >

            </div>
          </div>
        </div>
      </div>
    </section>











</div>
<div id="modal_holder"></div>

</div>
</main>




<div id="new_term_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="new_term_frm">
        <div class="modal-body">
          <h6 class="montserrat font-weight-bold">New Term / Condition</h6>
          <hr class="hr">

          <div class="form-group">
            <label for="">Term Or Condition</label>
            <input type="text" class="form-control" name="description" value="" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus mr-3"></i>
            Create Term
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

    $('#new_term_frm').on('submit', function(event) {
      event.preventDefault();
      $.ajax({
        url: '../serverscripts/admin/Invoices/create_term_frm.php',
        type: 'GET',
        data:$('#new_term_frm').serialize(),
        success:function(msg){
          if(msg==='save_successful'){
            bootbox.alert("Terms updated",function(){
              window.location.reload()
            })
          }else {
            bootbox.alert(msg)
          }
        }
      })
    });

    $('.delete_term').on('click', function(event) {
      event.preventDefault();
      var sn=$(this).attr('ID');
      bootbox.confirm("Do you want to delete this term/condition?",function(r){
        if(r===true){
          $.get('../serverscripts/admin/Invoices/delete_term.php?sn='+sn,function(msg){
            if(msg==='delete_successful'){
              bootbox.alert("Term deleted successfully",function(){
                window.location.reload()
              })
            }else {
              bootbox.alert(msg)
            }
          })
        }
      })
    });


    $('#update_config_frm').on('submit', function(event) {
      event.preventDefault();
      bootbox.confirm('Update configuration information?',function(r){
        if(r===true){
          $.ajax({
            url: '../serverscripts/admin/Invoices/update_config_frm.php',
            type: 'GET',
            data:$('#update_config_frm').serialize(),
            success:function(msg){
              if(msg==='update_successful'){
                bootbox.alert('Configuration updated successfully',function(){
                  window.location.reload()
                })
              }else {
                bootbox.alert(msg)
              }
            }
          })
        }
      })
    });
	</script>

</html>
