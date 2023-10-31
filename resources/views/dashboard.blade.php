@extends('layout.master')
@section('title',"Dashboard")
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
  </div>
  {{-- <div class="d-flex align-items-center flex-wrap text-nowrap">
    <div class="input-group datepicker wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
      <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar" class=" text-primary"></i></span>
      <input type="text" class="form-control border-primary bg-transparent">
    </div>
    <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="printer"></i>
      Print
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="download-cloud"></i>
      Download Report
    </button>
  </div> --}}
</div>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Retinue Branches</h6>
                </div>
                <div class="row">
                    <div class="col-6 col-md-12 col-xl-5">
                        <h3 class="mb-2 mt-3">{{dashboard_calculation('irss_branches')}}</h3>
                    </div>
                    <div class="col-6 col-md-12 col-xl-7">
                        <div id="customersChart" class="mt-md-3 mt-xl-0"></div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Departments</h6>

                </div>
                <div class="row">
                <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2 mt-3">{{dashboard_calculation('departments')}}</h3>

                </div>
                <div class="col-6 col-md-12 col-xl-7">
                    <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Companies</h6>

                </div>
                <div class="row">
                <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2 mt-3">{{dashboard_calculation('companies')}}</h3>
                </div>
                <div class="col-6 col-md-12 col-xl-7">
                    <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Products</h6>
                </div>
                <div class="row">
                <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2 mt-3">{{dashboard_calculation('products')}}</h3>
                </div>
                <div class="col-6 col-md-12 col-xl-7">
                    <div id="JsonthChart" class="mt-md-3 mt-xl-0"></div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Total Health Policies</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <h3 class="mb-2 mt-3">{{dashboard_calculation('health_policies')}}</h3>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Total Motor Policies</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <h3 class="mb-2 mt-3">{{dashboard_calculation('motor_policies')}}</h3>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Total SME Policies</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <h3 class="mb-2 mt-3">{{dashboard_calculation('sme_policies')}}</h3>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div> <!-- row -->

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/datepicker.js') }}"></script>
@endpush
