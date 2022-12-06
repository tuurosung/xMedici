$('.sidebar-fixed .list-group-item').removeClass('active')
$('#patients_nav').addClass('active')
$('#patients_submenu').addClass('show')
$('#patients_li').addClass('font-weight-bold')

var admission_id = '<?php echo $admission_id ?>'

// $('.select2').select2({
//     dropdownParent: $('#hidden_menu_modal')
// });



// Request Discharge
$('#request_discharge_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/Admissions/request_discharge_frm.php',
        type: 'GET',
        data: $('#request_discharge_frm').serialize(),
        success: function (msg) {
            if (msg === 'billing_successful') {
                bootbox.alert('Discharge Request Successful', function () {
                    window.location = 'inpatients.php';
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })
});


$('#billing_service_id').on('change', function (event) {
    event.preventDefault();
    var billing_service_cost = $(this).find(':selected').data('service_cost')
    $('#billing_service_cost').val(billing_service_cost)
});

$('#bill_patient_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm("Add bill to patients cost?", function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/OPD/bill_patient_frm.php',
                type: 'GET',
                data: $('#bill_patient_frm').serialize(),
                success: function (msg) {
                    if (msg === 'billing_successful') {
                        bootbox.alert("Bill added to patient", function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })
});



$('.delete_admission_billing').on('click', function (event) {
    event.preventDefault();
    var sn = $(this).data('sn')
    bootbox.confirm("Confirm. Delete this billing item?", function (r) {
        if (r === true) {
            $.get('../serverscripts/admin/Admissions/delete_admission_billing.php?sn=' + sn, function (msg) {
                if (msg === 'delete_successful') {
                    bootbox.alert('Bill Deleted Successfully', function () {
                        window.location.reload()
                    })
                }
            })
        }//
    })
});//End Admission Billing


TempGraph();
function TempGraph() {
    {
        $.post("../serverscripts/admin/Admissions/temperature_chart.php?admission_id=" + admission_id,
            function (data) {
                console.log(data);
                data = $.parseJSON(data)

                var time = [];
                var temp = [];

                for (var i in data) {
                    time.push(data[i].times);
                    temp.push(data[i].temp);
                    // alert(i)
                }
                // alert(sales)

                var chartdata = {
                    labels: time,
                    datasets: [
                        {
                            label: 'Temperature',
                            borderColor: 'rgb(0, 13, 126)',
                            pointBackgroundColor: 'rgb(0, 13, 126)',
                            backgroundColor: 'rgba(250, 250, 250, 0)',
                            data: temp
                        }
                    ]
                };

                var graphTarget = $("#temperature_chart");

                var barGraph = new Chart(graphTarget, {
                    type: 'line',
                    data: chartdata
                });
            });
    }
}



tinymce.init({
    selector: '#hpc,#doctors_notes,#clinical_examination,#review_notes,#nurses_notes',
    force_br_newlines: true,
    force_p_newlines: false,
    forced_root_block: '', // Needed for 3.x
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});

$('.remove_hpc').on('click', function (event) {
    event.preventDefault();
    var hpc_id = $(this).attr('ID')
    $.get('../serverscripts/admin/OPD/remove_hpc.php?hpc_id=' + hpc_id, function (msg) {
        if (msg === 'delete_successful') {
            bootbox.alert('HPC removed successfully', function () {
                window.location.reload();
            })
        } else {
            bootbox.alert(msg)
        }
    })
});

$('.remove_complain').on('click', function (event) {
    event.preventDefault();
    var complain_id = $(this).attr('ID')
    $.get('../serverscripts/admin/OPD/remove_complain.php?complain_id=' + complain_id, function (msg) {
        if (msg === 'delete_successful') {
            bootbox.alert('Complain removed successfully', function () {
                window.location.reload();
            })
        } else {
            bootbox.alert(msg)
        }
    })
});

$('#admission_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/admit_patient.php',
        type: 'GET',
        data: $('#admission_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert('Admission Request Successful', function () {
                    window.location.reload()
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })
});

$('.remove_diagnosis').on('click', function (event) {
    event.preventDefault();
    var removethis = $(this).attr('ID')
    $.get('../serverscripts/admin/OPD/remove_diagnosis.php?removethis=' + removethis, function (msg) {
        if (msg === 'delete_successful') {
            bootbox.alert('Diagnosis removed', function () {
                window.location.reload()
            })
        } else {
            bootbox.alert(msg)
        }
    })
});

$('#diagnosis_search_term').on('keyup', function (event) {
    event.preventDefault();
    var search_term = $(this).val()
    var patient_id = "<?php echo $patient_id; ?>"
    var visit_id = "<?php echo $visit_id; ?>"
    $.get('../serverscripts/admin/OPD/filter_diagnosis.php?search_term=' + search_term + '&patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#diagnosis_holder').html(msg)

        $('.diagnose_btn').on('click', function (event) {
            event.preventDefault();
            var diagnosis_id = $(this).data('diagnosis_id')
            var patient_id = $(this).data('patient_id')
            var visit_id = $(this).data('visit_id')
            $.get('../../serverscripts/admin/OPD/record_diagnosis.php?diagnosis_id=' + diagnosis_id + '&visit_id=' + visit_id + '&patient_id=' + patient_id, function (msg) {
                if (msg == 'save_successful') {
                    bootbox.alert('Diagnosis Made', function () {
                        window.location.reload()
                    })
                } else {
                    bootbox.alert(msg)
                }
            })
        });
    })
});


$('#patient_vitals_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm('Record patients vitals?', function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/Admissions/record_vitals.php',
                type: 'GET',
                data: $('#patient_vitals_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Patient Vitals Recorded successfully', function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })
});


$('#serve_meds_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm('Serve Medication?', function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/Admissions/serve_meds_frm.php',
                type: 'GET',
                data: $('#serve_meds_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Medication Served successfully', function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })
});


$('#reviews_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm('Record Review?', function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/Admissions/reviews_frm.php',
                type: 'GET',
                data: $('#reviews_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Review Saved successfully', function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })
});

$('#nurses_notes_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm('Record Notes?', function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/Admissions/nurses_notes_frm.php',
                type: 'GET',
                data: $('#nurses_notes_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Notes Saved successfully', function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })
});


$('#record_complain_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/record_complain.php',
        type: 'GET',
        data: $('#record_complain_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                LoadComplains()
            } else {
                bootbox.alert(msg)
            }
        }
    })
});

$('#hpc_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/record_hpc.php',
        type: 'GET',
        data: $('#hpc_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                window.location.reload()
                $('#hpc_tab').show()
            } else {
                bootbox.alert(msg)
            }
        }
    })
});

$('#record_diagnosis_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/record_diagnosis.php',
        type: 'GET',
        data: $('#record_diagnosis_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                LoadDiagnosis()
            } else {
                bootbox.alert(msg)
            }
        }
    })
});


$('#request_labs_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/request_labs_frm.php',
        type: 'GET',
        data: $('#request_labs_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert("Labs requests queued at laboratory", function () {
                    window.location.reload();
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })
});

$('.view_test_result').on('click', function (event) {
    event.preventDefault();
    var test_id = $(this).data('test_id')
    var request_id = $(this).data('request_id')
    $.get('../serverscripts/admin/OPD/view_test_result_modal.php?request_id=' + request_id + '&test_id=' + test_id, function (msg) {
        $('#modal_holder').html(msg)
        $('#view_test_result_modal').modal('show')
    })
});

$('#prescription_modal').on('shown.bs.modal', function (event) {
    event.preventDefault();
    $('#ex1-tab-3').tab('show')
    $('#prescription_drug_id').select2({
        dropdownParent: $('#prescription_modal')
    });
});

$('#prescription_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/prescription_frm.php',
        type: 'GET',
        data: $('#prescription_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert('Prescription updated successfully', function () {
                    LoadPrescription();
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })
});


$('#new_surgery_modal').on('shown.bs.modal', function (event) {
    event.preventDefault();
    $('#surgery_date').datepicker();
    // $('#procedure').select2()

    $('#procedure').select2({
        placeholder: 'Select a procedure',
        dropdownParent: $('#new_surgery_modal'),
        ajax: {
            url: '../serverscripts/admin/Surgery/filter_procedures.php',
            dataType: 'json',
            delay: 100,
            data: function (data) {
                return {
                    searchTerm: data.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
});

$('#new_surgery_frm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm("Book this patient for surgery?", function (r) {
        if (r === true) {
            $.ajax({
                url: '../../serverscripts/admin/OPD/new_surgery_frm.php',
                type: 'GET',
                data: $('#new_surgery_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Surgery Scheduled Successfully', function () {
                            window.location.reload()
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })//end ajax
        }
    })
});


$('#transfer_patient_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/Patients/transfer_patient_frm.php',
        type: 'GET',
        data: $('#transfer_patient_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert('Patient transfer successful', function () {
                    window.location.reload()
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })
});


function LoadComplains() {
    var patient_id = '<?php echo $patient_id; ?>'
    var visit_id = '<?php echo $visit_id; ?>'
    $.get('../serverscripts/admin/OPD/load_complains.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#complains_holder').html(msg)
    })
}

function LoadDiagnosis() {
    var patient_id = '<?php echo $patient_id; ?>'
    var visit_id = '<?php echo $visit_id; ?>'
    $.get('../serverscripts/admin/OPD/load_diagnosis.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#diagnosis_holder').html(msg)
    })
}

function LoadPrescription() {
    var patient_id = '<?php echo $patient_id; ?>'
    var visit_id = '<?php echo $visit_id; ?>'
    $.get('../serverscripts/admin/OPD/load_prescriptions.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#prescription_holder').html(msg)
    })
}







$('#new_patient_modal').on('shown.bs.modal', function (event) {
    event.preventDefault();
    if ($(this).val() == 'cash') {
        $('#nhis_number').val('N/A')
        $('#nhis_number').attr('readonly', 'readonly')
    } else {
        $('#nhis_number').val('')
        $('#nhis_number').attr('readonly', false)
    }
});

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




$('.payment_btn').on('click', function (event) {
    event.preventDefault();
    var bill_id = $(this).attr('ID')
    var patient_id = '<?php echo $patient_id; ?>'
    var visit_id = '<?php echo $visit_id; ?>'
    bootbox.confirm('Accept payment?', function (r) {
        if (r === true) {
            $.get('../../serverscripts/admin/Patients/payment_modal.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function (msg) {
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
                                    window.location.reload()
                                })
                            } else {
                                bootbox.alert(msg)
                            }
                        }
                    })
                });//end submit
            })
        }
    })
}); //end click

// $(document).on('keyup',function(e){
// 	if(e.keyCode=='78'){
// 		$('#new_drug_modal').modal('show')
// 	}
// })


$(document).ready(function () {

    $('#print').on('click', function (event) {
        event.preventDefault();
        print_popup('print_inventory.php');
    });


    $('#new_drug_modal').on('shown.bs.modal', function (event) {
        event.preventDefault();
        $('#drug_name').focus()
    });//end modal shown

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
                            bootbox.alert("Folder Created Successfully", function () {
                                window.location = 'patient_folder.php'
                            })
                        }
                        else {
                            bootbox.alert(msg, function () {
                                window.location.reload()
                            })
                        }
                    }
                })
            }
        })

    });//end submit


    $('.table tbody').on('click', '.edit', function (event) {
        event.preventDefault();
        var drug_id = $(this).attr('ID')

        $.ajax({
            url: '../serverscripts/admin/inventory_edit.php?drug_id=' + drug_id,
            type: 'GET',
            success: function (msg) {
                $('#modal_holder').html(msg)

                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove()

                $('#edit_drug_modal').modal('show')

                $('#edit_drug_frm').on('submit', function (event) {
                    event.preventDefault();

                    $.ajax({
                        url: '../serverscripts/admin/edit_item_frm.php',
                        type: 'GET',
                        data: $(this).serialize(),
                        success: function (msg) {
                            if (msg === 'save_successful') {
                                bootbox.alert('Drug Information Updated Successfully', function () {
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


    $('.table tbody').on('click', '.delete', function (event) {
        event.preventDefault();

        var drug_id = $(this).attr('ID')
        bootbox.confirm("Are you sure you want to delete this item?", function (r) {
            if (r === true) {
                $.ajax({
                    url: '../serverscripts/admin/inventory_delete.php?drug_id=' + drug_id,
                    type: 'GET',
                    success: function (msg) {
                        if (msg === 'delete_successful') {
                            bootbox.alert("Drug deleted successfully", function () {
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



});