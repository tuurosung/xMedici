<?php
require_once '../../dbcon.php';
require_once '../../Classes/Users.php';

$u=new User();
$user_id=clean_string($_GET['user_id']);
$u->user_id=$user_id;
$u->UserInfo();
 ?>

 <div class="modal fade" id="edit_user_modal">
   <div class="modal-dialog modal-side modal-top-right" role="document">
     <div class="modal-content">
 			<form id="edit_user_frm" autocomplete="off">
       <div class="modal-body">
 					<h6>Edit User Details</h6>
 					<hr class="hr">

          <div class="form-group d-none">
 						<label>User ID</label>
 						<input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $u->user_id; ?>" required readonly>
 					</div>

 					<div class="form-group">
 						<label>Access Level</label>
 						<select class="custom-select browser-default" id="access_level" name="access_level" required>
 							<option value="administrator" <?php if($u->access_level=='administrator'){ echo 'selected'; } ?>>Administrator</option>
 							<option value="sales" <?php if($u->access_level='sales'){ echo 'selected'; } ?>>Sales</option>
 						</select>
 					</div>

 					<div class="form-group">
 						<label>Full Name</label>
 						<input type="text" class="form-control" id="full_name" name="full_name" required value="<?php echo $u->full_name; ?>">
 					</div>

 					<div class="form-group">
 						<label>Phone Number</label>
 						<input type="text" class="form-control" id="phone_number" name="phone_number" required value="<?php echo $u->phone; ?>">
 					</div>

 					<div class="row">
 					  <div class="col-md-6">
 							<div class="form-group">
 								<label>Username</label>
 						    <input type="text" class="form-control" id="username" name="username" required value="<?php echo $u->username; ?>">
 							</div>
 					  </div>
 					  <div class="col-md-6">
 							<div class="form-group">
 								<label>Password</label>
 						    <input type="password" class="form-control" id="password" name="password" required value="<?php echo $u->password; ?>">
 							</div>
 					  </div>
 					</div>


       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Update Account</button>
       </div>
 			</form>
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->
