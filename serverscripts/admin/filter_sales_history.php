<?php
require_once '../dbcon.php';
require_once '../Classes/Pharmacy.php';
require_once '../Classes/Drugs.php';

$start_date=clean_string($_GET['start_date']);
$end_date=clean_string($_GET['end_date']);

$pharmacy=new Pharmacy();
$drug=new Drug();

// $attendant=$_GET['attendant'];
?>

<div class="row mb-5">
  <div class="col-3">
    <p class="montserrat font-weight-bold" style="font-size:16px">GHS <?php echo number_format($pharmacy->PharmacySales($start_date,$end_date),2) ?></p>
    <p>Internal Prescriptions</p>
  </div>
  <div class="col-3">
    <p class="montserrat font-weight-bold" style="font-size:16px">GHS <?php echo number_format($pharmacy->PharmacyWalkInSales($start_date,$end_date),2) ?></p>
    <p>Internal Prescriptions</p>
  </div>
  <div class="col-3">

  </div>
</div>


<table class="table datatables table-condensed">
  <thead class="">
    <tr>
      <th>#</th>
      <th>Description</th>
      <th>Qty</th>
      <th class="text-right">Total</th>
      <th class="text-right">Profit</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=1;
      $total=0;
      $get_cart=mysqli_query($db,"SELECT
                                                      drug_id,
                                                      SUM(qty) as total_qty,
                                                      SUM(total) as total_sale
                                                      FROM pharm_cart
                                                      WHERE
                                                      date BETWEEN '".$start_date."' AND '".$end_date."' AND
                                                        subscriber_id='".$active_subscriber."' AND
                                                        status='CHECKOUT'
                                                      GROUP BY
                                                        drug_id
                                                      ") or die(mysqli_error($db));

      while ($cart_items=mysqli_fetch_array($get_cart)) {
        $drug->drug_id=$cart_items['drug_id'];
        $drug->DrugInfo();
        ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $drug->drug_name; ?></td>
          <td><?php echo $cart_items['total_qty']; ?></td>
          <td class="text-right"><?php echo $cart_items['total_sale']; ?></td>
          <td class="text-right"><?php echo number_format($cart_items['total_qty'] * $drug->profit,2); ?></td>
          <td class="text-right">
            <button type="button" class="btn btn-sm btn-danger delete" id="<?php echo $cart_items['sn'] ?>">Delete</button>
          </td>
        </tr>
        <?php
        $total+=$cart_items['total_sale'];
        $profit+=$cart_items['total_qty'] * $drug->profit;
      }
     ?>
     <tr>
       <td colspan="3"></td>
       <td class="font-weight-bold text-right"><?php echo number_format($total,2); ?></td>
       <td class="font-weight-bold text-right"><?php echo number_format($profit,2); ?></td>
       <td></td>
     </tr>
  </tbody>
</table>
