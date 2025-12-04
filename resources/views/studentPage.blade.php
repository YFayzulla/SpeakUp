@extends('template.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Welcome Message -->
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white shadow-none">
                <div class="card-body">
                    <h4 class="text-white">Welcome back, {{ auth()->user()->name }}!</h4>
                    <p class="mb-0">Here's a summary of your academic progress and financial status.</p>
                </div>
            </div>
        </div>

        <!-- Group Information -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Your Group</h5>
                    <span class="badge bg-label-success">{{ auth()->user()->group->name }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-label-primary me-3 rounded-circle">
                            <span class="avatar-content"><i class="bx bx-chalkboard-teacher"></i></span>
                        </div>
                        <div>
                            <h6 class="mb-0">Teacher</h6>
                            <small class="text-muted">{{ auth()->user()->group->mainTeacher()->name ?? 'Not Assigned' }}</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-label-info me-3 rounded-circle">
                            <span class="avatar-content"><i class="bx bx-time"></i></span>
                        </div>
                        <div>
                            <h6 class="mb-0">Schedule</h6>
                            <small class="text-muted">{{ auth()->user()->group->start_time ?? 'N/A' }} - {{ auth()->user()->group->finish_time ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Results Chart -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Your Test Results</h5>
                </div>
                <div class="card-body">
                    <canvas id="studentTestResultsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Payment History</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Group</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(auth()->user()->payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->date->format('d M Y') }}</td>
                                    <td>{{ number_format($payment->payment, 0, '.', ' ') }} UZS</td>
                                    <td>{{ $payment->group }}</td>
                                    <td class="text-end"><span class="badge bg-label-success">Paid</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No payment history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('studentTestResultsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Quiz 1', 'Mid-term', 'Quiz 2', 'Final'], // Example labels
                datasets: [{
                    label: 'Your Score',
                    data: [88, 76, 92, 85], // Example data
                    backgroundColor: 'rgba(67, 83, 255, 0.7)',
                    borderColor: 'rgba(67, 83, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection