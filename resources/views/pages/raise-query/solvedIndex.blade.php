
@extends('layout.master')
@section('title',"Raise Query")
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- @include('layout.policy_cancel_modal') -->
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Solved Query</li>
  </ol>
</nav>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#search_criteria">
  Search Criteria
</button>
<!-- Search Criteria Modal -->
<div class="modal fade select" id="search_criteria" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filter_records_title">Filter  Records</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
        <form method="post" class="raise_query_searching_form" id="raise_query_searching_form">
          <div class="row">
          @php
                      $products = [['key' => 0 , 'value' => 'All'],['key' => 1 , 'value' => 'Motor'],['key' => 2 , 'value' => 'Health'],['key' => 3 , 'value' => 'SME']]
                  @endphp
            <div class="col-md-3 mb-3">
                <label for="product" class="  control-label">Main Product</label>
                <select class="form-select form-control " id="product" name="product" placeholder="Select product">
                  <option selected disabled class="input-cstm">Please Select</option>
                  @foreach ($products as $product)
                  <option 
                  value="{{ $product['key']}}">{{ ucfirst($product['value'])}}</option>
                  @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="company" class="control-label">Company </label>
                <select class="form-select form-control " id="company" name="company" placeholder="Select company">
                    @if(!$data['companies']->isEmpty())
                    <option selected disabled class="input-cstm" value="0">Please Select</option>
                    @foreach ($data['companies'] as $company)
                    <option value="{{ $company->id}}">{{ ucfirst($company->name)}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="agent_code" class="control-label">Agent Code</label>
                <input type="text" class="form-control" name="agent_code" id="agent_code" value="" autocomplete="off" placeholder="Enter Agent Code">
            </div>
            
            
            <div class="col-md-3 mb-3">
                <label for="inward_no" class="control-label">Inward No</label>
                <input type="text" class="form-control" name="inward_no" id="inward_no" value="" autocomplete="off" placeholder="Enter Inward No">
            </div>

            <div class="col-md-3 mb-3">
                <label for="days_passed" class="control-label">Time TO Solved</label>
                <input type="text" class="form-control" name="days_passed" id="days_passed" value="" autocomplete="off" placeholder="Enter Time TO Solved">
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="from_date" class="control-label">Raised On From Date</label>
                <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter From Date">
            </div>
            <div class="col-md-3 mb-3">
                <label for="end_date" class="control-label">Raised On To Date</label>
                <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="" autocomplete="off" placeholder="Enter End Date">
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-2" style="width: auto">
            <button  class="btn btn-primary filter_query" id="filter_query">Filter</button>
          </div>
          <div class="col-2" style="width: auto">
              <button  class="btn btn-primary reset_filter" id="reset_filter">Clear Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="loader-background" id="loader_bg" style="display:none">
      <div class="spinner-border"  id="loader"   role="status"></div>
    </div>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">Solved Query</h6>
            <div class="col-2 exportBtn" style="width: auto">
                 <button type="button" id="export" style="float: right" class="btn btn-primary me-2s"> Export Solved Query</button>
              </div>
        <div class="table-responsive mt-2">
          <table id="solved-query_tbl" class="table" style="width: 98% !important;">
            <thead>
              <tr>
                <th>Ticket Number</th>
                <th>Agent Code</th>
                <th>Inward Number</th>
                <th>Product</th>
                <th>Sub Product</th>
                <th>Registration No.</th>
                <th>Company Name</th>
                <th>Query Detail</th>
                <th>Raise On</th>
                <th>TAT</th>
                <th>Remark</th>
                <th>Closed By</th>
                <th>Closed Date</th>
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
<script src="{{ asset('assets/js/common/custom.js') }}"></script>
<script src="{{ asset('assets/js/query/solved-query.js') }}"></script>

@endpush
