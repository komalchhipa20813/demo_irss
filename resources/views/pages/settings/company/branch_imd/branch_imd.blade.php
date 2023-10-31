@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('title', 'Branch Imd')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Branch Imd</li>
    </ol>
</nav>
<!-- Branch Imd Modal -->
<div class="modal fade select  bd-example-modal-md" id="branch_imd_modal" tabindex="-1" aria-labelledby="title_branch_imd_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_branch_imd_modal">Add Branch Imd </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="branch_imd_form">
            @csrf
            <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
            <div class="mb-3">
              <label class="form-label">Company Name</label>
              <select class=" form-select company_name" data-width="100%" name="company_name" >
              @if($company->isEmpty())
                  <option selected disabled value='0'>First Enter Company Branch</option>
              @else
                  <option value="0" selected disabled>Select Company Name</option>
                  @foreach ($company as $values)
                  <option value="{{ $values['id'] }}">{{ $values['name'] }}</option>
                  @endforeach
              @endif
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Company branch Name</label>
              <select class=" form-select company_branch_name" data-width="100%" name="company_branch_name" >
              </select>
            </div>
            <div class="mb-3">
              <label>Branch Imd Name</label>
              <input type="text" class="form-control " id="branch_imd_name" value="" name="branch_imd_name" placeholder="Enter Company Branch Imd Name">
            </div>
            <button class="btn btn-primary submit_branch_imd" type="button"></button>
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
              <h6 class="card-title col">Branch Imds</h6>
              <div class="col-2 ">
                @if(in_array("26", permission()))
                    <a  class="btn btn-primary add_branch_imd" data-id="{{ encryptid('0') }}" style="float: right" id="add_branch_imd">Add Branch Imd</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="branch-imd_tbl" class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Company Name</th>
                  <th>Company Branch Name</th>
                  <th>Branch Imd Name</th>
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
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/branch-imd/branch_imd.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
@endpush