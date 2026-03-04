<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'name',
        'description',
        'credits',
        'duration_hours',
        'level'
    ];

    protected $casts = [
        'credits' => 'integer',
        'duration_hours' => 'integer',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot(['enrollment_date', 'status', 'grade'])->withTimestamps();
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}