<?php
require_once '../dbcon.php';

session_start();

$issue_id=$_SESSION['active_issue_id'];

?>

<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Qty</th>
      <th>

      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select=mysqli_query($db,"SELECT * FROM stores_issueditems WHERE issue_id='".$issue_id."'") or die('failed1');
    $i=1;
    while ($rows=mysqli_fetch_array($select)) {
      ?>
      <tr>
        <td><?php echo $i++ ; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php echo $rows['qty'] ?></td>
        <td class="text-right">
          <i class="fa fa-trash-o"></i>
        </td>
      </tr>

      <?php
    }

    ?>


  </tbody>
</table>
