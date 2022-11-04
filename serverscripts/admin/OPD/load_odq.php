<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);



 $query=mysqli_query($db,"SELECT *
                                             FROM patient_odq
                                             WHERE
                                               subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
                                   ") or die(mysqli_error($db));

 if(mysqli_num_rows($query)==0){
   echo 'No ODQ';
 }else {
   // $hpc_count=1;
   while ($odq=mysqli_fetch_array($query)) {
     ?>
     <div class="card">
       <div class="card-body py-2">
         <p class="m-0 poppins font-weight-bold"><?php echo $odq['question']; ?> <?php echo $odq['response']; ?></p>
       </div>
     </div>


     <div class="mb-3">

     </div>
     <?php
   }
 }

  ?>
