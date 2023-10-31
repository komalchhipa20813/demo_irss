@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('title', 'Sub Products')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sub Products</li>
        </ol>
    </nav>
    <!-- sub products modal -->
    <div class="modal fade select  bd-example-modal-md sub_modal" id="sub_product_modal" tabindex="-1"
        aria-labelledby="title_sub_product_modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_sub_product_modal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="POST" enctype="multipart/form-data" id="sub_product_form">
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
                            <select class=" form-select product" data-width="100%" name="sub_product">
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Sub Product Name</label>
                            <input type="text" class="form-control" id="sub_product_name" value="" name="sub_product_name" placeholder="Enter Sub Product Name">
                        </div>
                        <button class="btn btn-primary submit_sub_product" type="button"></button>
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
                        <h6 class="card-title col">Sub Products</h6>
                        <div class="col-2 ">
                            @if (in_array('34', permission()))
                                <a class="btn btn-primary add_sub_product" data-id="{{ encryptid('0') }}"
                                    style="float: right" id="add_sub_product">Add Sub Product</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="sub-product_tbl" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Sub Products Name</th>
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
    <script src="{{ asset('assets/js/products/sub-product.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/get_product.js') }}"></script>
@endpush
