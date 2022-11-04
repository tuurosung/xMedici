
<?php
require_once('../dbcon.php');

$query=mysqli_query($db,"SELECT * FROM accounts") or die('failed');

$sum_query=mysqli_query($db,"SELECT SUM(actual_balance) as total_balance FROM accounts") or die('failed1');
$balance_remaining=mysqli_fetch_assoc($sum_query);
$total_balance=$balance_remaining['total_balance'];

?>
<?php
while ($rows=mysqli_fetch_array($query)) {
  ?>
  <div class="panel panel-primary accounts" id="<?php echo $rows['account_id']; ?>" style="cursor:pointer;">
    <div class="panel-heading text-uppercase">
      <?php echo $rows['account_name']; ?> &nbsp;&nbsp;&nbsp; <i class="fa fa-trash-o del" title="delete account" data-toggle="tooltip" id="<?php echo $rows['account_id']; ?>"></i>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-7">
          Opening Balance
        </div>
        <div class="col-md-5 text-right">
          &cent; <?php echo $rows['opening_balance']; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7">
          Total Income
        </div>
        <div class="col-md-5 text-right">
          &cent; <?php echo $rows['total_income']; ?>
        </div>
      </div>
      <div class="row" style="color:#c0392b">
        <div class="col-md-7" >
          Total Expenditure
        </div>
        <div class="col-md-5 text-right">
          - &cent; <?php echo $rows['total_expenditure']; ?>
        </div>
      </div>
    </div>
    <div class="panel-footer">
        <div class="row">
          <div class="col-md-7">
            Account Balance
          </div>
          <div class="col-md-5 text-right">
            &cent; <?php echo $rows['actual_balance']; ?>
          </div>
        </div>
    </div>
  </div>


  <?php
}

 ?>
<hr>
<div class="row">
  <div class="col-md-7">
    Overall Balance
  </div>
  <div class="col-md-5 text-right">
    &cent; <?php echo $total_balance; ?>
  </div>
</div>
<br><br>
