<?php
// app/Http/Controllers/AttendanceController.php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'course']);
        
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }
        
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        
        $attendances = $query->paginate(15);
        $courses = Course::all();
        
        return view('attendance.index', compact('attendances', 'courses'));
    }

    public function create()
    {
        $courses = Course::with('students')->get();
        return view('attendance.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
        ]);

        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'course_id' => $request->course_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $record['status'],
                    'check_in_time' => $record['status'] === 'present' || $record['status'] === 'late' ? Carbon::now() : null,
                    'remarks' => $record['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        return view('attendance.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    public function markBulk(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
        ]);

        $course = Course::with('students')->find($request->course_id);
        
        return view('attendance.mark-bulk', compact('course'));
    }
}