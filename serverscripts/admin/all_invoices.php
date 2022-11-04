<div class="card">
  <div class="header">
      <h4 class="title">Purchase Invoices</h4>
  </div>
  <hr>
  <div class="content">
    <table class="table datatables">
      <thead>
        <tr>
          <th>#</th>
          <th>Invoice ID</th>
          <th>Supplier Inv. ID</th>
          <th>Supplier</th>
          <th>Purchase Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once '../dbcon.php';
        $get_items=mysqli_query($db,"SELECT * FROM invoices")  or die('failed');
        $i=1;
        while ($rows=mysqli_fetch_array($get_items)) {
          $get_supplier=mysqli_query($db,"SELECT * FROM suppliers WHERE supplier_id='".$rows['supplier_id']."'") or die('failed');
          $get_supplier=mysqli_fetch_array($get_supplier);
          $supplier_name=$get_supplier['supplier_name'];
          ?>

          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $rows['invoice_id']; ?></td>
            <td><?php echo $rows['supplier_invoice_id']; ?></td>
            <td><?php echo $supplier_name; ?></td>
            <td><?php echo $rows['purchase_date']; ?></td>

            <td class="text-right">
              <i class="fa fa-binoculars action_buttons view" id="<?php echo $rows['invoice_id']; ?>"></i>
              <i class="fa fa-pencil action_buttons edit" id="<?php echo $rows['invoice_id']; ?>"></i>
              <i class="fa fa-trash action_buttons delete" id="<?php echo $rows['sn']; ?>"></i>
            </td>

          </tr>

          <?php
        }
        ?>

      </tbody>
    </table>

  </div>
</div>
