<?php
require_once '../dbcon.php';
$purchase_id=$_GET['purchase_id'];
$select=mysqli_query($db,"SELECT * FROM stores_purchases WHERE purchase_id='".$purchase_id."'") or die('failed');
$info=mysqli_fetch_array($select);

$get_supplier=mysqli_query($db,"SELECT * FROM suppliers WHERE supplier_id='".$info['supplier_id']."'") or die('failed');
$get_supplier=mysqli_fetch_array($get_supplier);
$supplier_name=$get_supplier['supplier_name'];
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
            Invoice ID: <?php echo strtoupper($info['invoice_id']); ?>
          </div>
          <div class="col-md-6">
            Date:
          </div>
        </div>
        <br>
        <div class="row" style="color:#000;width:95%; margin:0px auto">
          <div class="col-md-6">
            Supplier:  <?php echo $supplier_name; ?>
          </div>
          <div class="col-md-6">
            Contact: <?php echo $get_supplier['phone_number']; ?>
          </div>
        </div>
        <hr style="border-top-width:1px; border-top-color:#000; width:95%" align="center">
        <table class="table card-table" style="color:#000;width:95%; margin:0px auto">
          <thead style="color:#000">
            <tr>
              <th>#</th>
              <th>Item Name</th>
              <th>Cost</th>
              <th>Qty</th>
              <th class="text-right">Total</th>

            </tr>
          </thead>
          <tbody >
            <?php
            $select=mysqli_query($db,"SELECT * FROM stores_purchaseditems WHERE purchase_id='".$purchase_id."'") or die('failed');
            $i=1;
            while ($rows=mysqli_fetch_array($select)) {
              ?>
              <tr style="font-family:tillana; color:#000">
                <td><?php echo $i++ ; ?></td>
                <td><?php echo $rows['drug_name']; ?></td>
                <td><?php echo $rows['supply_price']; ?></td>
                <td><?php echo $rows['qty'] ?></td>
                <td class="text-right"><?php echo $rows['cost']; ?></td>

              </tr>

              <?php
              $total_cost+=$rows['cost'];
            }

            ?>
            <td> </td>
            <td> </td>
            <td> </td>
            <td class="text-right" style="font-weight:bold; font-size:13px">Total </td>
            <td class="text-right" style="font-weight:bold; font-size:13px"><?php echo number_format($total_cost,2); ?></td>

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
