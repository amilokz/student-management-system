<?php
// app/Models/Student.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'birth_date',
        'address',
        'avatar',
        'gender',
        'status',
        'enrollment_date',
        'graduation_date',
        'emergency_contact',
        'medical_notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'graduation_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate student_id when creating
        static::creating(function ($student) {
            if (!$student->student_id) {
                $student->student_id = 'STU-' . str_pad(static::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withPivot(['enrollment_date', 'status', 'grade'])->withTimestamps();
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function parentInfo()
    {
        return $this->hasOne(ParentInfo::class);
    }

    // Calculate attendance percentage
    public function getAttendancePercentage($courseId = null)
    {
        $query = $this->attendance();
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        $total = $query->count();
        if ($total === 0) return 0;
        
        $present = $query->whereIn('status', ['present', 'late'])->count();
        
        return round(($present / $total) * 100, 2);
    }

    // Get total fees paid
    public function getTotalFeesPaid()
    {
        return $this->fees()->where('status', 'paid')->sum('paid_amount');
    }

    // Get outstanding fees
    public function getOutstandingFees()
    {
        return $this->fees()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->sum('amount') - $this->fees()->whereIn('status', ['pending', 'partial', 'overdue'])->sum('paid_amount');
    }
}