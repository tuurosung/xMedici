<?php

session_start();
require_once '../../dbcon.php';
require_once '../../Classes/Patient.php';
require_once '../../Classes/Billing.php';
require_once '../../Classes/Accounts.php';
require_once '../../Classes/Payments.php';

$start_date=clean_string($_GET['start_date']);
$end_date=clean_string($_GET['end_date']);
$payment_account=clean_string($_GET['payment_account']);

$p=new Patient();
$account=new Account();


$pmt=new Payment();

 ?>

 <h6 class="montserrat font-weight-bold">Payments Received From <?php echo date('d-M-Y',strtotime($start_date)); ?> To <?php echo date('d-M-Y',strtotime($end_date)); ?></h6>
 <hr class="hr mb-5">

 <div class="spacer"></div>

 <table class="table table-condensed datatables">
   <thead class="grey lighten-3 font-weight-bold">
     <tr>
       <th>#</th>
       <th>Date</th>
       <th>Bill#</th>
       <th>Patient ID</th>
       <th>Name</th>
       <th>Narration</th>
       <th class="text-right">Amount Paid</th>
       <th class="text-right">Balance</th>
       <th></th>
     </tr>
   </thead>
   <tbody>

     <?php
       $i=1;
       // $get_payments=mysqli_query($db,"SELECT *
       // 																												FROM payments
       // 																												WHERE
       // 																													subscriber_id='".$active_subscriber."'  AND
       // 																													status='active' AND
       // 																													date='".$today."'
       //
       // 																						") or die(mysqli_error($db));

       $payments=$pmt->AllPayments($start_date,$end_date,'','');

       // while ($rows=mysqli_fetch_array($get_payments)) {
       foreach ($payments as $rows) {
         $p->patient_id=$rows['patient_id'];
         $p->PatientInfo();
         $othernames=$p->othernames;

         $billing=new Billing();
         $billing->bill_id=$rows['bill_id'];
         $billing->BillInfo();

         // if($billing->payment_status=='PAID'){
         // 	continue;
         // }
         ?>
         <tr class="py-2">
           <td class="py-2"><?php echo $i++; ?></td>
           <td><?php echo $rows['date']; ?></td>
           <td><?php echo $rows['bill_id']; ?></td>
           <td><?php echo $p->patient_id; ?></td>
           <td class="text-capitalize"><?php echo ucfirst(mb_strtolower($p->patient_fullname)); ?></td>
           <td ><?php echo substr($billing->narration,0,80); ?></td>
           <td class="text-right"><?php echo $rows['amount_paid']; ?></td>
           <td class="text-right"><?php echo $rows['balance']; ?></td>
           <td class="text-right">

             <div class="dropdown open">
               <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 Options
               </button>
               <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
                 <ul class="list-group">
                   <li class="list-group-item print_btn" id="<?php echo $rows['payment_id']; ?>"><i class="fas fa-print mr-2" aria-hidden></i> Print Receipt</li>
                   <li class="list-group-item edit_payment" id="<?php echo $rows['payment_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i> Edit Payment</li>
                   <li class="list-group-item delete_payment" id="<?php echo $rows['payment_id']; ?>"><i class="fas fa-trash-alt mr-2" aria-hidden></i> Delete Payment</li>
                 </ul>
               </div>
             </div>
           </td>
         </tr>

         <?php
         $amount_paid+=$rows['amount_paid'];
         $balance+=$rows['balance'];
       }
      ?>

      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="text-right font-weight-bold" style="font-size:25px"><?php echo number_format($amount_paid,2); ?></td>
        <td></td>
        <td></td>
      </tr>

 </tbody>
 </table>
