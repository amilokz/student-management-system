@extends('layouts.app')

@section('content')
<div class="page-title">
    <h2>
        <i class="bi bi-people-fill"></i>
        All Students
    </h2>
</div>

<div class="mb-4">
    <a href="{{ route('students.create') }}" class="btn btn-custom">
        <i class="bi bi-plus-circle-fill me-2"></i>Add New Student
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td><span class="badge badge-custom">#{{ $student->id }}</span></td>
                    <td>
                        <i class="bi bi-person-circle me-2 text-primary"></i>
                        {{ $student->name }}
                    </td>
                    <td>
                        <i class="bi bi-envelope-fill me-2 text-info"></i>
                        {{ $student->email }}
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="bi bi-eye-fill me-1"></i>View
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="bi bi-pencil-fill me-1"></i>Edit
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">
                                    <i class="bi bi-trash-fill me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="bi bi-emoji-frown" style="font-size: 3rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No Students Found</h4>
                        <a href="{{ route('students.create') }}" class="btn btn-custom mt-3">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add Your First Student
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $students->links() }}
</div>
@endsection