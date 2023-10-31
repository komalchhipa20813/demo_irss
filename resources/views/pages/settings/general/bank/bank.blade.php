@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('title', 'Bank')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Bank</li>
    </ol>
</nav>
<!-- Country Modal -->
<div class="modal fade  bd-example-modal-md" id="bank_modal" tabindex="-1" aria-labelledby="title_bank_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_bank_modal">Add Bank </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="bank_form">
            @csrf
              <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
              <div class="mb-3">
                  <label for="name" class="form-label">Bank Name</label>
                  <input type="text" class="form-control " id="name"  name="name" placeholder="Enter Bank Name">
              </div>
            <button class="btn btn-primary submit_bank" type="button"></button>
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
              <h6 class="card-title col">Banks</h6>
              <div class="col-2 ">
                @if(in_array("98", permission()))
                    <a  class="btn btn-primary add_bank" data-id="{{encryptid('0')}}" style="float: right" id="add_bank">Add Bank</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="bank_tbl" class="table" style="width: 98% !important;" >
              <thead>
                <tr>
                  <th>No</th>
                  <th>Bank Name</th>
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
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/bank/bank.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
@endpush
