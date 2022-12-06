<?php require_once '../navigation/print_header_a4.php'; ?>

<?php
    require_once '../serverscripts/Classes/Drugs.php';

    $drug=new Drug();
 ?>

<div class="container-fluid">
  <div class="row" style="margin-bottom:70px">
    <div class="col-3">

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-8">
              <p class="big-text">
                <?php
                echo $drug->total_drugs;
               ?>
              </p>
              <p>Drugs Added So Far</p>
            </div>
            <div class="col-4">
              <div class="icon-box primary-color d-flex justify-content-center align-items-center">
                <i class="fas fa-pills" aria-hidden></i>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
    <div class="col-3">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-8">
              <p class="big-text">
                <?php	echo $drug->total_active;?>
              </p>
              <p>Active Drugs</p>
            </div>
            <div class="col-4">
              <div class="icon-box primary-color d-flex justify-content-center align-items-center">
                <i class="fas fa-check" aria-hidden></i>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
    <div class="col-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-8">
                <p class="big-text">
                  <?php	echo $drug->total_deleted;?>
                </p>
                <p>Deleted Drugs</p>
              </div>
              <div class="col-4">
                <div class="icon-box primary-color d-flex justify-content-center align-items-center">
                  <i class="fas fa-trash-alt" aria-hidden></i>
                </div>
              </div>
            </div>

          </div>
      </div>
    </div>
  </div>


  <div class="" id="data_holder">

    <div class="card">
      <div class="card-body py-5">
        <table class="datatables table table-condensed" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Unit</th>
              <th class="text-center">Qty</th>
              <th class="text-right">Cost Price</th>
              <th class="text-right">Retail Price</th>
              <th class="text-right">Profit</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $get_items=mysqli_query($db,"SELECT *
                                                              FROM
                                                                pharm_inventory
                                                              WHERE
                                                                status='active' && subscriber_id='".$active_subscriber."'
                                                              ORDER BY
                                                                generic_name asc
                                                    ")  or die(mysqli_error($db));
            $i=1;
            while ($rows=mysqli_fetch_array($get_items)) {

                $category_id=$rows['category'];

                $drug->drug_id=$rows['drug_id'];
                $drug->DrugInfo();

              ?>


            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $drug->drug_name; ?></td>
              <td><?php echo $rows['unit']; ?></td>
              <td class="text-center"><?php echo (int) $drug->qty_rem; ?></td>
              <td class="text-right"><?php echo $drug->cost_price; ?></td>
              <td class="text-right"><?php echo $drug->retail_price; ?></td>
              <td class="text-right"><?php echo $drug->profit_margin; ?></td>
            </tr>
            <?php
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>


  </div>

</div>



  <script type="text/javascript">
    print();
  </script>
