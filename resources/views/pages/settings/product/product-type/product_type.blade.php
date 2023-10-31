@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('title', 'Products Type')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products Type</li>
        </ol>
    </nav>
    <!-- sub products modal -->
    <div class="modal fade select  bd-example-modal-md sub_product_modal" id="product_type_modal" tabindex="-1"
        aria-labelledby="title_product_type_modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_product_type_modal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="POST" enctype="multipart/form-data" id="product_type_form">
                        @csrf
                        <input type="hidden" name="id" class="id" id="id" value="{{ encryptid('0') }}">
                        <div class="row">
                            @php
                                $policies = ['Motor Policy','Health Policy','SME Policy']
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">Type of policies</label>
                                <select class=" form-select  list_policies policy_type" data-width="100%" name="policy_type">
                                    <option value="0" selected disabled>Please Select policy</option>
                                    @foreach ($policies as $key=>$policy)
                                        <option value="{{ $key+1 }}" @isset($data['products'])@if($data['products']->policy_type==$key+1) selected @endif @endisset  >{{ $policy }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Products</label>
                            <select class=" form-select product" data-width="100%" name="product">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Product Type</label>
                            <input type="text" class="form-control" id="type" value="" name="type" placeholder="Enter Product Type">
                        </div>
                        <button class="btn btn-primary submit_product_type" type="button"></button>
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
                        <h6 class="card-title col">Products Type</h6>
                        <div class="col-2 ">
                            @if (in_array('86', permission()))
                                <a class="btn btn-primary add_product_type" data-id="{{ encryptid('0') }}"
                                    style="float: right" id="add_product_type">Add Product Type</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="product-type_tbl" class="table" style="width: 98% !important;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Product Type</th>
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
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/products/product-type.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/get_type_product.js') }}"></script>
@endpush
