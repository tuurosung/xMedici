<?php require_once ('../navigation/header.php'); ?>
    <?php require_once ('../navigation/admin_nav.php'); ?>

    <main class="pt-3 pb-5 mx-lg-5">
      <div class="container-fluid mt-2">

        <div class="row mb-5">
          <div class="col-md-6">
              <h4 class="titles montserrat">Account Balances</h4>
          </div>
          <div class="col-md-6">

          </div>
        </div>


        <?php
        $get_account_types=mysqli_query($db,"SELECT * FROM account_types") or die(mysqli_error($db));
        while ($account_types=mysqli_fetch_array($get_account_types)) {
          ?>
          <div class="py-2 px-3 primary-color-dark white-text mb-4">
            <p class="font-weight-bold montserrat m-0"><?php echo $account_types['description']; ?></p>
          </div>
          <div class="">
              <table class="table table-condensed">
              <thead>
                <tr>
                  <th  class="col-5">Account Name</th>
                  <th class="text-right">Debit</th>
                  <th class="text-right">Credit</th>
                  <th class="text-right">Transfer</th>
                  <th class="text-right">Balance</th>
                </tr>
              </thead>
              <tbody>


                <?php
                $balance=0;
                $get_accounts=mysqli_query($db,"
                SELECT a.sn,subscriber_id,account_header,account_number,account_name,debit,credit,balance,h.type FROM all_accounts a
                LEFT JOIN account_headers h on h.sn=a.account_header
                WHERE h.type='".$account_types['sn']."' && subscriber_id='".$active_subscriber."'
                ORDER BY a.sn
                ") or die(mysqli_error($db));
                  while ($rows=mysqli_fetch_array($get_accounts)) {
                    ?>
                    <tr>
                      <td class="col-5 font-weight-bold"><a href="account_transactions.php?account_number=<?php echo $rows['account_number']; ?>"> <?php echo $rows['account_name']; ?> </a> </td>
                      <td  class="text-right"><?php echo number_format($rows['debit'],2); ?></td>
                      <td class="text-right"><?php echo number_format($rows['credit'],2); ?></td>
                      <td class="text-right"><?php echo number_format($rows['transfer'],2); ?></td>
                      <td class="text-right"><?php echo number_format($rows['balance'],2); ?></td>
                    </tr>
                    <?php
                    $balance+=$rows['balance'];
                  }
                ?>
                <tr class="font-weight-bold">
                  <td colspan="4" class="text-right">Total <?php echo $account_types['description']; ?></td>
                  <td  class="text-right"><?php echo number_format($balance,2); ?></td>
                </tr>
              </tbody>
            </table>

          </div>
          <div class="mb-5">

          </div>
          <?php
        }
         ?>


    </div>



    <div id="new_expenditure_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-side modal-top-right">
        <div class="modal-content">
          <div class="modal-header info-color-dark">
            <h4 class="modal-title white-text" id="myModalLabel">New Expenditure</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>
          <form id="expenditure_frm">
          <div class="modal-body">
              <div class="form-group">
                <input type="text" class="form-control" id="expenditure_id" name="expenditure_id" placeholder="Expenditure ID" readonly>
              </div>

              <div class="form-group">
                <select class="browser-default custom-select" name="header_id" required>

                  <?php
                    require_once '../serverscripts/dbcon.php';
                    $get_headers=mysqli_query($db,'SELECT * FROM expenditure_headers ORDER BY header_name ASC') or die('failed');
                    while ($rows=mysqli_fetch_array($get_headers)) {
                      ?>
                      <option value="<?php echo $rows['header_id']; ?>"> <?php echo $rows['header_name']; ?></option>
                      <?php
                    }
                   ?>
                </select>
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Expenditure Purpose" required>
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="amount" name="amount" placeholder="Expenditure Amount" required>
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="date" name="date" placeholder="Date" value="<?php echo date('Y-m-d'); ?>" required>
              </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Record Expenditure</button>
          </div>
        </form>
        </div>
      </div>
    </div>



    <div id="new_category_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-side modal-top-right" style="width:300px">
        <div class="modal-content">
          <div class="modal-header info-color-dark">
            <h4 class="modal-title white-text" id="myModalLabel">New Category</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="new_expenditure_header_frm">
          <div class="modal-body">

            <!-- <div class="form-group">
              <label for="" class="label">Header ID</label>
              <input type="text" class="form-control" name="header_id" id="header_id">
            </div> -->

            <div class="form-group">
              <label for="" class="label">Header Name</label>
              <input type="text" class="form-control" name="header_name" id="header_name">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"> Create Category </button>
          </div>
          </form>
        </div>
      </div>
    </div>









    <div id="modal_holder">

    </div>
    <?php require_once ('../navigation/footer.php'); ?>


    <script type="text/javascript">

      $('#date').datepicker()

      // $('.list-group-item').removeClass('active')
      // $('#accounting_nav').addClass('active')
      // $('#accounting_submenu').addClass('show')
      $('#reports_nav').addClass('active-submenu')

      $('#date').on('change', function(event) {
        event.preventDefault();
        $(this).datepicker('hide')
      });



      $('.accounts').on('click', function(event) {
        event.preventDefault();
        var account_number=$(this).attr('ID')
        $.ajax({
          url: '../serverscripts/restadmin/account_history.php?account_number='+account_number,
          type: 'GET',
          success:function(msg){
            $('#data_holder').html(msg)
            $('html, body').animate({
                scrollTop: $("#data_holder").offset().top
            }, 2000);

          }
        })
      });



      $('.transfer_funds').on('click', function(event) {
        event.preventDefault();
        var account_number=$(this).attr("ID")
        $.ajax({
          url: '../serverscripts/restadmin/transfer_funds_modal.php?account_number='+account_number,
          type: 'GET',
          success:function(msg){
              $('#modal_holder').html(msg)
              $('#transfer_funds_modal').modal('show')

              $('#transfer_funds_frm').on('submit', function(event) {
                event.preventDefault();
                bootbox.confirm('Do you want to proceed with this transaction?',function(r){
                  if(r===true){
                    $.ajax({
                      url: '../serverscripts/restadmin/transfer_funds_frm.php',
                      type: 'GET',
                      data:$('#transfer_funds_frm').serialize(),
                      success:function(msg){
                        if(msg==='save_successful'){
                          bootbox.alert('Transfer Successful',function(){
                            window.location.reload()
                          })
                        }
                        else {
                          bootbox.alert(msg)
                        }
                      }
                    })
                  }
                })
              });
          }
        })
      });

    </script>
</body>
</html>
