
<table class="table datatables" style="font-size:15px; font-family:roboto" >
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Status</th>
      <th>Avail. Qty</th>

    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_items=mysqli_query($db,"SELECT item_code,item_name,restock_level FROM inventory")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_items)) {

      $get_qty=mysqli_query($db,  "SELECT SUM(qty_rem) as total_qty FROM stock WHERE item_code='".$rows['item_code']."'") or die('failed');
      $get_qty=mysqli_fetch_assoc($get_qty);

      $total_qty=$get_qty['total_qty'];

      if($total_qty > $rows['restock_level']){
        $status='In-Stock';
      }
      else
      if($total_qty < $rows['restock_level'] || $total_qty !== 0)
      {
        $status='Critical-Level';
      }
      else
      if($total_qty==0)
      {
        $status='Out-Of-Stock';
      }

      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['item_name']; ?></td>
        <td><?php echo $status; ?></td>
        <td><?php  echo $total_qty; ?></td>


      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
