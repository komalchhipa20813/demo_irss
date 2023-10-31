@extends('layout.master')
@section('title',"Customer")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ (!is_null($data['customer'])) ? 'Edit' :'Add'}}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">{{ (!is_null($data['customer'])) ? 'Edit Customer' :'Add Customer'}}</h6>
          </div>
          @php
            if (isset($data['customer']))
            $customer = $data['customer'];
          @endphp
          <form class="forms-sample" method="POST" name="registration" id="customer_form">
            @csrf
             <input type="hidden" name="update_customer" class="update_customer" id="update_customer" value="{{ (!is_null($data['customer'])) ? 1 : 2 }}">
            <input type="hidden" name="customer_id" class="customer_id" id="customer_id" value="{{ (!is_null($data['customer'])) ? encryptid($data['customer']->id) : encryptid('0')}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        @php
                            $prefixes=['Mr.','Mrs','Miss','Dr','Er','M/S']
                        @endphp
                        <label for="prefix" class="control-label">Prefix  <span class="text-danger"> * </span></label>
                        <select class="form-select form-control prefix " id="prefix" name="prefix" placeholder="Select prefix">
                            <option selected disabled class="input-cstm">Please Select</option>
                            @foreach ($prefixes as $prefix)
                            <option @isset($customer->prefix) @if($customer->prefix==$prefix) selected @endif @endisset value="{{ $prefix }}">{{ $prefix }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 first_name ms-prefix">
                    <div class="mb-3">
                        <label for="first_name" class="  control-label">First Name <span class="text-danger"> * </span></label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{ isset($customer)? $customer->first_name :'' }}" autocomplete="off" placeholder="Enter First Name">
                    </div>
                </div>
                <div class="col-md-6 middle_name ms-prefix">
                    <div class="mb-3">
                        <label for="middle_name" class="  control-label">Middle Name </label>
                        <input type="text" class="form-control" name="middle_name" id="middle_name" value="{{ isset($customer->middle_name)? $customer->middle_name :'' }}" autocomplete="off" placeholder="Enter middle Name">
                    </div>
                </div>
                <div class="col-md-6 last_name ms-prefix">
                    <div class="mb-3">
                        <label for="last_name" class="  control-label">Last Name <span class="text-danger"> * </span></label>
                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ isset($customer)? $customer->last_name :'' }}" autocomplete="off" placeholder="Enter Last Name">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="address" class="  control-label">Address  <span class="text-danger"> * </span></label>
                        <textarea name="address" id="address" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5" spellcheck="false">{{ isset($customer->address)? $customer->address :'' }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="  control-label">Email </label>
                        <input type="email" class="form-control" name="email" value="{{ isset($customer->email)? $customer->email :'' }}" id="email" placeholder="Enter Branch Email">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                      <label for="mobile_no" class="  control-label">Mobile No </label>
                      <input type="text" class="form-control" name="mobile_no" value="{{ isset($customer)? $customer->mobile_no :'' }}" id="mobile_no" placeholder="Enter Mobile Number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                      <label for="phone_no" class="  control-label">Phone No </label>
                      <input type="text" class="form-control" name="phone_no" value="{{ isset($customer->phone_no)? $customer->phone_no :'' }}" id="phone_no" placeholder="Enter Phone Number">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                      <label for="aadhaarcard_number" class="  control-label">Aadhaar Card No <span class="text-danger"> * </span></label>
                      <input type="text" class="form-control" name="aadhaarcard_number" data-inputmask-alias="9999 9999 9999" id="aadhaarcard_number" value="{{ isset($customer->adharcard_number)? $customer->adharcard_number :'' }}" autocomplete="off" placeholder="Enter Aadhaar Card Number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                      <label for="pancard_number" class="  control-label">Pan Card No <span class="text-danger"> * </span></label>
                      <input type="text" class="form-control" name="pancard_number" id="pancard_number" data-inputmask-alias="aaaaa9999a"  value="{{ isset($customer->pancard_number)? $customer->pancard_number :'' }}" autocomplete="off" placeholder="Enter Pan Card Number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                      <label for="gst_number" class="  control-label">GST Number</label>
                      <input type="text" class="form-control" name="gst_number" id="gst_number" value="{{ isset($customer->gst_number)? $customer->gst_number :'' }}" autocomplete="off" placeholder="Enter GST Number">
                    </div>
                </div>
                <div class="">
                  <button class="btn btn-primary submit_customer" type="button">{{ (!is_null($data['customer'])) ? 'Update' :'Save'}}</button>
                  <a href="{{ route('customer.index') }}">
                    <button class="btn btn-primary"  type="button">Back</button>
                  </a>
                </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>

@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/customer/customer.js') }}"></script>

@endpush
