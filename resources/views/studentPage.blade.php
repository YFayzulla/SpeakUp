@extends('template.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Welcome Message -->
        <div class="col-12 mb-4">
            <div class="card bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg border-0 rounded-lg">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h3>
                            <p class="mb-0 opacity-80">Here's a summary of your academic progress and financial status.</p>
                        </div>
                        <i class="bx bx-user-circle text-white text-5xl opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Group Information -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100 shadow-md rounded-lg">
                <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                    <h5 class="card-title mb-0 text-primary">Your Group</h5>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">{{ auth()->user()->group->name }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-primary-subtle text-primary me-3 rounded-circle">
                            <span class="avatar-content"><i class="bx bx-chalkboard-teacher"></i></span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Teacher</h6>
                            <small class="text-muted">{{ auth()->user()->group->mainTeacher()->name ?? 'Not Assigned' }}</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-info-subtle text-info me-3 rounded-circle">
                            <span class="avatar-content"><i class="bx bx-time"></i></span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Schedule</h6>
                            <small class="text-muted">{{ auth()->user()->group->start_time ?? 'N/A' }} - {{ auth()->user()->group->finish_time ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Results Chart -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100 shadow-md rounded-lg">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 text-primary">Your Test Results</h5>
                </div>
                <div class="card-body">
                    <canvas id="studentTestResultsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="col-12">
            <div class="card shadow-md rounded-lg">
                <h5 class="card-header bg-light border-bottom text-primary">Payment History</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-light">
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
                                    <td class="text-end"><span class="badge bg-success-subtle text-success">Paid</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No payment history found.</td>
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
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
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