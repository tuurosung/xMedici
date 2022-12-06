<div id="serve_meds_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="serve_meds_frm">
                <div class="modal-body">
                    <h6 class="font-weight-bold montserrat">Serve Medications</h6>
                    <hr class="hr">

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Medication</label>
                            <select class="custom-select browser-default" name="serve_meds_drug_id">
                                <?php

                                $get_current_meds = mysqli_query($db, "SELECT *
																																									FROM prescriptions
																																									WHERE
																																										patient_id='" . $patient_id . "' AND
																																										visit_id='" . $visit_id . "' AND
																																										subscriber_id='" . $active_subscriber . "'
																																				") or die(mysqli_error($db));
                                while ($meds = mysqli_fetch_array($get_current_meds)) {
                                    $drug->drug_id = $meds['drug_id'];
                                    $drug->DrugInfo();
                                ?>
                                    <option value="<?php echo $meds['drug_id']; ?>"><?php echo $drug->drug_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Time</label>
                            <input type="text" class="form-control" name="serve_meds_time" value="<?php echo date('H:i:s'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="">Nurse</label>
                            <select class="custom-select browser-default" name="nurse_id">
                                <option value="<?php echo $active_user; ?>"><?php echo $user_fullname; ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white">Close</button>
                    <button type="submit" class="btn btn-primary">Serve Meds</button>
                </div>
            </form>
        </div>
    </div>
</div>