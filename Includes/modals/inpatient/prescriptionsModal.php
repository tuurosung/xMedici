<div id="prescription_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="prescription_frm">
                <div class="modal-body">

                    <h6 class="montserrat font-weight-bold">Prescription Pad</h6>
                    <hr class="hr">

                    <div class="spacer"></div>

                    <div class="row d-none">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="patient_id" value="<?php echo $patient_id; ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="visit_id" value="<?php echo $visit_id; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Medication</label>
                        <select class="custom-select browser-default" name="drug_id" id="prescription_drug_id">
                            <?php
                            $get_drugs = mysqli_query($db, "SELECT *
																																	FROM
																																		pharm_inventory
																																	WHERE
																																		status='active' && subscriber_id='" . $active_subscriber . "'
																																	ORDER BY generic_name asc
																												")  or die(mysqli_error($db));
                            while ($drugs = mysqli_fetch_array($get_drugs)) {
                            ?>
                                <option value="<?php echo $drugs['drug_id']; ?>"><?php echo $drugs['generic_name'] . ' ' . $drugs['trade_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Dosage</label>
                                <input type="text" name="dosage" id="dosage" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Duration</label>
                                <input type="text" name="duration" id="duration" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Route</label>
                            <select name="route" id="route" class="custom-select browser-default">
                                <option value="oral">Oral</option>
                                <option value="im">Intra-Muscular (IM)</option>
                                <option value="iv">Intra-Venous (IV)</option>
                                <option value="supp">Suppository (supp)</option>
                                <option value="OD">OD</option>
                                <option value="OS">OS</option>
                                <option value="OU">OU</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Freq</label>
                            <select name="frequency" id="frequency" class="custom-select browser-default">
                                <option value="QD">QD</option>
                                <option value="BID">BID</option>
                                <option value="TID">TID</option>
                                <option value="QID">QID</option>
                                <option value="QHS">QHS</option>
                                <option value="Q4H">Q4H</option>
                                <option value="Q6H">Q6H</option>
                                <option value="QOH">QOH</option>
                                <option value="PRN">PRN</option>
                                <option value="QTT">QTT</option>
                                <option value="AC">AC</option>
                                <option value="PC">PC</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Doctors Notes</label>
                        <textarea name="doctors_notes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-signature mr-3" aria-hidden></i> Prescribe</button>
                </div>
            </form>
        </div>
    </div>
</div>