@extends('layout.master')
@section('title', 'Product')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Show</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Product detail</h6>
                    <div class="row mb-5 mt-5">
                        <h5 class="">Name: {{ ucwords($data['name']) }}</h5>
                    </div>
                    <div class="">Products</div>
                    <div class="row">
                        @php
                            $p_name = $data['p_id'];
                        @endphp
                        @foreach ($p_name as $p_name)
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <div class="btn btn-primary m-2">{{ $p_name }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
