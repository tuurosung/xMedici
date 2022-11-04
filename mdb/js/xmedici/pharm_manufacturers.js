
		$('#new_manufacturer_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			$('#manufacturer_name').focus()

			$('#new_manufacturer_frm').on('submit', function(event) {
				event.preventDefault();
				bootbox.confirm("Add this manufacturer?",function(r){
					if(r===true){
						$.ajax({
							url: '../serverscripts/admin/Drugs/new_manufacturer_frm.php',
							type: 'GET',
							data: $('#new_manufacturer_frm').serialize(),
							success: function(msg){
								if(msg==='save_successful'){
									bootbox.alert("Manufacturer added successfully",function(){
										window.location.reload()
									})
								}
								else {
									bootbox.alert(msg,function(){
										window.location.reload()
									})
								}
							}
						})//end ajax
					}
				})

			});//end submit
		});//end modal shown


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
									alert('Drug Information Updated Successfully')
									window.location.reload()
								}
								else {
									alert(msg)
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
								bootbox.alert("Item deleted successfully",function(){
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
