<nav class="sidebar">
    <div class="sidebar-header">
        <img  src="{{ asset('assets/images/logoMain.jpg') }}" class="header-logo" height="50px" width="130px" alt="">
        <div class="sidebar-toggler not-active">
        <span></span>
        <span></span>
        <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ route('fdo.agent.dashboard') }}" class="nav-link">
                <i class="link-icon" data-feather="box"></i>
                <span class="link-title">Dashboard</span>
                </a>
            </li>
            @if(!is_null(Auth::guard('fdo')->user()))
            <li class="nav-item nav-category">IRSS Master</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#irss_master" role="button" aria-expanded="{{ is_active_route(array_IRSS_master()) }}" aria-controls="irss_master">
                <i class="link-icon" data-feather="home"></i>
                <span class="link-title">IRSS Master</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(array_IRSS_master()) }}" id="irss_master">
                    <ul class="nav sub-menu">
                        <li class="{{ active_class(['fdo-agent/agent']) }} {{ active_class(['fdo-agent/agent/*']) }} nav-item">
                            <a href="{{ route('fdo-agent.agent.index') }}" class="nav-link">
                                <i class="link-icon" data-feather="users"></i>
                                <span class="link-title">Agent</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="nav-item ">
                <a class="nav-link" data-bs-toggle="collapse" href="#policy" role="button" aria-expanded="{{ is_active_route(array_policy_fdo_master()) }}" aria-controls="policy">
                    <i class="link-icon" data-feather="book"></i>
                    <span class="link-title">Policy Master</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(array_policy_fdo_master()) }}" id="policy">
                    <ul class="nav sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('fdo-agent.motor-policy.index') }}" class="nav-link {{ active_class(['fdo-agent/motor-policy']) }}">List Motor Policy</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fdo-agent.health-policy.index') }}" class="nav-link {{ active_class(['fdo-agent/health-policy']) }}">List Health Policy</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fdo-agent.sme-policy.index') }}" class="nav-link {{ active_class(['fdo-agent/sme-policy']) }}">List SME Policy</a>
                    </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
