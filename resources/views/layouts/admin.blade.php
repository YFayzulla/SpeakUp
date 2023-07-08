<!DOCTYPE html>

<!-- beautify ignore:start -->
<html
        lang="en"
        class="light-style layout-menu-fixed"
        dir="ltr"
        data-theme="theme-default"
        data-assets-path="{{asset('../assets/')}}"
        data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>SpeakUP learning center</title>
    <meta name="description" content=""/>
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('../assets/vendor/css/core.css')}}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/theme-default.css')}}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{asset('../assets/css/demo.css')}}">
    <!-- Vendors CSS -->
    {{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"--}}
    {{--          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">--}}
    {{--    --}}
    <link rel="stylesheet" href="{{asset('../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}"/>
    {{--script--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="{{asset('../assets/vendor/js/helpers.js')}}"></script>

    {{--    <script src="{{asset('../assets/js/config.js')}}"></script>--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</head>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <?php
        ?>
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <img src="{{asset('storage/Photo/logo.png')}}" alt="">
            </div>
            <div class="menu-inner-shadow"></div>
            @role('admin|manager')

            <ul class="menu-inner py-1">
                <!-- Dashboard -->

                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Partners</span>
                </li>

                <li class="menu-item @if(Route::is('dashboard.index')) active @endif">
                    <a href="{{route('dashboard.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Partners</div>
                    </a>
                </li>
                <!-- Layouts -->
                {{--                <li class="menu-item">--}}
                {{--                    <a href="javascript:void(0);" class="menu-link menu-toggle">--}}
                {{--                        <i class="menu-icon tf-icons bx bx-layout"></i>--}}
                {{--                        <div data-i18n="Layouts">Layouts</div>--}}
                {{--                    </a>--}}

                {{--                    <ul class="menu-sub ">--}}
                {{--                        <li class="menu-item  @if(route('student.index')) active @endif">--}}
                {{--                            <a href="{{route('student.index')}}" class="menu-link">--}}
                {{--                                <div data-i18n="Without menu">students</div>--}}
                {{--                            </a>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </li>--}}

                <li class="menu-item @if(Route::is('student.index')) active @endif">
                    <a href="{{route('student.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Students</div>
                    </a>
                </li>

                <li class="menu-item @if(Route::is('group.index')) active @endif">
                    <a href="{{route('group.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Groups</div>
                    </a>
                </li>

                <li class="menu-item @if(Route::is('extra.show')) active @endif">
                    <a href="{{route('extra.show',1)}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Groups&&Students</div>
                    </a>
                </li>

                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Pages</span>
                </li>

            </ul>
            @endrole

        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                 id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <i class="bx bx-search fs-4 lh-0"></i>
                            <input
                                    id="myInput"
                                    type="text"
                                    class="form-control border-0 shadow-none"
                                    placeholder="Search..."
                                    aria-label="Search..."
                            />
                        </div>
                    </div>
                    <!-- /Search -->

                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- Place this tag where you want the button to render. -->
                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                               data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">

                                    <img src="{{asset('storage/'.auth()->user()->image)}}" alt="" width="100%"
                                         class="w-px-200 h-auto rounded-circle"/>
                                    {{--                                    <img src="{{asset('../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle"/>--}}
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{route('dashboard.index')}}">
                                        {{--')}}">--}}
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="{{asset('storage/'.auth()->user()->image)}}" alt=""
                                                         width="100%"
                                                         class="w-px-200 h-auto rounded-circle"/>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold d-block">{{auth()->user()->name}}</span>
                                                {{--                                                <small class="text-muted">Admin</small>--}}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <a class="dropdown-item" href="{{route('profile.edit')}}">
                                    <i class="bx bx-cog me-2"></i>
                                    <span class="align-middle text-dark">Profile edit</span></a>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout')}}">
                                        @csrf
                                        <a :href="route('logout')" class="dropdown-item"
                                           onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                </div>
            </nav>
            <!-- / Navbar -->
            @yield('content')
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
                <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                    <div class="mb-2 mb-md-0">
                        ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        , made with speakUP ❤️
                    </div>
                </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->
@yield('scripts')
<!-- Core JS -->
<!-- Main JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
<script src="{{asset('../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('../assets/vendor/js/menu.js')}}"></script>
<script src="{{asset('../assets/js/main.js')}}"></script>

</body>
</html>
