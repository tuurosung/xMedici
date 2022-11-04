<table class="table datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Voucher ID</th>
      <th>Source</th>
      <th>Deposit Acc.</th>
      <th>Amount</th>
      <th>Depositor</th>

    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_expenditure=mysqli_query($db,"SELECT * FROM banking")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_expenditure)) {

      $get_source=mysqli_query($db,"SELECT * FROM Accounts WHERE account_id='".$rows['source_account']."'") or die('failed');
      $get_source=mysqli_fetch_array($get_source);

      $get_deposit=mysqli_query($db,"SELECT * FROM Accounts WHERE account_id='".$rows['deposit_account']."'") or die('failed');
      $get_deposit=mysqli_fetch_array($get_deposit);


      ?>

      <tr id="<?php echo $rows['expenditure_id']; ?>">
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['date']; ?></td>
        <td><?php echo $rows['voucher_id']; ?></td>
        <td><?php echo $get_source['account_name']; ?></td>
        <td><?php echo $get_deposit['account_name']; ?></td>
        <td><?php echo $rows['deposit_amount']; ?></td>
        <td><?php echo $rows['depositor_name']; ?></td>




      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
