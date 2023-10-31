@extends('layout.master')
@section('title',"Raise Query")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('layout.customer_modal')
@php
    $permissionList = permission();
@endphp
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('raise-query.index') }}">Raise Query</a></li>
    <li class="breadcrumb-item active" aria-current="page">@if(!empty($raise_query)) Edit @else Add @endif</li>
  </ol>
</nav>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <div class="row">
            <h6 class="card-title col">Raise Query Detail</h6>
            </div>
           
            <form class="forms-sample raise_query_form-form" method="POST" name="raise_query_form" id="raise_query_form">
                @csrf
                <input type="hidden" class="update_query" id="update_query" name="update_query" value="{{ (!empty($raise_query)) ? 1 : 0}}">
                <input type="hidden" name="raise_query_id" class="raise_query_id" id="raise_query_id" value="{{ (!empty($raise_query)) ? encryptid($raise_query['id']) : encryptid('0')}}">
            
            <div class="row">
                    @php
                      $products = [['key' => 1 , 'value' => 'Motor'],['key' => 2 , 'value' => 'Health'],['key' => 3 , 'value' => 'SME']]
                  @endphp
                <div class="col-md-3 mb-3">
                    <label for="policy_type" class="  control-label">Policy <span class="text-danger"> * </span></label>
                    <select class="fform-select form-control select2" id="policy_type" name="policy_type" placeholder="Select policy">
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($products as $product)
                    <option 
                    value="{{ $product['key']}}" @if(!empty($raise_query)) @if($raise_query->policy_type == $product['key']) selected @endif @endif>{{  ucfirst($product['value'])}}</option>
                    @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                      <label for="policy_id" class="control-label">Inward No. <span class="text-danger"> * </span></label>
                      <select class="form-select form-control select2" id="policy_id" name="policy_id" placeholder="Select Inward No">
                      <option selected disabled class="input-cstm">Please First Select Policy</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="raised_on" class="control-label">Date <span class="text-danger"> * </span></label>
                        <div class="input-group ">
                        <input type="text" name="raised_on"  class="form-control datepicker " autocomplete="off" id="issue_date" value="{{ isset($raise_query->raised_on)? date('d-m-Y', strtotime($raise_query->raised_on)) :'' }}" >
                        <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="details" class="  control-label">Detail <span class="text-danger"> * </span></label>
                        <textarea class="form-control" name="details" id="details" rows="4">{{ isset($raise_query->details)? $raise_query->details :'' }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="remark" class="  control-label">Remark</label>
                        <textarea class="form-control" name="remark" id="remark" rows="4">{{ isset($raise_query->remark)? $raise_query->remark :'' }}</textarea>
                    </div>
                </div>
                
            </div>
            <button class="btn btn-primary submit_query" type="button">Save</button>
            <a href="{{ route('raise-query.index') }}">
            <button class="btn btn-primary"  type="button">Back</button>
            </a>
        </form>
        </div>
        </div>
    </div>
</div>
@endsection
@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
    <script src="{{ asset('assets/js/query/raise-query.js') }}"></script>
@endpush
