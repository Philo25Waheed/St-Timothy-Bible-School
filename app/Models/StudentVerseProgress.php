<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVerseProgress extends Model
{
    protected $table = 'student_verse_progress';

    protected $fillable = [
        'student_id',
        'bible_verse_id',
        'status',
        'notes',
        'checked_by',
    ];

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function bibleVerse()
    {
        return $this->belongsTo(BibleVerse::class, 'bible_verse_id');
    }

    public function verse()
    {
        return $this->belongsTo(BibleVerse::class, 'bible_verse_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
