@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('title', 'Holiday')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Holiday</li>
    </ol>
</nav>
<!-- Country Modal -->
<div class="modal fade select  bd-example-modal-md holiday_modal" id="holiday_modal" tabindex="-1" aria-labelledby="title_holiday_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_holiday_modal">Add Holiday </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">

          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="holiday_form">
            @csrf

            <div>
              <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
            </div>

              <div class="mb-3">
                  <label for="title" class="form-label">Holiday Title <span class="text-danger"> * </span></label>
                  <input type="text" class="form-control " id="title"  name="title" placeholder="Enter Holiday Title">
                  <span class = 'alert-danger'>
                      <strong id="title_error"></strong>
                  </span>
              </div>
              <div class="mb-3">
                  <label for="date" class="  control-label">Holiday Date <span class="text-danger"> * </span></label>
                  <div class="input-group">
                    <input type="text" name="date" class="form-control datepicker" id="date" autocomplete="off">
                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                  </div>
                  <span class = 'alert-danger'>
                      <strong id="date_error"></strong>
                  </span>
              </div>
              <div class="mb-3">
                  <label for="holiday_type" class="  control-label">Holiday Type <span class="text-danger"> * </span></label>
                  <select class="form-select form-control holiday_type  " id="holiday_type" name="holiday_type" placeholder="Select Holiday Type">
                    <option selected disabled value="0" class="input-cstm">Please Select</option>
                    <option value="F">Full Day</option>
                    <option value="H">Half Day</option>
                  </select>
                  <span class = 'alert-danger'>
                      <strong id="holiday_type_error"></strong>
                  </span>
              </div>
              <div class="mb-3">
                  <label for="postal_code" class="  control-label">Status</label>
                  <div class="switchery-sm editholiday">
                        <input class="switchery" type="checkbox" id="" name="status" checked>
                    </div>
              </div>
            <button class="btn btn-primary submit_holiday" type="button"></button>
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
              <h6 class="card-title col">Holiday</h6>
              <div class="col-2 ">
                @if(in_array("66", permission()))
                    <a  class="btn btn-primary add_holiday" data-id="{{encryptid('0')}}" style="float: right" id="add_holiday">Add Holiday</a>
                @endif
              </div>
              <div class="col-2 " style="width: auto">
                <button class="btn btn-primary holiday-pdf" style="float: right" >Download PDF</button>
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="public-holiday_tbl" class="table" >
              <thead>
                <tr>
                    <th>No</th>
                    <th>Holiday Title</th>
                    <th>Holiday Date</th>
                    <th>Holiday Type</th>
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
    <script src="{{ asset('assets/plugins/forms/styling/switchery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}">
    </script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>

@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('assets/js/public-holiday/holiday.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
@endpush
