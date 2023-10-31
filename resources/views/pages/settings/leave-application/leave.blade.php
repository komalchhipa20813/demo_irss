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
           <form class="forms-sample" method="POST" enctype="multipart/form-data" id="leave_form">
            @csrf
          <div class="row">
            
             <div class="col-md-3">
                <div class="mb-3">
                  <input type="hidden" name="id" class="id" id="id" value="{{ encryptid('0')}}">
                    <label for="leave_type" class=" control-label">Leave Type <span class="text-danger"> * </span></label>
                    <select class="leave_type form-select form-control " id="leave_type" name="leave_type" placeholder="Select Leave Type">
                        <option selected disabled value="0" class="input-cstm">Please Select</option>
                        <option value="Casual leave">Casual Leave</option>
                        <option value="Sick leave">Sick leave</option>
                        <option value="Religious holidays">Religious holidays</option>
                        <option value="Maternity leave">Maternity leave</option>
                        <option value="Paternity leave">Paternity leave</option>
                        <option value="Bereavement leave">Bereavement leave</option>
                        <option value="Compensatory leave">Compensatory leave</option>
                        <option value="Sabbatical leave">Sabbatical leave</option>
                        <option value="Marriage leave">Marriage leave</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="from_date" class=" control-label">From Date <span class="text-danger"> * </span></label>
                    <div class="input-group">
                    <input type="text" name="from_date" class="form-control datepicker" id="from_date" autocomplete="off">
                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                  </div>  
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="to_date" class=" control-label">To Date<span class="text-danger"> * </span></label>
                    <div class="input-group">
                    <input type="text" name="to_date" class="form-control datepicker" id="to_date" autocomplete="off">
                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                  </div>  
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="leave_type_day" class=" control-label">Leave Type Day<span class="text-danger"> * </span></label>
                    <select class="form-select form-control leave_type_day " id="leave_type_day" name="leave_type_day" placeholder="Select Leave Type">
                      <option selected disabled value="0" class="input-cstm">Please Select</option>
                      <option value="F">Full Day</option>
                      <option value="H">Half Day</option>   
                  </select>
                  <span class = 'alert-danger'>
                      <strong id="holiday_type_error"></strong>
                  </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="work_handover_user_id" class=" control-label">Work Handover To </label>
                    <select class="work_handover form-select form-control " id="work_handover_user_id" name="work_handover_user_id" placeholder="Select User">
                        @if(empty($users))
                          <option value="0" selected disabled>First Enter User</option>
                      @else
                          <option value="0" selected disabled>Select User</option>
                          @foreach ($users as $values)
                          <option value="{{$values['id']}}">{{ $values['prefix'] .'.'.$values['first_name'] .' '.$values['middle_name'] .' '.$values['last_name']}}</option>
                          @endforeach
                      @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="leave_reason" class=" control-label">Leave Reason <span class="text-danger"> * </span> </label>
                    <textarea name="leave_reason" id="leave_reason" placeholder="Enter Only 255 Character" class="form-control" rows="2" cols="2" spellcheck="false"></textarea>
                </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label for="" class=" control-label"></label>
                <div class="text-center">
                    <button type="button" id="submit_leave" class="btn btn-success me-2 submit_leave">Save</button>
                    <a class="btn btn-warning clear_btn">Clear</a>
                </div>
              </div>
            </div>
          
            
          </div>
          </form>
           <hr> 
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
                  <th>From-To Date</th>
                  <th>Type</th>
                  <th>Reason</th>
                 <!--  <th>Child Status</th>
                  <th>Parent Status</th> -->
                  <th>Action / Leave Status</th>
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
    <script src="{{ asset('assets/js/leave-application/leave.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/getState.js') }}"></script>
@endpush