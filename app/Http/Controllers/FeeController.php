<?php
// app/Http/Controllers/FeeController.php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with('student');
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        $fees = $query->paginate(15);
        $totalCollected = Fee::where('status', 'paid')->sum('paid_amount');
        $totalPending = Fee::whereIn('status', ['pending', 'overdue'])->sum('amount') - 
                       Fee::whereIn('status', ['pending', 'overdue'])->sum('paid_amount');
        
        return view('fees.index', compact('fees', 'totalCollected', 'totalPending'));
    }

    public function create()
    {
        $students = Student::all();
        return view('fees.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $fee = Fee::create($validated);

        return redirect()->route('fees.index')
            ->with('success', 'Fee record created successfully.');
    }

    public function show(Fee $fee)
    {
        return view('fees.show', compact('fee'));
    }

    public function edit(Fee $fee)
    {
        $students = Student::all();
        return view('fees.edit', compact('fee', 'students'));
    }

    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:paid,pending,overdue,partial',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $fee->update($validated);

        return redirect()->route('fees.show', $fee)
            ->with('success', 'Fee record updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();

        return redirect()->route('fees.index')
            ->with('success', 'Fee record deleted successfully.');
    }

    public function receipt(Fee $fee)
    {
        $pdf = PDF::loadView('fees.receipt', compact('fee'));
        return $pdf->download('receipt-' . $fee->id . '.pdf');
    }

    public function studentFees(Student $student)
    {
        $fees = $student->fees()->paginate(15);
        return view('fees.student', compact('student', 'fees'));
    }
}