@extends('layout.master')
@section('title',"Not Policy PDF")
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Not upload Sme Policy</li>
  </ol>
</nav>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#search_criteria">
  Search Criteria
</button>
<!-- Modal -->
<div class="modal fade select" id="search_criteria" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filter_records_title">Filter  Records</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
        <form method="post" class="sme_policy_searching_form" id="sme_policy_searching_form">
          <div class="row">
            {{-- <div class="col-md-3 mb-3">
                <label for="sum_insured" class="control-label">FDO Code</label>
                <input type="text" class="form-control" name="" id="sum_insured" value="" autocomplete="off" placeholder="Enter ">
            </div> --}}
            <div class="col-md-3">
              <div class="mb-3">
                <label for="agent" class="control-label">Agent</label>
                <select class="form-select form-control select_dropdown" id="agent" name="agent" placeholder="Select agent">
                  @if(!$data['agents']->isEmpty())
                    <option selected disabled class="input-cstm" value="0">Please Select</option>
                    @foreach ($data['agents'] as $agent)
                    <option @isset($policy->agent_id) @if($policy->agent_id==$agent->id) selected @endif @endisset value="{{ $agent->id }}">{{ ucfirst($agent->code) .'-'. ucfirst($agent->prefix).' '.ucfirst($agent->first_name).' '.ucfirst($agent->last_name) }}</option>
                    @endforeach
                  @else
                    <option selected disabled class="input-cstm" value="0">Please First Enter Agent</option>
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="name" class="control-label">Customer Name</label>
                <input type="text" class="form-control" name="name" id="name" value="" autocomplete="off" placeholder="Enter Customer Name ">
            </div>
            <div class="col-md-3 mb-3">
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
                <label for="cheque_no" class="control-label">Cheque No</label>
                <input type="text" class="form-control" name="cheque_no" id="cheque_no" value="" autocomplete="off" placeholder="Enter Cheque Number">
            </div>
            <div class="col-md-3 mb-3">
                <label for="inward_no" class="control-label">Inward No</label>
                <input type="text" class="form-control" name="inward_no" id="inward_no" value="" autocomplete="off" placeholder="Enter Inward No">
            </div>
            <div class="col-md-3 mb-3">
                <label for="policy_no" class="control-label">Policy No</label>
                <input type="text" class="form-control" name="policy_no" id="policy_no" value="" autocomplete="off" placeholder="Enter Policy No">
            </div>
            <div class="col-md-3 mb-3">
                <label for="product" class="  control-label">Product</label>
                <select class="form-select form-control " id="product" name="product" placeholder="Select product">
                 @if(!$data['products']->isEmpty())
                  <option selected disabled class="input-cstm">Please Select</option>
                  @foreach ($data['products'] as $product)
                  <option @isset($policy) @if ($policy->product->id == $product->id) selected @endif @endisset
                  value="{{ $product->id}}">{{ ucfirst($product->name)}}</option>
                  @endforeach
                @else
                  <option selected disabled class="input-cstm">Please First Enter Product</option>
                @endif
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="policy_start_date" class="control-label">Policy Start Date</label>
                <input type="text" class="form-control datepicker" name="policy_start_date" id="policy_start_date" value="" autocomplete="off" placeholder="Enter Policy Start Date">
            </div>
            <div class="col-md-3 mb-3">
                <label for="policy_end_date" class="control-label">Policy End Date</label>
                <input type="text" class="form-control datepicker" name="policy_end_date" id="policy_end_date" value="" autocomplete="off" placeholder="Enter Policy End Date">
            </div>
            <div class="col-md-3 mb-3">
                <label for="from_date" class="control-label">From Date</label>
                <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter From Date">
            </div>
            <div class="col-md-3 mb-3">
                <label for="end_date" class="control-label">End Date</label>
                <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="" autocomplete="off" placeholder="Enter End Date">
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-2" style="width: auto">
            <button  class="btn btn-primary filter_sme_policy" id="filter_policy">Filter SME Policy</button>
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
    <div class="col-md-12 grid-margin stretch-card mb-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h6 class="card-title col">Not upload Sme policies</h6>
                      <div class="col-2 " style="width: auto">
                        <button type="button" id="export" style="float: right" class="btn btn-primary me-2s"> Export SME Policy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive mt-2">
          <table id="not-uploaded-policy_tbl" class="table" data-name="sme">
            <thead>
              <tr>
                <th>Policy Number</th>
                 <th>Inward No</th>
                <th>Company Name</th>
                <th>IRSS Branch</th>
                <th>Customer Name</th> 
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
<script src="{{ asset('assets/js/reports/not-policy-pdf/sme-pdf-policy.js') }}"></script>
<script src="{{ asset('assets/js/policies/custom.js') }}"></script>
<script src="{{ asset('assets/js/common/custom.js') }}"></script>
<script src="{{ asset('assets/js/common/get-product-data.js') }}"></script>
<script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>

@endpush
