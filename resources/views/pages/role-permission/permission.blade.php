@extends('layout.master')
@section('title', 'Permission')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Permission</li>
        </ol>
    </nav>
    <!-- add_permission_modal -->
    <div class="modal fade  bd-example-modal-md" id="permission_modal" tabindex="-1"
        aria-labelledby="title_permission_modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_permission_modal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" method="POST" name="registration" id="permission_form">
                        @csrf
                        <input type="hidden" name="permission_id" class="permission_id" value="{{ encryptid('0') }}">
                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name</label>
                            <input type="text" class="form-control name" id="name" name="name" placeholder="Enter Permission Name">
                        </div>
                        <button class="btn btn-primary submit_permission" type="button"></button>
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
                        <h6 class="card-title col">permissions</h6>
                        <div class="col-2 ">
                            @if (in_array('42', permission()))
                                <a class="btn btn-primary add_permission" data-id="{{ encryptid('0') }}"
                                    style="float: right" id="add_permission">Add permission</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="permission_tbl" class="table">
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
    <script src="{{ asset('assets/js/role-permission/permission.js') }}"></script>
@endpush
