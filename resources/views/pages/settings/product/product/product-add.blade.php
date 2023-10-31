@extends('layout.master')
@section('title', 'Product')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ !is_null($data['products']) ? 'edit' : 'add' }}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h6 class="card-title col">{{ !is_null($data['products']) ? 'Edit Products' : 'Add Product' }}</h6>
                    </div>
                    <form class="forms-sample" method="POST" name="registration" id="product_form">
                        @csrf
                        <input type="hidden" name="product_id" class="product_id" id="product_id"
                            value="{{ !is_null($data['products']) ? encryptid($data['products']->id) : encryptid('0') }}">
                            <div class="row">
                                @php
                                    $policies = ['Motor Policy','Health Policy','SME Policy']
                                @endphp
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Type of policies</label>
                                    <select class=" form-select  list_policies policy_type" data-width="100%" name="policy_type">
                                        <option value="0" selected disabled>Please Select policy</option>
                                        @foreach ($policies as $key=>$policy)
                                            <option value="{{ $key+1 }}" @isset($data['products'])@if($data['products']->policy_type==$key+1) selected @endif @endisset  >{{ $policy }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control name" id="name" name="name"
                                        placeholder="Please Enter Product Name"
                                        value="{{ !is_null($data['products']) ? $data['products']->name : '' }}">
                                </div>
                            </div>
                        <div class="mb-1 check">
                            <div class="checkbox-custom-cstm">
                                @foreach ($data['companies'] as $companies)
                                    @php
                                        $checked='';
                                        if (!is_null($data['products'])) {
                                            if (in_array($companies->id, $data['p_id']['p_id'])) {
                                                $checked="checked";
                                            }
                                        }
                                    @endphp
                                    <div class="form-check form-check-inline col-md-3">
                                        <input type="checkbox" value="{{ $companies['id'] }}" name="companies[]"
                                            class="form-check-input companies" id="" {{ $checked }}>
                                        <label class="form-check-label" for="">
                                            {{ $companies['name'] }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="form-check form-check-inline col-md-3">
                                    <input type="checkbox" value="" name="" class="form-check-input"
                                        id="selectall">
                                    <label class="form-check-label" for="selectall">
                                        Select All
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <button class="btn btn-primary submit_product"
                                type="button">{{ !is_null($data['products']) ? 'Update' : 'Save' }}</button>
                            <a href="{{ route('product.index') }}">
                                <button class="btn btn-primary" type="button">Back</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush
@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/products/products.js') }}"></script>
@endpush
