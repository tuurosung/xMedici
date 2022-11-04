<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Tests.php';

  $test_id=clean_string($_GET['test_id']);

  $test=new Test();
  $test->test_id=$test_id;
  $test->TestInfo();
 ?>

<div id="edit_test_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="edit_test_frm" autocomplete="off">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">Edit Test Info</h5>
					<hr class="hr mb-3">

          <div class="form-group d-none">
            <label for="">Test ID</label>
            <input type="text" name="test_id"  value="<?php echo $test_id; ?>">
          </div>


						<div class="row poppins">
						  <div class="col-md-6">
                <div class="form-group">
									<label for="">Category</label>
									<select class="custom-select browser-default" name="test_category">
										<?php
												$get_categories=mysqli_query($db,"SELECT * FROM lab_test_categories WHERE status='active' AND (subscriber_id='".$active_subscriber."' OR subscriber_id='system')") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_categories)) {
													?>
													<option value="<?php echo $rows['category_id']; ?>"><?php echo $rows['description']; ?></option>
													<?php
												}
										 ?>

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
									<input type="text" class="form-control" name="description" id="description" placeholder="Enter the name of the test" value="<?php echo $test->description; ?>">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Cost</label>
									<input type="text" class="form-control" name="test_cost" id="test_cost"  placeholder="Enter the cost of the test"  value="<?php echo $test->test_cost; ?>">
						    </div>
						  </div>
						</div>


						<div class="form-group">
							<label for="">Specimen</label>
							<select class="custom-select browser-default" name="specimen">
								<option value="Blood" <?php if($test->specimen=='Blood'){ echo 'selected'; } ?>>Blood</option>
								<option value="Urine" <?php if($test->specimen=='Urines'){ echo 'selected'; } ?>>Urine</option>
								<option value="Stool" <?php if($test->specimen=='Stool'){ echo 'selected'; } ?>>Stool</option>
								<option value="Seminal Fluid" <?php if($test->specimen=='Seminal Fluid'){ echo 'selected'; } ?>>Seminal Fluid</option>
							</select>
						</div>

            <div class="form-group">
              <label for="">Comment</label>
              <textarea name="comment" class="form-control"><?php echo $test->comment; ?></textarea>
            </div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Update Test</button>
      </div>
			</form>
    </div>
  </div>
</div>
