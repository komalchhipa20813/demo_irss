@extends('layout.master')
@section('title', 'Document-type')
@section('content')
    @push('plugin-styles')
        <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    @endpush

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Document Type</li>
        </ol>
    </nav>

    {{-- Listing Document-Type Data --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h6 class="card-title col">Document Types</h6>
                        <div class="col-2 ">
                            @if (in_array('14', permission()))
                                <a class="btn btn-primary document_type_modal_btn" data-id="{{ encryptid('0') }}"
                                    style="float: right" id="document_type_modal_btn">Add Document Type</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="document-type_tbl" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
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

    {{-- document-Type Modal --}}
    <div class="modal fade document_type_modal" id="document_type_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="document_type_modal_title">Add Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" id="document_type_form" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="hidden" name="document_type_id" class="document_type_id" value="{{ encryptid('0') }}">
                            <label for="exampleInputUsername1" class="form-label">Document Type</label>
                            <input type="text" class="form-control name" id="name" name="name" autocomplete="off" placeholder="Enter Document Type Name">
                        </div>
                        <button type="button" class="btn btn-primary add_document_type_btn" id="add_document_type_btn">Save Changes</button>
                    </form>
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
    <script src="{{ asset('assets/js/document-type/document_type.js') }}"></script>
@endpush
