@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('title', 'Model')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Model</li>
    </ol>
</nav>
<!-- Make Modal -->
<div class="modal fade select  bd-example-modal-md" id="model_modal" tabindex="-1" aria-labelledby="title_model_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_model_modal">Add Model </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <form class="forms-sample" method="POST" enctype="multipart/form-data" id="model_form">
            @csrf
            <input type="hidden" name="id" class="id" id="id" value="{{encryptid('0')}}">
            <div class="mb-3">
              <label class="form-label">Product</label>
              <select class=" form-select product" id="product" data-width="100%" name="product" >
              @if($productList->isEmpty())
                  <option selected disabled value="0">First Enter Product</option>
              @else
                  <option value="0" selected disabled>Select Product Name</option>
                  @foreach ($productList as $values)
                  <option value="{{ $values['id'] }}">{{ ucfirst($values['name']) }}</option>
                  @endforeach
              @endif
              </select>
            </div>
             <div class="mb-3">
              <label class="form-label">Product Make</label>
              <select class=" form-select make_id" id="make_id" data-width="100%" name="make_id" >
                 <option value="0" selected disabled>First select Product</option>
                  
              </select>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control name" id="name" name="name"
                  placeholder="Please Enter Model Name">
            </div>           
            <button class="btn btn-primary submit_model" type="button"></button>
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
              <h6 class="card-title col">Models</h6>
              <div class="col-2 ">
                @if(in_array("86", permission()))
                    <a  class="btn btn-primary add_model" data-id="{{ encryptid('0') }}" style="float: right" id="add_model">Add Model</a>
                @endif
              </div>
            </div>
          <div class="table-responsive mt-2">
            <table id="product-model_tbl" class="table" style="width: 98% !important;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Product</th>
                  <th>Make Name</th>
                  <th>Model Name</th>
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
    <script src="{{ asset('assets/js/product-model/model.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/getmake.js') }}"></script>
    
@endpush