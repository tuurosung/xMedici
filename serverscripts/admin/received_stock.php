<div class="header">
    <h4 class="title">Recieved Drugs History</h4>
    <p class="category">Displays the stock value of each drug in inventory</p>
</div>
<hr>
<div class="content">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Count</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once '../dbcon.php';
        $i=1;
        $select=mysqli_query($db,"SELECT DISTINCT stock_date FROM stock") or die('failed');
        while ($rows=mysqli_fetch_array($select)) {
          $count_drugs=mysqli_query($db,"SELECT COUNT(*) AS count_drugs FROM stock WHERE stock_date='".$rows['stock_date']."'") or die('failed');
          $count_drugs=mysqli_fetch_assoc($count_drugs);
          ?>

          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $rows['stock_date']; ?></td>
            <td><?php echo $count_drugs['count_drugs']; ?></td>
            <td class="text-right">
              <button type="button" class="btn btn-primary btn-sm print_btn" id="<?php echo $rows['stock_date']; ?>">
                Print
              </button>
            </td>
          </tr>

          <?php
        }

        ?>

      </tbody>
    </table>
  </div>
