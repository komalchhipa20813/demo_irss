@extends('layout.master')
@section('title',"Update Profile")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.profile') }}">User Profile</a></li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                <h6 class="card-title col">Profile</h6>
                </div>
                <form class="forms-sample" method="POST" name="profile" id="profile_form">
                    <input type="hidden" name="employee_id" class="employee_id" id="employee_id" value="{{ (!is_null($employee)) ? encryptid($employee->id) : encryptid('0')}}">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="row profile_div">
                            <div class="col-md-2">
                                <label class="  control-label">Profile Photos<span class="text-danger"> {{ isset($employee)? '' : '*'}} </span></label>
                            <div>
                                <div class="uploader" id="uniform-filename_employee" >
                                    <input type="file" accept="image/*" value="" name="image" id="filename_employee" class="file-styled-primary" >
                                </div>
                            </div>
                            </div>
                            <div class="col-md-3">
                                <img id="employee_image_preview" class="empimage" src="{{(isset($employee) && $employee->image ? asset('store/employee/profile').'/'. $employee->image : '')}}" width="120px">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="email" class="  control-label">Email <span class="text-danger"> * </span></label>
                            <input type="email" class="form-control" name="email" value="{{ isset($employee)? $employee->email :'' }}" id="email" placeholder="Enter Branch Email">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            @php
                                $prefixes=['Mr.','Mrs','Miss','Dr','Er']
                            @endphp
                            <label for="prefix" class="control-label">Prefix  <span class="text-danger"> * </span></label>
                            <select class="form-select form-control " id="prefix" name="prefix" placeholder="Select prefix">
                                <option selected disabled class="input-cstm">Please Select</option>
                                @foreach ($prefixes as $prefix)
                                <option @isset($employee->prefix) @if($employee->prefix==$prefix) selected @endif @endisset value="{{ $prefix }}">{{ $prefix }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="first_name" class="  control-label">First Name <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="{{ isset($employee)? $employee->first_name :'' }}" autocomplete="off" placeholder="Enter First Name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="middle_name" class="  control-label">Middle Name <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="middle_name" id="middle_name" value="{{ isset($employee->middle_name)? $employee->middle_name :'' }}" autocomplete="off" placeholder="Enter middle Name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="last_name" class="  control-label">Last Name <span class="text-danger"> * </span></label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{ isset($employee)? $employee->last_name :'' }}" autocomplete="off" placeholder="Enter Last Name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="phone" class="  control-label">Contact No <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="phone" value="{{ isset($employee)? $employee->phone :'' }}" id="phone" placeholder="Enter Contact Number">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="address" class="  control-label">Address  <span class="text-danger"> * </span></label>
                            <textarea name="address" id="address" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5" spellcheck="false">{{ isset($employee->address)? $employee->address :'' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @php
                            $genders = [['key' => 0 , 'value' => 'MALE'],['key' => 1 , 'value' => 'FEMALE']]
                        @endphp
                        <div class="mb-3">
                            <label for="gender" class="  control-label">Gender  <span class="text-danger"> * </span></label>
                            <select class="form-select form-control " id="gender" name="gender" placeholder="Select Gender">
                                <option selected disabled class="input-cstm">Please Select</option>
                                @foreach ($genders as $gender)
                                    <option @isset($employee->gender) @if($employee->gender==$gender['key']) selected @endif @endisset value="{{ $gender['key'] }}">{{ $gender['value'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="dob" class="  control-label">Date of Birth <span class="text-danger"> * </span></label>
                            <div class="input-group ">
                            <input type="text" name="dob" value="{{ isset($employee->dob)? $employee->dob :'' }}" class="form-control dobdatePicker" autocomplete="off" id="dobdatePicker">
                            <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="jdatePicker" class="control-label">Joining Date <span class="text-danger"> * </span></label>
                            <div class="input-group ">
                            <input type="text" name="joining_date" value="{{ isset($employee->joining_date)? $employee->joining_date :'' }}" class="form-control datepicker joindatePicker" autocomplete="off" id="" readonly>
                            <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="adatePicker" class="control-label">Anniversary Date </label>
                            <div class="input-group ">
                            <input type="text" name="anniversary_date" value="{{ isset($employee->anniversary_date)? $employee->anniversary_date :'' }}" class="form-control datepicker anniversary_date" autocomplete="off" id="adatePicker">
                            <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="salary" class="  control-label">Salary</label>
                            <input type="number" class="form-control" name="salary" id="salary" value="{{ isset($employee->salary)? $employee->salary :'' }}" autocomplete="off" placeholder="Enter Salary" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="salary_account" class="  control-label">Salary Account Number</label>
                            <input type="text" class="form-control" name="salary_account" id="salary_account" value="{{ isset($employee->account_number)? $employee->account_number :'' }}" autocomplete="off" placeholder="Enter Salary Account" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="ifsc_code" class="  control-label">IFSC code</label>
                            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="{{ isset($employee->ifsc_code)? $employee->ifsc_code :'' }}" autocomplete="off" placeholder="Enter IFSC code" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="account_holder" class="  control-label">Account Holder Name</label>
                            <input type="text" class="form-control" name="account_holder" id="account_holder" value="{{ isset($employee->holder_name)? $employee->holder_name :'' }}" autocomplete="off" placeholder="Enter Account Holder" readonly>
                        </div>
                    </div>
                    <div class="">
                        <button class="btn btn-primary submit_customer" type="button">Update Profile</button>
                        <a href="{{ route('employee.index') }}">
                        <button class="btn btn-primary"  type="button">Back</button>
                        </a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/profile/profile.js') }}"></script>
@endpush
