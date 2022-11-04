<div class="row">
  <div class="col-md-12">

    <table class="table" style="margin-bottom:40px">

      <tbody>
        <tr style="background-color:#7000ad; color:#fff">
          <td>#</td>
          <td>Item Name</td>
          <td>Last Stocking Date</td>
          <td class="text-center">Qty</td>
        </tr>
        <?php
        require_once '../dbcon.php';
        $select_items=mysqli_query($db,"SELECT * FROM inventory") or die('failed');
        $i=1;
        while ($inventory_rows=mysqli_fetch_array($select_items)) {
          $get_quantities=mysqli_query($db,"SELECT SUM(qty_rem) AS qty_rem FROM stock WHERE drug_id='".$inventory_rows['drug_id']."'") or die('failed');
          $get_quantities=mysqli_fetch_assoc($get_quantities);

          $last_stocking_date=mysqli_query($db, "SELECT MAX(stock_date) as last_stocked FROM stock WHERE drug_id='".$inventory_rows['drug_id']."'") or die('failed');
          $last_stocking_date=mysqli_fetch_assoc($last_stocking_date);

          if($get_quantities['qty_rem'] < $inventory_rows['restock_level'] ){
            ?>

            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $inventory_rows['drug_name']; ?></td>
              <td><?php echo $last_stocking_date['last_stocked']; ?></td>
              <td class="text-center"><?php echo $get_quantities['qty_rem']; ?></td>
            </tr>

            <?php
          }
          else {
            continue;
          }
        }

         ?>
      </tbody>
    </table>


  </div>
</div>
