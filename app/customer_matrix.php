<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
  $customer_id=clean_string($_GET['customer_id']);
  $customer_info=customer_info($customer_id);
 ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <div class="row mb-5">
      <div class="col-md-6">
          <h4 class="titles montserrat">Customer Matrix </h4>
          <h6><?php echo $customer_info['customer_name']; ?></h6>
      </div>
      <div class="col-md-6 text-right">

        <div class="btn-group">
            <button type="button" class="btn btn-primary">Create New</button>
            <button type="button" class="btn btn-primary dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#new_invoice_modal"><i class="fas fa-file mr-3"></i> Invoice</a>
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#new_invoice_modal"><i class="fas fa-credit-card mr-3"></i>Payment</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#"><i class="fas fa-print mr-3"></i>Print Statement</a>
              <a class="dropdown-item" href="customers.php"><i class="fas fa-arrow-left mr-3"></i>Return</a>
            </div>
          </div>
      </div>
    </div>





    <div class="row">
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Total Supply Cost
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format(drug_stock_value($drug_id),2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Amount Paid
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format(drug_expected_value($drug_id),2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Outstanding Balance
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format(drug_expected_value($drug_id)-drug_stock_value($drug_id),2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <!-- <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Total Payments
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS </p>
          </div>
        </div> -->
      </div>
    </div>

    <div class="mt-5">

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="card">
            <div class="card-body p-0">
              <ul class="list-group">
                <li class="list-group-item">Invoices</li>
                <li class="list-group-item">Payments</li>
                <li class="list-group-item">Account History</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-9">
          <div class="card">
            <div class="card-body">

              <h6 class="font-weight-bold montserrat">Invoices</h6>
              <hr class="hr">
              <table class="table datatables table-condensed" data-search='true'>
                <thead class="">
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Invoice ID</th>
                    <th>P/0 #</th>
                    <th class="text-right">Cost</th>
                    <th class="text-right">VAT</th>
                    <th class="text-right">Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // require_once '../dbcon.php';
                  $get_items=mysqli_query($db,"SELECT * FROM invoices WHERE customer_id='".$customer_id."' &&  subscriber_id='".$active_subscriber."'")  or die('failed');
                  $i=1;
                  while ($rows=mysqli_fetch_array($get_items)) {

                    ?>

                    <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $rows['date_issued']; ?></td>
                      <td><?php echo $rows['invoice_id']; ?></td>
                      <td><?php echo $rows['purchase_order_number']; ?></td>
                      <td class="text-right"><?php echo $rows['supply_cost']; ?></td>
                      <td class="text-right"><?php echo $rows['vat_amount']; ?></td>
                      <td class="text-right"><?php echo $rows['invoice_value']; ?></td>
                      <td class="text-right">
                        <div class="dropdown open">
                          <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                          </button>
                          <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
                            <ul class="list-group">
                              <li class="list-group-item"><a href="invoice_prepare.php?invoice_id=<?php echo $rows['invoice_id']; ?>">Modify</a></li>
                              <li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Delete</li>
                              <li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Print</li>
                            </ul>
                          </div>
                        </div>

                      </td>

                    </tr>

                    <?php
                  }
                  ?>

                </tbody>
              </table>


            </div>
          </div>
        </div>
      </div>

    </div>


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


<div id="new_payment_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="new_invoice_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">New Payment</h6>
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
              <input type="text" class="form-control input-sm" name="invoice_id" id="invoice_id" value="<?php echo invoice_idgen(); ?>" readonly>
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

      $('#add_stock_modal').on('shown.bs.modal', function(event) {
        event.preventDefault();
        $('#qty_stocked').focus()
        $('#expiry_date').datepicker()
      });

      $('#new_invoice_modal').on('shown.bs.modal', function(event) {
        event.preventDefault();
        $('#date_issued,#due_date').datepicker()
      });

      $('#date_issued,#due_date').on('change', function(event) {
        event.preventDefault();
        $(this).datepicker('hide')
      });

      $('#vat_status').on('change', function(event) {
        event.preventDefault();
        var vat_status=$('#vat_status').val()
        if(vat_status=='no'){
          $('#vat_percent').prop('readonly', true)
          $('#vat_percent').prop('value', 'N/A')
        }
      });

      $('#new_invoice_frm').one('submit', function(event) {
        event.preventDefault();
        var invoice_id=$('#invoice_id').val()
        bootbox.confirm("Create new invoice?",function(r){
          if(r===true){
            $.ajax({
              url: '../serverscripts/admin/new_invoice_frm.php',
              type: 'GET',
              data:$('#new_invoice_frm').serialize(),
              success: function(msg){
                if(msg==='save_successful'){
                  bootbox.alert("Invoice created successfully. Proceed to add items",function(){
                    window.location='invoice_prepare.php?invoice_id='+invoice_id
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


      $('.table tbody').on('click', '.delete_stock', function(event) {
        event.preventDefault();
        var drug_id=$(this).data('drug_id')
        var batch_code=$(this).data('batch_code')
        bootbox.confirm("Do you want to delete this quantity",function(r){
          if(r===true){
            $.ajax({
              url: '../serverscripts/admin/delete_stocking_history.php?drug_id='+drug_id+'&batch_code='+batch_code,
              type: 'GET',
              success:function(msg){
                if(msg==='delete_successful'){
                  bootbox.alert("Stock deleted successfully",function(){
                    window.location.reload()
                  })
                }
                else {
                  bootbox.alert(msg)
                }
              }
            })//end ajax
          }
        })
      });


          $('.table tbody').on('click', '.add_stock', function(event) {
            event.preventDefault();
             var drug_id=$(this).attr('ID')
             $.ajax({
                url: '../serverscripts/admin/add_stock_modal.php?drug_id='+drug_id,
                type: 'GET',
                success	: function(msg){

                  $('#modal_holder').html(msg)
                  $('#add_stock_modal').modal('show')

                  $('#add_stock_modal').on('shown.bs.modal', function(event) {
                    event.preventDefault();
                    $('#stocking_date, #expiry_date').datepicker()
                    $('#qty_stocked').focus()
                  });//end modal shown

                  $('#add_stock_frm').on('submit', function(event) {
                    event.preventDefault();
                    bootbox.confirm("Update stock quantity?",function(r){
                      if(r===true){
                        $.ajax({
                          url: '../serverscripts/admin/add_stock_frm.php',
                          type: 'GET',
                          data:$('#add_stock_frm').serialize(),
                          success: function(msg){
                            if(msg==='save_successful'){
                              bootbox.alert("Item stocked successfully",function(){
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



                }
              })
          });//end add stock click




				  $('#recent_stocking_btn').on('click', function(event) {
				  	event.preventDefault();
				  	all_items();
				  });

				  $('#stockout_btn').on('click', function(event) {
				  	event.preventDefault();
				  	stockout_items();
				  });

				  $('#damages_btn').on('click', function(event) {
				  	event.preventDefault();
				  	damaged_items();
				  });

					$('#restock_alert_btn').on('click', function(event) {
						event.preventDefault();
						$.ajax({
							url: '../serverscripts/admin/restock_alert.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)
								$('.datatables').DataTable({
									'sort':false
								})
								$('#tables_search').focus()
							}
						})
					});//end restock function

					$('#stock_value').on('click', function(event) {
						event.preventDefault();
						//window.open("../serverscripts/admin/stock_value.php","_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")

						$.ajax({
							url: '../serverscripts/admin/stock_value.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)
								$('.datatables').DataTable({
									'sort':false
								})
								$('#tables_search').focus()
							}
						})
					});//end restock function

					$('#stock_value_print').on('click', function(event) {
						window.open("../serverscripts/admin/stock_value_print.php","_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")
					})


					$('#received_stock').on('click', function(event) {
						event.preventDefault();
						$.ajax({
							url: '../serverscripts/admin/received_stock.php',
							type: 'GET',
							success:function(msg){
								$('#data_grid').html(msg)
								$('.table').DataTable({
									'paging':false,
									'sort':false
								})
								$('.table tbody').on('click', '.print_btn', function(event) {
									event.preventDefault();
									var con=confirm('Print this report?')
									if(con===true){
										var stock_date=$(this).attr('ID')
										window.open("../serverscripts/admin/received_stock_print.php?stock_date="+stock_date,"_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")
									}
								});
							}
						})
					});





					function batchcode_gen(){
						var text = "";
            var possible = "123456789";
            for( var i=0; i < 15; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            $('#batch_code').val(text)
					}


					function all_items(){
						$.ajax({
							url: '../serverscripts/admin/stock_items.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)

								$('.table').bootstrapTable()




								$('.datatables tbody').on('click', '.stockout', function(event) {
									event.preventDefault();
									 var item_code=$(this).attr('ID')


									 $.ajax({
	 										url: '../serverscripts/admin/stockout_frm.php?item_code='+item_code,
	 										type: 'GET',
	 										success	: function(msg){
												$('#data_grid').html(msg)

												$('#stockout_date').datepicker({
													dateFormat: 'yy-mm-dd',
													changeMonth: true,
													changeYear:true
												})


												$('.return').on('click', function(event) {
													event.preventDefault();
													all_items();
												});

												$('#stockout_frm').on('submit', function(event) {
													event.preventDefault();

													var data=$(this).serialize()
													$.ajax({
														url: '../serverscripts/admin/save_stockout.php',
														type: 'GET',
														data:data,
														success: function(msg){
															if(msg==='save_successful'){
																alert("Stock withdrawn successfully")
															}
															else {
																alert(msg)
															}
														}
													})


												});
											}
	 									})
								});//end stockout click


								$('.datatables tbody').on('click', '.damaged', function(event) {
									event.preventDefault();
									 var item_code=$(this).attr('ID')


									 $.ajax({
	 										url: '../serverscripts/admin/damaged_stock_frm.php?item_code='+item_code,
	 										type: 'GET',
	 										success	: function(msg){
												$('#data_grid').html(msg)

												$('#damage_date').datepicker({
													dateFormat: 'yy-mm-dd',
													changeMonth: true,
													changeYear:true
												})

												$('.return').on('click', function(event) {
													event.preventDefault();
													all_items();
												});

												$('#damaged_stock_frm').on('submit', function(event) {
													event.preventDefault();

													var data=$(this).serialize()
													$.ajax({
														url: '../serverscripts/admin/save_damages.php',
														type: 'GET',
														data:data,
														success: function(msg){
															if(msg==='save_successful'){
																alert("Damages recorded successfully")
															}
															else {
																alert(msg)
															}
														}
													})


												});
											}
	 									})
								});//end damages click


								$('.datatables tbody').on('click', '.matrix', function(event) {
									event.preventDefault();

									var drug_id=$(this).attr('ID')

									$.ajax({
										url: '../serverscripts/admin/drug_matrix.php?drug_id='+drug_id,
										type: 'GET',
										success: function(msg){
											$('#modal_holder').html(msg)
											$('#drug_matrix_modal').modal('show')


											$('.delete_stocking').on('click', function(event) {
												var con=confirm('Do you really want to delete this record?')

												if(con===true){
													var sn=$(this).attr('ID')
													$.ajax({
														url: '../serverscripts/admin/delete_stocking_record.php?sn='+sn,
														type: 'GET',
														success: function(msg){
															if(msg==='delete_successful'){
																alert('Record deleted successfully')
																actual_stock_value()
																expected_stock_value()
																all_items();
															}
															else {
																alert(msg)
															}
														}
													})
												}
												else {

												}
											})

											$('.return').on('click', function(event) {
												event.preventDefault();
												all_items();
											});
										}
									})


								});


							}
						})

					}//end function

					function stockout_items(){
						$.ajax({
							url: '../serverscripts/admin/stockout_items.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)
								$('.datatables').DataTable({
									'sort':false
								})
							}//end success
						})
					}//end function stockout_items


					function damaged_items(){
						$.ajax({
							url: '../serverscripts/admin/damaged_items.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)
								$('.datatables').DataTable({
									'sort':false
								})
							}//end success
						})
					}//end function stockout_items

					actual_stock_value()
					function actual_stock_value(){
						$.ajax({
							url: '../serverscripts/admin/actual_stock_value.php',
							type: 'GET',
							success: function(msg){
								$('#actual_stock_value').html(msg)
							}//end success
						})
					}

					expected_stock_value()
					function expected_stock_value(){
						$.ajax({
							url: '../serverscripts/admin/expected_stock_value.php',
							type: 'GET',
							success: function(msg){
								$('#expected_stock_value').html(msg)
							}//end success
						})
					}

					gross_profit()
					function gross_profit(){
						$.ajax({
							url: '../serverscripts/admin/gross_profit.php',
							type: 'GET',
							success: function(msg){
								$('#gross_profit').html(msg)
							}//end success
						})
					}



	</script>

</html>
