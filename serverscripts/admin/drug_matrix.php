<?php
    require_once '../dbcon.php';
    $drug_id=clean_string($_GET['drug_id']);

    $drug_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$drug_id."'") or die('failed');
    $drug_info=mysqli_fetch_array($drug_info);
    $selling_price=$drug_info['selling_price'];


 ?>

<div id="drug_matrix_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 900px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Drug Matrix</h4>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-4">
            <h4>Stocked Batches</h4>
            <hr>
            <ul class="list-group">
              <li class="list-group-item active">Stock Records</li>
              <?php
                $get_stocking_records=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$drug_id."'") or die('failed');
                while ($history=mysqli_fetch_array($get_stocking_records)) {
                  ?>
                  <li class="list-group-item"><?php echo $history['batch_code']; ?></li>
                  <?php
                }
               ?>

            </ul>
          </div>
          <div class="col-md-8">
            <h4>Last Stock Information</h4>
            <hr>
            <?php
              $get_last_record=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$drug_id."' ORDER BY sn DESC LIMIT 1") or die('failed');
              $get_last_record=mysqli_fetch_array($get_last_record);
              $batch_code=get_last_record['batch_code'];
             ?>

            <div class="row">
              <div class="col-md-6">
                <div class=" custom-label">Batch Code</div>
                <h3 class="custom-value-holder"><?php echo $get_last_record['batch_code']; ?></h3>
              </div>
              <div class="col-md-6">
                <div class="custom-label">Quantity Stocked</div>
                <h3 class="custom-value-holder"><?php echo $get_last_record['qty_stocked']; ?></h3>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class=" custom-label">Quantity Sold</div>
                <h3 class="custom-value-holder"><?php echo $get_last_record['qty_sold']; ?></h3>
              </div>
              <div class="col-md-6">
                <div class="custom-label">Quantity Remaining</div>
                <h3 class="custom-value-holder"><?php echo $get_last_record['qty_rem']; ?></h3>
              </div>
            </div>

            <?php
              $get_total_sale=mysqli_query($db,"SELECT SUM(total_cost) as total_sale FROM sales WHERE batch_code='".$batch_code."'") or die('failed');
              $get_total_sale=mysqli_fetch_assoc($get_total_sale);
              $get_total_sale=$get_total_sale['total_sale'];
              ?>

            <div class="row">
              <div class="col-md-6">
                <div class=" custom-label">Sale Value</div>
                <h3 class="custom-value-holder"><?php echo number_format($get_total_sale,2); ?></h3>
              </div>
              <div class="col-md-6">
                <div class="custom-label">Stock Value</div>
                <h3 class="custom-value-holder"><?php echo number_format($get_last_record['qty_rem'] * $selling_price,2); ?></h3>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class=" custom-label">Profit Made</div>
                <h3 class="custom-value-holder">0.00</h3>
              </div>
              <div class="col-md-6">
                <div class="custom-label">Expiry</div>
                <h3 class="custom-value-holder"><?php echo $get_last_record['expiry_date']; ?></h3>
              </div>
            </div>




          </div>
        </div>







      </div>
      <div class="modal-footer">
        ...
      </div>
    </div>
  </div>
</div>
