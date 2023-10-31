@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('title', 'RTO Code')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">RTO Code</li>
    </ol>
</nav>
<!-- rto_code Modal -->
<div class="modal fade  bd-example-modal-md" id="rto_modal" tabindex="-1" aria-labelledby="title_rto_code_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_rto_code_modal">Add RTO Code</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="rto_code_form">
            @csrf
              <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
              <div class="mb-3">
                  <label for="rto_code" class="form-label">RTO Code</label>
                  <input type="text" class="form-control " id="rto_code"  name="rto_code" placeholder="Enter RTO Code">
              </div>
              <div class="mb-3">
                  <label for="city_name" class="form-label">City Name</label>
                  <input type="text" class="form-control " id="city_name"  name="city_name" placeholder="Enter City Name">
              </div>
            <button class="btn btn-primary submit_rto_code" type="button"></button>
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
              <h6 class="card-title col">RTO Codes</h6>
              <div class="col-2 ">
                @if(in_array("54", permission()))
                    <a  class="btn btn-primary add_rto_code" data-id="{{encryptid('0')}}" style="float: right" id="add_rto_code">Add RTO Code</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="rto-code_tbl" class="table" >
              <thead>
                <tr>
                  <th>No</th>
                  <th>Code</th>
                  <th>City Name</th>
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
    <script src="{{ asset('assets/js/rto_code/rto-code.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
@endpush
