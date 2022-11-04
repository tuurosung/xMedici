<?php require_once '../navigation/print_header_a4.php'; ?>



<div class="container">
  <div class="font-weight-bold" style="font-weight:bold; margin:1rem 0rem 1rem">Surgery Invoice</div>

  <div class="row"  style="display:flex">
    <div class="col-6">

    </div>

  </div>

  <div class="d-flex mb-1" style="display:flex;">
      <div style="width:90px">Trans #:</div>
      <div class="font-weight-bold"><strong><?php echo $transaction_id; ?></strong></div>
  </div>
  <div class="d-flex mb-0"  style="display:flex">
      <div style="width:90px">Date:</div>
      <div class="font-weight-bold"><?php echo $transaction_info['date']; ?></div>
  </div>



  <div class="mt-3" style="margin-top:30px">
    <hr class="mb-2" style="border-top:dashed 1px #000">

    <div class="row font-weight-bold" style="font-size:14px; display:flex;">
      <div class="col-1" style="width:10%">
        #
      </div>
      <div class="col-5" style="width:50%">
        Description
      </div>
      <div class="col-2" style="width:20%">
        Qty
      </div>
      <div class="col-4" style="width:20%; text-align:right">
        Total
      </div>
    </div>

    <hr class="my-2" style="border-top:dashed 1px #000">

    <?php
      $get_drugs=mysqli_query($db,"SELECT * FROM cart WHERE transaction_id='".$transaction_id."' && status='active' && subscriber_id='".$active_subscriber."'") or die('failed');
      $i=1;
      while ($drug_rows=mysqli_fetch_array($get_drugs)) {
        $drug_info=drug_info($drug_rows['drug_id']);
        ?>

        <div class="row text-uppercase" style="display:flex">
          <div class="col-1"  style="width:10%">
            <?php echo $i++; ?>
          </div>
          <div class="col-5"  style="width:50%">
            <?php echo $drug_info['drug_name']; ?> @ <?php echo $drug_rows['cost']; ?>
          </div>
          <div class="col-2"  style="width:20%">
            <?php echo $drug_rows['qty']; ?>
          </div>
          <div class="col-4 text-right" style="width:20%; text-align:right">
            <?php echo $drug_rows['total']; ?>
          </div>
        </div>

        <hr class="my-2" style="border-top:dashed 1px #000">
        <?php
      }
     ?>




     <div class="" style="display:flex; margin-top:50px; margin-bottom:30px">
       <div class="" style="width:33%; border-right:solid 2px #000; margin-right:15px">
         Bill
         <div style="font-weight: bold; font-size:20px; margin-bottom:0px; margin-top:10px"><?php echo $transaction_info['cart_sum']; ?></div>
       </div>
       <div class="" style="width:33%; border-right:solid 2px #000; margin-right:15px">
         Paid
         <div style="font-weight: bold; font-size:20px; margin-top:10px"><?php echo $transaction_info['amount_paid']; ?></div>
       </div>
       <div class="" style="width:33%">
         Bal
         <div style="font-weight: bold; font-size:20px; margin-top:10px"><?php echo $transaction_info['balance']; ?></div>
       </div>
     </div>




          <hr class="my-2" style="border-top:dashed 1px #000">

       </div>

       <div class="montserrat" style="text-align:center; margin-top:20px">
         *** Thank you for your patronage. Get well soon ***
       </div>
       <div class="montserrat" style="text-align:center">
         Powered By Medici ERP Pharmacy Manager
       </div>

     </div>

     </body>
       <script type="text/javascript">
         print();
         // window.close()
       </script>
     </html>
