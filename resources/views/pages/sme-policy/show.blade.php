@php
use Carbon\Carbon;
    if (isset(Auth::user()->id)) {
        $permissionList = permission();
    }
    if (!is_null(Auth::guard('agent')->user()) || !is_null(Auth::guard('fdo')->user())) {
        $master = 'pages.fdo-agent-panel.layout.master';
    } else {
        $master = 'layout.master';
    }
@endphp
@extends($master)
@section('title', 'Show SME Policy')
@push('style')
    <style>
        .control-label {
            font-weight: 500;
        }
    </style>
@endpush
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ isset(Auth::user()->id) ? route('sme-policy.index') : route('fdo-agent.sme-policy.index') }}">SME
                    Policy</a></li>
            <li class="breadcrumb-item active" aria-current="page">Show</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin policy-grid-margin">
            <div class="card">
                <div class="card-body">
                    <div><h5 class="card-title">SME - {{$getPolicy->inward_no}} - {{$getPolicy->branch->name}}- {{$getPolicy->business_date}} </h5></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin policy-grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Policy Details</h4>
                    <hr>
                    <div class="row show-data">
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Type</label>
                            <span>{{ isset($getPolicy->policy_type) ? ucwords( $getPolicy->policy_type) : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Product Type</label>
                            <span>{{ isset($getPolicy->product_type->type) ? $getPolicy->product_type->type : '-'}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Main Product</label>
                            <span>{{ 'SME' .' - '.  (isset($getPolicy->product_id) ? $getPolicy->product->name :'') .''.
                                (isset($getPolicy->sub_product_id)?(' - '.$getPolicy->sub_product->name ): '')
                            }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Customer Code/Name</label>
                            <span>{{ isset($getPolicy->customer->customer_code) ? ($getPolicy->customer->customer_code ): '-' }} - {{ isset($getPolicy->customer->full_name) ? $getPolicy->customer->full_name : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Agent Code/Name</label>
                            <span>{{ isset($getPolicy->agent->code) ? $getPolicy->agent->code : '-' }} - {{ isset($getPolicy->agent_id) ? ($getPolicy->agent->first_name.' '.$getPolicy->agent->last_name ) : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Code Type</label>
                            <span>{{ ($getPolicy->code_type == 1) ? 'Agency' : 'Broker' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Company</label>
                            <span>{{ isset($getPolicy->company->name) ? $getPolicy->company->name : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Company Branch Name</label>
                            <span>{{ isset($getPolicy->companyBranch->name) ? $getPolicy->companyBranch->name : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Branch Imd Name</label>
                            <span>{{ isset($getPolicy->branch_imd_name->name) ? $getPolicy->branch_imd_name->name : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Tenure</label>
                            <span>{{ isset($getPolicy->policy_tenure) ? ($getPolicy->policy_tenure) : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Number</label>
                            <span>{{ isset($getPolicy->policy_number) ? $getPolicy->policy_number : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Start Date</label>
                            <span>{{ isset($getPolicy->start_date) ? $getPolicy->start_date : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy End Date</label>
                            <span>{{ isset($getPolicy->end_date) ? $getPolicy->end_date : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Occupancies</label>
                            <span>{{ isset($getPolicy->occupancies) ? $getPolicy->occupancies : '-' }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin policy-grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Previous Policy Details</h4>
                    <hr>
                    <div class="row show-data">
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Number</label>
                            <span>
                            @if($getPolicy->policy_type == 'renewal')
                                    {{ isset($getPolicy->renewal_previous_policy_number) ? $getPolicy->renewal_previous_policy_number : '-' }}
                                @else
                                    {{ isset($getPolicy->previous_policy_number) ? (($getPolicy->has_previous_policy == 2)? $getPolicy->previous_policy_number : '-') : '-' }}
                                @endif
                            </span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Company</label>
                            <span>
                            @if($getPolicy->policy_type == 'renewal')
                                    {{ isset($getPolicy->renewal_previous_company_id) ? $getPolicy->renewal_previous_company->name : '-' }}
                                @else
                                    {{ isset($getPolicy->previous_company_id) ? (($getPolicy->has_previous_policy == 2)? $getPolicy->previous_company->name : '-') : '-' }}
                                @endif
                            </span>
                        </div>
                       
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy Start Date</label>
                            <span>{{ isset($getPolicy->previous_start_date) ? (($getPolicy->policy_type != 'renewal' && $getPolicy->has_previous_policy == 2 )? $getPolicy->previous_start_date : '-') : '-' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Policy End Date</label>
                            <span>{{ isset($getPolicy->previous_end_date) ? (($getPolicy->policy_type != 'renewal' && $getPolicy->has_previous_policy == 2) ? $getPolicy->previous_end_date : '-') : '-' }}</span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin policy-grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Policy Premium Details</h4>
                    <hr>
                    <div class="row show-data">
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">OD</label>
                            <span>{{ isset($getPolicy->od) ? $getPolicy->od : '0' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Terrorism</label>
                            <span>{{ isset($getPolicy->terrorism_premium) ? $getPolicy->terrorism_premium : '0' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">GST / Value</label>
                            <span>{{ isset($getPolicy) ? $data['gst_value'] : '0' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Stamp Duty</label>
                            <span>{{ isset($getPolicy->stamp_duty) ? $getPolicy->stamp_duty : '0' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="product" class="control-label">Total Premium</label>
                            <span>{{ isset($getPolicy) ? $data['total_premium'] : '0' }}</span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin policy-grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment Details</h4>
                    <hr>
                    <div class="row show-data">
                        <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Payment Type</label>
                                <span>{{ isset($payment->payment_type) ? config('constants.health_policy_payments.payment_type.' . $payment->payment_type) : '-' }}</span>
                            </div>
                            @if (isset($payment->payment_type))
                                @if (in_array($payment->payment_type, [2, 5, 6, 9]))
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Bank</label>
                                        <span>{{ isset($payment->bank->name) ? $payment->bank->name : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Account Number</label>
                                        <span>{{ isset($payment->account_number) ? $payment->account_number : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Cheque Number</label>
                                        <span>{{ isset($payment->number) ? $payment->number : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Cheque Date</label>
                                        <span>{{ isset($payment->payment_date) ? $payment->payment_date : '-' }}</span>
                                    </div>
                                @endif

                                @if (in_array($payment->payment_type, [3, 6, 7, 10]))
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Bank</label>
                                        <span>{{ isset($payment->bank->name) ? $payment->bank->name : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Account Number</label>
                                        <span>{{ isset($payment->account_number) ? $payment->account_number : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">DD Number</label>
                                        <span>{{ isset($payment->number) ? $payment->number : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">DD Date</label>
                                        <span>{{ isset($payment->payment_date) ? $payment->payment_date : '-' }}</span>
                                    </div>
                                @endif

                                @if (in_array($payment->payment_type, [4, 8, 9, 10]))
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Bank</label>
                                        <span>{{ isset($payment->bank->name) ? $payment->bank->name : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Transaction Number</label>
                                        <span>{{ isset($payment->number) ? $payment->number : '-' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="product" class="control-label">Transaction Date</label>
                                        <span>{{ isset($payment->payment_date) ? $payment->payment_date : '-' }}</span>
                                    </div>
                                @endif
                            @endif
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Amount</label>
                                <span>{{ isset($payment->amount) ? $payment->amount : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin policy-grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Others Details</h4>
                        <hr>
                        <div class="row show-data">
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Entry Created By</label>
                                <span>{{ isset($getPolicy->created_by_id) ? ($getPolicy->created_by->code.' - '.ucwords( $getPolicy->created_by->first_name) .' '.ucwords($getPolicy->created_by->last_name)) : '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Entry Created Date</label>
                                <span>{{ isset($getPolicy->created_at) ? Carbon::parse($getPolicy->created_at)->format('d-m-Y') : '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Entry Edited By</label>
                                <span>{{ isset($getPolicy->edited_by_id) ? ($getPolicy->edited_by->code.' - '.ucwords($getPolicy->edited_by->first_name) .' '.ucwords($getPolicy->edited_by->last_name)) : '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Policy Updated By</label>
                                <span>{{ isset($getPolicy->updated_by_id) ? ($getPolicy->updated_by->code.' - '.ucwords($getPolicy->updated_by->first_name) .' '.ucwords($getPolicy->updated_by->last_name)) : '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">QC Status</label>
                                <span>{{ '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">QC Done By</label>
                                <span>{{ '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">QC Updated By</label>
                                <span>{{ '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">QC Remark</label>
                                <span>{{ '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Remark</label>
                                <span>{{ isset($getPolicy->policy_cancel_reason) ? $getPolicy->policy_cancel_reason : '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Policy Cancel Reason</label>
                                <span>{{ isset($getPolicy->policy_cancel_reason) ? $getPolicy->policy_cancel_reason : '-' }}</span>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="product" class="control-label">Policy Cancel Remark</label>
                                <span>{{ isset($getPolicy->policy_cancel_remark) ? $getPolicy->policy_cancel_remark : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
