<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $totalCourses = Course::count();
        $totalFeesCollected = Fee::where('status', 'paid')->sum('paid_amount');
        $pendingFees = Fee::whereIn('status', ['pending', 'overdue'])->sum('amount') - 
                      Fee::whereIn('status', ['pending', 'overdue'])->sum('paid_amount');
        
        // Today's attendance
        $todayAttendance = Attendance::whereDate('date', Carbon::today())->count();
        $presentToday = Attendance::whereDate('date', Carbon::today())
                                 ->whereIn('status', ['present', 'late'])
                                 ->count();
        
        // Recent students
        $recentStudents = Student::latest()->take(5)->get();
        
        // Upcoming fees
        $upcomingFees = Fee::with('student')
                          ->where('status', 'pending')
                          ->where('due_date', '>=', Carbon::today())
                          ->where('due_date', '<=', Carbon::today()->addDays(7))
                          ->get();
        
        // Monthly enrollment chart data - SQLite compatible version
        $currentYear = Carbon::now()->year;
        
        // For SQLite, we need to use strftime
        if (DB::connection()->getDriverName() === 'sqlite') {
            $monthlyEnrollments = Student::select(
                    DB::raw("strftime('%m', created_at) as month"),
                    DB::raw('COUNT(*) as count')
                )
                ->whereRaw("strftime('%Y', created_at) = ?", [$currentYear])
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        } else {
            // For MySQL/PostgreSQL
            $monthlyEnrollments = Student::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }
        
        // Fill missing months
        $enrollmentData = [];
        for ($i = 1; $i <= 12; $i++) {
            // Format month to handle SQLite returning '01', '02', etc.
            $monthKey = $i < 10 ? '0' . $i : (string)$i;
            $enrollmentData[] = $monthlyEnrollments[$monthKey] ?? $monthlyEnrollments[$i] ?? 0;
        }
        
        // Student status distribution
        $activeCount = Student::where('status', 'active')->count();
        $inactiveCount = Student::where('status', 'inactive')->count();
        $graduatedCount = Student::where('status', 'graduated')->count();
        $suspendedCount = Student::where('status', 'suspended')->count();
        
        return view('dashboard.index', compact(
            'totalStudents',
            'activeStudents',
            'totalCourses',
            'totalFeesCollected',
            'pendingFees',
            'todayAttendance',
            'presentToday',
            'recentStudents',
            'upcomingFees',
            'enrollmentData',
            'activeCount',
            'inactiveCount',
            'graduatedCount',
            'suspendedCount'
        ));
    }
}