<div id="vitals_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="patient_vitals_frm" autocomplete="off">
                <div class="modal-body">
                    <h6 class="montserrat font-weight-bold">Patient Vitals</h6>
                    <hr class="hr">

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
                    </div>

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
                    </div>

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Systolic B.P</label>
                                <input type="text" name="systolic" class="form-control" placeholder="" required value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div style="margin-top:30px; font-size:20px">
                                    /
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Diastolic B.P</label>
                                <input type="text" name="diastolic" class="form-control" placeholder="" required value="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Pulse</label>
                        <input type="text" name="pulse" class="form-control" placeholder="" value="">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Temperature</label>
                                <input type="text" name="temperature" class="form-control" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Weight</label>
                                <input type="text" name="weight" class="form-control" value="">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Vitals</button>
                </div>
            </form>
        </div>
    </div>
</div>