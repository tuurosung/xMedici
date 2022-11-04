<?php

    require '../../dbcon.php';
    require '../../Classes/Banking.php';

    $deposit_id=clean_string($_GET['deposit_id']);


    $banking=new Banking();

    $banking->deposit_id=$deposit_id;
    echo $banking->DeleteDeposit();
 ?>
