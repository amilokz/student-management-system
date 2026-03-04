<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Exports\StudentsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $students = $query->paginate(10);
        
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'enrollment_date' => 'nullable|date',
            'emergency_contact' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'courses' => 'nullable|array'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $student = Student::create($validated);

        // Assign courses
        if ($request->has('courses')) {
            $student->courses()->attach($request->courses, [
                'enrollment_date' => now(),
                'status' => 'enrolled'
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['courses', 'attendance', 'fees']);
        
        // Get attendance statistics
        $attendanceStats = [
            'present' => $student->attendance()->where('status', 'present')->count(),
            'absent' => $student->attendance()->where('status', 'absent')->count(),
            'late' => $student->attendance()->where('status', 'late')->count(),
            'excused' => $student->attendance()->where('status', 'excused')->count(),
        ];
        
        // Get fee statistics
        $feeStats = [
            'total' => $student->fees()->sum('amount'),
            'paid' => $student->fees()->sum('paid_amount'),
            'pending' => $student->getOutstandingFees(),
        ];
        
        return view('students.show', compact('student', 'attendanceStats', 'feeStats'));
    }

    public function edit(Student $student)
    {
        $courses = Course::all();
        $studentCourses = $student->courses->pluck('id')->toArray();
        
        return view('students.edit', compact('student', 'courses', 'studentCourses'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'required|in:active,inactive,graduated,suspended',
            'graduation_date' => 'nullable|date',
            'emergency_contact' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'courses' => 'nullable|array'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $student->update($validated);

        // Sync courses
        if ($request->has('courses')) {
            $student->courses()->sync($request->courses);
        } else {
            $student->courses()->detach();
        }

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Delete avatar
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }
        
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function exportPDF()
    {
        $students = Student::all();
        $pdf = PDF::loadView('students.pdf', compact('students'));
        return $pdf->download('students.pdf');
    }

    public function generateIdCard(Student $student)
    {
        $pdf = PDF::loadView('students.id-card', compact('student'));
        return $pdf->download('student-id-' . $student->student_id . '.pdf');
    }

    public function profile(Student $student)
    {
        return view('students.profile', compact('student'));
    }
}