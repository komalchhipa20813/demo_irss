@extends('layout.master')
@section('title', 'Product')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h6 class="card-title col">products</h6>
                        <div class="col-2 ">
                            @if (in_array('30', permission()))
                                <a href="{{ route('product.create') }}" class="btn btn-primary add_product"
                                    data-id="{{ encryptid('0') }}" style="float: right" id="add_product">Add Product</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="product_tbl" class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Policy Type</th>
                                    <th>Name</th>
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
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/products/products.js') }}"></script>
@endpush
