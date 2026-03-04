@extends('layouts.app')

@section('title', 'Add Fee Record')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-plus-circle me-2"></i>Add Fee Record</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('fees.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                    <select class="form-select @error('student_id') is-invalid @enderror" 
                            id="student_id" name="student_id" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->student_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('fee_type') is-invalid @enderror" 
                           id="fee_type" name="fee_type" value="{{ old('fee_type') }}" 
                           placeholder="e.g., Tuition, Library, Sports" required>
                    @error('fee_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" value="{{ old('amount') }}" required>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                           id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('fees.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-custom">Create Fee Record</button>
            </div>
        </form>
    </div>
</div>
@endsection