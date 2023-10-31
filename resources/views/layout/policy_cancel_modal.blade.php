<div class="modal fade" id="policy_cancel_policy_modal" tabindex="-1" aria-labelledby="policy_cancel_policy_modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="policy_cancel_policy_modal_title">Cancel Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form id="policy_cancel_form">
                    @csrf
                    <input type="hidden" name="id" class="id" id="policy_id" value="{{ encryptid('0')}}">
                    <div class="row">
                        <div class="col-md-12 mb-3">                            
                            <label for="reason" class="control-label">Reason</label>
                            <input type="text" class="form-control" name="reason" value="" id="reason" placeholder="Enter Reason For Cancel Policy">                            
                        </div>
                        <div class="col-md-12 mb-3">                            
                            <label for="remark" class="  control-label">Remark </label>
                            <input type="text" class="form-control" name="remark" value="" id="remark" placeholder="Enter Remark">                            
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary submit_cancel" type="button">Save</button>
            </div>
        </div>
    </div>
</div>