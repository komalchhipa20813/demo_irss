@extends('layout.master')
@section('title',"Employee")
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">employees</li>
  </ol>
</nav>
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
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">Employees</h6>
            <div class="col-2 " style="width: auto">
              @if(in_array("46", permission()))
                <a href="{{ route('employee.create') }}" class="btn btn-primary add_employee" data-id="{{ encryptid('0') }}" style="float: right" id="add_employee">Add Employee</a>
              @endif
            </div>
            <div class="col-2 " style="width: auto">
              <a class="btn btn-primary" style="float: right" href="{{ route('export-employee') }}">Export Employee</a>
            </div>
          </div>
        <div class="table-responsive mt-2">
          <table id="employee_tbl" class="table" >
            <thead>
              <tr>
                <th>Id</th>
                <th>Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Branch</th>
                <th>Department</th>
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
<script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/user/user.js') }}"></script>
<script src="{{ asset('assets/js/common/custom.js') }}"></script>
  
@endpush