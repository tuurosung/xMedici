<?php
  require_once '../dbcon.php';
  // $month=date('m');
  $yesterday=date('Y-m-d',strtotime(yesterday));
  $query=mysqli_query($db,"

  SELECT date AS dates,SUM(total) as amount FROM cart
  LEFT JOIN sales on sales.transaction_id=cart.transaction_id
  WHERE sales.date BETWEEN '".$month_start."' AND '".$month_end."' && cart.subscriber_id='".$active_subscriber."' && sales.subscriber_id='".$active_subscriber."' && cart.status='active'
  GROUP BY date
  ") or die(mysqli_error($db));
  $data=array();
  foreach ($query as $results) {
    $data[] = $results;
  }

  echo json_encode($data);

 ?>
