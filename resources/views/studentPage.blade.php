@extends('template.master')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- User's Group and Teacher Info -->
            <div class="col-lg-8 col-md-12 col-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Your Group Details</h5>
                            <small class="text-muted">Information about your current group and teacher</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2">{{ auth()->user()->group?->name ?? 'No Group' }}</h2>
                                <span>Group Name</span>
                            </div>
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2">{{ auth()->user()->group?->room?->room ?? 'No Room' }}</h2>
                                <span>Room</span>
                            </div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-user"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Teacher</h6>
                                        <small class="text-muted">{{ auth()->user()->group?->teachers->first()?->name ?? 'Not assigned' }}</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">{{ auth()->user()->group?->start_time }} - {{ auth()->user()->group?->finish_time }}</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Your Monthly Payment</h6>
                                        <small class="text-muted">Status of your payment for this month</small>
                                    </div>
                                    <div class="user-progress">
                                        @if(auth()->user()->status > 0)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Side Cards -->
            <div class="col-lg-4 col-md-12 col-12">
                <!-- Your Test Results -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Last Test Result</h5>
                                    <span class="badge bg-label-warning rounded-pill">Year of 2024</span>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ auth()->user()->mark }}%</h3>
                                </div>
                            </div>
                            <div id="profileReportChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Attendance -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Your Attendance</h5>
                    </div>
                    <div class="card-body">
                        <p>Details about your attendance will be shown here.</p>
                        <!-- Attendance data can be visualized here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
