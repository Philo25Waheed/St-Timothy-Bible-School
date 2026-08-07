<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'badge_code',
    ];

    public function students()
    {
        return $this->belongsToMany(StudentProfile::class, 'student_achievements', 'achievement_id', 'student_id')
                    ->withPivot('awarded_at')
                    ->withTimestamps();
    }
}
