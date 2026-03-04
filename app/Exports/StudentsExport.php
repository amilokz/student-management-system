<?php
// app/Exports/StudentsExport.php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with(['courses', 'fees'])->get();
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Name',
            'Email',
            'Phone',
            'Gender',
            'Status',
            'Enrollment Date',
            'Courses Enrolled',
            'Total Fees Paid',
            'Outstanding Fees',
            'Created At'
        ];
    }

    public function map($student): array
    {
        return [
            $student->student_id,
            $student->name,
            $student->email,
            $student->phone ?? 'N/A',
            $student->gender ?? 'N/A',
            ucfirst($student->status),
            $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : 'N/A',
            $student->courses->count(),
            '$' . number_format($student->getTotalFeesPaid(), 2),
            '$' . number_format($student->getOutstandingFees(), 2),
            $student->created_at->format('Y-m-d'),
        ];
    }
}