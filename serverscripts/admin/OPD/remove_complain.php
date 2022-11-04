<?php
    require_once '../../dbcon.php';
    $removethis=clean_string($_GET['complain_id']);

    $delete_query=mysqli_query($db,"UPDATE
                                                              patient_complains
                                                           SET
                                                              status='deleted'
                                                           WHERE
                                                              sn='".$removethis."' AND subscriber_id='".$active_subscriber."'
                                              ") or die(mysqli_error($db));
     if(mysqli_affected_rows($db)==1){
       echo 'delete_successful';
     }else {
       echo 'Unable to remove complain';
     }

 ?>
