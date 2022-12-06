<div id="new_surgery_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="new_surgery_frm">
                    <h6 class="font-weight-bold montserrat">Book New Surgery</h6>
                    <hr class="hr">

                    <div class="row d-none">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Patient ID</label>
                                <input type="text" class="form-control" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Visit ID</label>
                                <input type="text" class="form-control" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Proceure</label>
                            <select class="form-control" name="surgical_procedure" id="procedure">

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Type Of Procedure</label>
                            <select class="custom-select browser-default" name="procedure_type">
                                <option value="Major">Major Procedure</option>
                                <option value="Minor">Minor Procedure</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Date Scheduled</label>
                            <input type="text" name="date" id="surgery_date" class="form-control" value="<?php echo $today; ?>">
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Schedule Surgery</button>
            </div>
            </form>
        </div>
    </div>
</div>