@extends('template.master')
@section('content')
    @role('admin')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-lg-6 mb-6 order-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                    <div
                                            class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                        <div class="card-title">
                                            <h5 class="text-nowrap mb-2">Umumiy mijozlar</h5>
                                            <span
                                                    class="badge bg-label-success rounded-pill">{{now()->format('d-m-y')}}</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <h3 class="mb-0"><b>{{$students}}</b></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-6 order-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                    <div
                                            class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                        <div class="card-title">
                                            <h5 class="text-nowrap mb-2">Bugungi daromad</h5>
                                            <span
                                                    class="badge bg-label-warning rounded-pill">{{now()->format('d-m-y')}}</span>
                                        </div>
                                        <div class="mt-sm-auto">

                                            <h6 class="mb-0"> {{number_format($daily_profit, 0, '.', ' ')}} sum</h6>
                                        </div>
                                    </div>
                                    <div id="profileReportChart" data-trent="{{ $trent->toJson() }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 mt-3">
                        <div class="card">
                            <h5 class="card-header">Teachers</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>name</th>
                                        <th><b>%</b></th>
                                        <th>groups</th>
                                        <th>students</th>
                                        <th>salary</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    @foreach($teachers as $teacher)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>
                                                {{--                                                <i class="fab fa-angular fa-lg text-danger me-3"></i>--}}
                                                <strong>{{ $teacher->name }}</strong>
                                            </td>
                                            <td>
                                                {{ $teacher->percent }}
                                            </td>

                                            <td>
                                                {{ $teacher->teacherhasGroup() }}
                                            </td>

                                            <td>
                                                {{ $teacher->teacherHasStudents() }}
                                            </td>

                                            <td>
                                                {{ number_format( $teacher->teacherPayment() , 0, '.', ' ')    }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 order-1">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Yangi mijorlar</h5>
                            {{--                            <h2 class="mb-2">{{$new_clients}} ta</h2>--}}

                        </div>
                        <div class="dropdown">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div class="d-flex flex-column align-items-center gap-1">
                                <span>Ularga Ko'rsatilgan Xizmatlar</span>
                            </div>
                            <div id="orderStatisticsChart"></div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"
                            ><i class="bx bx-home-alt"></i
                                ></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Filter Almashtirildi</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">120.000 sum</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Suv Turba</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">232.000 sum</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                                class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Filter Tuzatildi</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">50.000 sum</small>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Bugungi xizmatlar</h5>
                            <h2 class="mb-2">120 ta</h2>

                        </div>
                        <div class="dropdown">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div class="d-flex flex-column align-items-center gap-1">
                                <span>Ularga Ko'rsatilgan Xizmatlar</span>
                            </div>
                            <div id="orderStatisticsChart"></div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="bx bx-home-alt"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Bajarilgan</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">10</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Bajarilmagan</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">20</small>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <!-- Total Revenue -->

            <!--/ Total Revenue -->

        </div>
        <!--/ Expense Overview -->
    </div>
    @endrole
    @role('user')
    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">


        <p class="text-success">success</p>

    </div>
    @endrole
@endsection
