<div class="modal fade select" id="customer_modal" tabindex="-1" aria-labelledby="customer_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customer_modal">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form id="customer_form">
                    @csrf
                     <input type="hidden" name="update_customer" class="update_customer" id="update_customer" value="2">
                    <input type="hidden" name="customer_id" class="customer_id" id="customer_id" value="{{ encryptid('0')}}">
                    <input type="hidden" name="customer_from_policy" class="customer_from_policy" id="customer_from_policy" value="{{ encryptid('0')}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                @php
                                $prefixes=['Mr.','Mrs','Miss','Dr','Er','M/S']
                                @endphp
                                <label for="prefix" class="control-label">Prefix <span class="text-danger"> * </span></label>
                                <select class="form-select form-control prefix " id="prefix" name="prefix" placeholder="Select prefix">
                                    <option selected disabled class="input-cstm">Please Select</option>
                                    @foreach ($prefixes as $prefix)
                                    <option value="{{ $prefix }}">{{ $prefix }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 first_name ms-prefix">
                            <div class="mb-3">
                                <label for="first_name" class="  control-label">First Name <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="" autocomplete="off" placeholder="Enter First Name">
                            </div>
                        </div>
                        <div class="col-md-6 middle_name ms-prefix">
                            <div class="mb-3">
                                <label for="middle_name" class="  control-label">Middle Name <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="middle_name" id="middle_name" value="" autocomplete="off" placeholder="Enter middle Name">
                            </div>
                        </div>
                        <div class="col-md-6 last_name ms-prefix">
                            <div class="mb-3">
                                <label for="last_name" class="  control-label">Last Name <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="" autocomplete="off" placeholder="Enter Last Name">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="  control-label">Address <span class="text-danger"> * </span></label>
                                <textarea name="address" id="address" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5" spellcheck="false"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="  control-label">Email </label>
                                <input type="email" class="form-control" name="email" value="" id="email" placeholder="Enter Branch Email">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mobile_no" class="  control-label">Mobile No </label>
                                <input type="text" class="form-control" name="mobile_no" value="" id="mobile_no" placeholder="Enter Mobile Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone_no" class="  control-label">Phone No </label>
                                <input type="text" class="form-control" name="phone_no" value="" id="phone_no" placeholder="Enter Phone Number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aadhaarcard_number" class="  control-label">Aadhaar Card No <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="aadhaarcard_number" data-inputmask-alias="9999 9999 9999" id="aadhaarcard_number" value="" autocomplete="off" placeholder="Enter Aadhaar Card Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pancard_number" class="  control-label">Pan Card No <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="pancard_number" id="pancard_number" data-inputmask-alias="aaaaa9999a" value="" autocomplete="off" placeholder="Enter Pan Card Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gst_number" class="  control-label">GST Number</label>
                                <input type="text" class="form-control" name="gst_number" id="gst_number" value="" autocomplete="off" placeholder="Enter GST Number">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary submit_customer" type="button">Save</button>
            </div>
        </div>
    </div>
</div>