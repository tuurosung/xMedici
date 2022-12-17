$('.sidebar-fixed .list-group-item').removeClass('active')
$('#patients_nav').addClass('active')
$('#patients_submenu').addClass('show')
$('#patients_li').addClass('font-weight-bold')

tinymce.init({
    selector: '#hpc,#doctors_notes,#clinical_examination,#secondary_diagnosis,#death_notes',
    force_br_newlines: true,
    force_p_newlines: false,
    forced_root_block: '', // Needed for 3.x
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});

$('#death_date').datepicker()
$('#death_date').on('changeDate', function (event) {
    event.preventDefault();
    $(this).datepicker('hide')
});


// Radiology Request JS==========================================
$('#ultrasound_service_id').on('change', function (event) {
    event.preventDefault();
    let service_cost = $(this).find(":selected").data('service_cost');
    $('#ultrasound_service_cost').val(service_cost)
});

// $('#radiology_request_frm').on('submit', function(event) {
//   event.preventDefault();
//   $.ajax({
//     url: '../serverscripts/admin/OPD/Radiology/new_request.php?patient_type=opd',
//     type: 'GET',
//     data:$('#radiology_request_frm').serialize(),
//     success:function(msg){
//       if(msg==='save_successful'){
//         bootbox.alert("Request successful",function(){
//           window.location.reload()
//         })
//       }else {
//         bootbox.alert(msg)
//       }
//     }
//   })
//
// });


// ===========================================================================


$('#billing_service_id').select2({
    dropdownParent: $('#billing_modal')
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


$('#discharge').on('click', function (event) {
    event.preventDefault();
    var patient_id = '<?php echo $patient_id; ?>'
    var visit_id = '<?php echo $visit_id; ?>'
    bootbox.confirm('Discharge patient? This action is not reversible', function (r) {
        if (r === true) {
            $.get('../serverscripts/admin/OPD/discharge_patient.php?visit_id=' + visit_id + '&patient_id=' + patient_id, function (msg) {
                if (msg === 'discharge_successful') {
                    bootbox.alert('Patient Discharged Successfully', function () {
                        window.location = 'opd.php';
                    })
                } else {
                    bootbox.alert(msg)
                }
            })
        }
    })
});


$('#deceased_frm').on('submit', function (event) {
    event.preventDefault();
    var patient_id = '<?php echo $patient_id; ?>'
    var visit_id = '<?php echo $visit_id; ?>'
    bootbox.confirm('Confirm Patient As Deceased? This action is not reversible', function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/OPD/patient_deceased.php',
                type: 'GET',
                data: $('#deceased_frm').serialize(),
                success: function (msg) {
                    if (msg === 'save_successful') {
                        bootbox.alert('Patient Status Updated', function () {
                            window.location.reload();
                        })
                    } else {
                        bootbox.alert(msg)
                    }
                }
            })
        }
    })
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
                LoadComplains();
                $('newComplain').val('')
                $('#complainDuration').val('')
                $('#newComplain').focus();
            })
        } else {
            bootbox.alert(msg)
        }
    })
});

$('.remove_odq').on('click', function (event) {
    event.preventDefault();
    var sn = $(this).attr('ID')
    $.get('../serverscripts/admin/OPD/remove_odq.php?sn=' + sn, function (msg) {
        if (msg === 'delete_successful') {
            bootbox.alert('ODQ removed successfully', function () {
                LoadODQ()
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
               LoadDiagnosis()
            })
        } else {
            bootbox.alert(msg)
        }
    })
});

$('#diagnosis_search_term').on('keyup', function (event) {
    event.preventDefault();
    var search_term = $(this).val()

    var patient_id = $('#activePatientID').val();
    var visit_id = $('#activeVisitID').val();

    $.get('../serverscripts/admin/OPD/filter_diagnosis.php?search_term=' + search_term + '&patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#diagnosis_holder').html(msg)

        $('.table tbody').on('click','.diagnose_btn', function (event) {
            event.preventDefault();
            var diagnosis_id = $(this).data('diagnosis_id')
            var patient_id = $(this).data('patient_id')
            var visit_id = $(this).data('visit_id')
            $.get('../serverscripts/admin/OPD/record_diagnosis.php?diagnosis_id=' + diagnosis_id + '&visit_id=' + visit_id + '&patient_id=' + patient_id, function (msg) {
                if (msg == 'save_successful') {
                    bootbox.alert('Diagnosis Made', function () {
                        LoadDiagnosis();
                    })
                } else {
                    bootbox.alert(msg)
                }
            })
        });

    })
});


$('#secondary_diagnosis_frm').on('submit', function (event) {
    event.preventDefault();

    var patient_id = $('#activePatientID').val();
    var visit_id = $('#activeVisitID').val();

    $.ajax({
        url: '../serverscripts/admin/OPD/record_secondary_diagnosis.php?patient_id=' + patient_id + '&visit_id=' + visit_id,
        type: 'GET',
        data: $('#secondary_diagnosis_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert("Secondary Diagnosis Recorded", function () {
                    window.location.reload()
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })

});


$('#doctors_notes_frm').on('submit', function (event) {
    event.preventDefault();
    var patient_id = $('#activePatientID').val();
    var visit_id = $('#activeVisitID').val();
    $.ajax({
        url: '../serverscripts/admin/OPD/record_doctors_notes.php?patient_id=' + patient_id + '&visit_id=' + visit_id,
        type: 'GET',
        data: $('#doctors_notes_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                bootbox.alert("Notes Recorded", function () {
                    window.location.reload()
                })
            } else {
                bootbox.alert(msg)
            }
        }
    })

});


$('#vitalsFrm').on('submit', function (event) {
    event.preventDefault();
    bootbox.confirm('Record patients vitals?', function (r) {
        if (r === true) {
            $.ajax({
                url: '../serverscripts/admin/OPD/record_vitals.php',
                type: 'GET',
                data: $('#vitalsFrm').serialize(),
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

$('#record_odq_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/record_odq.php',
        type: 'GET',
        data: $('#record_odq_frm').serialize(),
        success: function (msg) {
            if (msg === 'save_successful') {
                LoadODQ()
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

$('#clinical_examination_frm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: '../serverscripts/admin/OPD/record_clinical_examination.php',
        type: 'GET',
        data: $('#clinical_examination_frm').serialize(),
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
    // $('#prescription_drug_id').select2();
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
            }) //end ajax
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

    var patient_id =$('#activePatientID').val();
    var visit_id =$('#activeVisitID').val();

    $.get('../serverscripts/admin/OPD/load_complains.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#complains_holder').html(msg)
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
    })

}


function LoadODQ() {
    var patient_id = $('#activePatientID').val();
    var visit_id = $('#activeVisitID').val();

    $.get('../serverscripts/admin/OPD/load_odq.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#odq_holder').html(msg)
    })
    
}

function LoadDiagnosis() {
    var patient_id = $('#activePatientID').val();
    var visit_id = $('#activeVisitID').val();
    $.get('../serverscripts/admin/OPD/load_diagnosis.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#diagnosis_holder').html(msg)
    })
}

function LoadPrescription() {
    var patient_id = $('#activePatientID').val();
    var visit_id = $('#activeVisitID').val();
    $.get('../serverscripts/admin/OPD/load_prescriptions.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function (msg) {
        $('#prescription_holder').html(msg)
    })
}






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
                }); //end submit
            })
        }
    })
}); //end click




$(document).ready(function () {




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
                            } else {
                                bootbox.alert(msg)
                            }
                        }
                    }) //end ajax
                }); //end edit form
            }
        })

    }); //end edit function


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
                        } else {
                            bootbox.alert(msg)
                        }
                    }
                })
            }
        })
    }); //end delete



});