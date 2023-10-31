@extends('layout.master')
@section('title',"Add Member")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('layout.customer_modal')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('health-policy.index') }}">Health Policy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Member</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
           <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="inward_no"  id="inward_no" placeholder="Enter inward no">
                        </div>
                        <div class="col-md-3"><button type="button" class="btn btn-primary search_customer"> Search </button></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-lg-12 mt-3">
                        <table id="addMember_tbl" class="display table table-bordered table-condensed table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Policy No</th>
                                    <th>Product</th>
                                    <th>Company Name</th>
                                    <th>Agent Code</th>
                                    <th>Customer Name</th>
                                    <th>Inward No</th>
                                </tr>
                            </thead>
                            <tbody id="memberData">
                                
                           </tbody>
                        </table>
                    </div>
                </div>
                <div class="row member_relation_wrapper mt-5">

                    <form action="#" method="POST" id="addMemberForm" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 col-xs-12 col-lg-12">
                            <div class="row mb-3">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="hidden" class="id" value="" name="health_policy_id" id="health_policy_id">
                                        <label class="control-label" for="field-1">Relation</label>
                                        <div class="controls">
                                            <select class="form-control form-select  " id="relation" name="relation">
                                                <option selected disabled class="input-cstm">Please Select</option>
                                                <option value="Self">Self</option>
                                                <option value="Father">Father</option>
                                                <option value="Father In Law">Father In Law</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Mother in Law">Mother in Law</option>
                                                <option value="Husband">Husband</option>
                                                <option value="Wife">Wife</option>
                                                <option value="Brother">Brother</option>
                                                <option value="Sister">Sister</option>
                                                <option value="Son">Son</option>
                                                <option value="Daughter">Daughter</option>
                                                <option value="Daughter in Law">Daughter in Law</option>
                                                <option value="Son in Law">Son in Law</option>
                                            </select>
                                            <span style="color:red;" class="help-block form-error" id="v_relation"></span>
                                            <label id="relation-error" class="error" for="relation"></label>
    
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label" for="field-1">Name</label>
                                        <div class="controls">
                                            <input class="form-control" id="name" name="name" type="text" value="">
                                            <span style="color:red;" class="help-block form-error" id="v_name"></span>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="field-1">Birth Date</label>
                                        <div class="controls">
                                            <input class="form-control pre_start_date" onchange="countAge();" autocomplete="off" id="dobdatePicker" name="birthdate" placeholder="dd/mm/yyyy" type="text" value="">
                                            <span style="color:red;" class="help-block form-error" id=""></span>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="field-1">Age</label>
                                        <div class="controls">
                                            <input class="form-control" id="age" name="age" readonly="readonly" type="text" value="">
                                            <span style="color:red;" class="help-block form-error" id="v_age"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label class="control-label" for="field-1">&nbsp;</label>
                                            <input type="button" value="Add" id="btnaddmember" class="btn btn-primary">
                                            <input type="reset" value="Clear" id="btnaddclear" class="btn btn-warning">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <span style="color:red;" class="help-block form-error" id="v_tbldetails"></span>
                            <table id="member_tbl" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Relation</th>
                                        <th>Name</th>
                                        <th>DOB</th>
                                        <th>Age</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody  class="addMemberList">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/policies/health-policy.js') }}"></script>

    <script type = "text/javascript">
        function getAge(dob){
            dob = new Date(dob);
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            return age;
        }
        function countAge(){
            var dob = document.getElementById('dobdatePicker').value;
            $('#age').val(getAge(dob));
        }
    </script>

@endpush
