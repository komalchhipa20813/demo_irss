@extends('layout.master')
@section('title',"Roles")

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
    <li class="breadcrumb-item active" aria-current="page">Show</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Role detail</h6>
        <div class="row mb-5 mt-5">
            <h5 class="">
                Title: {{ ucwords($data['title']) }}
            </h5>   
        </div>
        <div class="">
            Permissions
        </div>
        <div class="row">
               @php
                $p_name=$data['p_id'];
               @endphp
               @foreach ( $p_name as $p_name )
                   <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="btn btn-primary m-2">{{ $p_name }}</div>
                    </div>
               @endforeach  
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection