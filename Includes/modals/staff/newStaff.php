	<!-- Modal -->
	<div class="modal fade" id="new_staff_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title">New Staff Registration</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <form id="new_staff_frm">
	                <div class="modal-body">


	                    <div class="row">
	                        <div class="col-md-6">
	                            <label for="">Title</label>
	                            <div class="form-group">
	                                <select class="custom-select browser-default" name="title">
                                        <option value="">-----</option>
	                                    <?php
                                        foreach ($staff->titles as $title) {
                                        ?>
	                                        <option value="<?php echo $title; ?>"><?php echo $title; ?></option>
	                                    <?php
                                        }
                                        ?>
	                                </select>
	                            </div>
	                        </div>
	                        <div class="col-md-6">
	                            <label for="">Role / Department</label>
	                            <div class="form-group">
	                                <select class="browser-default custom-select" name="role">
                                        <option value="">---</option>
	                                    <?php
                                        foreach ($staff->HrCategories() as $cats) {
                                        ?>
	                                        <option value="<?php echo $cats['alias']; ?>"><?php echo $cats['description']; ?></option>
	                                    <?php
                                        }
                                        ?>
	                                </select>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="row">

	                        <div class="col-md-12">
	                            <div class="form-group">
	                                <label>Specialisation</label>
	                                <select class="custom-select browser-default" id="rank" name="rank" required>
	                                    <option value="">-----</option>
	                                    <?php
                                        foreach ($staff->ranks as $rank) {
                                        ?>
	                                        <option value="<?php echo $rank; ?>"> <?php echo $rank; ?></option>
	                                    <?php
                                        }
                                        ?>
	                                </select>
	                            </div>
	                        </div>

	                    </div>

	                    <hr>

	                    <div class="row">
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Surname</label>
	                                <input type="text" class="form-control" id="surname" name="surname" required>
	                            </div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Othernames</label>
	                                <input type="text" class="form-control" id="othernames" name="othernames" required>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="row">
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Phone Number</label>
	                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
	                            </div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Address</label>
	                                <input type="text" class="form-control" id="address" name="address" required>
	                            </div>
	                        </div>
	                    </div>


	                    <div class="row gx-lg-5">

	                        <div class="col-md-6 mb-5 mb-lg-0">
	                            <div class="form-group">
	                                <label for="">Emergency Contact Person</label>
	                                <input type="text" name="emergency_contact_person" id="" class="form-control" placeholder="">
	                            </div>
	                        </div>

	                        <div class="col-md-6 mb-5 mb-lg-0">
	                            <div class="form-group">
	                                <label for="">Emergency Contact</label>
	                                <input type="text" name="emergency_contact" id="" class="form-control" placeholder="">
	                            </div>
	                        </div>

	                    </div>



	                    <hr>

	                    <div class="row">
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Username</label>
	                                <input type="text" class="form-control" id="username" name="username" required>
	                            </div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Password</label>
	                                <input type="password" class="form-control" id="password" name="password" required>
	                            </div>
	                        </div>
	                    </div>

	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                    <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-3" aria-hidden="true"></i> Save</button>
	                </div>
	            </form>
	        </div>
	    </div>
	</div>