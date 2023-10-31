@extends('layout.master')
@section('title',"Employee")
@section('content')
	<style type="text/css">
		.form-group {
    margin-bottom: 1.25rem;
}
	</style>
    <nav class="page-breadcrumb">
 		 <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('employee.index') }}"> <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Employee</span></a></li>
			<li class="breadcrumb-item active" aria-current="page">Employee Detail</li>
		</ol>
	</nav>

	<div class="accordion" id="accordionExample">
   	<div class="accordion-item">
    <h3 class="m-3" id="headingTwo">Profile Photos</h3>
	<div class="accordion-body">
        <div class="row">
            <div class="form-group col-6 d-flex">
                <label class="col-lg-6 ">Employee Image</label>
                <div class="col-lg-1">:</div>
                <div class="col-lg-5">
                    <img src="{{ asset('store/employee/profile/'.$employee->image) }}"/></div>
                </div>
            </div>
        </div>
  </div>
  <div class="accordion-item">
    <h3 class="m-3" id="headingOne">Employee Form</h3>
	<div class="accordion-body">
	<div class="row">
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">First Name</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ucfirst($employee->first_name) }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Middle Name</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ucfirst($employee->middle_name) }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Last Name </label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ucfirst($employee->last_name) }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Email Id</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ $employee->email }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Branch</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ($employee->branch->name)}} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Phone</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ $employee->phone }} </div>
	</div>
	</div>
	</div>
  </div>
  <div class="accordion-item">
    <h3 class="m-3" id="headingTwo">Employee Detail</h3>
	<div class="accordion-body">
		<div class="row">
			<div class="form-group col-12 d-flex">
					<label class="col-lg-2">Employee Address</label>
					<div class="col-lg-1">:</div>
				<div class="col-lg-9">@if(!empty($employee->address)){{$employee->address}} @else - @endif  </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 float-left  ">Date of Birth</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6 dob">
				@if(!empty($employee->dob)) {{ $employee->dob }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Age</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6" id="age"></div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Joining Date</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($employee->joining_date)) {{ $employee->joining_date }} @else - @endif</div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Gender</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($employee->gender)) {{ $employee->gender==0?'MALE':'FEMALE' }} @else - @endif</div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Department</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($employee->department)) {{ ($employee->department->name) }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Designation</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($employee->designation)) {{ $employee->designation->name }} @else - @endif</div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 float-left  ">Salary</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($employee->salary)) {{ $employee->salary }} @else 0 @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Salary Account Number</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($employee->account_number)) {{ $employee->account_number }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">IFSC code</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($employee->ifsc_code)) {{ $employee->ifsc_code }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Holder Name</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($employee->holder_name)) {{ $employee->holder_name }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Employee Role</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($employee->role)) {{ $employee->role->title }} @else - @endif  </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Status </label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(($employee->status == 1)) Active @else Inactive @endif </div>
			</div>
			</div>
		</div>
  	</div>
	<div class="row footer-btn mt-3">
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<a href="{{ route('employee.index')}}" class="btn btn-secondary">Back</a>
		</div>
	</div>
  </form>
</div>
@endsection
@push('custom-scripts')
<script>
	function getAge(date){
  	var selected = date;
    var date1 = new Date();
    var date2 = new Date(selected);
    var diff = Math.floor(date1.getTime() - date2.getTime());
    var day = 1000 * 60 * 60 * 24;
    var days = Math.floor(diff/day);
    var months = Math.floor(days/30);
    var years = Math.floor(months/12);
    return years;
}
$('#age').html(getAge('{{ $employee->dob }}'));
</script>
@endpush
