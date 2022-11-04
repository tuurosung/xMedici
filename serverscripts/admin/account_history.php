<?php
require_once('../dbcon.php');

$id=$_GET['id'];

$get_account=mysqli_query($db,"SELECT * FROM Accounts WHERE  account_id='".$id."'") or die('failed');
$account_info=mysqli_fetch_row($get_account);
$account_name=$account_info[2];
$date_created=$account_info[7];
$opening_balance=$account_info[3];

$get_history=mysqli_query($db,"SELECT * FROM transactions WHERE account_id='".$id."'") or die('failed');
 ?>

 <table class="table custom_tables" style="font-size:11px">
   <thead>
     <tr>
       <th>#</th>
       <th>Date</th>
       <th>Transaction ID</th>
       <th>Trans Type</th>
       <th >Trans. Amount</th>
       <th >Balance</th>
     </tr>
   </thead>
   <tbody>
     <tr>
       <td>#</td>
       <td><?php echo $date_created; ?></td>
       <td>############</td>
       <td >Opening Balance</td>
       <td class="text-right">&cent; <?php echo $opening_balance; ?></td>
       <td class="text-right">&cent; <?php echo $opening_balance; ?></td>
     </tr>
   <?php
   $i=1;
   while ($rows=mysqli_fetch_array($get_history)) {

     if($rows['transaction_type']=='EXPENDITURE'){
       $color="#c0392b";
       $amount= '- &cent; '.$rows['transaction_amount'];
     }
     else {
       $color="#000";
       $amount= '&cent; '.$rows['transaction_amount'];
     }
     ?>

       <tr style="color: <?php echo $color; ?>">
         <td><?php echo $i++ ?></td>
         <td><?php echo $rows['date']; ?></td>
         <td><?php echo $rows['transaction_id']; ?></td>
         <td><?php echo $rows['transaction_type']; ?></td>
         <td class="text-right"><?php echo $amount; ?></td>
         <td class="text-right">&cent; <?php echo $rows['account_balance']; ?></td>
       </tr>

     <?php
   }
   ?>
   </tbody>
 </table>
