<div id="email_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content ">
      <form id="update_user_email_frm">
      <div class="modal-body">
        <h6 class="font-weight-bold"><i class="fas fa-envelope-open mr-3" aria-hidden></i> Secure you account.</h6>
        <hr>

        <p class="mt-4">Hi <?php echo $user_fullname; ?>,</p>

        <p>Update your email so you don't get locked out of your account.</p>


        <div class="form-group mt-5">
          <label for="">Email Address</label>
          <input type="email" name="email_address" id="email_address" class="form-control" value="" placeholder="enter your email" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn elegant-color-dark white-text" data-dismiss="modal"><i class="fas fa-clock mr-2"></i>Remind Me Later</button>
        <button type="submit" class="btn btn-white"><i class="fas fa-check mr-2"></i>Confirm my email</button>
      </div>
      </form>
    </div>
  </div>
</div>


<?php
  ob_end_flush();
 ?>
<script src="../mdb/js/jquery-3.3.1.min.js"></script>
<script src="../mdb/js/popper.min.js"></script>
<script src="../mdb/js/bootstrap.js"></script>
<script src="../mdb/js/bootbox.all.min.js"></script>
<!-- <script src="../datatables/datatables.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.5/datatables.min.js"></script>
<script src="../mdb/js/bootstrap-datepicker.js"></script>
<script src="../mdb/js/mdb.js"></script>
<script src="../mdb/js/dropzone.js"></script>
<script src="../mdb/js/modules/chart.js"></script>
<!-- <script src="../mdb/js/printThis.js"></script> -->

<script src="../mdb/js/bootbox.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.tiny.cloud/1/6y37qpazcygcur4wavc0zhjg0d7r1rjjtxxaammod9aeucv8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">

  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  	$('.select2').select2()



  $('.datatable').DataTable({
    'searching':false,
    "pagingType": "full_numbers",
    "pageLength": 20,
    language: { search: "" }
  })

  $('.datatables').DataTable({
    "pagingType": "full_numbers",
    "pageLength": 20,
    language: { search: '', searchPlaceholder: "Search..." },
  })

  $('#start_date,#end_date').datepicker()

  $('#start_date,#end_date').on('change', function(event) {
    event.preventDefault();
    $(this).datepicker('hide')
  });

  function print_popup(id){
    window.open(id, "popupWindow", "width=620,height=600,scrollbars=yes");
  }

  $('#activity_type').on('change', function(event) {
    event.preventDefault();
    if($(this).val()==='break' || $(this).val()==='leave'){
      $('#destination').prop('readonly', false)
    }
    else if ($(this).val()==='attendance') {
      $('#destination').prop('readonly', true)
      $('#destination').val('N/A')
    }
  });

  $('#activity_log_frm').one('submit', function(event) {
    event.preventDefault()
    bootbox.confirm("Log this activity?",function(r){
      if(r===true){
        $.ajax({
          url: '../serverscripts/admin/activity_log_frm.php',
          type: 'GET',
          data:$('#activity_log_frm').serialize(),
          success:function(msg){
            if(msg==='save_successful'){
              bootbox.alert('Activity logged successfully',function(){
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

  $('#findpatientsearch').focus(function() {
    $('#findpatientresultsholder').addClass('findpatientresultsholder')
  });

  $('#findpatientsearch').blur(function() {
    $('#findpatientresultsholder').removeClass('findpatientresultsholder')
  });

  // $('#findpatientsearch').on('keyup', function(event) {
  //   event.preventDefault();
  //   var search_term=$(this).val();
  //   $.get('../serverscripts/admin/Patients/findpatientsearch.php?search_term='+search_term,function(msg){
  //     $('#findpatientresultsholder').html(msg)
  //   })
  // });





</script>
