<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BibleVerse extends Model
{
    protected $fillable = [
        'text',
        'reference',
        'stage_id',
        'grade_id',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentVerseProgress::class, 'bible_verse_id');
    }
}
