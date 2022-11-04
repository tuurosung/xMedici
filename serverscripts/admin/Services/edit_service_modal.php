<?php

  require_once '../../dbcon.php';
  require_once '../../Classes/Services.php';

  $service_id=clean_string($_GET['service_id']);

  $service=new Service();
  $service->service_id=$service_id;
  $service->ServiceInfo();

 ?>




 <div id="edit_service_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog " style="">
     <div class="modal-content">
 			<form id="edit_service_frm" autocomplete="off">
       <div class="modal-body">
 					<h5 class="font-weight-bold montserrat">Update Service Information</h5>
 					<hr class="hr mb-3">

            <div class="form-group d-none">
              <label for="">Service ID</label>
              <input type="text" name="service_id" value="<?php echo $service_id; ?>">
            </div>

 						<div class="row poppins">
 						  <div class="col-md-6">
 								<div class="form-group">
 									<label for="">Billing Type</label>
 									<select class="custom-select browser-default" name="billing_type">
 											<option value="mandatory" <?php if($service->billing_type=='mandatory'){ echo 'selected'; } ?>>Mandatory</option>
 											<option value="optional"  <?php if($service->billing_type=='optional'){ echo 'selected'; } ?>>Optional</option>
 									</select>
 						    </div>
 						  </div>
 						  <div class="col-md-6">

 						  </div>
 						</div>

 						<div class="row poppins">
 						  <div class="col-md-6">
 						    <div class="form-group">
 									<label for="">Description</label>
 									<input type="text" class="form-control" name="description" id="description" value="<?php echo $service->description; ?>" placeholder="Enter the name of the service">
 						    </div>
 						  </div>
 						  <div class="col-md-6">
 								<div class="form-group">
 									<label for="">Billing Point</label>
 									<select class="custom-select browser-default" name="billing_point">
 											<?php
 												$get_billing_points=mysqli_query($db,"SELECT * FROM billing_points") or die(mysqli_error($db));
 												while ($rows=mysqli_fetch_array($get_billing_points)) {
 													?>
 													<option value="<?php echo $rows['billing_point']; ?>" <?php if($service->billing_point==$rows['billing_point']){ echo 'selected'; } ?>><?php echo $rows['point_name']; ?></option>
 													<?php
 												}
 											 ?>
 									</select>
 						    </div>
 						  </div>
 						</div>


 						<div class="row poppins mt-2">
 						  <div class="col-md-6">
 						    <div class="form-group">
 									<label for="">Cost</label>
 									<input type="text" class="form-control" name="service_cost" id="service_cost" value="<?php echo $service->service_cost; ?>" placeholder="Enter the cost of the service">
 						    </div>
 						  </div>
 						  <div class="col-md-6">
 								<div class="form-group">
 									<label for="">Billing Cycle</label>
 									<select class="custom-select browser-default" name="billing_cycle">
 											<option value="one_time" <?php if($service->billing_cycle=='one_time'){ echo 'selected'; } ?>>One - Time</option>
 											<option value="daily" <?php if($service->billing_cycle=='daily'){ echo 'selected'; } ?>>Daily</option>
 									</select>
 						    </div>
 						  </div>
 						</div>





       </div>
       <div class="modal-footer">
         <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
         <button type="submit" class="btn primary-color-dark">
 					<i class="fas fa-file-alt mr-3"></i>
 					Update Service</button>
       </div>
 			</form>
     </div>
   </div>
 </div>
