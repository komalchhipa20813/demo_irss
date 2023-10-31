@php
if (!is_null(Auth::guard('agent')->user())||!is_null(Auth::guard('fdo')->user())){
    $master='pages.fdo-agent-panel.layout.master';
}
else{
    $master='layout.master';
}   
@endphp
@extends($master)
@section('title',"Change Password")
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="auth-form-wrapper px-4 py-5">
          <form class="forms-sample change_password_from">
            @csrf 
            <div class="mb-3">
              <label for="" class="form-label">Old password</label>
              <input type="password" class="form-control" name='oldpassword' id="" placeholder="Old Password">
              <div id="old_password_error" class="error"></div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" name='password' id="password"  placeholder="Password">
              <div id="new_password_error" class="error"></div>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name='confirmpassword' id="" placeholder="Confirm Password">
            </div>
            <div>
              <button type="button" class="btn btn-primary me-2 mb-2 mb-md-0 submit_change_password">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
@push('custom-scripts')
<script type="text/javascript">
    var aurl = {!! json_encode(url('/')) !!}
</script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/change-password/custom.js')}}"></script>
@endpush
