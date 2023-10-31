@extends('pages.fdo-agent-panel.layout.master')
@section('title',"Dashboard")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card scrollbar">
      <div class="card">
        <div class="card-body ">
            @foreach (Auth::guard('agent')->user()->notifications as $notification)
                @php
                    $class=!is_null($notification->read_at)?'text-muted':'text-primary'
                @endphp
                <a href="" class="dropdown-item d-flex align-items-center py-2">
                <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-feather="{{ $notification->data['icon'] }}"></i>
                </div>
                <div class="flex-grow-1 me-2">
                    <p>{{ $notification->data['message'] }}</p>
                    <p class="tx-12  {{ $class }}">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                </a>
            @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection