<?php
    require_once '../../dbcon.php';
    $search_term = $_GET['searchTerm'];

    $query=mysqli_query($db,"SELECT code,description FROM sys_procedures WHERE description LIKE '%".$search_term."%' LIMIT 20") or die(mysqli_error($db));

    $json = [];
    while($row =mysqli_fetch_array($query)){
        $json[] = ['id'=>$row['description'], 'text'=>$row['description']];
    }

    echo json_encode($json);
?>
