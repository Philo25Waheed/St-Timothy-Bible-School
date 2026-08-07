<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPoint extends Model
{
    protected $fillable = [
        'student_id',
        'given_by',
        'amount',
        'reason',
        'category',
    ];

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function giver()
    {
        return $this->belongsTo(User::class, 'given_by');
    }
}
