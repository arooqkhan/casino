<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="">
                    <a href="{{url('/')}}">
                        <img src="{{ asset('images/logo.svg') }}" class="" alt="logo" id="testLogo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{url('/')}}" class="nav-link">AiSearch</a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="profile-info">
            <div class="user-info">
                <div class="profile-img">
                    <img src="{{asset('admin-assets/src/assets/img/profile-30.png')}}" alt="avatar">
                </div>
                <div class="profile-content">
                    <h6 class="">Admin</h6>
                    <p class="">Project Leader</p>
                </div>
            </div>
        </div>

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="" class="dropdown-toggle">
                    <div>
                        <!-- Dashboard icon (meter-style) -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 12l3 -3" />
                            <path d="M12 3v2" />
                            <path d="M3.6 7l1.5 1" />
                            <path d="M3 12h2" />
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>


            <!-- Users -->

            <li class="menu {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Multi-users icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <!-- Main head -->
                            <circle cx="12" cy="8" r="4" />
                            <!-- Left head -->
                            <circle cx="6" cy="12" r="3" />
                            <!-- Right head -->
                            <circle cx="18" cy="12" r="3" />
                            <!-- Shoulders / bodies -->
                            <path d="M6 15v3" />
                            <path d="M18 15v3" />
                            <path d="M12 12v6" />
                        </svg>
                        <span>Users</span>
                    </div>
                </a>
            </li>


            <!-- Transaction Histories -->

            <li class="menu {{ request()->routeIs('transaction_histories.*') ? 'active' : '' }}">
                <a href="{{ route('transaction_histories.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Transaction / exchange icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather">
                            <!-- Left arrow -->
                            <line x1="17" y1="3" x2="7" y2="13"></line>
                            <polyline points="7 3 7 13 17 13"></polyline>
                            <!-- Right arrow -->
                            <line x1="7" y1="21" x2="17" y2="11"></line>
                            <polyline points="17 21 17 11 7 11"></polyline>
                        </svg>
                        <span>Transaction Histories</span>
                    </div>
                </a>
            </li>






        </ul>

    </nav>

</div>