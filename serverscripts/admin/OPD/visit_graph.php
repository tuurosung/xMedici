<?php
  require_once '../../dbcon.php';
  $month=date('m');


  $query=mysqli_query($db,"SELECT visit_date AS dates,COUNT(*) as total_visits
                                            FROM visits
                                            WHERE
                                                status!='deleted' AND MONTH(visit_date)='".$month."' AND
                                                subscriber_id='".$active_subscriber."'

                                            GROUP BY visit_date ") or die(mysqli_error($db));
  $data=array();
  foreach ($query as $results) {
    $data[] = $results;
  }

  echo json_encode($data);

 ?>
