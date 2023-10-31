@php
if (!is_null(Auth::guard('agent')->user())||!is_null(Auth::guard('fdo')->user())){
    $master='pages.fdo-agent-panel.layout.master';
}
else{
    $master='layout.master';
}   
@endphp
@extends($master)    
@section('title', 'Agent')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agent</li>
        </ol>
    </nav>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#search_criteria">
        Search Criteria
    </button>
    <!-- Password Modal -->
<div class="modal fade  bd-example-modal-md" id="password_modal" tabindex="-1" aria-labelledby="title_password_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_password_modal">Change Password </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST"  id="password_form">
            @csrf
              <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
              <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control " id="password"  name="password" placeholder="Enter Password">
              </div>
              <div class="mb-3">
                  <label for="confirmpassword" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control " id="confirmpassword"  name="confirmpassword" placeholder="Enter Confirm Password">
              </div>
            <button class="btn btn-primary submit_password" type="button"></button>
          </form>
        </div>
      </div>
    </div>
</div>
    <!-- Modal -->
    <div class="modal fade select" id="search_criteria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="filter_records_title">Filter  Records</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="agent_filter_section" id="agent_filter_section">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fdo_code" class="control-label">FDO Code</label>
                            <input type="text" class="form-control" name="fdo_code" id="fdo_code" value="" autocomplete="off" placeholder="Enter FDO Code">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="agent_code" class="control-label">Agent Code</label>
                            <input type="text" class="form-control" name="agent_code" id="agent_code" value="" autocomplete="off" placeholder="Enter Agent Code">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="agent_name" class="control-label">Agent Name</label>
                            <input type="text" class="form-control" name="agent_name" id="agent_name" value="" autocomplete="off" placeholder="Enter Agent Name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="business_category" class="control-label">Business Category</label>
                            <select class="form-select form-control " id="business_category" name="business_category" placeholder="Select Business Category Name">
                                @if(!$data['business_category']->isEmpty())
                                <option selected disabled class="input-cstm" value="0">Please Select</option>
                                @foreach ($data['business_category'] as $category)
                                <option  value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="home_branch" class="control-label">Home Branch</label>
                            <select class="form-select form-control " id="home_branch" name="home_branch" placeholder="Select Home Branch">
                                @if(!$data['home_branch']->isEmpty())
                                <option selected disabled class="input-cstm" value="0">Please Select</option>
                                @foreach ($data['home_branch'] as $branch)
                                <option  value="{{ $branch->id }}">{{ ucfirst($branch->name) }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sales_manager" class="control-label">Sales Manager</label>
                            <select class="form-select form-control " id="sales_manager" name="sales_manager" placeholder="Select Sales Manager">
                                @if(!$data['sales_manager']->isEmpty())
                                <option selected disabled class="input-cstm" value="0">Please Select</option>
                                @foreach ($data['sales_manager'] as $manager)
                                <option  value="{{ $manager->id }}">{{ ucfirst($manager->first_name.' '.$manager->middle_name.' '.$manager->last_name) }}</option>
                                @endforeach
                                @else
                                <option selected disabled class="input-cstm" value="0">Please Enter Sales Manager</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pancard_no" class="control-label">PAN Card NO.</label>
                            <input type="text" class="form-control" name="pancard_no" id="pancard_no" value="" autocomplete="off" placeholder="Enter Pancard No.">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="aadharcard_no" class="control-label">Aadhar Card No.</label>
                            <input type="text" class="form-control" name="aadharcard_no" id="aadharcard_no" value="" autocomplete="off" placeholder="Enter Aadhara No.">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mobile_no" class="control-label">Mobile NO.</label>
                            <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="" autocomplete="off" placeholder="Enter Mobile No">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="from_date" class="control-label">From Date</label>
                            <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="" autocomplete="off" placeholder="Enter From Date">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="end_date" class="control-label">End Date</label>
                            <input type="text" class="form-control datepicker" name="end_date" id="sum_insured" value="" autocomplete="off" placeholder="Enter End Date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2" style="width: auto">
                            <button  class="btn btn-primary filter_agent_record" id="filter_agent_record">Filter Agent</button>
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
                        <h6 class="card-title col">Agent</h6>
                        <div class="col-2 " style="width: auto">
                            @if (isset(Auth::user()->id)&&in_array('78', permission()))
                                <a href="{{ route('agent.create') }}" class="btn btn-primary add_agent"
                                    data-id="{{ encryptid('0') }}" style="float: right" id="add_agent">Add Agent</a>
                            @endif
                        </div>
                        <div class="col-2 exportBtn" style="width: auto">
                            <a class="btn btn-primary" style="float: right" href="{{ route('agents.export') }}">Export Agent</a>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="agent_tbl" class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>FDO Name</th>
                                    <th>Home Branch</th>
                                    <th>Documents</th>
                                    <th>Action</th>
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
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/agent/agent.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
@endpush
