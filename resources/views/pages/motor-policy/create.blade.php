@extends('layout.master')
@section('title',"Motor Policy")
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
    <li class="breadcrumb-item"><a href="{{ route('motor-policy.index') }}">Motor Policy</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ (!is_null($data['policy'])) ? 'edit' :'add'}}</li>
  </ol>
</nav>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <div class="row">
            <h6 class="card-title col">Policy Detail</h6>
            </div>
            @php
            if (isset($data['policy']))
            $policy = $data['policy'];
            @endphp
            <form class="forms-sample motor_policy-form" method="POST" name="registration" id="motor_policy_form">
                @csrf
                <input type="hidden" class="update_policy" id="update_policy" name="update_policy" value="{{ (!is_null($data['policy'])) ? 1 : 0}}">
                <input type="hidden" name="policy_id" class="policy_id" id="policy_id" value="{{ (!is_null($data['policy'])) ? encryptid($data['policy']->id) : encryptid('0')}}">
            <div class="row">
                <div class="col-md-3">
                    <label >Policy Type<span class="text-danger"> * </span></label>
                    <div class="mb-3">
                      <div class="form-check form-check-inline">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input fresh_policy_type" name="policy_type" id="fresh_policy_type" value="fresh" @isset($policy) @if ($policy->policy_type == 'fresh') checked @endif @endisset>
                          Fresh
                        </label>
                      </div>
                      <div class="form-check form-check-inline">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input fresh_policy_type" name="policy_type" id="fresh_policy_type" value="renewal" @isset($policy) @if ($policy->policy_type == 'renewal') checked @endif @endisset>
                          Renewal
                        </label>
                      </div>
                    </div>
                </div>
                <div class="col-md-3 dNone" id="has_policy_div">
                <div class="form-check mb-2">
                    <input @isset($policy) @if ($policy->has_previous_policy == 2) checked @endif @endisset type="checkbox" class="form-check-input vehicle" id="has_policy" name="has_policy" onchange="has_policy_check();">
                    <label class="form-check-label" for="has_policy">
                    Has Previous Policy No.
                    </label>
                </div>
                </div>
                <div class="col-md-3 dNone" id="pre_policy_number_div">
                <label for="pre_policy_number" class=" control-label">Previous Policy No</label>
                <input type="text" class="form-control" name="renew_pre_policy_number" id="pre_policy_number"  autocomplete="off" placeholder="Previous Policy Number" value="{{ isset($policy->renewal_previous_policy_number)?$policy->renewal_previous_policy_number:'' }}">
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="product" class="  control-label">Product  <span class="text-danger"> * </span></label>
                        <select class="product form-select form-control " id="product" name="product" placeholder="Select product">
                        @if(!$data['products']->isEmpty())
                        <option selected disabled class="input-cstm">Please Select</option>
                        @foreach ($data['products'] as $product)
                        <option @isset($policy) @if ($policy->product_id == $product->id) selected @endif @endisset
                        value="{{ $product->id}}">{{ ucfirst($product->name)}}</option>
                        @endforeach
                        @else
                        <option selected disabled class="input-cstm" style="text-overflow: ellipsis;">Please First Enter Product</option>
                        @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <input type="hidden" id="exit_product_type" name="exit_product_type" value="{{ isset($policy)? $policy->product_type_id :'' }}">
                        <label for="product_type" class="  control-label">Product Type <span class="text-danger"> * </span></label>
                        <select class="product_type form-select form-control " id="product_type" name="product_type" placeholder="Select product type">
                        <option selected disabled value="0" class="input-cstm">Please Select Product</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    @php
                     $business_datepicker=(isset($policy)) ? 'edit_business_date' : 'business_date';
                    @endphp
                <div class="mb-3">
                    <label for="business_date" class="control-label">Business Year And Month <span class="text-danger"> * </span></label>
                    <div class="input-group ">
                        <input type="text" name="business_date"  class="form-control {{$business_datepicker}}" autocomplete="off" id="business_date" value="{{ isset($policy)? $policy->business_date :''/* date('m', strtotime(date('Y-m-d H:i:s'))) */ }}">
                        <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
                </div>
            </div>
            <div class="row dNone" id="pre_policy_div">
                <div class="row">
                  <h6 class="card-title col">Previous Policy Detail</h6>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
                      <label for="pre_policy_number" class="  control-label">Previous Policy Number</label>
                      <input type="text" class="form-control" name="pre_policy_number" id="pre_policy_number"  autocomplete="off" placeholder="Enter Previous Policy Number" value="{{ isset($policy)? $policy->previous_policy_number :'' }}">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
                      <label for="" class="control-label">Previous Policy Start Date <span class="text-danger"> * </span></label>
                      <div class="input-group ">
                      <input type="text" name="pre_start_date"  class="form-control datepicker pre_start_date" autocomplete="off" id="pre_start_date" value="{{ isset($policy)? $policy->previous_start_date :'' }}">
                      <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
    
                      <label for="" class="control-label">Previous Policy End Date <span class="text-danger"> * </span></label>
                      <div class="input-group ">
                      <input type="text" name="pre_end_date"  class="form-control datepicker pre_end_date" autocomplete="off" id="pre_end_date" value="{{ isset($policy)? $policy->previous_end_date :'' }}">
                      <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
                    <input type="hidden" id="has_exit_pre_company" name="has_exit_pre_company" value="{{ isset($policy)? $policy->previous_company_id   :'' }}">
                      <label for="pre_company" class=" control-label">Previous Policy Company  <span class="text-danger"> * </span></label>
    
                      <select class="pre_company has_policy_pre_company form-select form-control  company" id="pre_company" name="pre_company">
                        <option selected disabled class="input-cstm">Please First Select Product</option>
                      </select>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                      <label for="agent" class="control-label">Agent <span class="text-danger"> * </span></label>
                      <select class="form-select form-control select2" id="agent" name="agent" placeholder="Select agent">
                        @if(!$data['agents']->isEmpty())
                          <option selected disabled class="input-cstm">Please Select</option>
                          @foreach ($data['agents'] as $agent)
                          <option @isset($policy->agent_id) @if($policy->agent_id==$agent->id) selected @endif @endisset value="{{ $agent->id }}">{{ ucfirst($agent->code) .'-'. ucfirst($agent->prefix).' '.ucfirst($agent->first_name).' '.ucfirst($agent->last_name) }}</option>
                          @endforeach
                        @else
                          <option selected disabled class="input-cstm">Please First Enter Agent</option>
                        @endif
                      </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="" for="field-1">Customer <span class="text-danger"> * </span></label>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="controls">
                                    <select class="form-select form-control " id="customer" name="customer" placeholder="Select customer">
                                      @if(!$data['customers']->isEmpty())
                                      <option selected disabled class="input-cstm">Please Select</option>
                                      @foreach ($data['customers'] as $customer)
                                      <option @isset($policy) @if ($policy->customer_id == $customer->id) selected @endif @endisset
                                      value="{{ $customer->id}}">{{ ucfirst($customer->customer_code.' '.$customer->prefix.' '.$customer->first_name.' '.$customer->middle_name.' '.$customer->last_name) }} </option>
                                      @endforeach
                                      @else
                                        <option selected disabled class="input-cstm">Please First Enter customer</option>
                                      @endif
                                    </select>
                                  </div>
                            </div>
                            @if ((in_array("82",$permissionList)))
                            <div class="col-md-5">
                                <div id="btnaddcus">
                                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customer_modal" data-bs-whatever="@getbootstrap"> <i class="fa fa-plus"></i> Add Customer </button>
                                  </div>
                            </div>
                            @endif
                        </div>
                    </div>
                  </div>
                <div class="col-md-3">
                <div class="mb-3">
                    <label for="branch" class="control-label">Retinue Branch <span class="text-danger"> * </span></label>
                    <select class="form-select form-control  " id="branch" name="branch" >
                    @if(!$data['irss_branches']->isEmpty())
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach ($data['irss_branches'] as $irss_branche)
                    <option @if (1 == $irss_branche->id) selected @endif value="{{ $irss_branche->id}}">{{ ucfirst($irss_branche->name)}}</option>
                    @endforeach
                    @else
                        <option selected disabled class="input-cstm">Please First Enter IMS Branch</option>
                    @endif
                    </select>
                </div>
                </div>
                <div class="col-md-3 dNone" id="Previews_without_fresh">
                    <div class="mb-3">
                      <input type="hidden" id="exit_renewal_pre_company" name="exit_renewal_pre_company" value="{{ isset($policy)? $policy->renewal_previous_company_id   :'' }}">
                        <label class="" for="field-1">Previous Policy Company <span class="text-danger"> * </span></label>
                        <select class="form-select form-control  company" id="previouspolicycompany" name="previouspolicycompany" placeholder="Previous Policy Company">
                          <option selected disabled class="input-cstm">Please First Select Product</option>
                        </select>
                    </div>
                  </div>
                  @php
                        $code_types = [['key' => 1 , 'value' => 'Agency'],['key' => 2 , 'value' => 'Broker']]
                    @endphp
                  <div class="col-md-3">
                    <div class="mb-3">
                        <label for="code_type" class=" control-label">Code Type  <span class="text-danger"> * </span></label>
                        <select class="code_type form-select form-control  code_type" id="code_type" name="code_type">
                        <option selected disabled class="input-cstm">Please Select</option>
                        @foreach ($code_types as $code_type)
                                <option @isset($policy) @if($policy->code_type==$code_type['key']) selected @endif @endisset value="{{ $code_type['key'] }}">{{ $code_type['value'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <input type="hidden" name="exit_company" id="exit_company" value="{{ isset($policy)? $policy->company_id   :'' }}">
                        <label for="company" class=" control-label">Company  <span class="text-danger"> * </span></label>
                        <select class="company_name form-select form-control  company" id="company" name="company">
                        <option selected disabled class="input-cstm">Please First Select Product</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <input type="hidden" name="exit_company_branch_name" id="exit_company_branch_name" value="{{ isset($policy)? $policy->company_branch_id  :'' }}">
                        <label class="control-label">Company Branch Name <span class="text-danger"> * </span></label>
                        <select class="form-select form-control company_branch_name " data-width="100%" name="company_branch_name" >
                        <option selected disabled class="input-cstm">Please First Select Company</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <input type="hidden" name="exit_branch_imd" id="exit_branch_imd" value="{{ isset($policy)? $policy->branch_imd_id   :'' }}">
                        <label class="control-label">Branch Imd Name <span class="text-danger"> * </span></label>
                        <select class="form-select form-control branch_imd "  name="branch_imd" >
                        <option selected disabled class="input-cstm">Please First Select Branch</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                <div class="mb-3">
                    <input type="hidden" name="exit_sub_product" id="exit_sub_product" value="{{ isset($policy)? $policy->sub_product_id  :'' }}" >
                    <label for="sub_product" class="  control-label">Sub Product <span class="text-danger"> * </span></label>
                    <select class="form-select form-control " id="sub_product" name="sub_product">
                    <option selected disabled class="input-cstm">Please First Select Product</option>
                    </select>
                </div>
                </div>
                <div class="col-md-3">
                <div class="mb-3">
                    <label for="policy_number" class="  control-label">Policy Number</label>
                    <input type="text" class="form-control" name="policy_number" id="policy_number"  autocomplete="off" placeholder="Enter Policy Number" value="@isset($policy){{$policy->policy_number}}@endisset">
                </div>
                </div>
                <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="control-label">Policy Issue Date </label>
                    <div class="input-group ">
                    <input type="text" name="issue_date" value="{{ isset($policy->issue_date)? $policy->issue_date :'' }}" class="form-control datepicker issue_date" autocomplete="off" id="issue_date">
                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="policy_tenure" class="  control-label">Policy Tenure  <span class="text-danger"> * </span></label>
                        <select class="form-select form-control " id="policy_tenure" name="policy_tenure" onchange="addYears()">
                            <option selected disabled class="input-cstm">Please Select</option>
                            @foreach (tenures() as $tenure)
                                <option @isset($policy) @if ($policy->policy_tenure == $tenure) selected @endif @endisset value="{{ $tenure }}">@if ($tenure == 'ABOVE15YRS') {{ 'Above 15 Years'}} @elseif($tenure == 'SHORT') {{ 'Short Period'}} @else  {{ $tenure.' Year'}} @endif</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
              <div class="mb-3">
                <label for="" class="control-label">Policy Start Date </label>
                <div class="input-group ">
                  <input type="text" name="start_date" value="{{ isset($policy->start_date)? $policy->start_date :'' }}" class="form-control datepicker start_date" autocomplete="off" id="start_date" onchange="addYears()">
                  <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                </div>
              </div>
            </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="" class="control-label">Policy End Date </label>
                        <div class="input-group ">
                          <input type="text" name="end_date" value="{{ isset($policy->end_date)? $policy->end_date :'' }}" class="form-control end_date" autocomplete="off" id="end_date" @isset($policy->tenure) @if ($tenure == 'ABOVE15YRS' || $tenure == 'SHORT')  @else  readonly @endif @endisset>
                          <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 dNone tpdate">
                <div class="mb-3">
                    <label for="" class="control-label">Tp Start Date </label>
                    <div class="input-group ">
                    <input type="text" name="tp_start_date" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['tp_start_date']}}@endif" class="form-control  tp_start_date" autocomplete="off" id="tp_start_date" readonly>
                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
                </div>
                <div class="col-md-3 dNone tpdate">
                <div class="mb-3">
                    <label for="" class="control-label">Tp End Date </label>
                    <div class="input-group ">
                    <input type="text" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['tp_end_date']}}@endif" name="tp_end_date" class="form-control  tp_end_date" autocomplete="off" id="tp_end_date" readonly>
                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="control-label" for="policy_copy">Policy Copy</label>
                        <input class="form-control" name="policy_copy" type="file" id="policy_copy" accept="application/pdf" disabled>
                    </div>
                </div>

            </div>
            <hr>
            <div class="row">
            <div class="row">
            <h6 class="card-title col">Vehicle Detail</h6>
            </div>
            <div class="col-md-3">
            <div class="mb-3">
            <label for="registration" class="  control-label">Registration No./ Is New? <span class="text-danger"> * </span>
            <input type="checkbox" id="newregistration" name="new_registration" style="display: inline;" class="registration" @if(isset($policy) && isset($data['policy']['motor_policy_vehicle_only'])) @if ($data['policy']['motor_policy_vehicle_only']['new_registration_no'] == 1) checked @endif @endif>
            </label>
            <input type="text" class="form-control" name="registration" id="vehical_registration"  autocomplete="off" placeholder="Enter Registration Number" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['registration_no']}}@endif">
            </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="rto_code_value" class="  control-label">RTO Code. <span class="text-danger"> * </span> </label>
                    <input type="hidden" name="rto_code_id" id="rto_code_id" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['rto_code_id']}}@endif" >
                    <input type="text" class="form-control rto_code_value" name="rto_code_value" id="rto_code_value"  autocomplete="off" placeholder="Enter RTO Code" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['rto_code_id']}}@endif" readonly>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="rto_city" class="  control-label">RTO City. <span class="text-danger"> * </span> </label>
                    <input type="text" class="form-control rto_city" name="rto_city" id="rto_city"  autocomplete="off" placeholder="Enter RTO City" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['rto_code_id']}}@endif" readonly>
                </div>
            </div>
            
            <div class="col-md-3">
            <div class="mb-3">
                <label for="engine_no" class="  control-label">Engine No. <span class="text-danger"> * </span> </label>
                <input type="text" class="form-control" name="engine_no" id="engine_no"  autocomplete="off" placeholder="Enter Engine Number" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['engine_no']}}@endif">
            </div>
            </div>
            <div class="col-md-3">
            <div class="mb-3">
                <label for="chasiss_no" class="  control-label">Chasiss No. <span class="text-danger"> * </span></label>
                <input type="text" class="form-control" name="chasiss_no" id="chasiss_no"  autocomplete="off" placeholder="Enter Chasiss Number" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['chasiss_no']}}@endif">
            </div>
            </div>
            <div class="col-md-3">
            <div class="mb-3">
                <input type="hidden" name="exit_make_id" id="exit_make_id" value="{{ isset($policy)? (isset($data['policy']['motor_policy_vehicle_only'])?$data['policy']['motor_policy_vehicle_only']['make_id']:'')  :'' }}" >
                <label class="control-label">Make <span class="text-danger"> * </span></label>
                    <select class="make_id form-select form-control  "  name="make_id" >
                    <option selected disabled class="input-cstm">Please First Select Product</option>
                    </select>
            </div>
            </div>
            <div class="col-md-3">
            <div class="mb-3">
                 <input type="hidden" name="exit_model_id" id="exit_model_id" value="{{ isset($policy)? (isset($data['policy']['motor_policy_vehicle_only'])?$data['policy']['motor_policy_vehicle_only']['model_id']:'')  :'' }}" >
                <label class="control-label">Model <span class="text-danger"> * </span></label>
                    <select class="model_id form-select form-control  "  name="model_id" >
                    <option selected disabled class="input-cstm">Please First Select Make</option>
                    </select>
            </div>
            </div>
            <div class="col-md-3">
            <div class="mb-3">
                <input type="hidden" name="exit_variant_id" id="exit_variant_id" value="{{ isset($policy)? (isset($data['policy']['motor_policy_vehicle_only'])?$data['policy']['motor_policy_vehicle_only']['variant_id']:'')  :'' }}" >
                <label class="control-label">Variant <span class="text-danger"> * </span></label>
                    <select class="variant_id form-select form-control  "  name="variant_id" >
                    <option selected disabled class="input-cstm">Please First Select Model</option>
                    </select>
            </div>
            </div>
            <div class="col-md-3">
            <div class="mb-3">
                <label for="cc_no" class="control-label">CC / GVW <span class="text-danger"> * </span></label>
                <input type="text" class="form-control" name="cc_gvw_no" id="cc_no" autocomplete="off" placeholder="Enter CC/GVW Number" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['cc_gvw_no']}}@endif">
            </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="manufacturing_year" class="control-label">Manufacturing Year <span class="text-danger"> * </span></label>
                    <div class="input-group ">
                        <input type="text" name="manufacturing_year"  class="form-control manufacturing_year" autocomplete="off" id="manufacturing_year" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['manufacturing_year']}}@endif">
                        <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="seating_capacity" class="  control-label">Seating Capacity <span class="text-danger"> * </span></label>
                    <input type="text" class="form-control" name="seating_capacity" id="seating_capacity"  autocomplete="off" placeholder="Seating Capacity" value="@if(isset($policy)&&isset($data['policy']['motor_policy_vehicle_only'])){{$data['policy']['motor_policy_vehicle_only']['seating_capacity']}}@endif">
                </div>
            </div>
            @php
            $fuel_types = ['Petrol','Diesel','Bio Fuel','Electric','CNG','Petrol + CNG'];
            @endphp
            <div class="col-md-3">
                <div class="mb-3">
                <label for="fuel_type" class="  control-label">Fuel Type <span class="text-danger"> * </span></label>
                <select class="form-control " data-validation="required" id="fueltype" name="fuel_type" >
                <option selected disabled class="input-cstm">Select</option>
                @foreach ($fuel_types as $fuel_type)
                    <option @if(isset($policy) && isset($data['policy']['motor_policy_vehicle_only'])) @if ($data['policy']['motor_policy_vehicle_only']['fuel_type'] == $fuel_type) selected @endif @endif value="{{ $fuel_type }}">{{$fuel_type}}</option>
                @endforeach
                </select>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="total_idv" class="  control-label">Total IDV</label>
                    <input type="text" class="form-control third_party_disabled" name="total_idv" id="total_idv"  autocomplete="off" placeholder="Enter Total IDV" value="@isset($policy){{$policy->total_idv}}@endisset"> 
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="discount" class="  control-label">Discount</label>
                    <input type="text" class="form-control third_party_disabled" name="discount" id="discount"  autocomplete="off" value="@isset($policy){{$policy->discount}}@endisset" placeholder="Enter Discount">
                </div>
            </div>
            @php
            $ncb_Data = ['0','20','25','35','45','50','55','60','65'];
            @endphp
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="ncb" class="  control-label">NCB</label>
                    <select class="form-select form-control  third_party_disabled"  name="ncb" >
                    <option selected disabled class="input-cstm">Select</option>
                    @foreach ($ncb_Data as $ncb)
                        <option @isset($policy) @if ($policy->ncb == $ncb) selected @endif @endisset value="{{$ncb}}">{{$ncb}}%</option>
                        @endforeach
                    </select>
                </div>
            </div>
                <div class="col-md-3">
                    @php
                    $checked='';
                    if(isset($policy)&&$policy->is_od_only==2)
                    $checked='checked'
                @endphp
                <div class="mb-3">
                    <label for="discount" class="  control-label">OD / Is only OD?<input type="checkbox" id="only_od" name="only_od" onchange="odValueChange($(this))" style="display: inline;" class="only_od" {{ $checked }}> <span class="text-danger"> * </span></label>
                    <input type="text" class="form-control third_party_disabled" name="od" id="od"  autocomplete="off" placeholder="OD" onkeyup="cal_totalpremium()" value="@isset($policy){{$policy->od}}@endisset" >
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="addonpremium" class="  control-label">Add-on Premium</label>
                    <input type="text" class="form-control third_party_disabled" name="addonpremium" id="addonpremium"  autocomplete="off" placeholder="Add-on Premium" onkeyup="cal_totalpremium()" value="@isset($policy){{$policy->addonpremium}}@endisset">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="tp" class="  control-label">TP</label>
                    <input type="text" class="form-control" name="tp" id="tp"  autocomplete="off" placeholder="TP" onkeyup="cal_totalpremium()" value="@isset($policy){{$policy->tp}}@endisset" @if ($checked!='')disabled @endif>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pay_to_owner" class="  control-label">CPA Cover</label>
                    <input type="text" class="form-control" name="pay_to_owner" id="pay_to_owner"  autocomplete="off" placeholder="CPR Cover" onkeyup="cal_totalpremium()" value="@isset($policy){{$policy->pay_to_owner}}@endisset">
                </div>
            </div>
            <div class="col-md-3">
                @php
                    $checked='';
                    if(isset($policy)&&$policy->is_gst_value==2)
                    $checked='checked'
                @endphp
                <div class="mb-3">
                    <label for="gst" class="control-label">GST% / GST Value? <input type="checkbox" id="gst_value" name="gst_value" style="display: inline;" class="gst_value" {{ $checked }}> <span class="text-danger"> * </span></label>
                    <input type="text" class="form-control" name="gst" id="gst"  autocomplete="off" placeholder="GST%" onkeyup="cal_totalpremium()" value="@isset($policy){{$policy->gst}}@endisset">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="stamp_duty" class="  control-label">Stamp Duty</label>
                    <input type="text" class="form-control" name="stamp_duty" id="stamp_duty"  autocomplete="off" placeholder="Stamp Duty" onkeyup="cal_totalpremium()" value="@isset($policy){{$policy->stamp_duty}}@endisset">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="total_premium" class="  control-label">Total Premium</label>
                    <input type="text" class="form-control" value="@isset($policy){{ $policy->total_premium}}@endisset" name="total_premium" id="total_premium"  autocomplete="off" placeholder="Total Premium" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="nominee_name" class="  control-label">Nominee Name</label>
                    <input type="text" class="form-control" name="nominee_name" id="nominee_name"  autocomplete="off" placeholder="Nominee Name" value="@isset($policy){{$policy->nominee_name}}@endisset">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="control-label" for="field-1">Nominee Relation</label>
                    <div class="controls">
                        <select class="form-control form-select " id="nominee_relation" name="nominee_relation">
                            <option selected disabled class="input-cstm" value="0">Please Select</option>
                            @foreach (relations() as $relation)
                            <option value="{{ $relation }}">{{ $relation }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                     <input type="hidden" name="exit_payment_type" id="exit_payment_type" value="@isset($policy)@if($data['payment_type']){{$data['payment_type']['payment_type']}}@endif @endisset">
                <label for="payment_type" class="control-label">Payment Type </label>
                <select class="form-select form-control " id="payment_type" name="payment_type" placeholder="Select payment_type">
                    <option selected disabled class="input-cstm">Please Select</option>
                    @foreach (payment_type() as $payment_type)
                    <option @if(isset($policy) && $data['payment_type']) @if($data['payment_type']['payment_type']==$payment_type['key']) selected @endif @endif value="{{ $payment_type['key'] }}">{{ ucfirst($payment_type['name']) }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="payment_details row">

            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="remark" class="  control-label">Remark</label>

                    <textarea class="form-control" name="remark" id="remark" rows="2">{{ isset($policy->remark)? $policy->remark :'' }}</textarea>
                </div>
            </div>
            </div>

            <button class="btn btn-primary submit_policy" type="button">{{ (!is_null($data['policy'])) ? 'Update' :'Save'}}</button>
            <a href="{{ route('motor-policy.index') }}">
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
    <script src="{{ asset('assets/js/customer/customer.js') }}"></script>
    <script src="{{ asset('assets/js/common/payment_module.js') }}"></script>
    <script src="{{ asset('assets/js/common/getmodel.js') }}"></script>
    <script src="{{ asset('assets/js/common/getvariant.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-product-data.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
    <script src="{{ asset('assets/js/policies/motor-policy.js') }}"></script>
    <script src="{{ asset('assets/js/policies/custom.js') }}"></script>
@endpush
