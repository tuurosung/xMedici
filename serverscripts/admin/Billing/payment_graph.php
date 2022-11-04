<?php
  require_once '../../dbcon.php';
  $limit= date('Y-m-d', strtotime('-30 days'));


  $query=mysqli_query($db,"SELECT date AS dates,SUM(amount_paid) as total_payments
                                            FROM payments
                                            WHERE
                                                date BETWEEN '".$limit."' AND '".$today."' AND
                                                status='active' AND
                                                subscriber_id='".$active_subscriber."'

                                            GROUP BY date
                                             ") or die(mysqli_error($db));
  $data=array();
  foreach ($query as $results) {
    $data[] = $results;
  }

  echo json_encode($data);

 ?>
