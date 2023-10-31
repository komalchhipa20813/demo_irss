-@extends('layout.master')
@section('title',"PDF Read")

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">PDF</a></li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">Upload 
          <form class="forms-sample" action="{{route('pdf-read.store')}}" method="POST" name="registration" id="pdf_form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="profile_div">
                      <label class="  control-label">Upload PDF</label>
                      <div>
                        <div class="uploader" id="uniform-filename_employee" >
                          <input type="file" accept="application/pdf" value="" name="pdf_file" id="pdf_file" class="file-styled-primary" >
                        </div>
                      </div>
                    </div>
                </div>
               
                <div class="">
                  <button class="btn btn-primary submit_employee" type="submit">Upload</button>
                  
                </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection



