@extends('layouts.app')

@section('content')
<div class="page-title">
    <h2>
        <i class="bi bi-person-badge-fill"></i>
        Student Details
    </h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                        Student Information
                    </h5>
                    <span class="badge badge-custom">ID: #{{ $student->id }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: #667eea;"></i>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="fw-bold text-muted mb-2">
                                <i class="bi bi-person-fill me-2"></i>Full Name
                            </label>
                            <p class="h4">{{ $student->name }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="fw-bold text-muted mb-2">
                                <i class="bi bi-envelope-fill me-2"></i>Email Address
                            </label>
                            <p class="h4">{{ $student->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="bg-light p-4 rounded">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-clock-history me-2"></i>Timestamps
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-calendar-plus me-2 text-success"></i>
                                        Created: {{ $student->created_at->format('F d, Y h:i A') }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-calendar-check me-2 text-info"></i>
                                        Updated: {{ $student->updated_at->format('F d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle-fill me-2"></i>Back to List
                    </a>
                    <div>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning text-white">
                            <i class="bi bi-pencil-fill me-2"></i>Edit
                        </a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">
                                <i class="bi bi-trash-fill me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection