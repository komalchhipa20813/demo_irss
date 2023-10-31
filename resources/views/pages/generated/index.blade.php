@extends('layout.master')
@section('title',"Generated Outward")
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">generated Outward</li>
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
        <form method="post" class="generated_outward_filter_section" id="generated_outward_filter_section">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="outward_status" class="control-label">Outward Status</label>
              <select class="form-select form-control " id="outward_status" name="outward_status">
                  <option selected disabled class="input-cstm" value="0">Please Select</option>
                  <option  value="1">Pending</option>
                  <option  value="1">Sent</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="branch" class="control-label">Retinue Branch</label>
                  <select class="form-select form-control " id="branch" name="branch" placeholder="Select Branch">
                      @if(!$data['irss_branches']->isEmpty())
                      <option selected disabled class="input-cstm" value="0">Please Select</option>
                      @foreach ($data['irss_branches'] as $branch)
                      <option @isset($policy) @if ($policy->irss_branch_id == $branch->id) selected @endif @endisset
                      value="{{ $branch->id}}">{{ ucfirst($branch->name)}}</option>
                      @endforeach
                      @else
                      <option selected disabled class="input-cstm">Please First Enter Retinue Branch</option>
                      @endif
                  </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="company" class="control-label">Company </label>
                <select class="form-select form-control  company_name" id="company" name="company" placeholder="Select company">
                    @if(!$data['companies']->isEmpty())
                    <option selected disabled class="input-cstm" value="0">Please Select</option>
                    @foreach ($data['companies'] as $company)
                    <option value="{{ $company->id}}">{{ ucfirst($company->name)}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="control-label">Company Branch Name</label>
              <select class="form-select form-control company_branch_name " data-width="100%" name="company_branch_name" >
                <option selected disabled class="input-cstm" value="0">Please First Select Company</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="control-label">Branch Imd Name</label>
            <select class="form-select form-control branch_imd "  name="branch_imd" >
                <option selected disabled class="input-cstm"  value="0">Please First Select Branch</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="outward_no" class="control-label">Generated Outward NO.</label>
              <input type="text" class="form-control" name="outward_no" id="outward_no" value="" autocomplete="off" placeholder="Enter Outward No.">
            </div>
            <div class="col-md-4 mb-3">
              <label for="from_date" class="control-label">Create From Date</label>
              <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter From Date">
            </div>
            <div class="col-md-4 mb-3">
              <label for="to_date" class="control-label">Create To Date</label>
              <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="" autocomplete="off" placeholder="Enter End Date">
            </div>
          </div>
          <div class="row">
            <div class="col-2" style="width: auto">
                <button  class="btn btn-primary filter_generated_outward" id="filter_fdo_record">Filter Generated Outward</button>
            </div>
            <div class="col-2" style="width: auto">
                <button  class="btn btn-primary reset_filter" id="reset_filter">Clear Filter</button>
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
            <h6 class="card-title col">Outward List</h6>
          </div>
        <div class="table-responsive mt-2">
          <table id="generated-outward_tbl" class="table" >
            <thead>
              <tr>
                <th>Id</th>
                <th>Outward No</th>
                <th>Branch</th>
                <th>Company</th>
                <th>Company Branch</th>
                <th>Branch IMD</th>
                <th>Documents</th>
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
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/generated/generated.js') }}"></script>
<script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
<script src="{{ asset('assets/js/common/custom.js') }}"></script>
  
@endpush