<?php
require_once '../dbcon.php';
$issue_id=$_GET['issue_id'];
$select=mysqli_query($db,"SELECT * FROM stores_issues WHERE issue_id='".$issue_id."'") or die('failed');
$info=mysqli_fetch_array($select);


?>

</a>
<div id="card_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:700px">
    <div class="modal-content">

      <div class="modal-body" style="font-family:tillana">
        <div class="text-center" style="font-size:30px; color:#000">
          Dartah Pharmacy Limited
        </div>
        <div class="text-center" style="font-size:20px; color:#000">
          Dokpong Branch - Wa-U/W/R
        </div>
        <hr style="border-top-width:1px; border-top-color:#000; width:95%" align="center">

        <div class="row" style="color:#000;width:95%; margin:0px auto">
          <div class="col-md-6">
            Invoice ID: <?php echo strtoupper($info['issue_id']); ?>
          </div>
          <div class="col-md-6">
            Date: <?php echo $info['date']; ?>
          </div>
        </div>
        <br>
        <div class="row" style="color:#000;width:95%; margin:0px auto">
          <div class="col-md-6">
            To Whom Issued:  <?php echo strtoupper($info['unit']); ?>
          </div>
          <div class="col-md-6">
            Receiver: <?php echo strtoupper($info['receiver']); ?>
          </div>
        </div>
        <hr style="border-top-width:1px; border-top-color:#000; width:95%" align="center">
        	<table class="table card-table" style="width:90%; margin:0px auto">
            <thead>
              <tr>
                <th>#</th>
                <th>Drug ID</th>
                <th>Drug Name</th>
                <th>Qty Issued</th>
              </tr>
            </thead>
            <tbody style="color:#000">
              <?php
              $i=1;
              $select_string=mysqli_query($db,"SELECT * FROM stores_issueditems WHERE issue_id='".$issue_id."'") or die('failed');
              while ($rows=mysqli_fetch_array($select_string)) {
                ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $rows['drug_id']; ?></td>
                  <td><?php echo $rows['drug_name']; ?></td>
                  <td><?php echo $rows['qty']; ?></td>
                  <td></td>
                </tr>
                <?php
              }

               ?>

            </tbody>
          </table>

        <div class="text-right" style="width:80%; margin:0px auto; margin-top:50px">
          <button type="button" data-dismiss="modal" class="btn custom_button_red" name="button">
            Close Card
          </button>
        </div>


      </div>

    </div>
  </div>
</div>

<style media="screen">
  .card-table > tbody > tr > td{
    font-family: tillana;
  }
  .card-table  thead {
    font-family: tillana;
    background-color: #fff;
    color: #000;
  }
  .card-table >thead > tr >th{
    color:#000;
  }
</style>
