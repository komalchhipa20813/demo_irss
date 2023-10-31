@extends('layout.master')
@section('title',"Generate Outward")
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Generate Outward</li>
  </ol>
</nav>
<div class="row">
   
    <div class="col-md-12 grid-margin stretch-card mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col">Filter  Records</h6>
                <form class="forms-sample" method="POST" name="registration" id="generate_outward_form">
                  @csrf
                <div class="row">
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
                        <select class="form-select form-control  company_name" id="company" name="company" placeholder="Select company">
                            @if(!$data['companies']->isEmpty())
                            <option selected disabled class="input-cstm" value="0">Please Select</option>
                            @foreach ($data['companies'] as $company)
                            <option value="{{ $company->id}}">{{ ucfirst($company->name)}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="control-label">Company Branch Name</label>
                        <select class="form-select form-control company_branch_name " data-width="100%" name="company_branch_name" >
                        <option selected disabled class="input-cstm">Please First Select Company</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="control-label">Branch Imd Name</label>
                        <select class="form-select form-control branch_imd "  name="branch_imd" id="branch_imd" >
                        <option selected disabled class="input-cstm">Please First Select Branch</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="from_date" class="control-label">From Date</label>
                        <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter From Date">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="to_date" class="control-label">End Date</label>
                        <input type="text" class="form-control datepicker" name="" id="to_date" value="" autocomplete="off" placeholder="Enter To Date">
                    </div>
                </div>
              </form>
                <div class="row">
                    <div class="col-2" style="width: auto">
                        <button  class="btn btn-primary get_generate_outward" id="get_generate_outward">search</button>
                    </div>
                    <div class="col-2" style="width: auto">
                        <button  class="btn btn-primary reset_filter" id="reset_filter">Clear Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <div class="col-md-12 grid-margin stretch-card" style="display: none" id="generate_outward_div">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <h6 class="card-title col">Generate Outward</h6>
        </div>
        <div class="table-responsive mt-2">
          <table id="generate-outward_tbl" class="table" >
            <thead>
              <tr>
                <th width="10px"><input type="checkbox" name="select_all" id="select_all" class="styled checkbox_head" onclick="select_all_policy(this);"></th>
                <th>Id</th>
                <th>Inward No</th>
                <th>Customer Name</th>
                <th>Main Product</th>
                <th>Company Name</th>
                <th>Branch</th>
                <th>Entry Date</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="col-2" style="width: auto">
          <button  class="btn btn-primary generate_outward" id="generate_outward">Generate Outward</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/generate/generate.js') }}"></script>
<script src="{{ asset('assets/js/policies/custom.js') }}"></script>
<script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>

@endpush