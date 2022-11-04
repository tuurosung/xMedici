<table class="table datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Expenditure ID</th>
      <th>Header</th>
      <th>Purpose</th>
      <th>Amount</th>
      
    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_expenditure=mysqli_query($db,"SELECT * FROM expenditure")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_expenditure)) {

      $get_header=mysqli_query($db,"SELECT * FROM expenditure_headers WHERE sn='".$rows['expenditure_header']."'") or die('failed');
      $get_header=mysqli_fetch_array($get_header);


      ?>

      <tr id="<?php echo $rows['expenditure_id']; ?>">
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['date']; ?></td>
        <td><?php echo $rows['expenditure_id']; ?></td>
        <td><?php echo $get_header['header_name']; ?></td>
        <td><?php echo $rows['expenditure_purpose']; ?></td>
        <td><?php echo $rows['expenditure_amount']; ?></td>




      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
