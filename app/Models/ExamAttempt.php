<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'total_marks',
        'percentage',
        'passed',
        'answers',
        'completed_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'answers' => 'array',
        'completed_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}
