@extends('layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-calendar-check me-2"></i>Attendance Report</h2>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reports.attendance') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ request('end_date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label for="course_id" class="form-label">Course</label>
                <select class="form-select" id="course_id" name="course_id">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

@if(request()->has('start_date'))
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Days</h6>
                    <h3>{{ $totalDays }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Present</h6>
                    <h3>{{ $totalPresent }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Absent</h6>
                    <h3>{{ $totalAbsent }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Attendance Rate</h6>
                    <h3>{{ $attendanceRate }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Attendance Records</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('M d, Y') }}</td>
                            <td>{{ $attendance->student->name }}</td>
                            <td>{{ $attendance->course->name }}</td>
                            <td>
                                <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : ($attendance->status == 'excused' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                No attendance records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection