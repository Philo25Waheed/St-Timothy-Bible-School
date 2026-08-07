<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'stage_id',
        'grade_id',
        'class_id',
        'parent_id',
        'servant_id',
        'code',
        'birth_date',
        'address',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function servantUser()
    {
        return $this->belongsTo(User::class, 'servant_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function points()
    {
        return $this->hasMany(StudentPoint::class, 'student_id');
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'student_achievements', 'student_id', 'achievement_id')
                    ->withPivot('awarded_at')
                    ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class, 'student_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    public function verseProgress()
    {
        return $this->hasMany(StudentVerseProgress::class, 'student_id');
    }

    public function getTotalPointsAttribute()
    {
        return $this->points()->sum('amount');
    }

    public function getAttendanceRateAttribute()
    {
        $total = $this->attendanceRecords()->count();
        if ($total === 0) return 100;
        $present = $this->attendanceRecords()->whereIn('status', ['present', 'late'])->count();
        return round(($present / $total) * 100);
    }

    public function getAverageGradeAttribute()
    {
        $quizAvg = $this->quizAttempts()->avg('percentage') ?? 0;
        $examAvg = $this->examAttempts()->avg('percentage') ?? 0;

        if ($quizAvg && $examAvg) {
            return round(($quizAvg + $examAvg) / 2, 1);
        }
        return round($quizAvg ?: $examAvg, 1);
    }
}
