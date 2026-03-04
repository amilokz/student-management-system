<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $query = Course::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by level
        if ($request->has('level') && $request->level != 'all') {
            $query->where('level', $request->level);
        }
        
        $courses = $query->paginate(10);
        
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|unique:courses|max:20',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'duration_hours' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced'
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load('students');
        
        // Get enrollment statistics
        $totalEnrolled = $course->students()->count();
        $activeEnrolled = $course->students()->wherePivot('status', 'enrolled')->count();
        $completedEnrolled = $course->students()->wherePivot('status', 'completed')->count();
        
        // Get recent students
        $recentStudents = $course->students()
            ->orderBy('course_student.created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('courses.show', compact('course', 'totalEnrolled', 'activeEnrolled', 'completedEnrolled', 'recentStudents'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses,course_code,' . $course->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'duration_hours' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced'
        ]);

        $course->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Check if course has students enrolled
        if ($course->students()->count() > 0) {
            return redirect()->route('courses.index')
                ->with('error', 'Cannot delete course with enrolled students.');
        }
        
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Display students enrolled in this course.
     */
    public function students(Course $course)
    {
        $students = $course->students()->paginate(15);
        
        return view('courses.students', compact('course', 'students'));
    }
}