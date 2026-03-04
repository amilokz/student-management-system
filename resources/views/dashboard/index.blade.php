@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Total Students</h6>
                    <h2>{{ $totalStudents }}</h2>
                    <small>+{{ $activeStudents }} active</small>
                </div>
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Total Courses</h6>
                    <h2>{{ $totalCourses }}</h2>
                    <small>Active courses</small>
                </div>
                <i class="bi bi-book"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Fees Collected</h6>
                    <h2>${{ number_format($totalFeesCollected, 2) }}</h2>
                    <small>${{ number_format($pendingFees, 2) }} pending</small>
                </div>
                <i class="bi bi-cash"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Today's Attendance</h6>
                    <h2>{{ $presentToday }}/{{ $todayAttendance }}</h2>
                    <small>{{ $todayAttendance > 0 ? round(($presentToday/$todayAttendance)*100) : 0 }}% present</small>
                </div>
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Monthly Enrollments ({{ date('Y') }})
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Student Status
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Students and Upcoming Fees -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-plus me-2"></i>Recent Students</span>
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-custom">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>ID</th>
                                <th>Enrolled</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStudents as $student)
                            <tr>
                                <td>
                                    <img src="{{ $student->avatar ? asset('storage/'.$student->avatar) : 'https://via.placeholder.com/40' }}" 
                                         class="avatar me-2" alt="">
                                    {{ $student->name }}
                                </td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No students found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2"></i>Upcoming Fees (Next 7 Days)</span>
                <a href="{{ route('fees.index') }}" class="btn btn-sm btn-custom">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingFees as $fee)
                            <tr>
                                <td>{{ $fee->student->name ?? 'N/A' }}</td>
                                <td>${{ number_format($fee->amount, 2) }}</td>
                                <td>{{ $fee->due_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $fee->due_date->isPast() ? 'status-inactive' : 'status-active' }}">
                                        {{ $fee->due_date->isPast() ? 'Overdue' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No upcoming fees</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Enrollment Chart
    const ctx1 = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Enrollments',
                data: {{ json_encode($enrollmentData) }},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Status Chart
    const ctx2 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Graduated', 'Suspended'],
            datasets: [{
                data: [
                    {{ $activeCount ?? 0 }}, 
                    {{ $inactiveCount ?? 0 }}, 
                    {{ $graduatedCount ?? 0 }}, 
                    {{ $suspendedCount ?? 0 }}
                ],
                backgroundColor: [
                    '#84fab0',
                    '#fda085',
                    '#667eea',
                    '#f5576c'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush