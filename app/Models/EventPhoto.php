<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPhoto extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'image_url',
        'caption',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
