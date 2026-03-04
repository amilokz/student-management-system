@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check me-2"></i>Attendance Records</h2>
    <a href="{{ route('attendance.create') }}" class="btn btn-custom">
        <i class="bi bi-plus-circle me-2"></i>Mark Attendance
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('attendance.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label for="course_id" class="form-label">Course</label>
                <select name="course_id" class="form-select">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Attendance Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->date->format('Y-m-d') }}</td>
                        <td>
                            <img src="{{ $attendance->student->avatar ? asset('storage/'.$attendance->student->avatar) : 'https://via.placeholder.com/30' }}" 
                                 class="avatar me-2" style="width: 30px; height: 30px;" alt="">
                            {{ $attendance->student->name }}
                        </td>
                        <td>{{ $attendance->course->name }}</td>
                        <td>
                            <span class="badge 
                                @if($attendance->status == 'present') bg-success
                                @elseif($attendance->status == 'late') bg-warning
                                @elseif($attendance->status == 'excused') bg-info
                                @else bg-danger
                                @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                        <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                        <td>{{ $attendance->remarks ?? '-' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete({{ $attendance->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $attendance->id }}" 
                                      action="{{ route('attendance.destroy', $attendance) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-emoji-frown" style="font-size: 2rem;"></i>
                            <p class="mt-2">No attendance records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $attendances->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this attendance record?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection