<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports index page.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Generate attendance report.
     */
    public function attendance(Request $request)
    {
        $courses = Course::all();
        $attendances = collect();
        $totalDays = 0;
        $totalPresent = 0;
        $totalAbsent = 0;
        $attendanceRate = 0;
        
        if ($request->has(['start_date', 'end_date'])) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $query = Attendance::with(['student', 'course'])
                ->whereBetween('date', [$request->start_date, $request->end_date]);

            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            $attendances = $query->orderBy('date', 'desc')->get();
            
            // Calculate statistics
            $totalDays = $attendances->groupBy('date')->count();
            $totalPresent = $attendances->whereIn('status', ['present', 'late'])->count();
            $totalAbsent = $attendances->where('status', 'absent')->count();
            $attendanceRate = $attendances->count() > 0 
                ? round(($totalPresent / $attendances->count()) * 100, 2) 
                : 0;
        }

        return view('reports.attendance', compact(
            'attendances', 
            'courses', 
            'totalDays', 
            'totalPresent', 
            'totalAbsent', 
            'attendanceRate'
        ));
    }

    /**
     * Generate fees report.
     */
    public function fees(Request $request)
    {
        $fees = collect();
        $totalAmount = 0;
        $totalPaid = 0;
        $totalPending = 0;
        $totalOverdue = 0;
        
        if ($request->has(['start_date', 'end_date'])) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $query = Fee::with('student');

            if ($request->start_date && $request->end_date) {
                $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            $fees = $query->orderBy('due_date')->get();
            
            // Calculate statistics
            $totalAmount = $fees->sum('amount');
            $totalPaid = $fees->sum('paid_amount');
            $totalPending = $totalAmount - $totalPaid;
            $totalOverdue = $fees->where('status', 'overdue')->sum('amount') - 
                           $fees->where('status', 'overdue')->sum('paid_amount');
        }

        return view('reports.fees', compact(
            'fees', 
            'totalAmount', 
            'totalPaid', 
            'totalPending', 
            'totalOverdue'
        ));
    }

    /**
     * Generate students report.
     */
    public function students(Request $request)
    {
        $query = Student::with(['courses', 'fees']);
        
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->get();
        
        // Calculate statistics
        $totalStudents = $students->count();
        $activeStudents = $students->where('status', 'active')->count();
        $graduatedStudents = $students->where('status', 'graduated')->count();
        $inactiveStudents = $students->where('status', 'inactive')->count();

        return view('reports.students', compact(
            'students', 
            'totalStudents', 
            'activeStudents', 
            'graduatedStudents', 
            'inactiveStudents'
        ));
    }
}