<?php
// app/Models/ParentInfo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentInfo extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'student_id',
        'father_name',
        'mother_name',
        'guardian_name',
        'relationship',
        'email',
        'phone',
        'alternative_phone',
        'address',
        'occupation'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}