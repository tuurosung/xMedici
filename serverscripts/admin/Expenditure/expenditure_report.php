<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Expenditure.php';

  $start_date=clean_string($_GET['start_date']);
  $end_date=clean_string($_GET['end_date']);
  $expenditure_account=clean_string($_GET['header_id']);


  $e=new Expenditure();
  $query=$e->ExpenditurePeriodQuery($start_date,$end_date,$expenditure_account);

 ?>


 <table class="table table-condensed datatables report_table">
   <thead class="">
     <tr>
       <th>#</th>
       <th>Date</th>
       <th>Exp. Account</th>
       <th>Description</th>
       <th>Account</th>
       <th class="text-right">Amount</th>

       <th></th>
     </tr>
   </thead>
   <tbody>
     <?php
       // require_once '../serverscripts/dbcon.php';

       // /$query=mysqli_query($db,"SELECT  * FROM expenditure WHERE date BETWEEN '".$year_start."' AND '".$year_end."' AND subscriber_id='".$active_subscriber."' AND status='active' ") or die(mysqli_error($db));
       $i=1;
       $total_expenditure=0;
       while ($rows=mysqli_fetch_array($query)) {


         $expenditure_account_info=account_info($rows['expenditure_account']);
         $payment_account_info=account_info($rows['payment_account']);
           ?>
           <tr>
             <td class=""><?php echo $i++; ?></td>
             <td class=""><?php echo $rows['date']; ?></td>
             <td class=""><?php echo $expenditure_account_info['account_name']; ?></td>
             <td class=""><?php echo $rows['description']; ?></td>

             <td><?php echo $payment_account_info['account_name']; ?></td>
             <td class="text-right"><?php echo number_format($rows['amount'],2); ?></td>


             <td class="text-right">
               <div class="dropdown open">
                 <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Option
                 </button>
                 <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
                   <ul class="list-group">
                     <li class="list-group-item edit edit" id="<?php echo $rows['expenditure_id']; ?>">Edit</li>
                     <li class="list-group-item delete" id="<?php echo $rows['expenditure_id']; ?>">Delete</li>
                   </ul>
                 </div>
               </div>
             </td>
           </tr>

         <?php
         $total_expenditure+=$rows['amount'];
       }
       ?>
       <tr>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td class="text-right font-weight-bold"><?php echo number_format($total_expenditure,2); ?></td>
         <td></td>
       </tr>
   </tbody>
 </table>
