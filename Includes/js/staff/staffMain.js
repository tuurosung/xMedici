$('#new_staff_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/Staff/new.php',
        type: 'GET',
        data: $('#new_staff_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert("New Staff Added Successfully", function () {
                    window.location.reload()
                })
            } else {
                bootbox.alert(msg)
            }
        }
    }) //end ajax
}); // new staff frm on submit end



$('.table tbody').on('click', '.edit', function (event) {
    event.preventDefault();
    var staff_id = $(this).data('staff_id')
    $.get('../Includes/modals/staff/editStaff.php?staff_id=' + staff_id, function (msg) {
        $('#modal_holder').html(msg)
        $('#editStaff_modal').modal('show')

        $('#editStaff_frm').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: '../serverscripts/admin/Staff/editStaff.php',
                type: 'GET',
                data: $('#editStaff_frm').serialize(),
                success: function (msg) {
                    if (msg === 'update_successful') {
                        bootbox.alert("Staff information updated successfully", function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            }) //end ajax
        });
    })
});


$('table tbody').on('click', '.delete', function (event) {
    event.preventDefault();
    var staff_id = $(this).data('staff_id')
    bootbox.confirm("Do you want to delete this account?", function (r) {
        if (r === true) {
            $.get('../serverscripts/admin/Staff/delete.php?staff_id=' + staff_id, function (msg) {
                if (msg === 'delete_successful') {
                    bootbox.alert("Success. Account deleted", function () {
                        window.location.reload()
                    })
                } else {
                    bootbox.alert(msg)
                }
            }) //end get
        } //end if
    }) //end confirm
});