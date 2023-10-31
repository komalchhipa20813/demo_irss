@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('title', 'City')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">City</li>
    </ol>
</nav>
<!-- City Modal -->
<div class="modal fade select  bd-example-modal-md" id="city_modal" tabindex="-1" aria-labelledby="title_city_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_city_modal">Add City </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="city_form">
            @csrf
            <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Country Name</label>
                <select class=" form-select country_name" data-width="100%" name="country_name" >
                @if($country->isEmpty())
                    <option selected disabled value="0">First Enter Country Name</option>
                @else
                    <option value="0" selected disabled>Select Country Name</option>
                    @foreach ($country as $values)
                    <option value="{{ $values['id'] }}">{{ $values['name'] }}</option>
                    @endforeach
                @endif
                </select>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label">State Name</label>
                  <select class=" form-select state_name" data-width="100%" name="state_name" >
                  </select>
                </span>
              </div>
              <div class="col-md-6 mb-3">
                <label>City Name</label>
                <input type="text" class="form-control " id="city" value="" name="city" placeholder="Enter City Name">
              </div>
              <div class="col-md-6 mb-3">
                <label>RTO Code</label>
                <input type="text" class="form-control " id="rto_code_id" value="" name="rto_code"  placeholder="Enter City RTO Code">
              </div>
            </div>
           
            <button class="btn btn-primary submit_city" type="button"></button>
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
              <h6 class="card-title col">Cities</h6>
              <div class="col-2 ">
                @if(in_array("62", permission()))
                    <a  class="btn btn-primary add_city" data-id="{{ encryptid('0') }}" style="float: right" id="add_city">Add City</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="city_tbl" class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Country Name</th>
                  <th>State Name</th>
                  <th>City Name</th>
                  <th>RTO Code</th>
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
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script> 
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/city/city.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/getState.js') }}"></script>
@endpush