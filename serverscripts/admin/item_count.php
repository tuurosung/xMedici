<?php
require_once '../dbcon.php';

$select=mysqli_query($db,"SELECT COUNT(*) AS item_count FROM inventory") or die('failed');
$item_count=mysqli_fetch_assoc($select);
echo $item_count['item_count'].' Items';



?>
