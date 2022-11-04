
<table style="width:300px">
  <tbody>
    <?php
    require_once '../dbcon.php';

    $drug_id=$_GET['drug_id'];
    $select=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$drug_id."' AND qty_rem >0") or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($select)) {
      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['batch_code']; ?></td>
        <td></td>
      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
