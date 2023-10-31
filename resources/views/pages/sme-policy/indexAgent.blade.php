@php
    if (isset(Auth::user()->id)) {
        $permissionList = permission();
    }
    if (!is_null(Auth::guard('agent')->user()) || !is_null(Auth::guard('fdo')->user())) {
        $master = 'pages.fdo-agent-panel.layout.master';
    } else {
        $master = 'layout.master';
    }
@endphp
@extends($master)
@section('title', 'Sme Policy')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @include('layout.policy_cancel_modal')
    <nav class="page-breadcrumb b-none">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sme Policy</li>
        </ol>
    </nav>
    <div class="row">
        <div class="loader-background" id="loader_bg" style="display:none">
            <div class="spinner-border" id="loader" role="status"></div>
        </div>
        <div class="col-md-12 grid-margin stretch-card motorPolicy">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h6 class="card-title col heading-big-one">Sme policies</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="sme-policy_tbl" class="table" style="width: 98% !important;">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Policy Number</th>
                                    <th>Product</th>
                                    <th>Company Name</th>
                                    <th>Inward Number</th>
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
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/policies/FdoAgent-policy.js') }}"></script>
    <script src="{{ asset('assets/js/policies/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-product-data.js') }}"></script>
    <script src="{{ asset('assets/js/common/get-company-data.js') }}"></script>
@endpush
