@extends('layouts.app')

@section('title', 'Add Course')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-plus-circle me-2"></i>Add New Course</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                           id="course_code" name="course_code" value="{{ old('course_code') }}" 
                           placeholder="e.g., CS101" required>
                    @error('course_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="name" class="form-label">Course Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="e.g., Introduction to Computer Science" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="credits" class="form-label">Credits <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                           id="credits" name="credits" value="{{ old('credits') }}" 
                           min="1" max="10" required>
                    @error('credits')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="duration_hours" class="form-label">Duration (hours) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" 
                           id="duration_hours" name="duration_hours" value="{{ old('duration_hours') }}" 
                           min="1" required>
                    @error('duration_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                    <select class="form-select @error('level') is-invalid @enderror" 
                            id="level" name="level" required>
                        <option value="">Select Level</option>
                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-custom">Create Course</button>
            </div>
        </form>
    </div>
</div>
@endsection