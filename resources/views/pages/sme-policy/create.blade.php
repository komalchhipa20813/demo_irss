@extends('layout.master')
@section('title',"SME Policy")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
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
    <li class="breadcrumb-item"><a href="{{ route('sme-policy.index') }}">SME Policy</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ (!is_null($data['policy'])) ? 'edit' :'add'}}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <h6 class="card-title col">{{ (!is_null($data['policy'])) ? 'Edit SME' :'Add SME'}}</h6>
        </div>
        @php
          if (isset($data['policy']))
          $policy = $data['policy'];
        @endphp
        <form class="forms-sample sme_policy_form" method="POST" name="registration" id="sme_policy_form">
          @csrf
          <input type="hidden" name="policy_id" class="policy_id" id="policy_id" value="{{ (!is_null($data['policy'])) ? encryptid($data['policy']->id) : encryptid('0')}}">
          <input type="hidden" name="update_policy" id="update_policy" value="{{ (!is_null($data['policy'])) ? 1 : 0 }}" >
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
                <label for="product" class=" control-label">Product  <span class="text-danger"> * </span></label>
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
            </div>

            <div class="col-md-3">
            @php
                $business_datepicker=(isset($policy)) ? 'edit_business_date' : 'business_date';
              @endphp
              <div class="mb-3">
                <label for="business_date" class="control-label">Business Year And Month <span class="text-danger"> * </span></label>
                <div class="input-group ">
                  <input type="text" name="business_date" value="{{ isset($policy->business_date)? $policy->business_date :'' }}" class="form-control {{$business_datepicker}}" autocomplete="off" id="business_date">
                  <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                </div>
              </div>
            </div>

            <div class="col-md-1"  @isset($policy) style="display: none;" @endisset>
              <div class="mb-3">
                <label for="co-sharing" class="control-label">Co-Sharing</label>
                <input type="checkbox" name="co_sharing" id="co_sharing"class="co_sharing">
              </div>
           </div>

          <div class="col-md-2">
            @php
                $arr=['Is Renewable','Not Renewable']
            @endphp
            <div class="mb-3">
                    <label for="field-1">Policy Renew Status</label>
                    <select class="form-select form-control " id="policyrenewstatus" name="policyrenewstatus" placeholder="Select product">
                      @foreach ($arr as $key => $item)
                        <option value="{{ $key+1 }}">{{ $item }}</option>
                      @endforeach
                    </select>
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
                            <select class="form-select form-control" id="customer" name="customer" placeholder="Select customer">
                              @if(!$data['customers']->isEmpty())
                              <option selected disabled class="input-cstm">Please Select</option>
                              @foreach ($data['customers'] as $customer)
                              <option @isset($policy) @if ($policy->customer->id == $customer->id) selected @endif @endisset
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
            <label for="branch" class="  control-label">Retinue Branch  <span class="text-danger"> * </span></label>
            <select class="form-select form-control " id="branch" name="irss_branch">
              @if(!$data['irss_branches']->isEmpty())
              <option selected disabled class="input-cstm">Please Select</option>
              @foreach ($data['irss_branches'] as $branch)
              <option @if (1 == $branch->id) selected @endif value="{{ $branch->id}}">{{ ucfirst($branch->name)}}</option>
              @endforeach
            @else
              <option selected disabled class="input-cstm">Please First Enter Retinue Branch</option>
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
            <input type="hidden" name="exit_company" id="exit_company" value="{{ isset($policy)? $policy->company_id :'' }}">
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
            <label for="sub_product" class="  control-label">Sub Product <span class="text-danger"> * </span> </label>
            <select class="form-select form-control " id="sub_product" name="sub_product">
              <option selected disabled class="input-cstm">Please First Select Product</option>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="mb-3">
              <label for="policy_number" class="  control-label">Policy Number</label>
              <input type="text" class="form-control" name="policy_number" id="policy_number" value="{{ isset($policy)? $policy->policy_number  :'' }}" autocomplete="off" placeholder="Enter Policy Number">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="mb-3">
            <label class="control-label" for="policy_copy">Policy Copy</label>
            <input class="form-control" name="policy_copy" type="file" id="policy_copy" accept="application/pdf" disabled>
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
        <div class="col-sm-3">
          <div class="mb-3">
              <label class="" for="field-1">Discount</label>
              <div class="controls">
                  <input class="form-control" data-val="true" data-val-number="The field discount must be a number." data-validation="" id="discount" maxlength="10" name="discount" placeholder="Discount" type="text" value="{{ isset($policy->discount)? $policy->discount :'' }}">
              </div>
          </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
            <label class="" for="field-1">Sum Insured <span class="text-danger"> * </span></label>
            <div class="controls">
                <input class="form-control" data-val="true" data-val-number="The field suminsured must be a number." data-validation="number" id="sum_insured" maxlength="10" name="sum_insured" placeholder="Sum Insured" type="text" value="{{ isset($policy->sum_insured)? $policy->sum_insured :'' }}">
            </div>
        </div>
     </div>
     <div class="col-md-3">
      <div class="mb-3">
          <label for="od" class="  control-label">OD <span class="text-danger"> * </span></label>
          <input type="text" class="form-control" name="od" id="od" value="{{ isset($policy->od)? $policy->od :'' }}" autocomplete="off" placeholder="Enter OD">
      </div>
      </div>
      

     <div class="col-sm-3">
      <div class="form-group has-error">
          <label class="" for="field-1">Terrorism Premium</b></label>
          <div class="controls">
              <input class="form-control" data-val="true" data-val-number="The field terrorism must be a number." data-validation="number" id="terrorism" maxlength="10" name="terrorism" placeholder="Terrorism" type="text" value="{{ isset($policy->terrorism_premium)? $policy->terrorism_premium :'' }}">
          </div>
      </div>
     </div>

      <div class="col-md-3" id="gst_div">
          @php
            $checked='';
            if(isset($policy)&&$policy->is_gst_value==2)
            $checked='checked'
          @endphp
        <div class="mb-3">
          <label for="gst" class="  control-label">GST% / <span>GST Value ?  <input type="checkbox" id="gstCheck" onchange="gstValueChange()" class="chkgstvalue" name="chkgstvalue" {{ $checked }}></span><span class="text-danger"> * </span></label>
          <input type="text" class="form-control" name="gst" id="gst" value="{{ isset($policy)? $policy->gst :'' }}" autocomplete="off" placeholder="Enter GST in %" >
        </div>
      </div>
      <div class="col-md-3">
                <div class="mb-3">
                    <label for="stamp_duty" class="  control-label">Stamp Duty</label>
                    <input type="text" class="form-control" name="stamp_duty" id="stamp_duty"  autocomplete="off" placeholder="Stamp Duty" value="@isset($policy){{$policy->stamp_duty}}@endisset">
                </div>
            </div>
      <div class="col-md-3">
        <div class="mb-3">
            <label for="total_premium" class="control-label">Total Premium </label>
        <input type="text" class="form-control" name="total_premium" id="total_premium" value="{{ isset($policy)? $policy->total_premium :'' }}" autocomplete="off" placeholder="Total Premium" readonly>
        </div>
      </div>
    <div class="col-sm-3">
        <div class="form-group has-error">
            <label class="" for="field-1">Occupancies<span class="text-danger"> * </span></b></label>
            <div class="controls">
                <input class="form-control" data-validation="required" id="occupancies" name="occupancies" placeholder="Occupancies"  type="text" value="{{ isset($policy)? $policy->occupancies :'' }}">
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
              <option @isset($policy) @if($data['payment_type']['payment_type']==$payment_type['key']) selected @endif @endisset value="{{ $payment_type['key'] }}">{{ ucfirst($payment_type['name']) }}</option>
              @endforeach
          </select>
        </div>
      </div>
      <div class="payment_details row mt-3">

      </div>
      <div class="row col-sm-12">
        <div class="col-sm-12">
            <div class="mb-3">
                <label for="field-1"><b>Remark</b></label>
                <div class="controls">
                    <textarea class="form-control" cols="20" id="remark" name="remark" placeholder="Remark" rows="2">{{ isset($policy->remark)? $policy->remark :'' }}</textarea>
                </div>
            </div>
        </div>
    </div>
  </div>
</form>
<div id="cosharedetails" style="background-color: rgb(228, 225, 225); display:none;" class="row col-sm-12">
  <p style="text-align:center"><b>Co-Sharing Details</b></p>
    <form class="forms-sample co_sharing_details_form" method="POST" id="co_sharing_details_form">
        @csrf
        <div class="row" id="dvpolicytype">
      <div class="col-md-3">
        @php
          $types = ['Leader','Follower','Both']
        @endphp
        <div class="mb-3 form-group">
                <label class="" for="field-1">Select Policy Type</label>
                <select class="form-select form-control  policy_type" id="policy_type" name="co_sharing_policy_type">
                  <option selected disabled class="input-cstm">Please Select</option>
                  @foreach ($types as $key => $item)
                  <option value="{{ $key+1 }}">{{ $item }}</option>
                  @endforeach
                </select>
        </div>
      </div>

      <div class="col-sm-3">
          <div class="form-group">
              <label class="" for="field-1">Policy Premium</label>
              <div class="controls">
                  <input class="form-control" id="policyprm"  name="policyprm"  placeholder="Policy Premium" type="text" value="">
              </div>
          </div>
      </div>
      <div class="col-sm-3">
          <div class="form-group">
              <label class="" for="field-1">Policy Terrorism Premium</label>
              <div class="controls">
                  <input class="form-control"  id="policyterrorism" name="policyterrorism" placeholder="Policy Terrorism Premium" type="text" value="">
              </div>
          </div>
      </div>
      <div class="col-sm-3 share_leader">
          <div class="form-group" id="dvShare" style="">
              <label class="" for="field-1"> Share% </label>

              <div class="controls">
                  <input class="form-control"  id="share" name="share" placeholder="Share%" type="text" value="">
              </div>
         </div>
      </div>
  </div>
          <div class="row" id="addcoshring" style="display:none">
              <div class="col-sm-3 co-sharing-company">
                <div class="mb-3">
                  <label for="company" class=" control-label">Company  <span class="text-danger"> * </span></label>
                  <select class="form-select form-control  company" id="cosharecompany" name="cosharecompany">
                    <option selected disabled class="input-cstm">Please First Select Product</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-label" for="field-1"><b> Type </b></label>
                    <div class="controls">
                        <label id="type">Follower</label>
                    </div>

                </div>
            </div>
              <div class="col-sm-3">
                  <div class="form-group">
                      <label class="" for="field-1"> Share% </label>

                      <div class="controls" id="">
                          <input class="form-control" data-validation="required" id="Bshare" maxlength="2" name="share" placeholder="Share%" type="text" value="">
                      </div>

                  </div>
              </div>

              <div class="col-sm-3">
                  <div class="form-group">
                      <label class="" for="field-1">Policy Premium</label>
                      <div class="controls">
                          <input class="form-control" data-validation="number" readonly id="Bpolicyprm" maxlength="10" name="Bpolicyprm"  placeholder="Policy Premium" type="text" value="">
                      </div>
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="form-group">
                      <label class="" for="field-1">Policy Terrorism Premium</label>
                      <div class="controls">
                          <input class="form-control" data-validation="number" readonly id="Bpolicyterrorism" maxlength="10" name="Bpolicyterrorism"  placeholder="Policy Terrorism Premium" type="text" value="">
                      </div>
                  </div>
              </div>


              <div class="col-sm-3">
                  <label class="control-label" for="field-1"></label>
                  <div class="control">
                      <button type="button" id="btnaddcosharing" class="btn btn-primary">Add</button>
                      <button type="button" onclick="EditCoSharingRow()" style="display:none" id="btnaeditcosharing" class="btn btn-primary">Edit</button>
                  </div>
              </div>
          </div>
      </form>

      <div class="row" id="coshringTbl" style="display:none">
            <div class="col-sm-12">
                <span style="color:red;" class="help-block form-error" id="v_tbldetails"></span>
                <table id="co_sharing_detail" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Company Name</th>
                            <th>Type</th>
                            <th>Share%</th>
                            <th>Policy Premium</th>
                            <th>Policy Terrorism Premium</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <hr>
      </div>

        <button class="btn btn-primary submit_policy" type="button">{{ (!is_null($data['policy'])) ? 'Update' :'Save'}}</button>
        <a href="{{ route('sme-policy.index') }}">
        <button class="btn btn-primary"  type="button">Back</button>
        </a>
      </div>
    </div>
  </div>
</div>

@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/policies/sme-policy.js') }}"></script>
  <script src="{{ asset('assets/js/policies/custom.js') }}"></script>
  <script src="{{ asset('assets/js/customer/customer.js') }}"></script>
  <script src="{{ asset('assets/js/common/payment_module.js') }}"></script>
  <script src="{{ asset('assets/js/common/get-product-data.js') }}"></script>
  <script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
@endpush
