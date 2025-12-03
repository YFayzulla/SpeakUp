<nav
        class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar"
>
    <!-- Mobile Menu Toggle -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <!-- Navbar Right Side -->
    <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">

        @role('admin')
        <!-- Search Wrapper (Flex container for Text Search + Date Search) -->
        <div class="d-flex align-items-center flex-grow-1">

            <!-- 1. Global Search -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input
                            id="myInput"
                            type="text"
                            class="form-control border-0 shadow-none ps-1 ps-sm-2"
                            placeholder="Search..."
                            aria-label="Search..."
                    />
                </div>
            </div>

            <!-- 2. Date Search Form -->
            <!-- Added ms-3 for separation from global search, and gap-2 for spacing between inputs -->
            <div class="d-none d-md-block ms-4">
                <form action="{{route('student.search')}}" method="post" class="d-flex align-items-center gap-2">
                    @csrf

                    <!-- Start Date -->
                    <div>
                        <input
                                type="date"
                                class="form-control"
                                placeholder="Start Date"
                                name="start_date"
                                aria-label="Start Date"
                                style="width: 160px;"
                        />
                    </div>

                    <!-- End Date -->
                    <div>
                        <input
                                type="date"
                                class="form-control"
                                placeholder="End Date"
                                name="end_date"
                                aria-label="End Date"
                                style="width: 160px;"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                </form>
            </div>
        </div>
        @endrole

        <!-- User Menu (Pushed to the right using ms-auto) -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('icon/avatar.png') }}" alt class="w-px-40 h-auto rounded-circle"/>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('icon/avatar.png') }}" alt class="w-px-40 h-auto rounded-circle"/>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                                    @foreach(\Illuminate\Support\Facades\Auth::user()->getRoleNames() as $item)
                                        <small class="text-muted">{{ $item }}</small>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <a class="dropdown-item" href="{{ route("profile.edit") }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route("profile.edit") }}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input type="hidden" name="logout" value="1">
                            <button type="submit" class="dropdown-item">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>