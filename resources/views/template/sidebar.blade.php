<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img style="width: 200px" src="{{asset('logos/main.svg')}}" alt="">
{{--            <span style="text-transform: uppercase" class="app-brand-text demo menu-text fw-bolder ms-2">RGD</span>--}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->

        <li class="menu-item @if(request()->routeIs('dashboard')) active @endif">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @role('admin')
        <li class="menu-item @if(request()->routeIs('teacher.index' , 'teacher.create' , 'teacher.edit', 'teacher.show')) active @endif">
            <a href="{{ route('teacher.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Teachers</div>
            </a>
        </li>

        <li class="menu-item @if(request()->routeIs('student.index' , 'student.create' , 'student.edit', 'student.show'  )) active @endif">
            <a href="{{ route('student.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user"></i>
                <div data-i18n="Analytics">Students</div>
            </a>
        </li>

        <li class="menu-item @if(request()->routeIs('group.index','group.create','group.edit','group.show','group.attendance','group.create.room')) active @endif">
            <a href="{{ route('group.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Analytics">Rooms</div>
            </a>
        </li>

        <li class="menu-item @if(request()->routeIs('dept.index')) active @endif">
            <a href="{{ route('dept.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Analytics"> Payment</div>
            </a>
        </li>

        <li class="menu-item @if(request()->routeIs('test','test.show')) active @endif">
            <a href="{{ route('test','test.show') }}" class="menu-link">
                <i class="menu-icon tf-icons bx-test-tube"></i>
                <div data-i18n="Analytics">Assessment</div>
            </a>
        </li>
        <li class="menu-item @if(request()->routeIs('waiters.index')) active @endif">
            <a href="{{ route('waiters.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-door-open"></i>
                <div data-i18n="Analytics">Waiting Room</div>
            </a>
        </li>

        {{--        <li class="menu-item">--}}
        {{--            <a href="javascript:void(0);" class="menu-link menu-toggle">--}}
        {{--                <i class="menu-icon tf-logos bx bx-coin-stack"></i>--}}
        {{--                <div data-i18n="Account Settings">Finance</div>--}}
        {{--            </a>--}}
        {{--            <ul class="menu-sub">--}}

        <li class="menu-item @if(request()->routeIs('finance.other')) active @endif">
            <a href="{{ route('finance.other') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-coin-stack"></i>
                <div data-i18n="Notifications"> Finance</div>
            </a>
        </li>
        {{--            </ul>--}}
        {{--        </li>--}}

        @endrole

        @role('user')

        <li class="menu-item @if(request()->routeIs('assessment.index','assessment.show')) active @endif">
            <a href="{{route('assessment.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div data-i18n="Analytics"> Test</div>
            </a>
        </li>

        <li class="menu-item @if(request()->routeIs('attendance','attendance.check')) active @endif">
            <a href="{{route('attendance')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task"></i>
                <div data-i18n="Analytics"> Checking Attendance</div>
            </a>
        </li>

        @endrole

    </ul>
</aside>
