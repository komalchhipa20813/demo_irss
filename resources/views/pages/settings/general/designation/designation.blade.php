@extends('layout.master')
@section('title',"Designation")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  @endpush

@section('content')
    <nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Designation</li>
    </ol>
    </nav>

    {{-- Designation Datatable --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
                <div class="row">
                  <h6 class="card-title col">Designations</h6>
                  <div class="col-2 ">
                    @if(in_array("10", permission()))
                      <a  class="btn btn-primary add_designation" data-id="{{ encryptid('0') }}" style="float: right" id="add_designation">Add Designation</a>
                    @endif
                    </div>
                </div>
              <div class="table-responsive mt-2">
                <table id="designation_tbl" class="table">
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

    {{-- Designation Modal --}}
    <div class="modal fade  bd-example-modal-md designation_modal" id="designation_modal" tabindex="-1" aria-labelledby="title_designation_modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="designation_modal_title"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
              <form class="forms-sample" method="POST" name="designation" id="designation_form">
                @csrf
                <input type="hidden" name="designation_id" class="designation_id" value="{{ encryptid('0') }}">
                <div class="mb-3">
                    <label for="name" class="form-label">Designation Name</label>
                    <input type="text" class="form-control name" id="name" name="name" placeholder="Please Enter Designation Name" autocomplete="off">
                </div>
                <button class="btn btn-primary submit_designation" type="button"></button>
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
 <!-- jquery validationjs -->
<script src="{{ asset('assets/js/common/custom.js') }}"></script>
<script src="{{ asset('assets/js/designation/designation.js') }}"></script>
@endpush
