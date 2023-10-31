@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('title', 'Leave Application')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Leavre Application</li>
    </ol>
</nav>
<style type="text/css">
  table{
    width: 98% !important;
  }

</style>


<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
           <div class="row">
             <div class="col-md-3">
                <div class="mb-3">
                    <label for="status" class=" control-label">Leave Status <span class="text-danger"> * </span></label>
                    <select class="form-select form-control " id="status" name="status" placeholder="Select Leave Status">
                        <option selected disabled class="input-cstm">Please Select</option>
                        <option value="0" >All</option>
                        <option value="1" >Pending</option>
                        <option value="2" >Approved</option>
                        <option value="3" >Not Approved</option>
                        <option value="4" >Rollback</option>
                    </select>
                </div>
            </div>
           </div> 
          <div class="table-responsive mt-2">
            <table id="leave-application_tbl" class="table" style="">
              <thead>
                <tr>
                  <th>Customer Name</th>
                  <th>From-To Date</th>
                  <th>Type</th>
                  <th>Reason</th>
                  <th>Action / Status</th>
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
     <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}">
    </script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
     <script type="text/javascript">
        let _token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('assets/js/leave-application/leave_list.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/getState.js') }}"></script>
@endpush