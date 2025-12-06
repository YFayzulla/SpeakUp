<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'finish_time',
        'room_id',
        'monthly_payment'
    ];

    /**
     * Get the room that the group belongs to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the students that are in the group.
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }

    /**
     * The teachers that belong to the group.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_teachers', 'group_id', 'teacher_id');
    }

    /**
     * Get the number of students in the group.
     * This is an accessor: $group->students_count
     */
    public function getStudentsCountAttribute(): int
    {
        // This avoids loading all student models just to count them.
        return $this->students()->count();
    }
}
