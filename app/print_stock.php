<?php require_once '../navigation/print_header.php'; ?>


<style media="">
table {
  border-collapse: collapse;
  width: 100%;
}

table, th, td {
  border: 1px solid black;
}
.text-center{
  text-align:center;
}
</style>


<div class="container">
  <div class="font-weight-bold" style="font-weight:bold; margin:1rem 0rem 1rem">CURRENT STOCK LEVELS</div>

  <div class="row"  style="display:flex">
    <div class="col-6">

    </div>

  </div>

  <div class="d-flex mb-0"  style="display:flex">
      <div style="width:90px">Date:</div>
      <div class="font-weight-bold"><?php echo date('Y-m-d'); ?></div>
  </div>
<hr class="mb-2" style="border-top:dashed 1px #000">


  <div class="mt-3" style="margin-top:30px">


    <table class="table">
      <thead class="">
        <tr>
          <th>#</th>
          <th>Item Name</th>
          <th>Status</th>
          <th class="text-center">Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // require_once '../dbcon.php';
        $get_items=mysqli_query($db,"SELECT * FROM inventory WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY drug_name")  or die('failed');
        $i=1;
        while ($rows=mysqli_fetch_array($get_items)) {

          // $get_qty=mysqli_query($db,  "SELECT SUM(qty_rem) as total_qty FROM stock WHERE drug_id='".$rows['drug_id']."' && subscriber_id='".$active_subscriber."'") or die('failed');
          // $get_qty=mysqli_fetch_assoc($get_qty);
          //
          // $total_qty=$get_qty['total_qty'];



          ?>

          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $rows['drug_name']; ?> / <?php echo $rows['unit']; ?></td>
            <td><?php echo $rows['stock_status']; ?></td>
            <td class="text-center"><?php  echo $rows['remaining_stock']; ?></td>
          </tr>

          <?php
        }
        ?>

      </tbody>
    </table>

          <hr class="my-2" style="border-top:dashed 1px #000">

       </div>

       <div class="" style="margin-top:30px; text-align:center;">
          Generated At <?php echo date('H:i:s'); ?> On <?php echo date('d-m-Y'); ?>
       </div>

       <div class="montserrat" style="text-align:center ; margin-top:40px">
         Powered By Medici ERP Pharmacy Manager
       </div>

     </div>

     </body>
       <script type="text/javascript">
         print();
         // window.close()
       </script>
     </html>
