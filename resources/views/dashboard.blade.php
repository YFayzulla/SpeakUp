@extends('template.master')
@section('content')
    @role('admin')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="row">

                    <div class="col-md-6 col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-wrap align-items-center gap-3">
                                    <div class="d-flex flex-column align-items-start justify-content-between">
                                        <div class="card-title">
                                            <h5 class="text-nowrap mb-2">Students</h5>
                                            <span class="badge bg-label-success rounded-pill">{{ now()->format('d-m-y') }}</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <h6 class="mb-0"><b>{{ $number_of_students }}</b></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mb-4 order-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                    <div class="d-flex flex-sm-column align-items-start justify-content-between">
                                        <div class="card-title">
                                            <h5 class="text-nowrap mb-2">Today's income</h5>
                                            <span class="badge bg-label-warning rounded-pill">{{ now()->format('d-m-y') }}</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <h6 class="mb-0">{{ number_format($daily_income, 0, '.', ' ') }} sum</h6>
                                        </div>
                                    </div>
                                    <div id="profileReportChart" class="w-100" data-trent="{{ $trent->toJson() }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card m-2">
                            <h5 class="card-header">Teachers</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th><b>%</b></th>
                                        <th>Groups</th>
                                        <th>Students</th>
                                        <th>Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($teachers as $teacher)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td><strong>{{ $teacher->name }}</strong></td>
                                            <td>{{ $teacher->percent }}</td>
                                            <td>{{ $teacher->teacherhasGroup() }}</td>
                                            <td>{{ $teacher->teacherHasStudents() }}</td>
                                            <td>{{ number_format($teacher->teacherPayment(), 0, ' ', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">Profit</h5>
                    </div>
                    <div class="card-body">
                        <span class="badge bg-label-info rounded-pill mb-2">{{ today()->format('d-m-y') }}</span>
                        <h6 class="mb-2">{{ number_format($profit, 0, '.', ' ') }} sum</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">{{ count($attendances) == 0 ? 'Attendance' : count($attendances) . " Students didn't come" }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach($attendances as $attendance)
                                <li class="d-flex justify-content-between align-items-center mb-3">
                                    <span>{{ $loop->index+1 }}. {{ $attendance->user->name }}</span>
                                    <small class="fw-semibold">{{ $attendance->group->name }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole

    @role('user')
    <div class="row">
        <div class="col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-success">Test Results</p>
                    <div class="w-100">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Salary</h5>
                </div>
            </div>
        </div>
    </div>
    @endrole

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .card-title h5 {
                font-size: 1rem;
            }

            .badge {
                font-size: 0.8rem;
            }

            canvas {
                width: 100% !important;
                height: auto !important;
            }
        }
    </style>

@endsection
