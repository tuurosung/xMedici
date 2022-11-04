<?php
    require_once '../../dbcon.php';
    $search_term = $_GET['searchTerm'];

    $query=mysqli_query($db,"SELECT patient_id,surname,othernames
                                                FROM
                                                  patients
                                                WHERE
                                                  (surname LIKE '%".$search_term."%'
                                                      OR
                                                    othernames LIKE '%".$search_term."%'
                                                      OR
                                                    patient_id LIKE '%".$search_term."%'
                                          ) AND subscriber_id='".$active_subscriber."' LIMIT 20") or die(mysqli_error($db));

    $json = [];
    while($row =mysqli_fetch_array($query)){
        $json[] = ['id'=>$row['patient_id'], 'text'=>ucfirst(mb_strtolower( $row['surname'])).' '.ucfirst(mb_strtolower( $row['othernames']))];
    }

    echo json_encode($json);
?>
