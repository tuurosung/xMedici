<?php

session_start();
require_once '../../dbcon.php';
require_once '../../Classes/Patient.php';
require_once '../../Classes/Billing.php';
require_once '../../Classes/Accounts.php';
// require_once '../../Classes/Payments.php';

$start_date=clean_string($_GET['start_date']);
$end_date=clean_string($_GET['end_date']);
$payment_account=clean_string($_GET['payment_account']);

$p=new Patient();
$account=new Account();


// $payment=new Payment();

 ?>


 <div class="card mb-5">
   <div class="card-body">
     <h6 class="font-weight-bold">Payment Summary</h6>

    <ul class="list-group">


       <?php
          $get_payments_summary=mysqli_query($db,"SELECT income_account, SUM(amount_paid) as total_payment
                                                                                  FROM payments
                                                                                  WHERE
                                                                                    date BETWEEN '".$start_date."' AND '".$end_date."' AND
                                                                                    subscriber_id='".$active_subscriber."'  AND
                                                                                    status='active'
                                                                                  GROUP BY
                                                                                    income_account
                                                                         ") or die(mysqli_error($db));
          while ($payment_accounts=mysqli_fetch_array($get_payments_summary)) {
            $income_account=$payment_accounts['income_account'];
            $account->account_number=$income_account;
            $account->AccountInfo();
            ?>
            <li class="list-group-item">
              <div class="row">
                <div class="col-md-8">
                  <?php echo $account->account_name; ?>
                </div>
                <div class="col-md-4 text-right">
                  <?php echo number_format($payment_accounts['total_payment'],2); ?>
                </div>
              </div>
            </li>
            <?php
          }
        ?>
      </ul>
   </div>
 </div>

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
       <th class="text-right">Account</th>
       <th></th>
     </tr>
   </thead>
   <tbody>

     <?php
       $i=1;
       if($payment_account=='all'){
         $get_payments=mysqli_query($db,"SELECT *
                                                                 FROM payments
                                                                 WHERE
                                                                   subscriber_id='".$active_subscriber."'  AND
                                                                   status='active' AND
                                                                   date BETWEEN '".$start_date."' AND '".$end_date."'

                                                     ") or die(mysqli_error($db));
       }else{
         $get_payments=mysqli_query($db,"SELECT *
                                                                 FROM payments
                                                                 WHERE
                                                                   subscriber_id='".$active_subscriber."'  AND
                                                                   status='active' AND income_account='".$payment_account."' AND
                                                                   date BETWEEN '".$start_date."' AND '".$end_date."'

                                                     ") or die(mysqli_error($db));
       }

       while ($rows=mysqli_fetch_array($get_payments)) {
         $p->patient_id=$rows['patient_id'];
         $p->PatientInfo();
         $othernames=$p->othernames;

         $billing=new Billing();
         $billing->bill_id=$rows['bill_id'];
         $billing->BillInfo();

         $payment_account=$rows['income_account'];
         $account->account_number=$payment_account;
         $account->AccountInfo();

         // if($billing->payment_status=='PAID'){
         // 	continue;
         // }
         ?>
         <tr>
           <td><?php echo $i++; ?></td>
           <td><?php echo $rows['date']; ?></td>
           <td><?php echo $rows['bill_id']; ?></td>
           <td><?php echo $p->patient_id; ?></td>
           <td class="text-capitalize"><?php echo ucfirst(mb_strtolower($p->patient_fullname)); ?></td>
           <td><?php echo $billing->narration; ?></td>
           <td class="text-right"><?php echo $rows['amount_paid']; ?></td>
           <td class="text-right"><?php echo $rows['balance']; ?></td>
           <td class="text-right"><?php echo $account->account_name; ?></td>
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
      <tr class="font-weight-bold">
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td class="text-right"><?php echo number_format($amount_paid,2); ?></td>
       <td class="text-right"><?php echo number_format($balance,2); ?></td>
       <td></td>
      </tr>


 </tbody>
 </table>
