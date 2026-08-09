<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequest extends Model
{
    protected $fillable = [
        'student_id',
        'servant_id',
        'title',
        'details',
        'status',
        'servant_notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function servant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'servant_id');
    }
}
