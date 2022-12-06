<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <div class="row mb-5">
      <div class="col-md-6">
        <h4 class="titles montserrat">Stock Management</h4>
      </div>
      <div class="col-md-6 text-right">
        <div class="dropdown open">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Print Options
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <a class="dropdown-item print" href="#" id="print">Print Stock Levels</a>
            <a class="dropdown-item print_stock_levels" href="#" id="print_stock_levels">Print Stock Values</a>
          </div>
        </div>
      </div>
    </div>



    <div class="row">
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Actual Stock
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format(stock_value(),2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Expected Stock
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format(expected_stock_value(),2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Profit Margin
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format(profit_margin(),2); ?></p>
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
      <table class="table datatables table-condensed" data-search='true'>
        <thead class="">
          <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Status</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Stock Val</th>
            <th class="text-right">Expected Val</th>
            <th class="text-right">Profit</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          // require_once '../dbcon.php';
          $get_items=mysqli_query($db,"SELECT
                                                                  drug_id, drug_name,remaining_stock,stock_status,cost_price,retail_price,restock_level

                                                                  FROM inventory WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY drug_name
                                                  ")  or die(mysqli_error($db));
          $i=1;
          while ($rows=mysqli_fetch_array($get_items)) {

            ?>

            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $rows['drug_name']; ?> <?php if($rows['stock_status']=='OUT-OF-STOCK'){ echo '<span class="badge badge-danger">Restock</span>'; } ?></td>
              <td><?php echo $rows['stock_status']; ?></td>
              <td class="text-center"><?php  echo $rows['remaining_stock']; ?></td>
              <td class="text-right"><?php  echo number_format($rows['cost_price'] * $rows['remaining_stock'],2); ?></td>
              <td class="text-right"><?php  echo number_format($rows['retail_price'] * $rows['remaining_stock'],2); ?></td>
              <td class="text-right"><?php  echo number_format(($rows['retail_price']-$rows['cost_price']) * $rows['remaining_stock'],2); ?></td>
              <td class="text-right">
                <div class="dropdown open">
                  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                  </button>
                  <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
                    <ul class="list-group">
                      <li class="list-group-item add_stock" id="<?php echo $rows['drug_id']; ?>">Add Stock</li>
                      <a href="drug_matrix.php?drug_id=<?php echo $rows['drug_id']; ?>"><li class="list-group-item ">Matrix</li></a>
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
<div id="modal_holder"></div>

</div>
</main>










</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

          $('.sidebar-fixed .list-group-item').removeClass('active')
          $('#inventory_nav').addClass('active')
          $('#inventory_submenu').addClass('show')
          $('#stock_li').addClass('font-weight-bold')

          $('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_stock.php');
					});

          $('#print_stock_levels').on('click', function(event) {
						event.preventDefault();
						print_popup('print_stock_values.php');
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
