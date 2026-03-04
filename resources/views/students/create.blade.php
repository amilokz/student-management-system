@extends('layouts.app')

@section('content')
<div class="page-title">
    <h2>
        <i class="bi bi-person-plus-fill"></i>
        Add New Student
    </h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                    Student Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">
                            <i class="bi bi-person-fill me-2"></i>Full Name
                        </label>
                        <input type="text" 
                               class="form-control form-control-custom @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter student's full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Enter the complete name as per records.</div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">
                            <i class="bi bi-envelope-fill me-2"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter student's email address"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">We'll never share the email with anyone else.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-custom">
                            <i class="bi bi-save-fill me-2"></i>Save Student
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle-fill me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection