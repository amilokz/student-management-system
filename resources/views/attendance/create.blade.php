@extends('layouts.app')

@section('title', 'Mark Attendance')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-calendar-plus me-2"></i>Mark Attendance</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="course_id" class="form-label">Select Course <span class="text-danger">*</span></label>
                    <select class="form-select @error('course_id') is-invalid @enderror" 
                            id="course_id" name="course_id" required>
                        <option value="">Choose a course...</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->course_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                           id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses->first()->students ?? [] as $student)
                        <tr>
                            <td>
                                <input type="hidden" name="attendance[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                <img src="{{ $student->avatar ? asset('storage/'.$student->avatar) : 'https://via.placeholder.com/30' }}" 
                                     class="avatar me-2" style="width: 30px; height: 30px;" alt="">
                                {{ $student->name }}
                                <br>
                                <small class="text-muted">{{ $student->student_id }}</small>
                            </td>
                            <td>
                                <select name="attendance[{{ $loop->index }}][status]" class="form-select form-select-sm">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="attendance[{{ $loop->index }}][remarks]" 
                                       class="form-control form-control-sm" placeholder="Optional remarks">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-custom">Save Attendance</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Load students when course is selected
    document.getElementById('course_id').addEventListener('change', function() {
        if (this.value) {
            window.location.href = '{{ route("attendance.create") }}?course_id=' + this.value;
        }
    });
</script>
@endpush
@endsection