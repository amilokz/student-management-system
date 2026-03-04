<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Fee;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data in correct order (respect foreign keys)
        $this->command->info('Clearing existing data...');
        
        // Disable foreign key checks for SQLite
        if (config('database.default') === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = OFF;');
        }
        
        // Truncate tables in correct order
        Attendance::truncate();
        Fee::truncate();
        \DB::table('course_student')->truncate();
        Course::truncate();
        Student::truncate();
        
        // Re-enable foreign key checks
        if (config('database.default') === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = ON;');
        }
        
        $this->command->info('Old data cleared!');

        // Create courses with firstOrCreate to avoid duplicates
        $this->command->info('Creating courses...');
        
        $courses = [
            [
                'course_code' => 'CS101',
                'name' => 'Introduction to Computer Science',
                'description' => 'Basic concepts of programming and computer science',
                'credits' => 3,
                'duration_hours' => 45,
                'level' => 'beginner'
            ],
            [
                'course_code' => 'MATH201',
                'name' => 'Calculus I',
                'description' => 'Differential and integral calculus',
                'credits' => 4,
                'duration_hours' => 60,
                'level' => 'intermediate'
            ],
            [
                'course_code' => 'PHY101',
                'name' => 'Physics Fundamentals',
                'description' => 'Basic physics principles and experiments',
                'credits' => 3,
                'duration_hours' => 45,
                'level' => 'beginner'
            ],
            [
                'course_code' => 'ENG205',
                'name' => 'Technical Writing',
                'description' => 'Professional and technical communication',
                'credits' => 3,
                'duration_hours' => 45,
                'level' => 'intermediate'
            ],
            [
                'course_code' => 'DB301',
                'name' => 'Database Management Systems',
                'description' => 'SQL, database design and administration',
                'credits' => 4,
                'duration_hours' => 60,
                'level' => 'advanced'
            ],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                ['course_code' => $courseData['course_code']],
                $courseData
            );
        }

        $this->command->info('Courses created successfully!');

        // Create 20 students using factory
        $this->command->info('Creating students...');
        $students = Student::factory(20)->create();
        
        $this->command->info('Students created successfully!');

        // Get all courses
        $allCourses = Course::all();

        // For each student, enroll in random courses and create attendance records
        $this->command->info('Creating enrollments, attendance, and fees...');
        
        foreach ($students as $student) {
            // Enroll in 2-3 random courses
            $randomCourses = $allCourses->random(rand(2, 3));
            
            foreach ($randomCourses as $course) {
                // Enroll student in course (check if already enrolled)
                if (!$student->courses()->where('course_id', $course->id)->exists()) {
                    $student->courses()->attach($course->id, [
                        'enrollment_date' => Carbon::now()->subMonths(rand(1, 6)),
                        'status' => 'enrolled',
                        'grade' => null,
                    ]);
                }
                
                // Create 10 attendance records for each course
                for ($i = 0; $i < 10; $i++) {
                    $date = Carbon::now()->subDays(rand(1, 30));
                    
                    // Check if attendance record already exists for this date
                    $exists = Attendance::where('student_id', $student->id)
                        ->where('course_id', $course->id)
                        ->whereDate('date', $date)
                        ->exists();
                    
                    if (!$exists) {
                        Attendance::create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'date' => $date,
                            'status' => ['present', 'absent', 'late', 'excused'][rand(0, 3)],
                            'remarks' => rand(0, 10) > 7 ? 'Student arrived late' : null,
                        ]);
                    }
                }
            }
            
            // Create 3 fee records for each student
            for ($i = 0; $i < 3; $i++) {
                $amount = rand(500, 2000);
                $paidAmount = rand(0, 10) > 3 ? $amount : rand(0, $amount);
                $feeType = ['Tuition', 'Library', 'Sports'][rand(0, 2)];
                
                // Check if similar fee record exists
                $exists = Fee::where('student_id', $student->id)
                    ->where('fee_type', $feeType)
                    ->where('amount', $amount)
                    ->exists();
                
                if (!$exists) {
                    Fee::create([
                        'student_id' => $student->id,
                        'fee_type' => $feeType,
                        'amount' => $amount,
                        'due_date' => Carbon::now()->addMonths(rand(-1, 2)),
                        'paid_date' => $paidAmount > 0 ? Carbon::now()->subDays(rand(1, 30)) : null,
                        'status' => $paidAmount == 0 ? 'pending' : ($paidAmount == $amount ? 'paid' : 'partial'),
                        'paid_amount' => $paidAmount,
                        'payment_method' => $paidAmount > 0 ? ['Cash', 'Card', 'Bank Transfer'][rand(0, 2)] : null,
                        'transaction_id' => $paidAmount > 0 ? 'TXN' . rand(10000, 99999) : null,
                    ]);
                }
            }
        }

        $this->command->info('Database seeding completed successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . Course::count() . ' courses');
        $this->command->info('- ' . Student::count() . ' students');
        $this->command->info('- ' . Attendance::count() . ' attendance records');
        $this->command->info('- ' . Fee::count() . ' fee records');
    }
}