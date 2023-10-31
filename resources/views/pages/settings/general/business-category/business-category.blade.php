@extends('layout.master')
@section('title', 'Business Category')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Business Category</li>
        </ol>
    </nav>
    <!-- business category modal -->
    <div class="modal fade  bd-example-modal-md" id="business_category_modal" tabindex="-1"
        aria-labelledby="title_business_category_modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_business_category"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="POST" name="registration" id="business_category_form">
                        @csrf
                        <input type="hidden" name="business_category_id" class="business_category_id" value="{{ encryptid('0') }}">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control name" id="name" name="name"
                                placeholder="Please Enter Business Category Name">
                        </div>
                        <button class="btn btn-primary submit_business_category" type="button"></button>
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
                        <h6 class="card-title col">business categories</h6>
                        <div class="col-2">
                            @if (in_array('50', permission()))
                                <a class="btn btn-primary add_business_category"
                                    data-id="{{ encryptid('0') }}"id="add_business_category">Add Business Category</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="business-category_tbl" class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
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
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/business-category/business-category.js') }}"></script>
@endpush
