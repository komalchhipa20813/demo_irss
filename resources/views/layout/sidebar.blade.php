@php
    $permissionList = permission();
@endphp
<nav class="sidebar">
    <div class="sidebar-header">
        <img  src="{{ asset('assets/images/logoMain.jpg') }}" class="header-logo" height="50px" width="130px" alt="" style="display: none">
        <div class="sidebar-toggler active">
        <span></span>
        <span></span>
        <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="link-icon" data-feather="box"></i>
                <span class="link-title">Dashboard</span>
                </a>
            </li>
            @if ((in_array("81",$permissionList)) ||(in_array("73",$permissionList)) || (in_array("77",$permissionList)))
                <li class="nav-item nav-category">Retinue Master</li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#irss_master" role="button" aria-expanded="{{ is_active_route(array_IRSS_master()) }}" aria-controls="irss_master">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Retinue Master</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(array_IRSS_master()) }}" id="irss_master">
                        <ul class="nav sub-menu">
                            @if (in_array('73', $permissionList))
                                <li class="{{ active_class(['fdo']) }} {{ active_class(['fdo/*']) }} nav-item">

                                    <a class="nav-link" data-bs-toggle="collapse" href="#fdo_master" role="button" aria-expanded="{{ is_active_route(fdo_master()) }}" aria-controls="irss_master">
                                    <i class="link-icon" data-feather="user"></i>
                                    <span class="link-title">FDO Master</span>
                                    <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(fdo_master()) }}" id="fdo_master">
                                        <ul class="nav sub-menu">
                                            @if (in_array('74', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{ route('fdo.create') }}" class="nav-link {{ active_class(['fdo/*']) }}">Add FDO</a>
                                            </li>
                                             @endif
                                            @if (in_array('73', $permissionList))
                                            <li class="nav-item">
                                                 <a href="{{ route('fdo.index') }}" class="nav-link {{ active_class(['fdo']) }}">
                                                     <span class="">FDO List</span>
                                                 </a>
                                            </li>
                                             @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if (in_array('77', $permissionList))
                                <li class="{{ active_class(['agent']) }} {{ active_class(['agent/*']) }} nav-item">
                                    <a class="nav-link" data-bs-toggle="collapse" href="#agent_master" role="button" aria-expanded="{{ is_active_route(agent_master()) }}" aria-controls="irss_master">
                                    <i class="link-icon" data-feather="user"></i>
                                    <span class="link-title">Agent Master</span>
                                    <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(agent_master()) }}" id="agent_master">
                                        <ul class="nav sub-menu">
                                            @if (in_array('78', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{ route('agent.create') }}" class="nav-link {{ active_class(['agent/*']) }}">Add Agent</a>
                                            </li>
                                             @endif
                                            @if (in_array('77', $permissionList))
                                            <li class="nav-item">
                                                 <a href="{{ route('agent.index') }}" class="nav-link {{ active_class(['agent']) }}">
                                                     <span class="">Agent List</span>
                                                 </a>
                                            </li>
                                             @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if(in_array("81", $permissionList))
                            <li class="{{ active_class(['customer']) }} {{ active_class(['customer/*']) }} nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link {{ active_class(['customer']) }}">
                                <i class="link-icon mdi mdi-account"></i>
                                <span class="link-title">Customer Master</span>
                            </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if((in_array('109', $permissionList)) || (in_array('113', $permissionList))  || (in_array('101', $permissionList)))
            <li class="nav-item ">
                <a class="nav-link" data-bs-toggle="collapse" href="#policy" role="button" aria-expanded="{{ is_active_route(array_policy()) }}" aria-controls="policy">
                    <i class="link-icon" data-feather="book"></i>
                    <span class="link-title">Policy Master</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(array_policy()) }}" id="policy">
                    <ul class="nav sub-menu">
                    @if (in_array('109', $permissionList) || in_array('110', $permissionList))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#motor_policy" role="button" aria-expanded="{{ is_active_route(motor_policy_inward()) }}" aria-controls="policy">
                            <span>Motor Policy Inward</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(motor_policy_inward()) }}" id="motor_policy">
                            <ul class="nav sub-menu">
                                @if (in_array('110', $permissionList))
                                <li class="nav-item">
                                    <a href="{{ route('motor-policy.create') }}" class="nav-link {{ active_class(['motor-policy/*']) }}">Add Motor Policy</a>
                                </li>
                                 @endif
                                @if (in_array('109', $permissionList))
                                <li class="nav-item">
                                    <a href="{{ route('motor-policy.index') }}" class="nav-link {{ active_class(['motor-policy']) }}">List Motor Policy</a>
                                </li>
                                 @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if (in_array('101', $permissionList) || in_array('102', $permissionList))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#health_policy" role="button" aria-expanded="{{ is_active_route(helth_policy_inward()) }}" aria-controls="policy">
                            <span>Health Policy Inward</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(helth_policy_inward()) }}" id="health_policy">
                            <ul class="nav sub-menu">
                                @if (in_array('102', $permissionList))
                                <li class="nav-item">
                                    <a href="{{ route('health-policy.create') }}" class="nav-link {{ active_class(['health-policy/*']) }}">Add Health Policy</a>
                                </li>
                                 @endif
                                @if (in_array('101', $permissionList))
                                <li class="nav-item">
                                    <a href="{{ route('health-policy.index') }}" class="nav-link {{ active_class(['health-policy']) }}">List Health Policy</a>
                                </li>
                                 @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if (in_array('113', $permissionList) || in_array('114', $permissionList))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sme_policy" role="button" aria-expanded="{{ is_active_route(sme_policy_inward()) }}" aria-controls="policy">
                            <span>SME Policy Inward</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(sme_policy_inward()) }}" id="sme_policy">
                            <ul class="nav sub-menu">
                                @if (in_array('114', $permissionList))
                                <li class="nav-item">
                                    <a href="{{ route('sme-policy.create') }}" class="nav-link {{ active_class(['sme-policy/*']) }}">Add SME Policy</a>
                                </li>
                                 @endif
                                @if (in_array('113', $permissionList))
                                <li class="nav-item">
                                    <a href="{{ route('sme-policy.index') }}" class="nav-link {{ active_class(['sme-policy']) }}">List SME Policy</a>
                                </li>
                                 @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    </ul>
                </div>
            </li>
            @endif
            @if ((in_array('117', $permissionList)) || (in_array('118', $permissionList)) || (in_array('119', $permissionList)) || (in_array('120', $permissionList)))
            <li class="nav-item ">
            <a class="nav-link" data-bs-toggle="collapse" href="#updatepolicy" role="button" aria-expanded="{{ is_active_route(array_updatepolicy()) }}" aria-controls="updatepolicy">
                <i class="link-icon" data-feather="book"></i>
                <span class="link-title">Update Policy</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(array_updatepolicy()) }}" id="updatepolicy">
                <ul class="nav sub-menu">
                @if (in_array('117', $permissionList))
                <li class="nav-item">
                    <a href="{{ route('update.policy') }}" class="nav-link {{ active_class(['update-policy/motor-policy-update']) }}">Motor Policy</a>
                </li>
                @endif
                @if (in_array('118', $permissionList))
                <li class="nav-item">
                    <a href="{{ route('update.health_policy') }}" class="nav-link {{ active_class(['update-policy/health-policy-update']) }}">Health Policy</a>
                </li>
                @endif
                @if (in_array('119', $permissionList))
                <li class="nav-item">
                    <a href="{{ route('update.sme_policy') }}" class="nav-link {{ active_class(['update-policy/sme-policy-update']) }}">SME Policy</a>
                </li>
                @endif
                @if (in_array('120', $permissionList))
                <li class="nav-item">
                    <a href="{{ route('update.allpolicy') }}" class="nav-link {{ active_class(['update-policy/list-all-policy']) }}">All Policy List</a>
                </li>
                @endif
                </ul>
            </div>
            </li>
            @endif
            @if ((in_array('121', $permissionList)) || (in_array('122', $permissionList)) || (in_array('123', $permissionList)) || (in_array('124', $permissionList)) || (in_array('125', $permissionList)))
            <li class="nav-item ">
            <a class="nav-link" data-bs-toggle="collapse" href="#reports" role="button" aria-expanded="{{ is_active_route(array_reports()) }}" aria-controls="reports">
                <i class="link-icon" data-feather="book-open"></i>
                <span class="link-title">Reports</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(array_reports()) }}" id="reports">
                <ul class="nav sub-menu">
                    @if (in_array('121', $permissionList))
                    <li class="nav-item">
                        <a href="{{ route('generate-outward.index') }}" class="nav-link {{ active_class(['generate-outward']) }}">Generate Outward</a>
                    </li>
                    @endif
                    @if (in_array('122', $permissionList))
                    <li class="nav-item">
                        <a href="{{ route('generated-outward.index') }}" class="nav-link {{ active_class(['generated-outward']) }}">Generated Outward</a>
                    </li>
                    @endif
                    @if (in_array('123', $permissionList))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#notpolicypdf" role="button" aria-expanded="{{ is_active_route(array_notUpload_pdf_reports()) }}" aria-controls="notpolicypdf">
                            <i class="link-icon" data-feather="file"></i>
                            <span class="link-title">Not Policy PDF</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(array_notUpload_pdf_reports()) }}" id="notpolicypdf">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('notUpload.motorploicy') }}" class="nav-link {{ active_class(['not-uploaded-policy/motor']) }}">Motor Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notUpload.healthploicy') }}" class="nav-link {{ active_class(['not-uploaded-policy/health']) }}">Health Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notUpload.smeploicy') }}" class="nav-link {{ active_class(['not-uploaded-policy/sme']) }}">SME Policy</a>
                            </li>
                        </ul>
                    </div>
                    </li>
                    @endif
                    @if (in_array('128', $permissionList))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#cancelPolicy" role="button" aria-expanded="{{ is_active_route(array_cancel_policy_reports()) }}" aria-controls="cancelPolicy">
                            <i class="link-icon" data-feather="file"></i>
                            <span class="link-title">Cancel Policy</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(array_cancel_policy_reports()) }}" id="cancelPolicy">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('cancel-policy.motor') }}" class="nav-link {{ active_class(['cancel-policy/motor']) }}">Motor Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cancel-policy.health') }}" class="nav-link {{ active_class(['cancel-policy/health']) }}">Health Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cancel-policy.sme') }}" class="nav-link {{ active_class(['cancel-policy/sme']) }}">SME Policy</a>
                            </li>
                        </ul>
                    </div>
                    </li>
                    @endif
                @if (in_array('127', $permissionList))
                <li
                    class="nav-item {{ active_class(['gross-business']) }}">
                    <a href="{{ route('gross-business.index') }}" class="nav-link {{ active_class(['gross-business']) }}">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">GBR</span>
                    </a>
                </li>
                @endif
                @if (in_array('124', $permissionList))
                <li
                    class="nav-item {{ active_class(['agent-details']) }}">
                    <a href="{{ route('agent-details.index') }}" class="nav-link {{ active_class(['agent-details']) }}">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Agent Detials</span>
                    </a>
                </li>
                @endif
                @if (in_array('125', $permissionList))
                <li
                    class="nav-item {{ active_class(['fdo-details']) }}">
                    <a href="{{ route('fdo-details.index') }}" class="nav-link {{ active_class(['fdo-details']) }}">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">FDO Detials</span>
                    </a>
                </li>
                @endif
               
                </ul>
            </div>
            </li>
            @endif

            @if (in_array('45', $permissionList))
                <li
                    class="nav-item {{ active_class(['employee']) }} {{ active_class(['employee/create']) }} {{ active_class(['employee/*']) }}">
                    <a href="{{ route('employee.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Employee</span>
                    </a>
                </li>
            @endif

            {{-- @if (in_array('45', $permissionList))
                <li
                    class="nav-item {{ active_class(['pdf-read']) }} ">
                    <a href="{{ route('pdf-read.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">PDF Reader</span>
                    </a>
                </li>
            @endif --}}
            @if ((in_array("41", $permissionList)) || (in_array('37', $permissionList)) )
                <li class="nav-item nav-category">Role & Permission</li>
                @if (in_array('37', $permissionList))
                    <li class="nav-item {{ active_class(['role','role/*']) }}">
                        <a href="{{ route('role.index') }}" class="nav-link">
                            <i class="link-icon" data-feather="users"></i>
                            <span class="link-title">Role</span>
                        </a>
                    </li>
                @endif
                @if(in_array("41", $permissionList))
                    <li class="nav-item {{ active_class(['permission']) }} ">
                    <a href="{{ route('permission.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="check-square"></i>
                        <span class="link-title">Permission</span>
                    </a>
                    </li>
                @endif

            @endif



            @if ((in_array('1', $permissionList)) || (in_array('109', $permissionList)) ||(in_array('5', $permissionList))|| (in_array('9', $permissionList)) || (in_array('13', $permissionList)) ||(in_array('49', $permissionList)) || (in_array('97', $permissionList)) || (in_array('17', $permissionList)) || (in_array('21', $permissionList)) || (in_array('25', $permissionList)))

                <li class="nav-item nav-category">setting apps</li>
                @if ((in_array('1', $permissionList)) ||(in_array('109', $permissionList)) ||(in_array('5', $permissionList))|| (in_array('9', $permissionList))|| (in_array('13', $permissionList)) ||(in_array('49', $permissionList))||(in_array('97', $permissionList)))
                                <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_general()) }}" data-bs-toggle="collapse" href="#general" role="button" aria-expanded="{{ is_active_route(array_general()) }}" aria-controls="General">

                                        <i class="link-icon" data-feather="users"></i>
                                        <span class="link-title">General</span>
                                        <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>

                                    <div class="collapse {{ show_class(array_general()) }}" id="general">
                                        <ul class="nav sub-menu">
                                        @if(in_array('1', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('retinue-branch.index')}}" class="nav-link {{ active_class(['retinue-branch']) }}">retinue-branch</a>
                                        </li>
                                        @endif

                                        @if (in_array('5', $permissionList))
                                            <li class="nav-item">
                                            <a href="{{ route('department.index') }}" class="nav-link {{ active_class(['department']) }}">Department</a>
                                            </li>
                                        @endif
                                        @if (in_array('9', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{ route('designation.index') }}" class="nav-link {{ active_class(['designation']) }}">Designation</a>
                                        </li>
                                        @endif
                                        @if (in_array('13', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{ route('document-type.index') }}" class="nav-link {{ active_class(['document-type']) }}">Document Type</a>
                                        </li>
                                        @endif
                                        @if (in_array('49', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{ route('business-category.index') }}" class="nav-link {{ active_class(['business-category']) }}">Business Category</a>
                                        </li>
                                        @endif
                                        @if (in_array('97', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{ route('bank.index') }}" class="nav-link {{ active_class(['bank']) }}">Bank</a>
                                        </li>
                                        @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            @if ((in_array('17', $permissionList))||(in_array('21', $permissionList))||(in_array('25', $permissionList)))
                                <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_company()) }}" data-bs-toggle="collapse" href="#Company" role="button" aria-expanded="{{ is_active_route(array_company()) }}" aria-controls="Company">

                                        <i class="link-icon mdi mdi-hospital-building"></i>
                                        <span class="link-title">Company</span>
                                        <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(array_company()) }}" id="Company">
                                        <ul class="nav sub-menu">
                                        @if(in_array('17', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('company.index')}}" class="nav-link {{ active_class(['company']) }}">Company</a>
                                        </li>
                                        @endif
                                        @if(in_array('21', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{ route('company-branch.index') }}" class="nav-link {{ active_class(['company-branch']) }}">Company-branch</a>
                                        </li>
                                        @endif
                                        @if(in_array('25', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('branch-imd.index') }}" class="nav-link {{ active_class(['branch-imd']) }}">Branch-imd</a>
                                        </li>
                                        @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if ((in_array('29', $permissionList))||(in_array('33', $permissionList))||(in_array('33', $permissionList)))
                                <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_products()) }}" data-bs-toggle="collapse" href="#Products" role="button"
                                        aria-expanded="{{ is_active_route(array_products()) }}" aria-controls="Products">

                                        <i class="link-icon" data-feather="truck"></i>
                                        <span class="link-title">Products</span>
                                        <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(array_products()) }}" id="Products">
                                        <ul class="nav sub-menu">
                                            @if(in_array('29', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{ route('product.index') }}"
                                                    class="nav-link {{ active_class(['product','product/*']) }}">Products</a>
                                            </li>
                                            @endif
                                            @if(in_array('33', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{ route('sub-product.index') }}"
                                                    class="nav-link {{ active_class(['sub-product']) }}">Sub-Products</a>
                                            </li>
                                            @endif
                                            @if(in_array('105', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{ route('product-type.index') }}"
                                                    class="nav-link {{ active_class(['product-type']) }}">Products Type</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if ((in_array('53', $permissionList))||(in_array('57', $permissionList))||(in_array('61', $permissionList)))
                                <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_address()) }}" data-bs-toggle="collapse" href="#Address" role="button"
                                        aria-expanded="{{ is_active_route(array_address()) }}" aria-controls="Address">

                                        <i class="link-icon" data-feather="map-pin"></i>
                                        <span class="link-title">Address</span>
                                        <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(array_address()) }}" id="Address">
                                        <ul class="nav sub-menu">
                                            @if (in_array('53', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{route('country.index')}}"
                                                    class="nav-link {{ active_class(['country']) }}">Country</a>
                                            </li>
                                            @endif
                                            @if (in_array('57', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{route('state.index')}}"
                                                    class="nav-link {{ active_class(['state']) }}">State</a>
                                            </li>
                                            @endif
                                            @if (in_array('61', $permissionList))
                                            <li class="nav-item">
                                                <a href="{{route('city.index')}}"
                                                    class="nav-link {{ active_class(['city']) }}">City</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            
                                @if(in_array('65', $permissionList))
                                <li class="nav-item">
                                    <a href="{{route('public-holiday.index')}}"
                                        class="nav-link {{ active_class(['public-holiday']) }}"> <i class="link-icon mdi mdi-calendar-today"></i>
                                    <span class="link-title">Public Holiday</span></a>
                                </li>
                                 @endif   
                                 @if ((in_array('65', $permissionList))||(in_array('66', $permissionList))) 
                                <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_leave_application()) }}" data-bs-toggle="collapse" href="#leave_application" role="button" aria-expanded="{{ is_active_route(array_leave_application()) }}" aria-controls="">
                                        <i class="link-icon mdi mdi-application"></i>
                                        <span class="link-title">Leave Application</span>
                                        <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(array_leave_application()) }}" id="leave_application">
                                        <ul class="nav sub-menu">
                                        @if(in_array('65', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('leave-application.index')}}" class="nav-link {{ active_class(['leave-application']) }}">Leave List</a>
                                        </li>
                                        @endif
                                        @if(in_array('66', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('leave-application.create')}}" class="nav-link {{ active_class(['leave-application/*']) }}">Apply Leave</a>
                                        </li>
                                        @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if ((in_array('85', $permissionList))||(in_array('89', $permissionList))||in_array('93', $permissionList))
                            <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_motor_policy()) }}" data-bs-toggle="collapse" href="#motor_policy" role="button" aria-expanded="{{ is_active_route(array_motor_policy()) }}" aria-controls="motor_policy">

                                            <i class="link-icon mdi mdi-hospital-building"></i>
                                                <span class="link-title">Motor Policy</span>
                                            <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(array_motor_policy()) }}" id="motor_policy">
                                        <ul class="nav sub-menu">
                                        @if(in_array('85', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('make-product.index')}}" class="nav-link {{ active_class(['make-product']) }}">Product Make</a>
                                        </li>
                                        @endif
                                        @if(in_array('89', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('product-model.index')}}" class="nav-link {{ active_class(['product-model']) }}">Product Model</a>
                                        </li>
                                        @endif
                                        @if(in_array('93', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('product-variant.index')}}" class="nav-link {{ active_class(['product-variant']) }}">Product Variant</a>
                                        </li>
                                        @endif
                                        
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if ((in_array('85', $permissionList))||(in_array('89', $permissionList))||in_array('93', $permissionList))
                            <li class="nav-item">
                                    <a class="nav-link {{ active_class(array_raise_query()) }}" data-bs-toggle="collapse" href="#query" role="button" aria-expanded="{{ is_active_route(array_raise_query()) }}" aria-controls="motorquery_policy">

                                            <i class="link-icon mdi mdi-alert-circle-outline"></i>
                                                <span class="link-title">Query</span>
                                            <i class="link-arrow" data-feather="chevron-down"></i>
                                    </a>
                                    <div class="collapse {{ show_class(array_raise_query()) }}" id="query">
                                        <ul class="nav sub-menu">
                                        @if(in_array('85', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('raise-query.index')}}" class="nav-link {{ active_class(['raise-query','raise-query/create']) }}">Raise Query</a>
                                        </li>
                                        @endif
                                        @if(in_array('89', $permissionList))
                                        <li class="nav-item">
                                            <a href="{{route('solved.index')}}" class="nav-link {{ active_class(['raise-query/solved-query']) }}">Solved Query</a>
                                        </li>
                                        @endif
                                       
                                       
                                        </ul>
                                    </div>
                                </li>
                            @endif
                           
                {{-- <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#setting" role="button" aria-expanded="{{ is_active_route(array_setting()) }}" aria-controls="setting">
                    <i class="link-icon" data-feather="settings"></i>
                    <span class="link-title">Setting</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(array_setting()) }}" id="setting">
                    <ul class="nav sub-menu">

                        </ul>
                    </div>
                </li> --}}
            @endif
        </ul>
    </div>
</nav>
