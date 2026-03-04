@extends('layouts.app')

@section('title', 'Fees Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cash-stack me-2"></i>Fees Management</h2>
    <a href="{{ route('fees.create') }}" class="btn btn-custom">
        <i class="bi bi-plus-circle me-2"></i>Add Fee Record
    </a>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Collected</h5>
                <h3>${{ number_format($totalCollected, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Pending Fees</h5>
                <h3>${{ number_format($totalPending, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Fees Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Fee Type</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                    <tr>
                        <td>
                            <img src="{{ $fee->student->avatar ? asset('storage/'.$fee->student->avatar) : 'https://via.placeholder.com/30' }}" 
                                 class="avatar me-2" style="width: 30px; height: 30px;" alt="">
                            {{ $fee->student->name }}
                        </td>
                        <td>{{ $fee->fee_type }}</td>
                        <td>${{ number_format($fee->amount, 2) }}</td>
                        <td>${{ number_format($fee->paid_amount, 2) }}</td>
                        <td>${{ number_format($fee->balance, 2) }}</td>
                        <td>{{ $fee->due_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge 
                                @if($fee->status == 'paid') bg-success
                                @elseif($fee->status == 'partial') bg-info
                                @elseif($fee->status == 'overdue') bg-danger
                                @else bg-warning
                                @endif">
                                {{ ucfirst($fee->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('fees.show', $fee) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('fees.edit', $fee) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('fees.receipt', $fee) }}" class="btn btn-sm btn-secondary text-white">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete({{ $fee->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $fee->id }}" 
                                      action="{{ route('fees.destroy', $fee) }}" 
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
                            <p class="mt-2">No fee records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $fees->links() }}
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this fee record?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection