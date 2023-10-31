@extends('layout.master')
@section('title',"FDO")
@section('content')
	<style type="text/css">
		.form-group {
    margin-bottom: 1.25rem;
}
	</style>
    <nav class="page-breadcrumb">
 		 <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('fdo.index') }}"> <i class="link-icon" data-feather="users"></i>
          <span class="link-title">FDO</span></a></li>
			<li class="breadcrumb-item active" aria-current="page">FDO Detail</li>
		</ol>
	</nav>

	<div class="accordion" id="accordionExample">
   	<div class="accordion-item">
    <h3 class="m-3" id="headingTwo">Profile Photos</h3>
	<div class="accordion-body">
        <div class="row">
            <div class="form-group col-6 d-flex">
                <label class="col-lg-6 ">FDO Image</label>
                <div class="col-lg-1">:</div>
                <div class="col-lg-5">
                    <img src="{{ url('storage/fdo/profile/'.$fdo->image) }}" height="100px" width="100px"/></div>
                </div>
            </div>
        </div>
  </div>
  <div class="accordion-item">
    <h3 class="m-3" id="headingOne">FDO Form</h3>
	<div class="accordion-body">
	<div class="row">
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">First Name</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ucfirst($fdo->first_name) }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Middle Name</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ucfirst($fdo->middle_name) }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Last Name </label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ucfirst($fdo->last_name) }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Email Id</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ $fdo->email }} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Branch</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ ($fdo->branch->name)}} </div>
	</div>
	<div class="form-group col-4 d-flex">
		<label class="col-lg-4">Phone</label>
		<div class="col-lg-2">:</div>
		<div class="col-lg-6">{{ $fdo->phone }} </div>
	</div>
	</div>
	</div>
  </div>
  <div class="accordion-item">
    <h3 class="m-3" id="headingTwo">FDO Detail</h3>
	<div class="accordion-body">
		<div class="row">
			<div class="form-group col-12 d-flex">
                <label class="col-lg-2">FDO Office Address</label>
                <div class="col-lg-1">:</div>
				<div class="col-lg-9">@if(!empty($fdo->office_address)){{$fdo->office_address}} @else - @endif  </div>
			</div>
            <div class="form-group col-12 d-flex">
                <label class="col-lg-2">FDO Resindencial Address</label>
                <div class="col-lg-1">:</div>
                <div class="col-lg-9">@if(!empty($fdo->residential_address)){{$fdo->residential_address}} @else - @endif  </div>
            </div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 float-left  ">Date of Birth</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6 dob">
				@if(!empty($fdo->dob)) {{ $fdo->dob }} @else - @endif </div>
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
				@if(!empty($fdo->joining_date)) {{ $fdo->joining_date }} @else - @endif</div>
			</div>
            <div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Business Category</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($fdo->business_category)) {{ $fdo->business_category->name }} @else - @endif  </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Gender</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($fdo->gender)) {{ $fdo->gender==0?'MALE':'FEMALE' }} @else - @endif</div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 float-left  ">Salary</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($fdo->salary)) {{ $fdo->salary }} @else 0 @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Salary Account Number</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($fdo->account_number)) {{ $fdo->account_number }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">IFSC code</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!empty($fdo->ifsc_code)) {{ $fdo->ifsc_code }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4 ">Holder Name</label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(!is_null($fdo->holder_name)) {{ $fdo->holder_name }} @else - @endif </div>
			</div>
			<div class="form-group col-6 d-flex">
				<label class="col-lg-4">Status </label>
				<div class="col-lg-2">:</div>
				<div class="col-lg-6">
				@if(($fdo->status == 1)) Active @else Inactive @endif </div>
			</div>
			</div>
		</div>
  	</div>
	<div class="row footer-btn mt-3">
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<a href="{{ route('fdo.index')}}" class="btn btn-secondary">Back</a>
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
$('#age').html(getAge('{{ $fdo->dob }}'));
</script>
@endpush
