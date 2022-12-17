$('.sidebar-fixed .list-group-item').removeClass('active')
$('#accounting_nav').addClass('active')
$('#accounting_submenu').addClass('show')
$('#billing_li').addClass('font-weight-bold')

$('.bill_info').on('click', function (event) {
    event.preventDefault();
    var bill_id = $(this).attr('ID')
    $.get('../serverscripts/admin/Cashier/bill_info.php?bill_id=' + bill_id, function (msg) {
        $('#modal_holder').html(msg)
        $('#bill_info_modal').modal('show')
    })
});

$('.table tbody').on('click', '.delete_bill', function (event) {
    event.preventDefault();
    var bill_id = $(this).attr('ID')
    bootbox.confirm("Delete this invoice?", function (r) {
        if (r === true) {
            $.get('../serverscripts/admin/Billing/delete_bill.php?bill_id=' + bill_id, function (msg) {
                if (msg === 'delete_successful') {
                    bootbox.alert("Bill Invoice Deleted Successfully", function () {
                        window.location.reload()
                    })
                } else {
                    bootbox.alert(msg)
                }
            })
        }
    })

});

$('.payment_btn').on('click', function (event) {
    event.preventDefault();
    var bill_id = $(this).attr('ID')
    var patient_id = $(this).data('patient_id');
    var visit_id = $(this).data('visit_id');
    $.get('../Includes/modals/billing/payment_modal.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function (msg) {
        $('#modal_holder').html(msg)
        $('#new_payment_modal').modal('show')

        $('#amount_paid').on('keyup', function (event) {
            event.preventDefault();
            var amount_payable = $('#amount_payable').val()
            var amount_paid = $('#amount_paid').val()
            $('#balance').val((parseFloat(amount_payable) - parseFloat(amount_paid)).toFixed(2))
        });
        // End keyup

        $('#payment_frm').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: '../serverscripts/admin/Patients/payment_frm.php',
                type: 'GET',
                data: $('#payment_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Payment successful', function () {
                            window.location = 'payments.php'
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }); //end submit
    })
}); //end click


$('.modify_billing').on('click', function (event) {
    event.preventDefault();
    var bill_id = $(this).attr('ID')
    var patient_id = $(this).data('patient_id');
    var visit_id = $(this).data('visit_id');

    $.get('../serverscripts/admin/Billing/modify_billing.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function (msg) {
        $("#modal_holder").html(msg)
        $('#modify_billing_modal').modal('show')
        $('#modify_billing_frm').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: '../serverscripts/admin/Billing/modify_billing_frm.php',
                type: 'GET',
                data: $('#modify_billing_frm').serialize(),
                success: function (msg) {
                    if (msg === 'update_successful') {
                        bootbox.alert('Bill Amount Modified', function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        });
    })
});

