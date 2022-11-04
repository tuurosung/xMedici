<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Expenditure.php';

  $expenditure_id=clean_string($_GET['expenditure_id']);
  $e=new Expenditure():
  $e->expenditure_id=$expenditure_id;
  $e->ExpenditureInfo();
 ?>


<div id="edit_expenditure_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h6 class="font-weight-bold">Edit Expenditure</h6>
        <hr class="hr">
      </div>
      <div class="modal-footer">
        ...
      </div>
    </div>
  </div>
</div>
