<?php
    require_once '../../dbcon.php';
    $search_term = $_GET['searchTerm'];

    $query=mysqli_query($db,"SELECT drug_id,trade_name,generic_name
                                              FROM pharm_inventory
                                              WHERE
                                              (generic_name LIKE '%".$search_term."%' OR trade_name LIKE '%".$search_term."%') AND status='active' && subscriber_id='".$active_subscriber."'

                                              ") or die(mysqli_error($db));

    $json = [];
    while($row =mysqli_fetch_array($query)){
        $json[] = ['id'=>$row['drug_id'], 'text'=>$row['generic_name'].' '.$row['trade_name']];
    }

    echo json_encode($json);
?>
