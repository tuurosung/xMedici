
<div id="billing_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <h6 class="montserrat font-weight-bold">Bill Patient</h6>
                <hr class="hr">

                <form id="bill_patient_frm">

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
                    </div>

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
                    </div>


                    <div class="form-group">
                        <label for="">Service Name</label>
                        <select class="custom-select browser-default" name="service_id" id="billing_service_id" required>
                            <option value="">Select Service</option>
                            <?php
                            $list=$billing->BillingPoints();
                            foreach ($list as $billing_points) {
                                $billing_point=$billing_points['billing_point'];
                            ?>
                                <optgroup label="<?php echo $billing_points['point_name']; ?>">
                                    <?php
                                    
                                    $services=$service->servicesFilter($billing_point);
                                    foreach ( $services as $rows  ) {
                                    ?>
                                        <option class="poppins" value="<?php echo $rows['service_id'] ?>" data-service_cost="<?php echo $rows['service_cost']; ?>"><?php echo $rows['description']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </optgroup>
                            <?php
                            }
                            ?>


                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Service Cost</label>
                        <input type="text" class="form-control" name="billing_service_cost" value="" id="billing_service_cost" required>
                    </div>

                    <div class="form-group">
                        <label for="">Narration</label>
                        <input type="text" class="form-control" name="narration" value="" required>
                    </div>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Bill To Patient</button>
            </div>
            </form>
        </div>
    </div>
</div>