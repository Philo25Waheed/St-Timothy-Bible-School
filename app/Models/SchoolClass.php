<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['grade_id', 'name', 'room', 'servant_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function servant()
    {
        return $this->belongsTo(User::class, 'servant_id');
    }

    public function servants()
    {
        return $this->belongsToMany(User::class, 'class_servant', 'class_id', 'servant_id');
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class, 'class_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'class_id');
    }
}
