<?php require_once '../navigation/print_header.php'; ?>
<?php

    $payment_id=clean_string($_GET['payment_id']);
    $pmt=new Payment();
    $pmt->payment_id=$payment_id;
    $pmt->PaymentInfo();

    $bill_id=$pmt->bill_id;
    $visit_id=$pmt->visit_id;

    $billing=new Billing();
    $billing->bill_id=$bill_id;
    $billing->BillInfo();

    $patient=new Patient();
    $patient_id=$billing->patient_id;
    $patient->patient_id=$patient_id;
    $patient->PatientInfo();

 ?>

<div class="" style="padding:10px; width:80mm">
  <div class="" style="text-align:center">
    ==================================
    <p class="montserrat font-weight-bold" style="font-size:14px; font-weight:800; text-align:center">PAYMENT RECEIPT</p>
    ==================================
  </div>

  <div class="" style="margin-bottom:30px; margin-top:30px">
      <p>OPD # :  <span style="font-weight:700"><?php echo $visit_id; ?></span></p>
      <p>Name :  <span style="font-weight:700"><?php echo $patient->patient_fullname; ?></span></p>
  </div>


  <hr style="border-top:dashed 1px #000">
  <div class="d-flex my-2" style="width:80mm">
    <div class="" style="width:70%">
      <?php echo $billing->narration; ?>
    </div>
    <div class=""  style="width:30%; text-align:right">
      <?php echo $billing->bill_amount; ?>
    </div>
  </div>

  <hr style="border-top:dashed 1px #000">

  <div class="d-flex my-2" style="width:80mm">
    <div class="" style="width:70%; font-weight:bold">
      TOTAL AMOUNT
    </div>
    <div class=""  style="width:30%; text-align:right; font-weight:bold">
      <?php echo $billing->bill_amount; ?>
    </div>
  </div>

  <hr style="border-top:dashed 1px #000">

  <div class="d-flex my-2" style="width:80mm">
    <div class="" style="width:70%">
      Amount Paid
    </div>
    <div class=""  style="width:30%; text-align:right">
      <?php echo $pmt->amount_paid; ?>
    </div>
  </div>

  <div class="d-flex my-2" style="width:80mm">
    <div class="" style="width:70%">
      Balance
    </div>
    <div class=""  style="width:30%; text-align:right">
      <?php echo $pmt->balance; ?>
    </div>
  </div>

  <hr style="border-top:dashed 1px #000">

  <div class="montserrat" style="text-align:center; margin-top:30px; font-weight:bold; font-size:17px">
    *** THANK YOU ***
  </div>
  <div class="montserrat" style="text-align:center">
    <strong>xMedici</strong> - The Paperless Clinic
  </div>

  <div class="" style="margin-top:20px">
    <img src="../images/barcode.png" alt="" style="width:100%; height:40px">
  </div>

</div>
<script type="text/javascript">
  print();
</script>
