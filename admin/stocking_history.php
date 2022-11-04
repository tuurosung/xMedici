<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <div class="row mb-5">
      <div class="col-md-6">
        <h4 class="titles">Recently Stocked Items</h4>
      </div>
      <div class="col-md-6 text-right">
          <button type="button" class="btn btn-primary"><i class="fas fa-print mr-3"></i> Print</button>
      </div>
    </div>



    <div class="mt-5">
      <table class="table datatables table-condensed" data-search='true'>
        <thead class="">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Time</th>
            <th>Drug Name</th>
            <th>Batch Code</th>
            <th class="text-center">Qty</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          // require_once '../dbcon.php';
          $get_items=mysqli_query($db,"SELECT * FROM stock WHERE  subscriber_id='".$active_subscriber."' && stock_date BETWEEN '".$week_start."' AND '".$week_end."' ORDER BY sn desc")  or die('failed');
          $i=1;
          while ($rows=mysqli_fetch_array($get_items)) {
              $drug_info=drug_info($rows['drug_id']);
            ?>

            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $rows['stock_date']; ?></td>
              <td><?php echo date('H:i:s',$rows['timestamp']); ?></td>
              <td><?php echo $drug_info['drug_name']; ?> / <?php echo $drug_info['unit']; ?></td>
              <td><?php echo $rows['batch_code']; ?></td>
              <td class="text-center"><?php echo $rows['qty_stocked']; ?></td>
              <td class="text-right">
                <div class="dropdown open">
                  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Options
                  </button>
                  <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
                    <ul class="list-group">
                      <li class="list-group-item" id="<?php echo $rows['drug_id']; ?>">Reduce Stock</li>
                      <li class="list-group-item" id="<?php echo $rows['drug_id']; ?>">Record Damages</li>
                      <li class="list-group-item" id="<?php echo $rows['drug_id']; ?>">Delete</li>
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
