<?php
    require_once '../../dbcon.php';
    $removethis=clean_string($_GET['removethis']);

    $sql="UPDATE patient_diagnosis  SET   status='deleted'  WHERE sn='".$removethis."' AND subscriber_id='".$active_subscriber."'";
    $r=$mysqli->query($sql);


     if($mysqli->affected_rows ==1){
       echo 'delete_successful';
     }else {
       echo 'Unable to remove diagnosis';
     }

 ?>
