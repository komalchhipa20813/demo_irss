@extends('layout.master')
@section('title',"Reports")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php
     $permissionList = permission();
@endphp
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Agent Details</li>
  </ol>
</nav>
<div class="row">
   
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">Agent Details</h6>
          </div>
          <form class="forms-sample agent-detail-form" method="POST" name="agent_detail" id="agent_detail" >
            @csrf
          <div class="row">
            
            <div class="col-md-4 mb-4">
              <label for="branch" class="control-label">FDO</label>
                <select class="form-select form-control " id="fdo" name="fdo" placeholder="Select FDO">
                    @if(!$data['fdos']->isEmpty())
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($data['fdos'] as $fdo)
                    <option value="{{ $fdo->id}}">{{$fdo->code .' - '. ucfirst($fdo->prefix) .''. ucfirst($fdo->first_name) .' '. ucfirst($fdo->middle_name) .' '. ucfirst($fdo->last_name)}}</option>
                    @endforeach
                    @else
                    <option selected disabled class="input-cstm">Please First Enter Retinue Branch</option>
                    @endif
                </select>
          </div>
          <div class="col-md-12 mb-12">
              <label for="branch" class="control-label">Select Column To Display</label>
              <input type="checkbox" class="form-check-input" id="code" name="select_column[]" value="code"> Code &nbsp;
              <input type="checkbox" class="form-check-input" id="name" name="select_column[]" value="name"> Name &nbsp;
              <input type="checkbox" class="form-check-input" id="account_no" name="select_column[]" value="account_no"> Bank A/C No &nbsp;
              <input type="checkbox" class="form-check-input" id="bank_name" name="select_column[]" value="bank_name"> Bank Name &nbsp;
              <input type="checkbox" class="form-check-input" id="dob" name="select_column[]" value="dob"> Birth Date &nbsp;
              <input type="checkbox" class="form-check-input" id="ifsc_code" name="select_column[]" value="ifsc_code"> IFSC Code &nbsp;
              <input type="checkbox" class="form-check-input" id="pan_no" name="select_column[]" value="pan_no"> Pan No. &nbsp;
              <input type="checkbox" class="form-check-input" id="created_on" name="select_column[]" value="created_on"> Created On &nbsp;
          </div>
          <div class="col-md-3 mb-3">
            <label for="from_date" class="control-label">From Date</label>
            <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter From Date">
          </div>
          <div class="col-md-3 mb-3">
              <label for="end_date" class="control-label">End Date</label>
              <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="" autocomplete="off" placeholder="Enter End Date">
          </div>
          <div class="col-md-3 mb-3">
              <label for="branch" class="control-label">Status</label>
                <select class="form-select form-control " id="status" name="status" placeholder="Select Status">
                    <option selected disabled class="input-cstm">Please Select</option>
                    <option value="Active">Active</option>
                    <option value="DeActive">DeActive</option>
                </select>
          </div>
          <div class="col-md-3 mb-3">
              <label for="branch" class="control-label">Export To</label>
                <select class="form-select form-control " id="export_type" name="export_type" placeholder="Select Export Type">
                    <option selected disabled class="input-cstm">Please Select</option>
                    <option value="Excel">Excel</option>
                    <option value="PDF">PDF</option>
                </select>
          </div>
           <div class="col-sm-2">
               <button class="btn btn-primary submit_agent_detail" type="button">Export</button>
            </div>
          
        </div>
        </form>
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
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/reports/agent_detail/agent-detail.js') }}"></script>
    <script src="{{ asset('assets/js/policies/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-product-data.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
@endpush
