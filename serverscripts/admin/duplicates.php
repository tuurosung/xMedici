<ul class="list-group">

<?php
require_once '../dbcon.php';
$i=1;
$select=mysqli_query($db,"SELECT drug_name, count(*) FROM inventory GROUP BY drug_name HAVING COUNT(*) > 1") or die('failed');
while ($rows=mysqli_fetch_array($select)) {
  ?>

  <li class="list-group-item">
    <?php echo $i++.'. '.$rows['drug_name'] ?>
  </li>

  <?php
}



?>


</ul>
