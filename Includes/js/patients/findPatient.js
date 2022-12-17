$('.sidebar-fixed .list-group-item').removeClass('active')
$('#patients_nav').addClass('active')

$('#findpatientsearch').on('keyup', function (event) {
    event.preventDefault();
    var search_term = $(this).val();
    $.get('../serverscripts/admin/Patients/findpatientsearch.php?search_term=' + search_term, function (msg) {
        $('#data_holder').html(msg)
    })
})

$('#new_patient_modal').on('shown.bs.modal', function (event) {
    event.preventDefault();
    $('#surname').focus();
});

if ($('#payment_mode').val() == 'cash') {
    $('#nhis_number').val('N/A')
    $('#nhis_number').attr('readonly', 'readonly')
} else {
    $('#nhis_number').val('')
    $('#nhis_number').attr('readonly', false)
}



$('#date_of_birth').datepicker()
$('#date_of_birth').on('changeDate', function (event) {
    event.preventDefault();
    $(this).datepicker('hide')
});

$('#payment_mode').on('change', function (event) {
    event.preventDefault();
    if ($(this).val() == 'cash') {
        $('#nhis_number').val('N/A')
        $('#nhis_number').attr('readonly', 'readonly')
    } else {
        $('#nhis_number').val('')
        $('#nhis_number').attr('readonly', false)
    }
});


$('#new_patient_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm("Create new folder?", function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/Patients/new_patient_frm.php',
                type: 'GET',
                data: $('#new_patient_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert("Success. Folder Created Successfully", function () {
                            window.location = 'patient_folder.php'
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })

}); //end submit