@extends('layout.master')
@section('title',"Agent")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agent.index') }}">Agent</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ (!is_null($data['agent'])) ? 'Edit' :'Add'}}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">{{ (!is_null($data['agent'])) ? 'Edit agent' :'Add agent'}}</h6>
          </div>
          @php
            if (isset($data['agent']))
            $agent = $data['agent'];
          @endphp
          <form class="forms-sample agent-form" method="POST" name="registration" id="agent_form">
            @csrf
            <input type="hidden" name="agent_id" class="agent_id" id="agent_id" value="{{ (!is_null($data['agent'])) ? encryptid($data['agent']->id) : encryptid('0')}}">
            <input type="hidden" name="agent_edit" class="agent_edit" id="agent_edit" value="{{ (!is_null($data['agent'])) ? 1 : 2}}">
            <div class="row">
              <div class="col-md-12">
                  <div class="profile_div">
                    <label class="  control-label">Profile Photos</label>
                    <div>
                      <div class="uploader" id="uniform-filename_agent" >
                        <input type="file" accept="image/*" value="" name="image" id="filename_agent" class="file-styled-primary" >
                      </div>
                    </div>
                    <img id="agent_image_preview" class="empimage"  width="120px">
                  </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                    <label for="fdo" class="  control-label">FDO  <span class="text-danger"> * </span></label>
                    <select class="form-select form-control select2" id="fdo" name="fdo" placeholder="Select fdo">
                      @if(!$data['fdos']->isEmpty())
                      <option selected disabled class="input-cstm">Please Select</option>
                      @foreach ($data['fdos'] as $fdo)
                      <option @isset($agent) @if ($agent->fdo_id == $fdo->id) selected @endif @endisset
                      value="{{ $fdo->id}}">{{ ucfirst($fdo->code).' - '.ucfirst($fdo->prefix).' '.ucfirst($fdo->first_name).' '.ucfirst($fdo->last_name) }}</option>
                      @endforeach
                    @else
                      <option selected disabled class="input-cstm">Please First Enter FDO</option>
                    @endif
                    </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <input type="hidden" name="exit_branch" value="{{ isset($agent)? $agent->home_irss_branch_id   :'' }}" class="exit_branch">
                    <label for="branch" class="  control-label">Home Branch  <span class="text-danger"> * </span></label>
                    <select class="form-select form-control " id="branch" name="branch" placeholder="Select Branch">
                    <option selected disabled value='0'>Please Select FDO</option>
                    </select>
                </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                      <label for="email" class="  control-label">Email</label>
                      <input type="email" class="form-control" name="email" value="{{ isset($agent)? $agent->email :'' }}" id="email" placeholder="Enter Branch Email">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                      <label for="secondary_email" class="  control-label">Secondary Email </label>
                      <input type="secondary_email" class="form-control" name="secondary_email" value="{{ isset($agent)? $agent->secondary_email :'' }}" id="secondary_email" placeholder="Enter Branch Secondary Email">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                      @php
                          $prefixes=['Mr.','Mrs','Miss','Dr','Er']
                      @endphp
                      <label for="prefix" class="control-label">Prefix  <span class="text-danger"> * </span></label>
                      <select class="form-select form-control " id="prefix" name="prefix" placeholder="Select prefix">
                          <option selected disabled class="input-cstm">Please Select</option>
                          @foreach ($prefixes as $prefix)
                          <option @isset($agent->prefix) @if($agent->prefix==$prefix) selected @endif @endisset value="{{ $prefix }}">{{ $prefix }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                      <label for="first_name" class="  control-label">First Name <span class="text-danger"> * </span></label>
                      <input type="text" class="form-control" name="first_name" id="first_name" value="{{ isset($agent)? $agent->first_name :'' }}" autocomplete="off" placeholder="Enter First Name">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                      <label for="middle_name" class="  control-label">Middle Name <span class="text-danger"> * </span></label>
                      <input type="text" class="form-control" name="middle_name" id="middle_name" value="{{ isset($agent->middle_name)? $agent->middle_name :'' }}" autocomplete="off" placeholder="Enter middle Name">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                      <label for="last_name" class="  control-label">Last Name <span class="text-danger"> * </span></label>
                  <input type="text" class="form-control" name="last_name" id="last_name" value="{{ isset($agent)? $agent->last_name :'' }}" autocomplete="off" placeholder="Enter Last Name">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="phone" class="  control-label">Contact No <span class="text-danger"> * </span></label>
                    <input type="text" class="form-control" name="phone" value="{{ isset($agent)? $agent->phone :'' }}" id="phone" placeholder="Enter Contact Number">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="secondary_phone" class="  control-label">Secondary Contact No </label>
                    <input type="text" class="form-control" name="secondary_phone" value="{{ isset($agent)? $agent->secondary_phone :'' }}" id="secondary_phone" placeholder="Enter Contact Number">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="mb-3">
                      <label for="office_address" class="  control-label">Office Address  <span class="text-danger"> * </span></label>
                      <textarea name="office_address" id="office_address" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5" spellcheck="false">{{ isset($agent->office_address)? $agent->office_address :'' }}</textarea>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="mb-3">
                      <label for="residential_address" class="  control-label">Residential Address  <span class="text-danger"> * </span></label>
                      <textarea name="residential_address" id="residential_address" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5" spellcheck="false">{{ isset($agent->residential_address)? $agent->residential_address :'' }}</textarea>
                  </div>
              </div>
              <div class="col-md-3">
                  @php
                      $genders = [['key' => 0 , 'value' => 'MALE'],['key' => 1 , 'value' => 'FEMALE']]
                  @endphp
                  <div class="mb-3">
                      <label for="gender" class="  control-label">Gender  <span class="text-danger"> * </span></label>
                      <select class="form-select form-control " id="gender" name="gender" placeholder="Select Gender">
                          <option selected disabled class="input-cstm">Please Select</option>
                          @foreach ($genders as $gender)
                              <option @isset($agent->gender) @if($agent->gender==$gender['key']) selected @endif @endisset value="{{ $gender['key'] }}">{{ $gender['value'] }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="dob" class="  control-label">Date of Birth <span class="text-danger"> * </span></label>
                    <div class="input-group ">
                      <input type="text" name="dob" value="{{ isset($agent->dob)? $agent->dob :'' }}" class="form-control dobdatePicker" autocomplete="off" id="dobdatePicker">
                      <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="jdatePicker" class="control-label">Joining Date <span class="text-danger"> * </span></label>
                    <div class="input-group ">
                      <input type="text" name="joining_date" value="{{ isset($agent->joining_date)? $agent->joining_date :'' }}" class="form-control datepicker joindatePicker" autocomplete="off" id="">
                      <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="adatePicker" class="control-label">Anniversary Date </label>
                    <div class="input-group ">
                      <input type="text" name="anniversary_date" value="{{ isset($agent->anniversary_date)? $agent->anniversary_date :'' }}" class="form-control datepicker anniversary_date" autocomplete="off" id="adatePicker">
                      <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="salary" class="  control-label">Salary</label>
                    <input type="number" class="form-control" name="salary" id="salary" value="{{ isset($agent->salary)? $agent->salary :'' }}" autocomplete="off" placeholder="Enter Salary">
                  </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="bank_name" class="  control-label">Bank Name</label>
                  <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ isset($agent->bank_name)? $agent->bank_name :'' }}" autocomplete="off" placeholder="Enter Bank Name">
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="bank_branch_name" class="  control-label">Bank Branch Name</label>
                  <input type="text" class="form-control" name="bank_branch_name" id="bank_branch_name" value="{{ isset($agent->bank_branch_name)? $agent->bank_branch_name :'' }}" autocomplete="off" placeholder="Enter Bank Branch Name">
                </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="salary_account" class="  control-label">Salary Account Number</label>
                    <input type="text" class="form-control" name="salary_account" id="salary_account" value="{{ isset($agent->account_number)? $agent->account_number :'' }}" autocomplete="off" placeholder="Enter Salary Account">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="ifsc_code" class="  control-label">IFSC code</label>
                    <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="{{ isset($agent->ifsc_code)? $agent->ifsc_code :'' }}" autocomplete="off" placeholder="Enter IFSC code">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-3">
                    <label for="account_holder" class="  control-label">Account Holder Name</label>
                    <input type="text" class="form-control" name="account_holder" id="account_holder" value="{{ isset($agent->holder_name)? $agent->holder_name :'' }}" autocomplete="off" placeholder="Enter Account Holder">
                  </div>
              </div>
              <div class="col-md-3 mb-3">
                <input type="hidden" name="exit_sales_manager" value="{{ isset($agent->sales_manager_id )? $agent->sales_manager_id :'' }}" class="exit_sales_manager">
                <label for="sales_manager" class="control-label">Sales Manager <span class="text-danger"> * </span></label>
                <select class="form-select form-control  sales_manager" id="sales_manager" name="sales_manager" placeholder="Select sales manager">
                  <option selected disabled value='0'>Please Select Retinue Branch</option>
                </select>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="aadhaarcard_number" class="control-label">Aadhaar Card No <span class="text-danger"> * </span></label>
                  <input type="text" class="form-control adharcard_number" name="adharcard_number" data-inputmask-alias="9999 9999 9999" value="{{isset($agent->adharcard_number)? $agent->adharcard_number :''}}" id="adhaarcard_number" autocomplete="off" placeholder="Enter Aadhaar Card Number" inputmode="text">
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="pancard_number" class="  control-label">Pan Card No <span class="text-danger"> * </span></label>
                  <input type="text" class="form-control" name="pancard_number" id="pancard_number" data-inputmask-alias="aaaaa9999a" value="{{ isset($agent->pancard_number)? $agent->pancard_number :'' }}" autocomplete="off" placeholder="Enter Pan Card Number">
                </div>
              </div>
            </form>
            <form class="forms-sample" method="POST" name="registration" id="document_form">
              <div class="row">
                <div class="col-md-12 document_div">
                  <div class="mb-3">
                    <label for="document_type" class="  control-label">Document Type</label>
                    <select class="form-select form-control  document_type" id="document_type" name="document_type" placeholder="Select document_type">
                      @if(!$data['document_types']->isEmpty())
                      <option selected disabled class="input-cstm" value="0">Please Select</option>
                      @foreach ($data['document_types'] as $document_type)
                      <option @isset($agent) @if (in_array($document_type->id, $data['agent_documents'])) disabled @endif @endisset
                      value="{{ $document_type->id}}" data-name="{{ ucfirst($document_type->name) }}">{{ ucfirst($document_type->name)}}</option>
                      @endforeach
                      @else
                      <option selected disabled class="input-cstm">Please First Enter Document Type</option>
                      @endif
                    </select>
                  </div>
                </div>
                <div class="document_detail row" style="display: none">
                  <div class="col-md-3">
                    <div class="mb-3">
                        <label class="control-label" for="document_file">File Upload</label>
                        <input class="form-control" name="document_file" type="file" id="document_file">
                    </div>
                  </div>
                  <div class="col-md-3">
                      <div class="mb-3">
                          <label for="document_number" class="  control-label">Document Number</label>
                          <input type="number" class="form-control" name="document_number" id="document_number" value="" autocomplete="off" placeholder="Enter Document Number">
                      </div>
                  </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary add_document" data-id="" type="button">Add</button>
                </div>
                </div>
              </div>
            </form>

            <div class="row document_tbl" >
              <table id="document_tbl" class="table" >
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Document Type</th>
                    <th>Document File</th>
                    <th>Document Number</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>

            <div class="">
              <button class="btn btn-primary submit_agent" type="button">{{ (!is_null($data['agent'])) ? 'Update' :'Save'}}</button>
              <a href="{{ route('agent.index') }}">
                <button class="btn btn-primary"  type="button">Back</button>
              </a>
            </div>
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
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/agent/agent.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
@endpush
