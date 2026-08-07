<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['stage_id', 'name', 'order'];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'grade_id');
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class, 'grade_id');
    }
}
