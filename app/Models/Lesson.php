<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'unit_id',
        'title',
        'description',
        'content',
        'bible_verse',
        'memory_verse',
        'objectives',
        'cover_image',
        'video_url',
        'pdf_file',
        'order',
        'status',
    ];

    protected $casts = [
        'objectives' => 'array',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'lesson_id');
    }

    public function progressRecords()
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    public function isCompletedByStudent($studentId): bool
    {
        return $this->progressRecords()
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->exists();
    }
}
