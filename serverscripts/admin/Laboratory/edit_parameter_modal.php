<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Tests.php';

  $parameter_id=clean_string($_GET['parameter_id']);

  $test=new Test();
  $test->ParameterInfo($parameter_id);
 ?>

<div id="edit_parameter_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="edit_parameter_frm" autocomplete="off">

      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">Edit Parameter Info</h5>
					<hr class="hr mb-3">



            <input type="text" name="parameter_id" value="<?php echo $parameter_id; ?>" class="d-none">

            <section  class="">
              <h6 class="montserrat font-weight-bold">General Information</h6>
              <div class="spacer"></div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Description</label>
                    <input class="form-control" type="text"  name="description" value="<?php echo $test->parameter_name; ?>" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Unit</label>
                    <select class="custom-select browser-default" name="unit">
                        <option value="N/A">Not Applicable</option>
                      <?php
                          $get_units=mysqli_query($db,"SELECT * FROM measurement_units") or die(mysqli_error($db));
                          while ($units=mysqli_fetch_array($get_units)) {
                            ?>
                            <option value="<?php echo $units['unit']; ?>" <?php if($test->parameter_unit==$units['unit']){ echo 'selected'; } ?>><?php echo $units['unit']; ?></option>
                            <?php
                          }
                        ?>

                    </select>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">General Minimum Value</label>
                    <input type="text" name="general_min" class="form-control" value="<?php echo $test->parameter_general_min; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">General Max Value</label>
                    <input type="text" name="general_max" class="form-control" value="<?php echo $test->parameter_general_max; ?>">
                  </div>
                </div>
              </div>

            </section>

            <section class="mt-5">
              <h6 class="montserrat font-weight-bold mb-4">Variable Parameters</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Male Min</label>
                    <input type="text" name="male_min" class="form-control" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Male Max</label>
                    <input type="text" name="male_max" class="form-control" value="">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Female Min</label>
                    <input type="text" name="female_min" class="form-control" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Female Max</label>
                    <input type="text" name="female_max" class="form-control" value="">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Neonates Min</label>
                    <input type="text" name="child_min" class="form-control" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Neonates Max</label>
                    <input type="text" name="child_max" class="form-control" value="">
                  </div>
                </div>
              </div>

            </section>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Update Parameter</button>
      </div>
      </form>
    </div>
  </div>
</div>
