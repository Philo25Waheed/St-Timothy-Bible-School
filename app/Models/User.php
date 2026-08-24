<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'gender',
        'birth_date',
        'address',
        'pending_children_info',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'pending_children_info' => 'array',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isServant(): bool
    {
        return $this->role === 'servant';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(StudentProfile::class, 'parent_id');
    }

    public function assignedClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_servant', 'servant_id', 'class_id');
    }

    /**
     * Get all class IDs assigned to this servant (via pivot, direct servant_id on class, or student servant_id).
     */
    public function getServantClassIdsAttribute(): array
    {
        $pivot = $this->assignedClasses()->pluck('classes.id')->toArray();
        $direct = SchoolClass::where('servant_id', $this->id)->pluck('id')->toArray();
        $fromStudents = StudentProfile::where('servant_id', $this->id)->whereNotNull('class_id')->pluck('class_id')->toArray();
        return array_values(array_unique(array_merge($pivot, $direct, $fromStudents)));
    }

    /**
     * Get all SchoolClass models assigned to this servant.
     */
    public function getServantClassesAttribute()
    {
        return SchoolClass::whereIn('id', $this->servant_class_ids)->with(['grade.stage', 'servants'])->get();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(public_path('storage/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=0f172a&color=f59e0b&bold=true";
    }
}
