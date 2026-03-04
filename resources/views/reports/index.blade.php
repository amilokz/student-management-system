@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-file-text me-2"></i>Reports Dashboard</h2>
</div>

<div class="row">
    <!-- Attendance Report Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="display-1 text-primary mb-3">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h4 class="card-title">Attendance Report</h4>
                <p class="card-text text-muted">
                    Generate attendance reports by date range and course.
                </p>
                <a href="{{ route('reports.attendance') }}" class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Fees Report Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="display-1 text-success mb-3">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h4 class="card-title">Fees Report</h4>
                <p class="card-text text-muted">
                    Track fee collections and pending payments.
                </p>
                <a href="{{ route('reports.fees') }}" class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Students Report Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="display-1 text-info mb-3">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="card-title">Students Report</h4>
                <p class="card-text text-muted">
                    Comprehensive student list with status information.
                </p>
                <a href="{{ route('reports.students') }}" class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection