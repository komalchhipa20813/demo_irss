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
    <li class="breadcrumb-item active" aria-current="page">GBR Report</li>
  </ol>
</nav>
<div class="row">
   
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">GBR Report</h6>
          </div>
          <form class="forms-sample gbr_from" method="POST" name="gbr_from" id="gbr_from" >
            @csrf
          <div class="row">
            <div class="col-md-4 mb-4">
            	 @php
                    $insurances = ['ALL','HEALTH','MOTOR','SME'];
                @endphp
              <label for="branch" class="control-label">Insurance</label>
                <select class="form-select form-control " id="insurance" name="insurance" placeholder="Select Insurance">                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($insurances as $insurance)
                    <option value="{{ $insurance}}">{{$insurance}}</option>
                    @endforeach
                </select>
          </div>
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
          <div class="col-md-4 mb-4">
              <label for="branch" class="control-label">Agent</label>
                <select class="form-select form-control select2" id="agent" name="agent" placeholder="Select Agent">
                    @if(!$data['agents']->isEmpty())
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($data['agents'] as $agent)
                    <option value="{{ $agent->id}}">{{$agent->code .' - '. ucfirst($agent->prefix) .''. ucfirst($agent->first_name) .' '. ucfirst($fdo->middle_name) .' '. ucfirst($agent->last_name)}}</option>
                    @endforeach
                    @else
                    <option selected disabled class="input-cstm">Please First Enter Agent</option>
                    @endif
                </select>
          </div>
			<div class="col-md-4">
				<div class="mb-4">
					<label for="company" class=" control-label">Company </label>
					<select class="company_name form-select form-control  company" id="company" name="company">
					@if(!$data['companies']->isEmpty())
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($data['companies'] as $companie)
                    <option value="{{ $companie->id}}">{{ ucfirst($companie->name)}}</option>
                    @endforeach
                    @else
                    <option selected disabled class="input-cstm">Please First Enter Company</option>
                    @endif
					</select>
				</div>
           </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <label class="control-label">Company Branch Name</label>
                    <select class="form-select form-control company_branch_name " data-width="100%" name="company_branch_name" >
                    <option selected disabled class="input-cstm">Please First Select Company</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-4">
            	 @php
                    $products = ['ALL','HEALTH','MOTOR','SME'];
                @endphp
              <label for="branch" class="control-label">Product</label>
                <select class="form-select form-control " id="" name="product" placeholder="Select Product">                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($products as $product)
                    <option value="{{ $product}}">{{$product}}</option>
                    @endforeach
                </select>
          </div>
          <div class="col-md-2 mb-2">
            <label for="from_date" class="control-label">Entry Date From</label>
            <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter Entry Date From">
          </div>
          <div class="col-md-2 mb-2">
              <label for="end_date" class="control-label">Entry Date To</label>
              <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="" autocomplete="off" placeholder="Enter Entry Date To">
          </div>
          <div class="col-md-2 mb-2">
            <label for="from_date" class="control-label">Expiry Date From</label>
            <input type="text" class="form-control datepicker" name="expiry_from_date" id="expiry_from_date" value="" autocomplete="off" placeholder="Enter Expiry Date From">
          </div>
          <div class="col-md-2 mb-2">
              <label for="end_date" class="control-label">Expiry Date To</label>
              <input type="text" class="form-control datepicker" name="expiry_end_date" id="expiry_end_date" value="" autocomplete="off" placeholder="Enter Expiry Date To">
          </div>
          <div class="col-md-2">
                <div class="mb-2">
                    <label class="control-label">IRSS Branch </label>
                    <select class="form-select form-control  "  name="branch" >
                    @if(!$data['irss_branches']->isEmpty())
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($data['irss_branches'] as $irss_branche)
                    <option @isset($policy) @if ($policy->irss_branch_id == $irss_branche->id) selected @endif @endisset  value="{{ $irss_branche->id}}">{{ ucfirst($irss_branche->name)}}</option>
                    @endforeach
                    
                    @endif
                    </select>
                </div>
            </div>
             <div class="col-md-2 mb-2">
              <label for="end_date" class="control-label">Date Format</label>
              <span >DD/MM/YYYY </span><input type="checkbox" id="date_format" name="date_format" style="display: inline;margin-left: 5px;" class="date_format" >
          </div>
        </div>
        <button class="btn btn-primary submit_report" type="button">View Report</button>
         <button class="btn btn-primary clear"  type="button">Clear</button>
           
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
    <script src="{{ asset('assets/js/reports/gbr/gbr.js') }}"></script>
    <script src="{{ asset('assets/js/policies/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-product-data.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
@endpush
