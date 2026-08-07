<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'curriculum_id',
        'title',
        'term',
        'description',
        'order',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'unit_id')->orderBy('order');
    }
}
