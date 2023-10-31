@php
if (!is_null(Auth::guard('agent')->user())||!is_null(Auth::guard('fdo')->user())){
    $master='pages.fdo-agent-panel.layout.master';
}
else{
    $master='layout.master';
}   
@endphp
@extends($master)
@section('title',"Customer")
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customers</li>
  </ol>
</nav>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#search_criteria">
  Search Criteria
</button>
<!-- Modal -->
<div class="modal fade select" id="search_criteria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filter_records_title">Filter  Records</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="POST">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                  <label for="customer_code" class="  control-label">Customer code</label>
                  <input type="text" name="customer_code" id="customer_code" class="form-control" autocomplete="off"
                              placeholder="Enter Customer code">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="customer_id" class="  control-label">Customer Name</label>
                {{-- <input type="text" name="customer_id" id="customer_id" class="form-control" autocomplete="off"
                              placeholder="Enter Customer Name"> --}}
                 <select class="form-select form-control select_dropdown" id="customer_id" name="customer_id" placeholder="Select Customer Name">
                  <option selected disabled value="0" class="input-cstm">Please Select</option>
                  @if(!empty($data['customers']))
                  @foreach ($data['customers'] as $customer)
                    <option
                      value="{{encryptid($customer->id)}}">{{ $customer->prefix.' '.ucwords($customer->first_name).' '.ucwords($customer->last_name)}}</option>
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="adharcard_number" class="  control-label">Aadhaar Card No</label>
                <input type="text" class="form-control adharcard_number" name="adharcard_number" data-inputmask-alias="9999 9999 9999" id="aadhaarcard_number"  autocomplete="off" placeholder="Enter Aadhaar Card Number">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pancard_number" class="  control-label">Pan Card No</label>
                <input type="text" class="form-control" name="pancard_number" id="pancard_number" value="{{ isset($customer->pancard_number)? $customer->pancard_number :'' }}" autocomplete="off" placeholder="Enter Pan Card Number">
              </div>
            </div>
          </div>
          <div class="row footer-btn">
            <div class="col-xs-12 col-sm-12 col-md-12 ">
              <button type="button" id="btnsubmit" class="btn btn-primary me-2 " onclick="searching_data();">Filter Customer </button>
              <button type="button" id="btnsubmit" class="btn btn-primary me-2 " title="Clear Filter" onclick="clear_searching_data();">Clear Filter</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">Customers</h6>
            <div class="col-2 ">
              @if(isset(Auth::user()->id)&&in_array("82", permission()))
                <a href="{{ route('customer.create') }}" class="btn btn-primary add_employee" data-id="{{ encryptid('0') }}" style="float: right" id="add_employee">Add Customer</a>
              @endif
            </div>
            <div class="col-2 " style="width: auto">
              <a class="btn btn-primary" style="float: right" href="{{ route('export-customer') }}">Export Customer</a>
            </div>
          </div>
        <div class="table-responsive mt-2">
          <table id="customer_tbl" class="table" style="width: 98% !important">
            <thead>
              <tr>
                <th>No</th>
                <th>Customer Code</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Aadhaar Card No</th>
                <th>Pan Card No</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script type="text/javascript">
        let _token = "{{ csrf_token() }}";;
    </script>
<script src="{{ asset('assets/js/customer/customer.js') }}"></script>
<script src="{{ asset('assets/js/common/custom.js') }}"></script>

@endpush
