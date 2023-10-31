@php
  $guard_name=isset(Auth::guard('fdo')->user()->id)?'fdo':'agent';
  $profile= (!is_null(Auth::guard($guard_name)->user()->image)) ? asset('store/'.$guard_name.'/profile/'.Auth::guard($guard_name)->user()->image) : asset('images/users/profile/admin_logo.png');
  $notifications=Auth::guard($guard_name)->user()->notifications;
  $j=count($notifications)<=5?count($notifications):5;
@endphp
<nav class="navbar">
  <a href="#" class="sidebar-toggler">
    <i data-feather="menu"></i>
  </a>
  <div class="navbar-content">
    <div class="mobile-logo"><img src="{{ asset('assets/images/miniLogo.png') }}" alt=""></div>
    <ul class="navbar-nav">
      @if (count($notifications))
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="bell"></i>
          <div class="indicator">
            <div class="circle"></div>
          </div>
        </a>
        <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
          <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
            <p>{{ count(Auth::guard('agent')->user()->unreadNotifications) }} New Notifications</p>
            {{-- <a href="javascript:;" class="text-muted">Clear all</a> --}}
          </div>
          <div class="p-1 notification-div">
            @for ($i=0;$i<$j;$i++)
            @php
                $class=!is_null($notifications[$i]->read_at)?'text-muted':'text-primary'
            @endphp
            <a href="" class="dropdown-item d-flex align-items-center py-2">
              <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                <i class="icon-sm text-white" data-feather="{{ $notifications[$i]->data['icon'] }}"></i>
              </div>
              <div class="flex-grow-1 me-2">
                <p>{{ $notifications[$i]->data['message'] }}</p>
                <p class="tx-12  {{ $class }}">{{ $notifications[$i]->created_at->diffForHumans() }}</p>
              </div>
            </a>
            @endfor
          </div>
          <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
            <a href="{{ route('view.notification') }}">View all</a>
          </div>
        </div>
      </li>
      @endif
      <li class="nav-item dropdown">
        @if(isset($profile))
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img class="wd-30 ht-30 rounded-circle" src="{{ $profile }}" alt="profile">
        </a>
        @endif
        <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
          <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
            <div class="mb-3">
              <img class="wd-80 ht-80 rounded-circle" src="{{ $profile }}" alt="">
            </div>
            <div class="text-center">
              <p class="tx-14 ">{{ Auth::guard($guard_name)->user()->code }}</p>
              <p class="tx-16 fw-bolder">{{ Auth::guard($guard_name)->user()->first_name . ' ' . Auth::guard($guard_name)->user()->last_name }}</p>
              <p class="tx-12 text-muted">{{ Auth::guard($guard_name)->user()->email }}</p>
            </div>
          </div>
          <ul class="list-unstyled p-1">
            {{-- <li class="dropdown-item py-2">
              <a href="{{ route('user.profile')}}" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="edit"></i>
                <span>Edit Profile</span>
              </a>
            </li> --}}
            <li class="dropdown-item py-2">
              <a href="{{ route('fdo.agent.change-password.index') }}" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="repeat"></i>
                <span>Change Password</span>
              </a>
            </li>
            <li class="dropdown-item py-2">
              <a href="{{route('fdo.agent.logout')}}" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="log-out"></i>
                <span>Log Out</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</nav>
