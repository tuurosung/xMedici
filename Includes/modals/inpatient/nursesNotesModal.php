<div id="nurses_notes_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="nurses_notes_frm">
                <div class="modal-body">
                    <h6 class="montserrat font-weight-bold">Nurse's Notes</h6>
                    <hr class="hr">

                    <div class="form-group d-none">
                        <input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="">Notes</label>
                        <textarea name="nurses_notes" id="nurses_notes"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white">Close</button>
                    <button type="submit" class="btn btn-primary">Save Notes</button>
                </div>
            </form>
        </div>
    </div>
</div>