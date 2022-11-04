<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
  if(isset($_SESSION['active_drug']) && $_SESSION['active_drug'] !=''){
    $drug_id=clean_string($_SESSION['active_drug']);
  }elseif ($_GET['drug_id'] !='') {
    $drug_id=clean_string($_GET['drug_id']);
  }
  // $drug_id=clean_string($_GET['drug_id']);
  if($drug_id==''){
    header('location:inventory.php');
  }

  $drug=new Drug();
  $drug->drug_id=$drug_id;
  $drug->DrugInfo();
 ?>

<main class="py-3 mx-lg-5">
  <div class="container-fluid mt-2">

    <div class="row mb-5">
      <div class="col-md-4">
          <h4 class="titles montserrat">Drug Matrix </h4>
          <h6><?php echo $drug->drug_name; ?></h6>
      </div>
      <div class="col-md-8 text-right">
        <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal" data-target="#add_stock_modal"><i class="fas fa-plus mr-3"></i> New Stock</button>
        <div class="btn-group  mr-2">
            <button type="button" class="btn btn-primary">Stock Options</button>
            <button type="button" class="btn btn-primary dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu b-0 p-0">
              <ul class="list-group">
                <li class="list-group-item" data-toggle="modal" data-target="#reducestock_modal"><i class="fas fa-minus-circle mr-2" aria-hidden></i> Reduce Stock</li>
                <!-- <li class="list-group-item">Record Damages</li> -->
                <li class="list-group-item"><i class="fas fa-print mr-2" aria-hidden></i> Print Stocking Report</li>
              </ul>
            </div>
          </div>
        <div class="btn-group ">
            <button type="button" class="btn warning-color-dark">Drug Actions</button>
            <button type="button" class="btn warning-color-dark dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu b-0 p-0">
            <ul class="list-group">
              <li class="list-group-item" data-toggle="modal" data-target="#edit_drug_modal"><i class="fas fa-pencil-alt mr-2 text-info" aria-hidden></i> Edit Drug</li>
              <li class="list-group-item" id="delete_drug"><i class="fas fa-trash-alt text-danger mr-2 " aria-hidden></i> Delete Drug</li>
            </ul>
            </div>
          </div>
      </div>
    </div>





    <div class="row mb-5">
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Quantity Remaining
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px"><?php echo $drug->qty_rem; ?> Units</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Actual Stock
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format($drug->actual_stock_value,2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Expected Stock
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format($drug->expected_stock_value,2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-3">
          <div class="card-body pt-3 pb-3">
            Profit Margin
            <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format($drug->profit_margin,2); ?></p>
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

    <!-- Pills navs -->
    <ul class="nav nav-pills xmedici_pills3   mb-5" id="ex1" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active"   id="ex1-tab-1"   data-toggle="pill" href="#ex1-pills-1" role="tab" aria-controls="ex1-pills-1"
          aria-selected="true">
          Stocking History</a>
      </li>
      <li class="nav-item" role="presentation">
        <a  class="nav-link" id="ex1-tab-2"    data-toggle="pill"  href="#ex1-pills-2" role="tab" aria-controls="ex1-pills-2"
          aria-selected="false">
          Dispensing History
          </a>
      </li>
      <li class="nav-item" role="presentation">
        <a  class="nav-link"  id="ex1-tab-3" data-toggle="pill"  href="#ex1-pills-3" role="tab" aria-controls="ex1-pills-3"
          aria-selected="false">
          Stock Reduction History
          </a>
      </li>
    </ul>
    <!-- Pills navs -->

    <!-- Pills content -->
    <div class="tab-content" id="ex1-content">
      <div   class="tab-pane fade show active" id="ex1-pills-1" role="tabpanel" aria-labelledby="ex1-tab-1">

        <div class="card" style="height:600px">
          <div class="card-body py-4">

            <h5 class="montserrat font-weight-bold mb-5">Stocking History</h5>

              <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Expiry</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th class="text-right">Qty Stocked</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // require_once '../dbcon.php';
                  $get_items=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$drug_id."' AND  subscriber_id='".$active_subscriber."'")  or die('failed');
                  $i=1;
                  $total_stocked=0;
                  while ($rows=mysqli_fetch_array($get_items)) {

                    $drug->drug_id=$rows['drug_id'];
                    $drug->DrugInfo();

                    ?>
                    <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $rows['stock_date']; ?></td>
                      <td><?php echo $rows['expiry_date']; ?></td>
                      <td><?php echo date('H:i:s',$rows['timestamp']); ?></td>
                      <td class="
                        <?php
                          if($rows['status']=='deleted'){
                            echo 'text-danger';
                          }
                         ?>"
                      >
                        <?php echo $rows['status']; ?>
                      </td>
                      <td class="text-right"><?php echo (int) $rows['qty_stocked']; ?></td>

                      <td class="text-right">
                         <div class="dropdown open">
                           <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             Option
                           </button>
                           <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
                             <ul class="list-group">
                               <li class="list-group-item"><i class="fas fa-pencil-alt mr-2" aria-hidden></i> Edit</li>
                               <li class="list-group-item delete_stock" data-drug_id="<?php echo $rows['drug_id']; ?>" data-batch_code="<?php echo $rows['batch_code']; ?>"><i class="fas fa-trash-alt mr-2"></i> Delete</li>
                             </ul>
                           </div>
                         </div>
                      </td>
                    </tr>
                    <?php
                      if($rows['status']=='active'){
                        $total_stocked+=$rows['qty_stocked'];
                      }

                  }
                  ?>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo $total_stocked; ?></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>


          </div>
        </div>

      </div>
      <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">

          <div class="card">
            <div class="card-body py-4">

              <h5 class="montserrat font-weight-bold mb-5">Dispensing History</h5>

              <table class="table table-condensed ">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th class="text-right">Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $total_sold=0;
                    $query=mysqli_query($db,"SELECT * FROM (

                                                                SELECT date , SUM(qty) as qty_sold
                                                                FROM pharm_cart
                                                                WHERE drug_id='".$drug_id."' AND subscriber_id='".$active_subscriber."' AND status='CHECKOUT' GROUP BY date

                                                                UNION

                                                                SELECT date , SUM(qty) as qty_sold
                                                                FROM pharm_walkin_cart
                                                                WHERE drug_id='".$drug_id."' AND subscriber_id='".$active_subscriber."' AND checkout_status='CHECKOUT' GROUP BY date

                                                        )r ORDER BY date desc
                    ") or die(mysqli_error($db));
                    while ($rows=mysqli_fetch_array($query)) {
                      ?>
                      <tr>
                        <td><?php echo $rows['date']; ?></td>
                        <td class="text-right"><?php echo $rows['qty_sold']; ?></td>
                      </tr>
                      <?php
                      $total_sold+=$rows['qty_sold'];
                    }
                   ?>
                   <tr>
                     <td></td>
                     <td class="text-right"><?php echo $total_sold; ?></td>
                   </tr>
                </tbody>
              </table>

            </div>
          </div>

      </div>
      <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">

        <div class="card">
          <div class="card-body">

            <h5 class="montserrat font-weight-bold mb-5">Stock Reduction History</h5>

            <table class="table table-condensed ">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th class="">Narration</th>
                  <th class="text-right">Quantity</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i=1;
                  $total_sold=0;
                  $query=mysqli_query($db,"SELECT * FROM pharm_reducestock ORDER BY date desc
                  ") or die(mysqli_error($db));
                  while ($rows=mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $rows['date']; ?></td>
                      <td class=""><?php echo $rows['narration']; ?></td>
                      <td class="text-right"><?php echo $rows['qty']; ?></td>

                    </tr>
                    <?php
                    $total+=$rows['qty'];
                  }
                 ?>
                 <tr>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td class="text-right" style="font-size:25px; font-weight:600"><?php echo $total; ?></td>
                 </tr>
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
    <!-- Pills content -->










          </tbody>
        </table>

      </div>
    </div>


</div>
<div id="modal_holder"></div>

</div>
</main>



<div id="add_stock_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="add_stock_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Add Stock Form</h6>
        <hr class="hr">

        <div class="form-group d-none">
          <label for="">Drug Id</label>
          <input type="text" class="form-control input-sm" name="drug_id" value="<?php echo $drug_id; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Drug Name</label>
          <input type="text" class="form-control input-sm" name="drug_name" value="<?php echo $drug->drug_name; ?>" readonly>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Batch Code</label>
              <input type="text" class="form-control input-sm" name="batch_code" value="<?php echo time(); ?>" readonly required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Stocking Date</label>
              <input type="text" class="form-control" name="stocking_date" id="stocking_date" value="<?php echo date('Y-m-d'); ?>" required autocomplete="off">
            </div>
          </div>
        </div>






        <div class="row" style="margin-bottom:15px">
          <div class="col-md-6 col-xs-6">
            <label for="">Qty Stocked</label>
            <input type="text" class="form-control input-sm" name="qty_stocked" id="qty_stocked"required autocomplete="off" required>
          </div>
          <div class="col-md-6 col-xs-6">
            <label for="">Expiry Date</label>
            <input type="text" class="form-control" name="expiry_date" id="expiry_date">
          </div>
        </div>

      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          Add Stock
        </button>
      </div>
      </form>
    </div>
  </div>
</div>

<div id="edit_drug_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:900px">
    <div class="modal-content">
			<form id="edit_drug_frm">
      <div class="modal-body">
					<h6>Edit Drug Information | <?php echo $drug->drug_name; ?></h6>
					<hr class="hr">

          <div class="form-group d-none">
            <label for="">Drug ID</label>
            <input type="text" name="drug_id" class="form-control" value="<?php echo $drug_id; ?>">
          </div>

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Unit</label>
								<select class="browser-default custom-select" name="unit">
                  <?php
                    $get_units=mysqli_query($db,"SELECT * FROM drug_units") or die(mysqli_error($db));
                    while ($units=mysqli_fetch_array($get_units)) {
                      ?>
                      <option value="<?php echo $units['code'] ?>" <?php if($drug->unit==$units['code']){ echo 'selected'; } ?>><?php echo $units['description']; ?></option>
                      <?php
                    }
                   ?>

					    	</select>
							</div>
					  </div>
					</div>


					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Generic Name</label>
								<input type="text" class="form-control" id="generic_name" name="generic_name" required="required" value="<?php echo $drug->generic_name; ?>">
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Brand Name (Trade Name)</label>
							  <input type="text" class="form-control" id="trade_name" name="trade_name" required="required" autocomplete="off"  value="<?php echo $drug->trade_name; ?>">
							</div>
					  </div>
					</div>



					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Manufacturer</label>
							  <select class="browser-default custom-select" id="manufacturer" name="manufacturer">
									<?php
										// require
										$query=mysqli_query($db,"SELECT * FROM pharm_manufacturers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
										while ($manufacturers=mysqli_fetch_array($query)) {
											?>
											<option value="<?php echo $manufacturers['manufacturer_id']; ?>" ><?php echo $manufacturers['name']; ?></option>
											<?php
										}
									 ?>

								</select>
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Category</label>
								<select class="browser-default custom-select" id="category" name="drug_category">
									<?php
										// require
										$query=mysqli_query($db,"SELECT * FROM pharm_drug_category ORDER BY category_name") or die(mysqli_error($db));
										while ($rows=mysqli_fetch_array($query)) {
											?>
											<option value="<?php echo $rows['category_id']; ?>"><?php echo $rows['category_name']; ?></option>
											<?php
										}
									 ?>

								</select>
							</div>
					  </div>
					</div>

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Shelf</label>
							  <input type="text" class="form-control" id="shelf" name="shelf"  value="<?php echo $drug->shelf; ?>">
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Restock-Limit</label>
								<input type="text" class="form-control" id="restock_level" name="restock_level" autocomplete="off"  value="<?php echo $drug->restock_level; ?>">
							</div>
					  </div>
					</div>


					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Cost Price</label>
								<input type="text" class="form-control" id="cost_price" name="cost_price" required="required" autocomplete="off"  value="<?php echo $drug->cost_price; ?>">
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Retail Price</label>
							  <input type="text" class="form-control" id="retail_price" name="retail_price" required="required" autocomplete="off"  value="<?php echo $drug->retail_price; ?>">
							</div>
					  </div>
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-black">Edit Drug</button>
      </div>
			</form>
    </div>
  </div>
</div>

<div id="reducestock_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="reducestock_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Reduce Stock Form</h6>
        <hr class="hr">

        <div class="form-group d-none">
          <label for="">Drug Id</label>
          <input type="text" class="form-control input-sm" name="drug_id" value="<?php echo $drug_id; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="">Drug Name</label>
          <input type="text" class="form-control input-sm" name="drug_name" value="<?php echo $drug->drug_name; ?>" readonly>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Qty Stocked</label>
              <input type="text" class="form-control input-sm" name="qty" id=""required autocomplete="off" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Stocking Date</label>
              <input type="text" class="form-control" name="date" id="" value="<?php echo date('Y-m-d'); ?>" required autocomplete="off">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="">Narration</label>
          <textarea class="form-control" name="narration"></textarea>
        </div>



      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          <i class="fas fa-minus-circle mr-2" aria-hidden></i>
          Reduce Stock
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

  $('#pharmacy_nav').addClass('active')
  $('#pharmacy_submenu').addClass('show')
  $('#inventory_li').addClass('font-weight-bold')

  $('#delete_drug').on('click', function(event) {
    event.preventDefault();
    bootbox.confirm("Delete this drug with all its records?",function(r){
      if(r===true){
        var drug_id="<?php echo $drug_id; ?>"
        $.get('../serverscripts/admin/Drugs/inventory_delete.php?drug_id='+drug_id,function(msg){
          if(msg==='delete_successful'){
            bootbox.alert("Drug deleted successfully",function(){
              window.location='inventory.php';
            })
          }else {
            bootbox.alert(msg)
          }///end if
        })
      }//end if
    })
  });


  $('#edit_drug_frm').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: '../serverscripts/admin/Drugs/edit_drug_frm.php',
      type: 'GET',
      data: $('#edit_drug_frm').serialize(),
      success:function(msg){
        if(msg==='update_successful'){
          bootbox.alert("Drug information updated successfully",function(){
            window.location.reload()
          })
        }else {
          bootbox.alert(msg)
        }
      }
    })
  });

  $('#add_stock_modal').on('shown.bs.modal', function(event) {
    event.preventDefault();
    $('#qty_stocked').focus()
    $('#expiry_date,#stocking_date').datepicker()
  });

  $('#expiry_date').on('change', function(event) {
    event.preventDefault();
    $(this).datepicker('hide')
  });

  $('#add_stock_frm').on('submit', function(event) {
        event.preventDefault();
        bootbox.confirm("Update stock quantity?",function(r){
          if(r===true){
            $.ajax({
              url: '../serverscripts/admin/Drugs/add_stock_frm.php',
              type: 'GET',
              data:$('#add_stock_frm').serialize(),
              success: function(msg){
                if(msg==='save_successful'){
                  bootbox.alert("Item stocked successfully",function(){
                    window.location.reload();
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
        bootbox.confirm("Do you want to delete this stock",function(r){
          if(r===true){
            $.ajax({
              url: '../serverscripts/admin/Drugs/delete_stocking_history.php?drug_id='+drug_id+'&batch_code='+batch_code,
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


    $('#reducestock_frm').on('submit', function(event) {
      event.preventDefault();
      bootbox.confirm("Reduce quantity of drug?",function(r){
        if(r===true){
          $.ajax({
            url: '../serverscripts/admin/Drugs/reducestock_frm.php',
            type: 'GET',
            data:$('#reducestock_frm').serialize(),
            success: function(msg){
              if(msg==='save_successful'){
                bootbox.alert("Stocked Reduction successful",function(){
                  window.location.reload();
                })
              }else {
                bootbox.alert(msg)
              }//end if
            }//end success
          }) //end ajax
        }
      })

    }); //end submit




					$('#stock_value_print').on('click', function(event) {
						window.open("../serverscripts/admin/stock_value_print.php","_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")
					})


	</script>

</html>
