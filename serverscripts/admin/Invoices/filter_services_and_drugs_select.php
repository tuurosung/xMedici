<?php
    require_once '../../dbcon.php';
    $search_term = $_GET['searchTerm'];


    $query=mysqli_query($db, "SELECT id, one,two,three FROM(
                                                    SELECT drug_id as id, generic_name as one, trade_name as two,retail_price as three FROM
                                                        pharm_inventory WHERE (generic_name LIKE '%".$search_term."%' OR trade_name LIKE '%".$search_term."%') AND subscriber_id='".$active_subscriber."'

                                                    UNION

                                                    SELECT service_id, description as one,'' as two,service_cost as three FROM
                                                        services WHERE description LIKE '%".$search_term."%' AND subscriber_id='".$active_subscriber."'

                                                )results LIMIT 5
    ")or die(mysqli_error($db));

    // $query=mysqli_query($db,"SELECT patient_id,surname,othernames
    //                                             FROM
    //                                               patients
    //                                             WHERE
    //                                               (surname LIKE '%".$search_term."%'
    //                                                   OR
    //                                                 othernames LIKE '%".$search_term."%'
    //                                                   OR
    //                                                 patient_id LIKE '%".$search_term."%'
    //                                       ) AND subscriber_id='".$active_subscriber."' LIMIT 20") or die(mysqli_error($db));

    $json = [];
    while($row =mysqli_fetch_array($query)){
        $json[] = ['id'=>ucfirst(mb_strtolower( $row['one'])).' '.ucfirst(mb_strtolower( $row['two'])), 'text'=>ucfirst(mb_strtolower( $row['one'])).' '.ucfirst(mb_strtolower( $row['two'])).' - GHS' .$row['three']];
    }

    echo json_encode($json);
?>
