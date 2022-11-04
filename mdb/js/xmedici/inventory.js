$('.drugs').on('click', function(event) {
  event.preventDefault();
  var drug_id=$(this).attr('ID');
  window.location='drug_matrix.php?drug_id='+drug_id
});

$('#drug_category_filter').select2();

$('#print').on('click', function(event) {
  event.preventDefault();
  print_popup('print_inventory.php');
});


$('#new_drug_modal').on('shown.bs.modal', function(event) {
  event.preventDefault();
  $('#drug_name').focus()
});//end modal shown

$('#new_drug_frm').on('submit', function(event) {
  event.preventDefault();
  bootbox.confirm("Add this drug to inventory",function(r){
    if(r===true){
      $.ajax({
        url: '../serverscripts/admin/Drugs/new_drug_frm.php',
        type: 'GET',
        data: $('#new_drug_frm').serialize(),
        success: function(msg){
          if(msg==='save_successful'){
            bootbox.alert("Drug added successfully",function(){
              window.location='drug_matrix.php'
            })
          }
          else {
            bootbox.alert(msg)
          }
        }
      })
    }
  })

});//end submit


$('.table tbody').on('click','.edit', function(event) {
  event.preventDefault();
   var drug_id=$(this).attr('ID')

   $.ajax({
    url: '../serverscripts/admin/inventory_edit.php?drug_id='+drug_id,
    type: 'GET',
    success: function(msg){
      $('#modal_holder').html(msg)

      $('body').removeClass('modal-open');
      $('.modal-backdrop').remove()

      $('#edit_drug_modal').modal('show')

      $('#edit_drug_frm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
          url: '../serverscripts/admin/edit_item_frm.php',
          type: 'GET',
          data: $(this).serialize(),
          success: function(msg){
            if(msg==='save_successful'){
              bootbox.alert('Drug Information Updated Successfully',function(){
                window.location.reload()
              })
            }
            else {
              bootbox.alert(msg)
            }
          }
        })//end ajax
      });//end edit form
    }
   })

});//end edit function


$('.table tbody').on('click','.delete', function(event) {
  event.preventDefault();

  var drug_id=$(this).attr('ID')
  bootbox.confirm("Are you sure you want to delete this item?",function(r){
    if(r===true){
      $.ajax({
        url: '../serverscripts/admin/inventory_delete.php?drug_id='+drug_id,
        type: 'GET',
        success: function(msg){
          if(msg==='delete_successful'){
            bootbox.alert("Drug deleted successfully",function(){
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
});//end delete
