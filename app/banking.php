<?php require_once ('../navigation/header.php'); ?>
    <?php require_once ('../navigation/admin_nav.php'); ?>

    <?php
      $banking=new Banking();
      $acc=new Account();
     ?>

    <main class="pt-3 mx-lg-5">
      <div class="container-fluid mt-2">


        <?php

        $get_accounts=mysqli_query($db,"
        SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
        LEFT JOIN account_headers h on h.sn=a.account_header
        WHERE h.type=5 && subscriber_id='".$active_subscriber."';
        ") or die(mysqli_error($db));

        if(mysqli_num_rows($get_accounts)<1){
          die('<h4>Please create accounts for your expenditure.</h4>');
        }

         ?>

        <div class="row mb-5">
          <div class="col-4">
            <h4  class="titles montserrat">Bank Deposits</h4>
          </div>
          <div class="col-8">
            <div class="text-right">
              <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_deposit_modal">
                <i class="fas fa-plus mr-3"></i>
                New Deposit
              </button>
              <button type="button" class="btn warning-color-dark white-text btn-rounded" >
                <i class="fas fa-print mr-3"></i>
                Print Report
              </button>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-6">
                <h5 class="OpenSans mb-5">Banking Records</h5>
              </div>
              <div class="col-6">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" class="form-control" name="start_date" id="start_date" value="<?php echo $year_start; ?>">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" class="form-control" name="end_date" id="end_date" value="<?php echo $year_end; ?>">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <select class="custom-select browser-default" name="header_id" id="header_id">
                          <option value="all">All Headers</option>
                          <?php
                              $get_accounts=mysqli_query($db,"
                              SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                              LEFT JOIN account_headers h on h.sn=a.account_header
                              WHERE h.type=5 && subscriber_id='".$active_subscriber."';
                              ") or die('failed');
                              while ($accounts=mysqli_fetch_array($get_accounts)) {
                                ?>
                                <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                                <?php
                              }
                           ?>

                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <table class="table table-condensed datatables">
              <thead class="">
                <tr>
                  <th>#</th>
                  <th>Deposit ID</th>
                  <th>Date</th>
                  <th>Narration</th>
                  <th>Deposit Account</th>
                  <th>Source Account</th>
                  <th>Amount</th>
                  <th>Options</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  require_once '../serverscripts/dbcon.php';

                  $query=mysqli_query($db,"SELECT  * FROM banking WHERE subscriber_id='".$active_subscriber."' AND  status='active'") or die(mysqli_error($db));
                  $i=1;
                  while ($rows=mysqli_fetch_array($query)) {

                    $acc->account_number=$rows['deposit_account'];
                    $acc->AccountInfo();
                    $deposit_account_name=$acc->account_name;


                    $acc->account_number=$rows['source_account'];
                    $acc->AccountInfo();
                    $source_account_name=$acc->account_name;


                    $expenditure_account_info=account_info($rows['expenditure_account']);
                    $payment_account_info=account_info($rows['payment_account']);
                      ?>
                      <tr>
                        <td class=""><?php echo $i++; ?></td>
                        <td class=""><?php echo $rows['deposit_id']; ?></td>
                        <td class=""><?php echo $rows['date']; ?></td>
                        <td class=""><?php echo $rows['narration']; ?></td>
                        <td class=""><?php echo $deposit_account_name; ?></td>
                        <td class=""><?php echo $source_account_name; ?></td>
                        <td class=""><?php echo number_format($rows['amount'],2); ?></td>
                        <td class="text-right">
                          <div class="dropdown open">
                            <a class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Option
                            </a>
                            <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
                              <ul class="list-group">
                                <li class="list-group-item edit edit" id="<?php echo $rows['deposit_id']; ?>">
                                  <i class="fas fa-pencil-alt mr-3" aria-hidden></i>
                                  Edit</li>
                                <li class="list-group-item delete" id="<?php echo $rows['deposit_id']; ?>">
                                  <i class="far fa-trash-alt mr-3" aria-hidden></i>
                                  Delete</li>
                              </ul>
                            </div>
                          </div>
                        </td>
                      </tr>

                    <?php
                  }
                  ?>

              </tbody>
            </table>

          </div>
        </div>


    </div>



    <div id="new_deposit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-side modal-top-right">
        <div class="modal-content">

          <form id="new_deposit_frm" autocomplete="off">
          <div class="modal-body">
            <h6 class="font-weight-bold montserrat">New Deposit</h6>
            <hr class="hr">


              <div class="form-group">
                <label for="">Source Account</label>
                <select class="custom-select browser-default" name="source_account" id="source_account" required>
                  <?php
                      $get_accounts=mysqli_query($db,"
                      SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                      LEFT JOIN account_headers h on h.sn=a.account_header
                      WHERE h.type=1 && subscriber_id='".$active_subscriber."';
                      ") or die('failed');
                      while ($accounts=mysqli_fetch_array($get_accounts)) {
                        ?>
                        <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                        <?php
                      }
                   ?>
                </select>
              </div>



              <div class="form-group">
                <label for="">Amount Deposited</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="" required>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Deposit Account</label>
                    <select class="custom-select browser-default" name="deposit_account" id="deposit_account" required>
                      <?php
                          $get_accounts=mysqli_query($db,"
                          SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                          LEFT JOIN account_headers h on h.sn=a.account_header
                          WHERE h.sn=1 && subscriber_id='".$active_subscriber."';
                          ") or die('failed');
                          while ($accounts=mysqli_fetch_array($get_accounts)) {
                            ?>
                            <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                            <?php
                          }
                       ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Deposit Date</label>
                    <input type="text" class="form-control" id="deposit_date" name="deposit_date" placeholder="" value="<?php echo $today; ?>" required>
                  </div>
                </div>
              </div>


              <div class="form-group">
                <label for="">Narration</label>
                <textarea  class="form-control" id="narration" name="narration" placeholder=""></textarea>
              </div>



          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Record Deposit</button>
          </div>
        </form>
        </div>
      </div>
    </div>




<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
  $('.sidebar-fixed .list-group-item').removeClass('active')
  $('#accounting_nav').addClass('active')
  $('#accounting_submenu').addClass('show')
  $('#deposit_li').addClass('font-weight-bold')

    $('#header_id').select2()


    $('#new_deposit_modal').on('shown.bs.modal', function(event) {
      event.preventDefault();
      $('#deposit_date').datepicker()
      $('#amount').focus();
    });

    $('#start_date,#end_date,#header_id').on('changeDate', function(event) {
      event.preventDefault();
      var start_date=$('#start_date').val()
      var end_date=$('#end_date').val()
      var header_id=$('#header_id').val()
      $.get('../serverscripts/admin/Expenditure/expenditure_report.php?start_date='+start_date+'&end_date='+end_date+'&header_id='+header_id,function(msg){
        $('#data_holder').html(msg)
        $('.report_table').DataTable({
          'sorting':false,
          'paging':false,
          'searching':false,
          language: { search: "" }
        })
      })
    });

    $('#header_id').on('change', function(event) {
      event.preventDefault();
      var start_date=$('#start_date').val()
      var end_date=$('#end_date').val()
      var header_id=$('#header_id').val()
      $.get('../serverscripts/admin/Expenditure/expenditure_report.php?start_date='+start_date+'&end_date='+end_date+'&header_id='+header_id,function(msg){
        $('#data_holder').html(msg)
        $('.report_table').DataTable({
          'sorting':false,
          'paging':false,
          'searching':false,
          language: { search: "" }
        })
      })
    });



    $('#new_deposit_frm').on('submit', function(event) {
      event.preventDefault();
      var deposit_account=$('#deposit_account').val()
      var source_account=$('#source_account').val()

      if(deposit_account==source_account){
        bootbox.alert('Sorry, source account cannot be the same as the deposit account');
      }else {
        bootbox.confirm("Record this deposit?",function(r){

          if(r===true){
            $.ajax({
              url: '../serverscripts/admin/Banking/new_deposit_frm.php',
              type: 'GET',
              data:$('#new_deposit_frm').serialize(),
              success:function(msg){
                if(msg==='save_successful'){
                  bootbox.alert('Banking Recorded Successfully',function(){
                      window.location.reload();
                  })
                }
                else {
                  bootbox.alert(msg)
                }
              }//end success
            })//end ajax
          }//end if
        })
      }//end if

    });



    $('.table tbody').on('click', '.delete', function(event) {
      event.preventDefault();
      var deposit_id=$(this).attr('ID')
      bootbox.confirm("Do you want to delete this deposit?",function(r){
        if(r===true){
          $.get('../serverscripts/admin/Banking/delete_deposit.php?deposit_id='+deposit_id,function(msg){
            if(msg==='delete_successful'){
              bootbox.alert("Deposit deleted successfully",function(){
                window.location.reload()
              })
            }else {
                bootbox.alert(msg)
            }
          })
        }
      })
    });

    $('.table tbody').on('click', '.edit', function(event) {
      event.preventDefault();
      var deposit_id=$(this).attr('ID')
      $.get('../serverscripts/admin/Banking/edit_deposit_modal.php?deposit_id='+deposit_id,function(msg){
        $('#modal_holder').html(msg)
        $('#edit_deposit_modal').modal('show')

        $('#edit_deposit_modal').on('shown.bs.modal', function(event) {
          event.preventDefault();
          $('#edit_deposit_date').datepicker()
        });

        $('#edit_deposit_date').on('change', function(event) {
          event.preventDefault();
          $(this).datepicker('hide')
        });


        $('#edit_deposit_frm').on('submit', function(event) {
          event.preventDefault();
          bootbox.confirm("Update this deposit?",function(r){
            if(r===true){
              $.ajax({
                url: '../serverscripts/admin/Banking/edit_deposit_frm.php',
                type: 'GET',
                data:$('#edit_deposit_frm').serialize(),
                success:function(msg){
                  if(msg==='update_successful'){
                    bootbox.alert("Deposit information updated successfully",function(){
                      window.location.reload()
                    })
                  }else {
                    bootbox.alert(msg)
                  }
                }
              })//end ajax
            }//end if
          })//end confirm
        });//end submit
      })
    });

	</script>

</html>
