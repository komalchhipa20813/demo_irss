@extends('layout.master')
@section('title',"Department")
@section('content')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />

@endpush

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Department</li>
    </ol>
</nav>

  {{-- Listing Department Data --}}
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <h6 class="card-title col">Departments</h6>
              <div class="col-2 ">
                @if(in_array("6", permission()))
                  <a  class="btn btn-primary department_modal_btn"  style="float: right" id="department_modal_btn" data-bs-toggle="modal" data-bs-target="#department_modal"  data-id="{{ encryptid('0') }}">Add Department</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="department_tbl" class="table" >
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Department Modal --}}
  <div class="modal fade department_modal" id="department_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="depatment_modal_title">Add Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
            <form class="forms-sample" id="department_form" method="POST">
              @csrf
                <div class="mb-3">
                  <input type="hidden" name="department_id" class="department_id" value="{{ encryptid('0') }}">
                  <label for="exampleInputUsername1" class="form-label">Department Name</label>
                  <input type="text" class="form-control department_name" id="department_name" name="department_name" autocomplete="off" placeholder="Please Enter Department Name">
                </div>
                <button type="button" class="btn btn-primary add_department_btn" id="add_department_btn" ></button>
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
@endpush
@push('custom-scripts')
  <script src="{{ asset('assets/js/common/custom.js') }}"></script>
  <script src="{{ asset('assets/js/department/department.js')}}"></script>
@endpush
