<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['name', 'description', 'order'];

    public function grades()
    {
        return $this->hasMany(Grade::class)->orderBy('order');
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class, 'stage_id');
    }

    public function curricula()
    {
        return $this->hasMany(Curriculum::class, 'stage_id');
    }
}
