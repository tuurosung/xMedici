<?php
require_once '../dbcon.php';
$drug_id=clean_string($_GET['drug_id']);

$drug_info=drug_info($drug_id);
?>


<div id="edit_drug_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right" style="width:400px">
    <div class="modal-content">
			<form id="edit_drug_frm">
      <div class="modal-body">
        <h6 class="font-weight-bold">Edit Drug Info</h6>
        <hr class="hr">
				<div class="form-group d-none">
				  <label for="">Drug Code</label>
				  <input type="text" class="form-control" id="" name="drug_id" value="<?php echo $drug_info['drug_id']; ?>" readonly required>
				</div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Unit</label>
              <select class="browser-default custom-select" name="unit">
                <option value="Tab" <?php if($drug_info['unit']=='Tab'){ echo 'selected';} ?>>Tablets</option>
                <option value="Cap" <?php if($drug_info['unit']=='Cap'){ echo 'selected';} ?>>Capsules</option>
                <option value="Blstr" <?php if($drug_info['unit']=='Blster'){ echo 'selected';} ?>>Blister</option>
                <option value="Case" <?php if($drug_info['unit']=='Case'){ echo 'selected';} ?>>Case</option>
                <option value="Pill" <?php if($drug_info['unit']=='Pill'){ echo 'selected';} ?>>Pill</option>
                <option value="Piece" <?php if($drug_info['unit']=='Piece'){ echo 'selected';} ?>>Piece</option>
                <option value="Vile" <?php if($drug_info['unit']=='Vile'){ echo 'selected';} ?>>Vile</option>
                <option value="Amp" <?php if($drug_info['unit']=='Amp'){ echo 'selected';} ?>>Ampule</option>
                <option value="Pck" <?php if($drug_info['unit']=='Pck'){ echo 'selected';} ?>>Pack</option>
                <option value="Scht" <?php if($drug_info['unit']=='Scht'){ echo 'selected';} ?>>Sachet</option>
                <option value="Box" <?php if($drug_info['unit']=='Box'){ echo 'selected';} ?>>Box</option>
                <option value="Btl" <?php if($drug_info['unit']=='Btl'){ echo 'selected';} ?>>Bottle</option>
                <option value="Roll" <?php if($drug_info['unit']=='Rol'){ echo 'selected';} ?>>Roll</option>
                <option value="Tube" <?php if($drug_info['unit']=='Tube'){ echo 'selected';} ?>>Tube</option>
              </select>
            </div>
          </div>
        </div>


				<div class="form-group">
				  <label for="">Drug Description</label>
				  <input type="text" class="form-control" id="" name="drug_name" value="<?php echo $drug_info['drug_name']; ?>" required>
				</div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Manufacturer</label>
              <select class="browser-default custom-select" id="manufacturer" name="manufacturer">
                <?php
                  // require
                  $query=mysqli_query($db,"SELECT * FROM manufacturers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                  while ($manufacturers=mysqli_fetch_array($query)) {
                    ?>
                    <option value="<?php echo $manufacturers['manufacturer_id']; ?>" <?php if($manufacturers['manufacturer_id']==$drug_info['manufacturer']){ echo 'selected';} ?>><?php echo $manufacturers['name']; ?></option>
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
                  $query=mysqli_query($db,"SELECT * FROM drug_category") or die(mysqli_error($db));
                  while ($rows=mysqli_fetch_array($query)) {
                    ?>
                    <option value="<?php echo $rows['category_id']; ?>" <?php if($rows['category_id']==$drug_info['category']){ echo 'selected';} ?>><?php echo $rows['category_name']; ?></option>
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
              <input type="text" class="form-control" id="shelf" name="shelf"  value="<?php echo $drug_info['shelf']; ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Restock Level</label>
              <input type="text" class="form-control" id="" name="restock_level" value="<?php echo $drug_info['restock_level']; ?>" required>
            </div>
          </div>
        </div>



        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="">Cost Price</label>
              <input type="text" class="form-control" id="" name="cost_price" value="<?php echo $drug_info['cost_price']; ?>" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
    				  <label for="">Wholesale Price</label>
    				  <input type="text" class="form-control" id="" name="wholesale_price" value="<?php echo $drug_info['wholesale_price']; ?>" required>
    				</div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
    				  <label for="">Selling Price</label>
    				  <input type="text" class="form-control" id="" name="retail_price" value="<?php echo $drug_info['retail_price']; ?>" required>
    				</div>
          </div>
        </div>



      </div>
      <div class="modal-footer">
				<button type="button" class=" btn btn-white" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-black">Update Drug Info</button>
      </div>
			</form>
    </div>
  </div>
</div>
