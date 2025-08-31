<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="">
                    <a href="{{url('/')}}">
                        <img src="{{ asset('1.png') }}" style="width: 50px; height:50px; border-radius:50%;" class="" alt="logo" id="testLogo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{url('/')}}" class="nav-link">Casino</a>
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
                    <img src="{{ asset(auth()->user()->image ? asset(auth()->user()->image) : asset('1.png')) }}" alt="avatar">
                </div>
                <div class="profile-content">
                    <h6 class="">{{auth()->user()->first_name}}</h6>
                    <p class="">Project Leader</p>
                </div>
            </div>
        </div>

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{url('/')}}" class="dropdown-toggle">
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


            <!-- Transaction -->

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
                        <span>Transaction</span>
                    </div>
                </a>
            </li>


            <!--campaigns -->

            <li class="menu {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                <a href="{{ route('campaigns.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Campaigns / Bullhorn Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <path d="M3 11V5a2 2 0 0 1 2-2h1l7 7v6l-7 7H5a2 2 0 0 1-2-2v-6"></path>
                            <line x1="15" y1="12" x2="21" y2="12"></line>
                            <line x1="18" y1="9" x2="21" y2="12" x2="18" y2="15"></line>
                        </svg>
                        <span>Campaigns</span>
                    </div>
                </a>
            </li>

            <!-- Bonus -->

            <li class="menu {{ request()->routeIs('bonus.*') ? 'active' : '' }}">
                <a href="{{ route('bonus.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Bonus / Gift Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <rect x="3" y="8" width="18" height="4" rx="1"></rect>
                            <path d="M12 8v13"></path>
                            <path d="M3 12v9a1 1 0 0 0 1 1h7"></path>
                            <path d="M21 12v9a1 1 0 0 1-1 1h-7"></path>
                            <path d="M7.5 8a2.5 2.5 0 1 1 5-2.5L12 8"></path>
                            <path d="M16.5 8a2.5 2.5 0 1 0-5-2.5L12 8"></path>
                        </svg>
                        <span>Bonus</span>
                    </div>
                </a>
            </li>


            <!-- Card Details -->

            <li class="menu {{ request()->routeIs('createds.*') ? 'active' : '' }}">
                <a href="{{ route('createds.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Created (Document + Plus) Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <span>Card Detail</span>
                    </div>
                </a>
            </li>


            <!-- Pachages -->

            <li class="menu {{ request()->routeIs('packages.*') ? 'active' : '' }}">
                <a href="{{ route('packages.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Package / Box Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-package">
                            <path d="M16.5 9.4V4.2a1 1 0 0 0-.55-.9L12.2 1.5a1 1 0 0 0-1.05 0L8.05 3.3a1 1 0 0 0-.55.9v5.2M3 6.8l9 5.2 9-5.2M3 6.8v10.4a1 1 0 0 0 .55.9l7.65 4.4a1 1 0 0 0 1.05 0l7.65-4.4a1 1 0 0 0 .55-.9V6.8" />
                        </svg>
                        <span>Packages</span>
                    </div>
                </a>
            </li>



            <!-- Wallet -->

            <li class="menu {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                <a href="{{ route('wallet.index') }}" class="dropdown-toggle">
                    <div>
                        <!-- Wallet Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 12h2a2 2 0 0 1 0 4h-2"></path>
                            <circle cx="16" cy="14" r="1"></circle>
                        </svg>
                        <span>Wallet</span>
                    </div>
                </a>
            </li>



            <!-- User Profile -->

            <li class="menu {{ request()->routeIs('userprofile') ? 'active' : '' }}">
                <a href="{{ route('userprofile') }}" class="dropdown-toggle">
                    <div>
                        <!-- Settings Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33
                         1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51
                         1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06
                         a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3
                         a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1
                         1.65 1.65 0 0 0-.33-1.82l-.06-.06
                         a2 2 0 1 1 2.83-2.83l.06.06
                         a1.65 1.65 0 0 0 1.82.33H9
                         a1.65 1.65 0 0 0 1-1.51V3
                         a2 2 0 0 1 4 0v.09
                         a1.65 1.65 0 0 0 1 1.51
                         1.65 1.65 0 0 0 1.82-.33l.06-.06
                         a2 2 0 1 1 2.83 2.83l-.06.06
                         a1.65 1.65 0 0 0-.33 1.82V9
                         c0 .69.41 1.31 1.04 1.58
                         .64.27 1.38.11 1.89-.39z"></path>
                        </svg>
                        <span>Setting</span>
                    </div>
                </a>
            </li>














        </ul>

    </nav>

</div>