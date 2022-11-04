<?php
  require_once '../../dbcon.php';

  $admission_id=clean_string($_GET['admission_id']);

  $query=mysqli_query($db,"SELECT date_time AS times,temperature as temp
                                            FROM admissions_vitals
                                            WHERE
                                                status='active' AND
                                                subscriber_id='".$active_subscriber."' AND
                                                admission_id='".$admission_id."'
                                            GROUP BY date_time ") or die(mysqli_error($db));
  $data=array();
  foreach ($query as $results) {
    $data[] = $results;
  }

  echo json_encode($data);

 ?>
