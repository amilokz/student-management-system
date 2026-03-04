@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-book me-2"></i>Courses</h2>
    <a href="{{ route('courses.create') }}" class="btn btn-custom">
        <i class="bi bi-plus-circle me-2"></i>Add New Course
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('courses.index') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name, code, or description..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="level" class="form-select">
                    <option value="all">All Levels</option>
                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Courses Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Credits</th>
                        <th>Duration</th>
                        <th>Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td><span class="badge badge-custom">{{ $course->course_code }}</span></td>
                        <td>{{ $course->name }}</td>
                        <td>
                            <span class="status-badge 
                                @if($course->level == 'beginner') bg-info
                                @elseif($course->level == 'intermediate') bg-warning
                                @else bg-success
                                @endif text-white">
                                {{ ucfirst($course->level) }}
                            </span>
                        </td>
                        <td>{{ $course->credits }}</td>
                        <td>{{ $course->duration_hours }} hrs</td>
                        <td>
                            <span class="badge bg-secondary">{{ $course->students_count ?? $course->students()->count() }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete({{ $course->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $course->id }}" 
                                      action="{{ route('courses.destroy', $course) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-emoji-frown" style="font-size: 2rem;"></i>
                            <p class="mt-2">No courses found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $courses->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this course?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection