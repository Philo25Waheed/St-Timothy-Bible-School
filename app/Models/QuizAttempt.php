<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
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

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}
