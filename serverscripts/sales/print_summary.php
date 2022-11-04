<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.css" media="print" title="no title">

    <style media="print">
     .table > tbody{
       font-family: roboto;
     }

    body {
         column-count: 3;
         column-gap: 2em;
         column-rule: thin solid black;
         -moz-column-count:3; /* Firefox */
         -webkit-column-count:3; /* Safari and Chrome */
         -moz-column-gap:2em;
         -moz-column-rule:thin solid black;
          font-family: roboto;
     }

     /* Default left, right, top, bottom margin is 2cm */
    @page {
      margin: 5px;
      -moz-margin:5px;
    }

    /* First page, 10 cm margin on top */
    @page :first {
      margin-top: 10px;
      -moz-margin-top:10px;
    }

    /* Left pages, a wider margin on the left */
    @page :left {
      margin-left: 5px;
      margin-right: 5px;
      -moz-margin-left: 5px;
      -moz-margin-right: 5px;
    }

    @page :right {
      margin-left: 5px;
      margin-right: 5px;
      -moz-margin-left: 5px;
      -moz-margin-right: 5px;
    }

    section,table {
      page-break-before: always;
      -moz-page-break-before:always;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 2px;
      line-height: 1.0;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }
    .column-center-outer {float:left}

    @media print {
      body {
           column-count: 3;
           column-gap: 2em;
           column-rule: thin solid black;
           -moz-column-count:3; /* Firefox */
            -webkit-column-count:3; /* Safari and Chrome */
            font-family: roboto;
       }
       section {
         page-break-before: always;
         -moz-page-break-before:always;
       }
    }

    .table {
      -moz
    }

    </style>
  </head>


  <body>

    <div class="" style=" padding:10px">
      <div class="" style="font-family:myoswald-Bold; border-top-style:solid; border-top-width:1px; border-bottom-style:solid; border-bottom-width:1px">
        <div class="row" style="padding:10px">
          <!-- <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2">
            <img src="../../images/Total.svg.png"  style="width:80px"/>
          </div> -->
          <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 " >
            <span style="font-size:13px;margin-bottom:15px ">HOSPITAL ROAD TOTAL SERVICE STATION</span>
            <br>
            <span style="font-size:12px">P. O. BOX 13011, TAMALE. N/R</span>
            <br>
            <span style="font-size:11px">0208332205</span>
          </div>
        </div>


      </div>

      <div class="row" style="margin-top:15px; font-size:12px">
        <div class="col-md-6 col-xs-6 col-sm-6">
          Year: <?php echo date('Y'); ?>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-6">
          Month: <?php echo date('F'); ?>
        </div>
      </div>
      <div class="row" style="margin-top:15px; margin-bottom:15px; font-size:12px">
        <div class="col-md-6  col-xs-6 col-sm-6">
          Date: <?php echo date('Y-m-d'); ?>
        </div>
        <div class="col-md-6  col-xs-6 col-sm-6">
          Attendant: <?php echo $attendant; ?>
        </div>
      </div>

      <div class="" style="">
        <table class="table " style="font-size:12px; font-family:roboto;">
          <thead>
            <tr>

              <th>#</th>
              <th>Item Name</th>
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

              ?>

              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $rows['item_name']; ?></td>
                <td><?php  echo $total_qty; ?></td>


              </tr>

              <?php
            }
            ?>

          </tbody>
        </table>
      </div>

      <div class="row" style="margin-top:30px; border-top-style:double; border-top-width:1px; padding:20px">
        <div class="col-md-6 col-md-offset-6">
          Signature: ...............................
        </div>
      </div>
    </div>

  </body>
  <script type="text/javascript">
  //  print()
  </script>

</html>
