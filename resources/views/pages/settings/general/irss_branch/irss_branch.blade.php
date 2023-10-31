@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('title', 'IRSS Branch')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">IRSS Branch</li>
    </ol>
</nav>
<!-- IRSS Branch Modal -->
<div class="modal fade select bd-example-modal-md" id="irssBranch_modal" tabindex="-1" aria-labelledby="title_irssBranch_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_irssBranch_modal">Add IRSS Branch </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="irssBranch_form">
            @csrf
            <input type="hidden" name="id" class="id" id="id" value="{{encryptid(0)}}">
            <div class="row">
              <div class="col-md-4 mb-3">
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
              <div class="col-md-4 mb-3">
                  <label class="form-label">State Name</label>
                  <select class=" form-select state_name" data-width="100%" name="state_name" >
                  </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">City Name</label>
                <select class=" form-select city_name" data-width="100%" name="city_name" >
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="IrssBranch_name" class="form-label">IRSS Branch Name</label>
                <input type="text" class="form-control " id="IrssBranch_name" value="" name="IrssBranch_name" placeholder="Enter IRSS Branch Name">
              </div>
              <div class="col-md-4 mb-3">
                <label for="IrssBranch_inward_code" class="form-label">IRSS Branch Inward Code</label>
                <input type="text" class="form-control " id="IrssBranch_inward_code" value="" name="IrssBranch_inward_code" placeholder="Enter IRSS Branch Inward Code" oninput="this.value = this.value.toUpperCase()">
              </div>
              <div class="col-md-12 mb-3">
                <label>Address</label>
                <textarea class="form-control" id="address" placeholder="Enter  Address" name="address" rows="5" value=""></textarea>
              </div>
            </div>
           
            <button class="btn btn-primary submit_irss_Branch" type="button"></button>
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
              <h6 class="card-title col">IRSS Branches</h6>
              <div class="col-2 ">
                @if(in_array("2", permission()))
                    <a  class="btn btn-primary add_IrssBranch" data-id="{{ encryptid('0') }}" style="float: right" id="add_IrssBranch">Add IRSS Branch</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="retinue-branch_tbl" class="table" >
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Policy Inward Code</th>
                  <th>Country</th>
                  <th>State</th>
                  <th>City</th>
                  <th>Address</th>
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
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/IrssBranch/irss_branch.js') }}"></script>
  <script src="{{ asset('assets/js/common/custom.js') }}"></script>
  <script src="{{ asset('assets/js/common/getState.js') }}"></script>
  <script src="{{ asset('assets/js/common/getCity.js') }}"></script>
@endpush