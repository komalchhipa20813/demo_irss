@extends('layout.master')
@section('title',"Roles")

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ (!is_null($data['role'])) ? 'Update' :'Add'}}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <h6 class="card-title col">{{ (!is_null($data['role'])) ? 'Update Role' :'Add Role'}}</h6>
          </div>
          <form class="forms-sample" method="POST" name="registration" id="role_form">
            @csrf
            <input type="hidden" name="role_id" class="role_id" id="role_id" value="{{ (!is_null($data['role'])) ? encryptid($data['role']->id) : encryptid('0')}}">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control title" id="title" name="title"  value="{{ (!is_null($data['role'])) ? $data['role']->title :''}}">
            </div>
            <div class="mb-3 check">
                <div class="checkbox-custom-cstm">
                    @foreach ($data['permissions'] as $permission)
                      @php 
                      $checked='';
                      if(!is_null($data['role'])){
                        if(in_array($permission->id, $data['p_id']['p_id'])){
                          $checked="checked";
                        }
                      }
                      @endphp
                      <div class="form-check form-check-inline col-md-3">
                        <input type="checkbox" value="{{ $permission['id'] }}" name="permission[]" class="form-check-input permission" id="" {{ $checked }} >
                        <label class="form-check-label" for="">
                          {{ $permission['name'] }}
                        </label>
                      </div>
                    @endforeach
                    <div class="form-check form-check-inline col-md-3">
                      <input type="checkbox" value="" name="" class="form-check-input " id="selectall" >
                      <label class="form-check-label" for="selectall">
                        Select All
                      </label>
                    </div>
                </div>
            </div>
            <div class="">
              <button class="btn btn-primary submit_role" type="button">{{ (!is_null($data['role'])) ? 'Update' :'Save'}}</button>
              <a href="{{ route('role.index') }}">
                <button class="btn btn-primary"  type="button">Back</button>
              </a>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/js/jquery.validate.min.js')}}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/role-permission/role.js') }}"></script>
@endpush