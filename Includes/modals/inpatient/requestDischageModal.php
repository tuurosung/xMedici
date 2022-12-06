<div id="request_discharge_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="request_discharge_frm">
                <div class="modal-body">

                    <h6 class="montserrat font-weight-bold">Request Discharge</h6>
                    <hr class="hr">

                    <p class="mb-4">*Requesting discharge by the doctor will move the disable patient's in-patient folder. No prescriptions, review or vitals can be recorded after this.</p>



                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
                    </div>

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
                    </div>

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="">Admission Billing</label>
                        <input type="text" class="form-control" name="admission_bill_narration" value="Total Admission And Reviews Charge For <?php echo $days_on_admission; ?> Days" id="admission_bill_narration" required>
                    </div>

                    <div class="form-group">
                        <label for="">Admission Bill</label>
                        <input type="text" class="form-control" name="admission_bill" value="<?php echo $total_admission_bill; ?>" id="admission_bill" required>
                    </div>


                    <div class="form-group">
                        <label for="">Discharge Notes (Doctor)</label>
                        <textarea name="discharge_notes" class="form-control"></textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Request Patient Discharge</button>
                </div>
            </form>
        </div>
    </div>
</div>