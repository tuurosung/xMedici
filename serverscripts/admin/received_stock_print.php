<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.css" media="" title="no title">
    <title></title>
  </head>
  <body>
    <?php
    $stock_date=$_GET['stock_date'];
    ?>

    <div class="" style="font-family:roboto; border-top-style:solid; border-top-width:1px; border-bottom-style:solid; border-bottom-width:1px; margin-bottom:10px">
      <div class="row" style="padding:10px">
        <div class="text-center">
          <span style="font-size:20px; font-weight:bold">DARTAH PHARMACY LIMITED</span>	<br>
          <span style="font-size:16px; ">DOKPONG BRANCH</span> <br>
          <span style="font-size:16px; ">WA-UPPER WEST REGION</span> <br>
          <span style="font-size:16px; ">NEW REGIONAL HOSPITAL MAIN ROAD</span> <br>
          <span style="font-size:16px; ">0208238834 / 0244444706 / 0207856467</span>
        </div>

      </div>
    </div>
    <div class="text-center" style="font-size:15px; font-weight:bold; margin-bottom:10px">
        Drugs Received On <?php echo $stock_date; ?>
    </div>
    <table class="table datatables card-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Drug ID</th>
          <th>Drug Name</th>
          <th>Qty Stkd</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once '../dbcon.php';

        $select=mysqli_query($db,"SELECT * FROM stock WHERE stock_date='".$stock_date."'") or die('failed');
        $i=1;
        while ($rows=mysqli_fetch_array($select)) {
          $get_drug_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$rows['drug_id']."'") or die('failed');
          $drug_info=mysqli_fetch_array($get_drug_info);

          ?>

          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $drug_info['drug_id']; ?></td>
            <td><?php echo $drug_info['drug_name']; ?></td>
            <td><?php  echo $rows['qty_stocked']; ?></td>

          </tr>

          <?php
        }
        ?>

      </tbody>
    </table>


  </body>
</html>



<style media="screen">
  .card-table > tbody > tr > td{
    font-family: tillana;
  }
  .card-table  thead {
    font-family: tillana;
    background-color: #fff;
    color: #000;
  }
  .card-table >thead > tr >th{
    color:#000;
  }
</style>
