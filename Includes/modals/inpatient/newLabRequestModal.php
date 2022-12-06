<div id="new_lab_request_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="request_labs_frm">
                <div class="modal-body">
                    <h6>Request Labs</h6>
                    <hr class="hr">
                    <div class="spacer"> </div>
                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
                    </div>

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
                    </div>

                    <ul class="list-group">
                        <?php
                        $get_tests = mysqli_query($db, "SELECT * FROM lab_tests WHERE subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
                        while ($tests = mysqli_fetch_array($get_tests)) {
                        ?>

                            <li class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="test_id[]" value="<?php echo $tests['test_id']; ?>" id="<?php echo $tests['test_id']; ?>" />
                                    <label class="form-check-label" for="<?php echo $tests['test_id']; ?>">
                                        <?php echo $tests['description']; ?>
                                    </label>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                    <div class="spacer">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2" aria-hidden></i>Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>